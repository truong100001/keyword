<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Http\Request;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $domains = DB::table('tbl_pbn')->get();
        $domain_expired = [];
        foreach ($domains as $domain)
        {
            if($domain->expired_date != null)
            {
                $domain->expired_day = Carbon::now('Asia/Ho_Chi_Minh')->diffInDays($domain->expired_date);
            }
            else
            {
                $domain->expired_day = '+999';
            }

            $domain->register_date = date('d/m/Y', strtotime($domain->register_date));
            $domain->expired_date = date('d/m/Y', strtotime($domain->expired_date));

            if($domain->expired_day < 10)
                array_push($domain_expired,$domain);
        }

        $domains_error = DB::table('tbl_pbn')->where('status_domain',0)->get();
        foreach ($domains_error as $domain)
        {
            $domain->register_date = date('d/m/Y', strtotime($domain->register_date));
            $domain->expired_date = date('d/m/Y', strtotime($domain->expired_date));
        }


        $keywords = DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain"));
        $count = count($keywords);
        return view('pages.home',compact('domains','keywords','count','domains_error','domain_expired'));
    }

    public function addDomain()
    {
        return view('pages.AddDomain');
    }

    public function postAddDomain(Request $request)
    {
        $this->validate($request,[
            'domain' => 'bail|required|unique:tbl_pbn|max:255',
            'link_to' => 'bail|required|max:255',
            'cdn' => 'bail|required',
            'ip' => 'bail|required|ipv4',
            'name_register' => 'bail|required|max:255',
            'email' => 'bail|required|email',
        ],[
            'domain.required' => 'Tên domain không được để trống',
            'domain.unique' => 'Tên domain đã tồn tại',
            'domain.max' => 'Dữ liệu không hợp lệ',
            'link_to.required' => 'Link to không được để trống',
            'link_to.max' => 'Dữ liệu không hợp lệ',
            'cdn.required' => 'CDN không được để trống',
            'ip.required' => 'IP không được để trống',
            'ip.ipv4' => 'IP không hợp lệ',
            'name_register.required' => 'Tên nhà đăng ký không được để trống',
            'name_register.max' => 'Dữ liệu không hợp lệ',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
        ]);

        DB::table('tbl_pbn')->insert([
            'domain' => $request->domain,
            'link_to' => $request->link_to,
            'cdn' => $request->cdn,
            'ip' => $request->ip,
            'name_register' => $request->name_register,
            'email' => $request->email,
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
        ]);


        return redirect()->back()->with('message','success');
    }

    public function addkeyWord(Request $request)
    {
        $this->validate($request,[
            'id_domain' => 'bail|required',
            'keyword' => 'bail|required',

        ],[
            'id_domain.required' => 'Chọn một domain',
            'keyword.required' => 'Từ khóa không được để trống',
        ]);

        $keywords = explode(',',$request->keyword);

        foreach ($keywords as $keyword)
        {
            if($keyword != '')
            {
                $check = DB::table('tbl_keyword')->where('id_domain',$request->id_domain)->where('key_word',$keyword)->first();
                if($check == null)
                {
                    DB::table('tbl_keyword')->insert([
                        'id_domain' => $request->id_domain,
                        'key_word' => $keyword,
                        'rank' => 0,
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                    ]);

                }
            }
        }

        return redirect()->back()->with('message','success');
    }

    public function history_keyword(Request $request)
    {
        $keywords = DB::select(DB::raw("SELECT history_keyword.*,tbl_keyword.key_word,tbl_pbn.domain FROM history_keyword 
                                        INNER JOIN tbl_keyword ON tbl_keyword.id = history_keyword.id_keyword 
                                        INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain
                                        WHERE id_keyword = $request->id_keyword"));

        foreach ($keywords as $keyword)
        {
            $keyword->date_check = date('H:i d/m/Y', strtotime($keyword->date_check));
        }
        return response()->json($keywords,200);

    }

    public function delete_keyword($id)
    {
        DB::table('tbl_keyword')->where('id',$id)->delete();
        return redirect()->back()->with('message','complete');
    }

    public function filter_keyword(Request $request)
    {
        if($request->id_domain == null && $request->rank == null)
            $keywords = [];
        else if($request->id_domain == null)
            $keywords = DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain WHERE rank = $request->rank"));
        else if($request->rank == null)
            $keywords = DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain WHERE id_domain = $request->id_domain"));
        else if($request->id_domain != null && $request->rank != null)
            $keywords = DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain WHERE id_domain = $request->id_domain AND rank = $request->rank"));
        else
            $keywords = [];

        foreach ($keywords as $keyword)
        {
            $keyword->updated_at = date('H:i d/m/Y', strtotime($keyword->updated_at));
        }

        $data['keywords'] = $keywords;
        $data['count'] = count($keywords);
        return response()->json($data,200);
    }

    public function refresh()
    {
        $keywords = DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain"));
        foreach ($keywords as $keyword)
        {
            $keyword->updated_at = date('H:i d/m/Y', strtotime($keyword->updated_at));
        }
        $data['keywords'] = $keywords;
        $data['count'] = count($keywords);
        return response()->json($data,200);
    }



    public function check_domain()
    {
        $domains = DB::table('tbl_pbn')->get();
        foreach ($domains as $domain)
        {
            $status = $this->get_Status_Domain($domain->domain);
            if($status == 200)
            {
                DB::table('tbl_pbn')->where('id',$domain->id)->update([
                    'status_domain' => 1,
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
            else
            {
                DB::table('tbl_pbn')->where('id',$domain->id)->update([
                    'status_domain' => 0,
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }

        }

        return redirect()->back()->with('message2','success');
    }

    public function get_Status_Domain($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode;
    }

    public function check_keyword()
    {
        $listKey =  DB::select(DB::raw("SELECT tbl_keyword.*,tbl_pbn.domain fROM tbl_keyword INNER JOIN tbl_pbn ON tbl_pbn.id = tbl_keyword.id_domain"));
        $this->check_rank($listKey);
    }

    public function update_rank($id,$rank)
    {
        DB::table('tbl_keyword')->where('id',$id)->update([
            'rank' => $rank,
            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);
    }

    public function check_rank($listKey)
    {
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $USE_FIREFOX = true; // if false, will use chrome.
        $caps = DesiredCapabilities::chrome();
        $prefs = array();
        $options = new ChromeOptions();
        $prefs['profile.default_content_setting_values.notifications'] = 2;
        $options->setExperimentalOption("prefs", $prefs);
        // firefox
        $profile = new FirefoxProfile();
        // $profile->setPreference('network.proxy.type', 1);
        # Set proxy to Tor client on localhost
        // $profile->setPreference('network.proxy.socks', '67.205.180.86');
        // $profile->setPreference('network.proxy.socks_port', 28982);

        $caps = DesiredCapabilities::firefox();
        $caps->setCapability(FirefoxDriver::PROFILE, $profile);
        //$caps->setCapability(ChromeOptions::CAPABILITY, $options);
        // $capabilities = [
        //     WebDriverCapabilityType::BROWSER_NAME => 'firefox',
        //     WebDriverCapabilityType::PROXY => [
        //         'proxyType' => 'manual',
        //         'socksProxy' => '104.248.64.188:28982',
        //         //'sslProxy' => '127.0.0.1:2043',
        //     ],
        // ];
        // $caps->setCapability(
        //     'moz:firefoxOptions',
        //    ['args' => ['-headless']]
        // );
        if ($USE_FIREFOX)
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        else
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        $driver->get("https://www.google.com?hl=en");
        $driver->findElement(WebDriverBy::cssSelector('input.gLFyf'))->click();
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('input.gLFyf'))->sendKeys($listKey[0]->key_word);
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('input.gNO89b'))->click();
        sleep(3);
        if(count($driver->findElements(WebDriverBy::cssSelector('div.xpdopen'))) > 0)
            $driver->findElement(WebDriverBy::cssSelector('div.xpdopen'))->clear();
        $domains = $driver->findElements(WebDriverBy::cssSelector('cite.iUh30'));
        $i = 1;
        $status_update = false;

        var_dump(count($domains));
        foreach ($domains as $domain)
        {
            $url = $domain->getAttribute('innerHTML');
            $listKey[0]->domain = str_replace('http://','',$listKey[0]->domain);
            $listKey[0]->domain = str_replace('https://','',$listKey[0]->domain);
            $listKey[0]->domain = str_replace('/','',$listKey[0]->domain);

            if (strlen(strstr($url, $listKey[0]->domain)) > 0)
            {
                $this->update_rank($listKey[0]->id,$i);
                DB::table('history_keyword')->insert([
                    'id_keyword' => $listKey[0]->id,
                    'rank' => $i,
                    'date_check' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                ]);
                $status_update = true;
                break;
            }
            $i++;
        }
        if($status_update == false)
        {
            $this->update_rank($listKey[0]->id,0);
            DB::table('history_keyword')->insert([
                'id_keyword' => $listKey[0]->id,
                'rank' => 0,
                'date_check' => Carbon::now('Asia/Ho_Chi_Minh'),
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
            ]);
        }
        unset($listKey[0]);


        foreach ($listKey as $key)
        {
            $driver->findElement(WebDriverBy::cssSelector('input.gLFyf'))->clear();
            $driver->findElement(WebDriverBy::cssSelector('input.gLFyf'))->click();
            sleep(1);
            $driver->findElement(WebDriverBy::cssSelector('input.gLFyf'))->sendKeys($key->key_word);
            sleep(1);
            $driver->findElement(WebDriverBy::cssSelector('button.Tg7LZd'))->click();
            sleep(3);
            $domains = $driver->findElements(WebDriverBy::cssSelector('cite.iUh30'));
            var_dump(count($domains));

            $i = 1;
            $status_update = false;
            foreach ($domains as $domain)
            {
                $url = $domain->getAttribute('innerHTML');

                $key->domain = str_replace('http://','',$key->domain);
                $key->domain = str_replace('https://','',$key->domain);
                $key->domain = str_replace('/','',$key->domain);
                if (strlen(strstr($url, $key->domain)) > 0)
                {
                    $this->update_rank($key->id,$i);
                    DB::table('history_keyword')->insert([
                        'id_keyword' => $key->id,
                        'rank' => $i,
                        'date_check' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                    ]);
                    $status_update = true;
                    break;
                }
                $i++;
            }
            if($status_update == false)
            {
                $this->update_rank($key->id,0);
                DB::table('history_keyword')->insert([
                    'id_keyword' => $key->id,
                    'rank' => 0,
                    'date_check' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
                ]);
            }
        }

       // $driver->quit();
    }


    public function check_RD_Anchor()
    {
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $USE_FIREFOX = true; // if false, will use chrome.
        $caps = DesiredCapabilities::chrome();
        $prefs = array();
        $options = new ChromeOptions();
        $prefs['profile.default_content_setting_values.notifications'] = 2;
        $options->setExperimentalOption("prefs", $prefs);
        // firefox
        $profile = new FirefoxProfile();
        $caps = DesiredCapabilities::firefox();
        $caps->setCapability(FirefoxDriver::PROFILE, $profile);

        if ($USE_FIREFOX)
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        else
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        $driver->get("https://dexuat.com/");

        $domains = DB::table('tbl_pbn')->get();
        foreach ($domains as $domain)
        {
            $driver->findElement(WebDriverBy::cssSelector('input.text_domainpbn'))->sendKeys($domain->domain);
            sleep(1);
            $driver->findElement(WebDriverBy::cssSelector('button.check_domainpbn'))->click();
            sleep(5);
            $rd = $driver->findElement(WebDriverBy::id('referring_domains'))->getAttribute('innerHTML');
            $anchor_list = $driver->findElement(WebDriverBy::id('anchors_list'));
            $anchor = $anchor_list->findElements(WebDriverBy::tagName('br'));

            var_dump($rd);
            var_dump(count($anchor));
            DB::table('tbl_pbn')->where('id',$domain->id)->update([
                'rd' => $rd,
                'anchor' => count($anchor),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
            ]);
            var_dump($domain->domain . ' success');
            $driver->findElement(WebDriverBy::cssSelector('input.text_domainpbn'))->clear();
        }
       var_dump('All complete');
       $driver->quit();
    }

    public function check_Whois()
    {
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $USE_FIREFOX = true; // if false, will use chrome.
        $caps = DesiredCapabilities::chrome();
        $prefs = array();
        $options = new ChromeOptions();
        $prefs['profile.default_content_setting_values.notifications'] = 2;
        $options->setExperimentalOption("prefs", $prefs);
        // firefox
        $profile = new FirefoxProfile();
        $caps = DesiredCapabilities::firefox();
        $caps->setCapability(FirefoxDriver::PROFILE, $profile);

        if ($USE_FIREFOX)
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        else
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }

        $domains = DB::table('tbl_pbn')->get();
        foreach ($domains as $domain)
        {
            $dm = str_replace('http://','',$domain->domain);
            $dm = str_replace('https://','',$dm);
            $dm = str_replace('/','',$dm);

            $url = 'https://www.whois.com/whois/'.$dm;
            $driver->get($url);
            $heading = $driver->findElements(WebDriverBy::cssSelector('div.df-heading'));

            $whosis = 0;
            foreach ($heading as $element)
            {
                if($element->getText() == 'Administrative Contact')
                {
                    $whosis = 1;
                    break;
                }
            }

            $label = $driver->findElements(WebDriverBy::cssSelector('div.df-label'));
            $value = $driver->findElements(WebDriverBy::cssSelector('div.df-value'));

            $dns = '';
            $register_date = '';
            $exprired_date = '';
            for($i = 0;$i < count($label) ;$i++)
            {
                if($label[$i]->getText() == 'Name Servers:')
                {
                    $dns = $value[$i]->getText();
                }
                if($label[$i]->getText() == 'Registered On:')
                {
                    $register_date = $value[$i]->getText();
                }
                if($label[$i]->getText() == 'Expires On:')
                {
                    $exprired_date = $value[$i]->getText();
                }
            }
            DB::table('tbl_pbn')->where('id',$domain->id)->update([
                'whois' =>  $whosis,
                'dns' => $dns,
                'register_date' => $register_date,
                'expired_date' => $exprired_date,
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
            ]);

            var_dump($domain->domain. ' success');

        }
        var_dump('All complete');
        $driver->quit();
    }

    public function delele_domain($id)
    {
        DB::table('tbl_pbn')->where('id',$id)->delete();
        return redirect()->back();
    }

}
