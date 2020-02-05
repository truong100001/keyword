<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use function foo\func;
use Illuminate\Http\Request;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class CheckController extends Controller
{
    public function loginGG(){
        echo 'login';
//        $mail = 'thanhtoanlc104';
//        $mailPass = 'Toan1234@';
        $mail = 'tranviettruong100001';
        $mailPass = 'truong04061998';

        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $USE_FIREFOX = true; // if false, will use chrome.
        $caps = DesiredCapabilities::chrome();
        $prefs = array();
        $options = new ChromeOptions();
        $prefs['profile.default_content_setting_values.notifications'] = 2;
        $prefs = array('download.default_directory' => 'c:/temp');
        $options->setExperimentalOption("prefs", $prefs);
        $caps->setCapability(
            'moz:firefoxOptions',
            ['args' => ['-headless']]
        );
        //$ss = array('--headless','--start-maximized');
        //$options->addArguments($ss);
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);

        if ($USE_FIREFOX)
        {
            $driver = RemoteWebDriver::create(
                $host,
                DesiredCapabilities::firefox()
            );
        }
        else
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        #Access link Google Ads
        $driver->get("https://ads.google.com/intl/vi_vn/home/");
        sleep(2);

        #Click button sign-in
        $driver->findElement(WebDriverBy::linkText(
            'Đăng nhập'))
            ->click();
        sleep(1);

        #Fill email
        $driver->findElement(WebDriverBy::id('identifierId'))->sendKeys($mail);
        $driver->findElement(WebDriverBy::className('CwaK9'))->click();
        sleep(1);
        #Fill password
        $driver->findElement(WebDriverBy::cssSelector('input[type="password"]'))->sendKeys($mailPass);
        $driver->findElement(WebDriverBy::className('CwaK9'))->click();
        sleep(3);

        #Select account Ads
        /*$elements = $driver->findElements(WebDriverBy::cssSelector('a.umx-l'));
        foreach ($elements as $element) {
            if ($element->getAttribute('href') == "https://ads.google.com/um/identity?authuser=2&dst=/um/homepage?__e%3D6119414799") {
                $element->click();
                break;
            }
        }*/
        try {
            $driver->findElement(WebDriverBy::partialLinkText(
                'bui quoc toan'))->click();sleep(5);
        } catch (\Exception $e) {
            sleep(5);
        }
        $list = $driver->manage()->getCookies();
        foreach ($list as $cookie) {
            //$domain = $cookie->getDomain();
            print_r($cookie);
            $tmp = $cookie['name'].':'.$cookie['value'];
            file_put_contents(public_path('gg.txt'), $tmp.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        $driver->quit();
    }

    public function kwplanerRelateApp($tabs_id){
       // $this->loginGG();

        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $USE_FIREFOX = true; // if false, will use chrome.
        $caps = DesiredCapabilities::chrome();
        $prefs = array();
        $options = new ChromeOptions();
        $prefs['profile.default_content_setting_values.notifications'] = 2;
        $prefs = array('download.default_directory' => 'c:/temp');
        $options->setExperimentalOption("prefs", $prefs);
        //$ss = array('--headless','--start-maximized');
        //$options->addArguments($ss);
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $capabilities = DesiredCapabilities::firefox();
        /*$capabilities->setCapability(
            'moz:firefoxOptions',
           ['args' => ['-headless']]
        );*/
        if ($USE_FIREFOX)
        {
            $driver = RemoteWebDriver::create(
                $host,
                $capabilities
            );
        }
        else
        {
            $driver = RemoteWebDriver::create(
                $host,
                $caps
            );
        }
        $driver->get("https://ads.google.com/intl/vi_vn/home/");
        sleep(3);

        $cookies = file_get_contents(public_path('gg.txt'));
        $cookies = explode(PHP_EOL,$cookies);

        foreach ($cookies as $cookie) {

            $tmp = explode(':',$cookie);
            if(isset($tmp[1])){
                $tmp_ck = array('name' => $tmp[0], 'value' => $tmp[1]);
                $driver->manage()->addCookie($tmp_ck);
            }

        }


        #Access link planner Ads
        $driver->get('https://ads.google.com/aw/keywordplanner/home?ocid=404176715&euid=386562172&__u=9317744028&uscid=404176715&__c=1874106035&authuser=0');
        sleep(7);


        $driver->findElement(WebDriverBy::cssSelector('div.card-frame'))->click();
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->click();
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys('test');
        sleep(0.5);
        #Click enter on keyboard to search
        $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys(WebDriverKeys::ENTER);
        sleep(2);
        #Click button 'search' to redirect page resutl
        $driver->findElement(
            WebDriverBy::cssSelector('div.get-results-button-container > material-button.get-results-button')
        )->click();
        sleep(3);

        #Remove localtion in Viet Nam
        $driver->findElement(WebDriverBy::className('location-button'))->click();
        sleep(2);
        $driver->findElement(WebDriverBy::cssSelector('td.remove'))->click();
        sleep(3);
        try {
            $driver->findElement(WebDriverBy::cssSelector('material-button.highlighted'))->click();
            sleep(3);
        } catch (\Exception $e1) {
            $driver->findElement(WebDriverBy::cssSelector('material-button.highlighted'))->click();
            sleep(3);
        }


        #Select language English
        $driver->findElement(WebDriverBy::cssSelector('language-selector'))->click();
        sleep(2);
        $driver->findElement(WebDriverBy::cssSelector('input[aria-label="Tìm kiếm ngôn ngữ"]'))
            ->sendKeys('Anh');
        sleep(1);
        $driver->findElement(WebDriverBy::cssSelector('input[aria-label="Tìm kiếm ngôn ngữ"]'))
            ->sendKeys(WebDriverKeys::ENTER);

        sleep(2);
        //============================================================================================================

        $page = DB::table('tabs')->where('id',$tabs_id)->first();

        for($i = $page->page_from+1; $i < $page->page_to ; $i++) {

            $apps = $this->get_Api($i, 10)['data'];

            $driver->findElement(WebDriverBy::cssSelector('search-chips-summary.enable-background > div.summary'))->click();
            sleep(1);

            for ($j = 0; $j < 10; $j++) {
                // xóa từ khóa cũ đi
                $driver->findElement(WebDriverBy::cssSelector('.seeds-underline .search-input'))->sendKeys(WebDriverKeys::BACKSPACE);
                sleep(0.3);
            }

            $dem = 0;
            for ($j = 0; $j < count($apps); $j++) {
                if (isset($apps[$j]['title'])) {
                    if ($this->xoa_ky_tu_dac_biet($apps[$j]['title']) != '') {

                        $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->click();
                        sleep(0.5);
                        $input = $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'));
                        $input->sendKeys($this->xoa_ky_tu_dac_biet($apps[$j]['title']));
                        sleep(0.7);
                        $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys(WebDriverKeys::ENTER);
                        sleep(0.5);

                        $dem++;
                    }
                }
            }

            if ($dem == 0)
            {
                $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->click();
                sleep(0.5);
                $input = $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'));
                $input->sendKeys('test');
                sleep(0.7);
                $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys(WebDriverKeys::ENTER);
                sleep(0.5);
            }

            $driver->findElement(
                WebDriverBy::cssSelector('div.get-results-button-container > material-button.get-results-button')
            )->click();
            sleep(3);


            // $keys = $driver->findElements(WebDriverBy::cssSelector('div.particle-table-row > ess-cell.resizable > keyword-text._nghost-awn-KP-86 > div.keyword-text > span.keyword'));

            try{
                $driver->wait(10, 300)->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//ess-cell[@essfield="text"]'))
                );

                $keys = $driver->findElements(WebDriverBy::xpath('//ess-cell[@essfield="text"]'));

                sleep(0.5);

                $driver->wait(10, 300)->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//ess-cell[@essfield="search_volume"]'))
                );

                $search = $driver->findElements(WebDriverBy::xpath('//ess-cell[@essfield="search_volume"]'));

                sleep(0.5);

                $driver->wait(10, 300)->until(
                    WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//ess-cell[@essfield="competition"]'))
                );

                $compete = $driver->findElements(WebDriverBy::xpath('//ess-cell[@essfield="competition"]'));
            }
            catch (\Exception $e)
            {
                var_dump($i);
                DB::table('tabs')->where('id',$tabs_id)->update([
                    'page_from' => $i
                ]);
                var_dump('php artisan check'.$tabs_id);
                continue;
            }



            $keyword = '';
            for($j = 0; $j < count($apps); $j++)
            {
                $num_search = '';
                $canhtranh = '';

                if(isset($apps[$j]['title']) && $apps[$j]['title'] != '')
                {
                    $app_title = strtolower($apps[$j]['title']);

                    for($k = 0; $k < count($keys); $k++)
                    {

                        $key_planner = strtolower($keys[$k]->getText());

                        if($this->check_latin($key_planner))
                        {
                            similar_text($app_title,$key_planner,$percent);

                            if($percent > 80)
                            {
                                $keyword = $keys[$k]->getText();
                                $num_search = $this->convertToNumber($search[$k]->getText());
                                $canhtranh = $compete[$k]->getText();
                                break;
                            }
                        }
                        else
                        {
                            if($this->check_similar($app_title,$key_planner))
                            {
                                $keyword = $keys[$k]->getText();
                                $num_search = $this->convertToNumber($search[$k]->getText());
                                $canhtranh = $compete[$k]->getText();
                                break;
                            }
                        }

                    }

                    if($num_search == '')
                        continue;

                    var_dump($apps[$j]['title']);
                    var_dump($keyword);
                    var_dump($num_search);
                    var_dump($canhtranh);
                    var_dump('------------------------');

                    $ct = 0;
                    if($canhtranh == 'Vừa')
                        $ct = 1;
                    if($canhtranh == 'Cao')
                        $ct = 2;
                    if($canhtranh == '—')
                        $ct = 3;


                    DB::table('apktovi_keywords')->insert([
                        'appid' => $apps[$j]['appid'],
                        'title' => $apps[$j]['title'],
                        'keyword' => $keyword,
                        'num_search' => $num_search,
                        'compete' => $ct,
                        'language' => 'english',
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
                }

            }

            var_dump($i);
            DB::table('tabs')->where('id',$tabs_id)->update([
               'page_from' => $i
            ]);
            var_dump('php artisan check'.$tabs_id);
            var_dump('=====================================================');
        }
        var_dump('success');

        die();
        //=============================================================================================================


//        $keywords = DB::table('keywords')->where('checked',0)->get();
//
//        $start = 0;
//
//        for($i = 10; $i < count($keywords) ;$i += 10)
//        {
//            for($j = $start;$j < $i; $j++)
//            {
//                DB::table('keywords')->where('id',$keywords[$j]->id)->update([
//                    'checked' => 1
//                ]);
//            }
//
//            $driver->findElement(WebDriverBy::cssSelector('search-chips-summary.enable-background > div.summary'))->click();
//            sleep(1);
//
//            for($j = $start;$j < $i; $j++)
//            {
//                // xóa từ khóa cũ đi
//                $driver->findElement(WebDriverBy::cssSelector('.seeds-underline .search-input'))->sendKeys(WebDriverKeys::BACKSPACE);
//                sleep(0.5);
//            }
//
//            for($j = $start;$j < $i; $j++)
//            {
//                $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->click();
//                sleep(0.5);
//                $input= $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'));
//                $input->sendKeys($keywords[$j]->keyword);
//                sleep(1);
//                $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys(WebDriverKeys::ENTER);
//                sleep(0.5);
//            }
//
//            $driver->findElement(
//                WebDriverBy::cssSelector('div.get-results-button-container > material-button.get-results-button')
//            )->click();
//            sleep(3);
//
//
//           // $keys = $driver->findElements(WebDriverBy::cssSelector('div.particle-table-row > ess-cell.resizable > keyword-text._nghost-awn-KP-86 > div.keyword-text > span.keyword'));
//
//           $keys = $driver->findElements(WebDriverBy::xpath('//ess-cell[@essfield="text"]'));
//
//            $text = $driver->findElements(WebDriverBy::cssSelector('div.particle-table-row > ess-cell.data-numeric > sparkline-graph._nghost-awn-KP-87 > span._ngcontent-awn-KP-87'));
//
//            //$compete = $driver->findElements(WebDriverBy::cssSelector('ess-cell.resizable text-field'));
//
//            //$compete = $driver->findElements(WebDriverBy::cssSelector('div.particle-table-row > ess-cell.resizable > text-field'));
//
//            $compete = $driver->findElements(WebDriverBy::xpath('//ess-cell[@essfield="competition"]'));
//
//
//            for($j = $start;$j < $i; $j++)
//            {
//                $num_search = '';
//                $canhtranh = '';
//
//                for($k = 0; $k < count($keys); $k++)
//                {
//                    if($keys[$k]->getText() == $keywords[$j]->keyword)
//                    {
//                        $num_search = $this->convertToNumber($text[$k]->getText());
//                        $canhtranh = $compete[$k]->getText();
//                        break;
//                    }
//                }
//
//
//                if($num_search == '')
//                    continue;
//
//                var_dump($keywords[$j]->keyword);
//                var_dump($num_search);
//                var_dump($canhtranh);
//                var_dump('------------------------');
//
//
//                DB::table('keywords')->where('id',$keywords[$j]->id)->update([
//                    'num_search' => $num_search,
//                    'compete' => $canhtranh
//                ]);
//
//            }
//            var_dump($start);
//            var_dump('----------------');
//            $start = $i;
//        }
//        var_dump('success');
//
//        die();
//*************************************************************************************************************
//        foreach ($keywords as $keyword)
//        {
//            sleep(1);
//            // xóa từ khóa cũ đi
//            $driver->findElement(WebDriverBy::cssSelector('search-chips-summary.enable-background > div.summary'))->click();
//            sleep(1);
//            $driver->findElement(WebDriverBy::cssSelector('.seeds-underline .search-input'))->sendKeys(WebDriverKeys::BACKSPACE);
//            sleep(1);
//
//            // điền từ khóa mới vào
//            $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->click();
//            sleep(1);
//            $input= $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'));
//            $input->sendKeys($keyword->keyword);
//
//            sleep(0.5);
//
//            // ấn search
//            $driver->findElement(WebDriverBy::cssSelector('div.input-container > input.search-input'))->sendKeys(WebDriverKeys::ENTER);
//            sleep(1);
//            $driver->findElement(
//                WebDriverBy::cssSelector('div.get-results-button-container > material-button.get-results-button')
//            )->click();
//            sleep(2);
//
//
//
//            $text = $driver->findElements(WebDriverBy::cssSelector('div.particle-table-row > ess-cell.data-numeric > sparkline-graph._nghost-awn-KP-87 > span._ngcontent-awn-KP-87'));
//
//            $compete = $driver->findElements(WebDriverBy::cssSelector('ess-cell.resizable text-field'));
//
//            if(!isset($text[0]))
//                continue;
//
//            var_dump($text[0]->getText());
//            var_dump($this->convertToNumber($text[0]->getText()));
//            var_dump($compete[0]->getText());
//            var_dump('-------------------------------------------');
//
//            DB::table('keywords')->where('id',$keyword->id)->update([
//                'num_search' => $this->convertToNumber($text[0]->getText()),
//                'compete' => $compete[0]->getText()
//            ]);
//        }

    }

    public function convertToNumber($str)
    {
        $string = explode('-',$str)[0];
        $arr = explode(' ',$string);
        $num = '';
        if($arr[1] == 'N')
            $num = '000';
        else if($arr[1] == 'Tr')
            $num = '000000';
        else if($arr[1] == '')
            $num = '';
        return $arr[0].$num;
    }

    public function readFileExecl()
    {
        $path = public_path('dataset.csv');
        $rows = Excel::load($path,function ($reader){})->toArray();

        foreach ($rows as $columns)
        {
            DB::table('keywords')->insert([
                'url' => $columns['url'],
                'keyword' => $columns['top_keyword'],
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            ]);
            var_dump('ok ');
        }
        var_dump('success');
    }

    public function get_Api($page,$size)
    {
        $url = 'http://api.tovicorp.com/listAppNoFile?page='.$page.'&size='.$size;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data  = json_decode($response);
        $value = json_decode(json_encode($data), true);
        return $value;
    }

    public function check_latin($str)
    {
        $arr_latinh = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            't',
            'w',
            'z',
            'v',
            'x',
            'u'
        ];
        $arr_str = [];

        for($i = 0; $i <  strlen($str);$i++)
        {
            array_push($arr_str,substr($str,$i,1));
        }

        foreach ($arr_latinh as $latinh)
        {
            foreach ($arr_str as $value)
            {
                if($latinh == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function check_similar($str1,$str2)
    {
        if(strlen($str1) > strlen($str2))
        {
            $temp = $str2;
            $str2 = $str1;
            $str1 = $temp;
        }

        $str1 = strtolower($str1);
        $str2 = strtolower($str2);

        $str1 = preg_replace('/\s+/', '', $str1);
        $str2 = preg_replace('/\s+/', '', $str2);

        $arr_str1 = [];
        $arr_str2 = [];

        for($i = 0; $i <  strlen($str1);$i++)
        {
            array_push($arr_str1,substr($str1,$i,1));
        }

        for($i = 0; $i <  strlen($str2);$i++)
        {
            array_push($arr_str2,substr($str2,$i,1));
        }

        $dem = 0;
        for($i = 0; $i <  strlen($str1);$i++)
        {
            if(isset($arr_str2[$i]))
            {
                if($arr_str1[$i] == $arr_str2[$i])
                    $dem++;
            }
        }

        if($dem >= count($arr_str1) - 2)
        {
            return true;
        }
        return false;
    }

    public function xoa_ky_tu_dac_biet($str)
    {
        $ketqua = preg_replace('/([^\pL\.\ ]+)/u', '', $str);
        return $ketqua;
    }


    //*****************************************************************************************

    public function tab1()
    {
        $this->kwplanerRelateApp(1);
    }

    public function tab2()
    {
        $this->kwplanerRelateApp(2);
    }

    public function tab3()
    {
        $this->kwplanerRelateApp(3);
    }

    public function tab4()
    {
        $this->kwplanerRelateApp(4);
    }

    public function tab5()
    {
        $this->kwplanerRelateApp(5);
    }

    public function tab6()
    {
        $this->kwplanerRelateApp(6);
    }

    public function tab7()
    {
        $this->kwplanerRelateApp(7);
    }

    public function tab8()
    {
        $this->kwplanerRelateApp(8);
    }

    public function tab9()
    {
        $this->kwplanerRelateApp(9);
    }

    public function tab10()
    {
        $this->kwplanerRelateApp(10);
    }






}
