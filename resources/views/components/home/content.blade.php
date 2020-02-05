
@if(session('message') && session('message') == 'success')
    <script>
        Swal.fire({
            icon: 'success',
            text:"Thêm thành công",
            showConfirmButton: true,
            timer:3000
        });
    </script>
@endif

@if(session('message2'))
    <script>
        Swal.fire({
            icon: 'success',
            text:"Quét hoàn thành",
            showConfirmButton: true,
            timer:3000
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            text:"Keyword này đã có trong domain này rồi",
            showConfirmButton: true,
            timer:3000
        });
    </script>
@endif


<!-- Breadcomb area Start-->
<div class="row" style="margin-top: 50px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="widget-tabs-list tab-pt-mg sm-res-mg-t-30 tb-res-mg-t-30">
            <ul class="nav nav-tabs tab-nav-center">
                <li class="@if(!session('message')) {{'active'}} @endif"><a data-toggle="tab" href="#home4">Domain</a></li>
                <li class="@if(session('message')) {{'active'}} @endif"><a data-toggle="tab" href="#menu14">Key word</a></li>
            </ul>
            <div class="tab-content tab-custom-st">
                <div id="home4" class="tab-pane in @if(!session('message')) {{'active'}} @endif animated zoomInRight">
                    <div class="breadcomb-area" style="margin-top: 30px;">
                        <div class="container1">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="breadcomb-list">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                                <div class="breadcomb-wp">
                                                    <div class="breadcomb-icon">
                                                        <i class="fas fa-network-wired"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                                <div class="breadcomb-report" style="display: block !important;">
                                                    <a href="{{asset('/check-domain')}}">
                                                        <button data-toggle="tooltip" data-placement="left" title="Quét trạng thái domain" class="btn">
                                                            <i class="notika-icon notika-refresh"></i>
                                                        </button>
                                                    </a>
                                                    <a href="{{asset('/add-domain')}}">
                                                        <button data-toggle="tooltip" data-placement="top" title="Thêm mới" class="btn"><i class="fas fa-plus"></i></button>
                                                    </a>
                                                    <button data-toggle="tooltip" data-placement="bottom" title="Nhập từ file excel" class="btn"><i class="fas fa-file-excel"></i></button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Breadcomb area End-->
                    <!-- Data Table area Start-->
                    @if(count($domain_expired) > 0)
                        <div class="data-table-area" style="margin-bottom: 50px;">
                            <div class="container1">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="data-table-list">
                                            <h3 style="margin-left: 10px">Danh sách domain sắp hết hạn</h3>
                                            <div class="table-responsive">
                                                <table id="data-table-basic-expired" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Domain</th>
                                                        <th>RD</th>
                                                        <th>Whois</th>
                                                        <th>DNS</th>
                                                        <th>CDN</th>
                                                        <th>IP (VPS)</th>
                                                        <th>Nhà đăng ký</th>
                                                        <th>Email mua</th>
                                                        <th>Ngày đăng ký</th>
                                                        <th>Ngày hết hạn</th>
                                                        <th>Link to</th>
                                                        <th>Anchor</th>
                                                        <th>Số bài</th>
                                                        <th>Bài gần nhất</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($domain_expired as $i => $domain)
                                                        <tr>
                                                            <td>{{$i+1}}</td>
                                                            <td>

                                                                @if($domain->status_domain ==1)
                                                                    <span class="text-success"> {{$domain->domain}}<i class="fas fa-check-circle"></i></span>
                                                                @else
                                                                    <span class="text-danger"> {{$domain->domain}}<i class="fas fa-times-circle"></i></span>

                                                                @endif
                                                            </td>
                                                            <td>{{$domain->rd}}</td>
                                                            <td>
                                                                @if($domain->whois == 1)
                                                                    {{'public'}}
                                                                @else
                                                                    {{'private'}}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <p class="dns">{{$domain->dns}}</p>
                                                            </td>
                                                            <td>
                                                                {{$domain->cdn}}
                                                            </td>
                                                            <td>
                                                                {{$domain->ip}}
                                                            </td>
                                                            <td>{{$domain->name_register}}</td>
                                                            <td>{{$domain->email}}</td>
                                                            <td>{{$domain->register_date}}</td>
                                                            <td>
                                                                {{$domain->expired_date}}
                                                                <span class="bage">{{$domain->expired_day}} </span>
                                                            </td>
                                                            <td>{{$domain->link_to}}</td>
                                                            <td>{{$domain->anchor}}</td>
                                                            <td>{{$domain->num_post}}</td>
                                                            <td>{{$domain->latest_post}}</td>
                                                            <td>
                                                                <a href="{{asset('/delete-domain/'.$domain->id)}}">
                                                                    <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>

                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(count($domains_error) > 0)
                    <div class="data-table-area" style="margin-bottom: 50px;">
                        <div class="container1">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="data-table-list">
                                        <h3 style="margin-left: 10px">Danh sách domain lỗi</h3>
                                        <div class="table-responsive">
                                            <table id="data-table-basic-error" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Domain</th>
                                                    <th>RD</th>
                                                    <th>Whois</th>
                                                    <th>DNS</th>
                                                    <th>CDN</th>
                                                    <th>IP (VPS)</th>
                                                    <th>Nhà đăng ký</th>
                                                    <th>Email mua</th>
                                                    <th>Ngày đăng ký</th>
                                                    <th>Ngày hết hạn</th>
                                                    <th>Link to</th>
                                                    <th>Anchor</th>
                                                    <th>Số bài</th>
                                                    <th>Bài gần nhất</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($domains_error as $i => $domain)
                                                    <tr>
                                                        <td>{{$i+1}}</td>
                                                        <td>

                                                            @if($domain->status_domain ==1)
                                                                <span class="text-success"> {{$domain->domain}}<i class="fas fa-check-circle"></i></span>
                                                            @else
                                                                <span class="text-danger"> {{$domain->domain}}<i class="fas fa-times-circle"></i></span>

                                                            @endif
                                                        </td>
                                                        <td>{{$domain->rd}}</td>
                                                        <td>
                                                            @if($domain->whois == 1)
                                                                {{'public'}}
                                                            @else
                                                                {{'private'}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <p class="dns">{{$domain->dns}}</p>
                                                        </td>
                                                        <td>
                                                            {{$domain->cdn}}
                                                        </td>
                                                        <td>
                                                            {{$domain->ip}}
                                                        </td>
                                                        <td>{{$domain->name_register}}</td>
                                                        <td>{{$domain->email}}</td>

                                                        <td>{{$domain->register_date}}</td>
                                                        <td>
                                                            {{$domain->expired_date}}
                                                            <span class="bage">{{$domain->expired_day}} </span>
                                                        </td>
                                                        <td>{{$domain->link_to}}</td>
                                                        <td>{{$domain->anchor}}</td>
                                                        <td>{{$domain->num_post}}</td>
                                                        <td>{{$domain->latest_post}}</td>
                                                        <td>
                                                            <a href="{{asset('/delete-domain/'.$domain->id)}}">
                                                                <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>

                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="data-table-area">
                        <div class="container1">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="data-table-list">
                                        <div class="table-responsive">
                                            <table id="data-table-basic" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Domain</th>
                                                    <th>RD</th>
                                                    <th>Whois</th>
                                                    <th>DNS</th>
                                                    <th>CDN</th>
                                                    <th>IP (VPS)</th>
                                                    <th>Nhà đăng ký</th>
                                                    <th>Email mua</th>
                                                    <th>Ngày đăng ký</th>
                                                    <th>Ngày hết hạn</th>

                                                    <th>Link to</th>
                                                    <th>Anchor</th>
                                                    <th>Số bài</th>
                                                    <th>Bài gần nhất</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($domains as $i => $domain)
                                                <tr>
                                                    <td>{{$i+1}}</td>
                                                    <td>

                                                        @if($domain->status_domain ==1)
                                                                <span class="text-success"> {{$domain->domain}}<i class="fas fa-check-circle"></i></span>
                                                        @else
                                                                <span class="text-danger"> {{$domain->domain}}<i class="fas fa-times-circle"></i></span>
                                                        @endif
                                                    </td>
                                                    <td>{{$domain->rd}}</td>
                                                    <td>
                                                        @if($domain->whois == 1)
                                                            <span class="text-info">public</span>
                                                        @else
                                                            <span class="text-warning">private</span>
                                                         @endif
                                                    </td>
                                                    <td>
                                                        <p class="dns">{{$domain->dns}}</p>
                                                    </td>
                                                    <td>
                                                        {{$domain->cdn}}
                                                    </td>
                                                    <td>
                                                        {{$domain->ip}}
                                                    </td>
                                                    <td>{{$domain->name_register}}</td>
                                                    <td>{{$domain->email}}</td>
                                                    <td>{{$domain->register_date}}</td>
                                                    <td>
                                                        {{$domain->expired_date}}
                                                        <span class="bage">{{$domain->expired_day}} </span>
                                                    </td>
                                                    <td>{{$domain->link_to}}</td>
                                                    <td>{{$domain->anchor}}</td>
                                                    <td>{{$domain->num_post}}</td>
                                                    <td>{{$domain->latest_post}}</td>
                                                    <td>
                                                        <a href="{{asset('/delete-domain/'.$domain->id)}}">
                                                            <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>

                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="menu14" class="tab-pane @if(session('message')) {{'active'}} @endif animated zoomInRight">
                    <div class="breadcomb-area" style="margin-top: 30px;">
                        <div class="container1 listKeyWord">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="breadcomb-list">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="breadcomb-wp">
                                                    <div class="breadcomb-icon">
                                                        <i class="fas fa-key"></i>
                                                    </div>
                                                    <div class="breadcomb-ctn" >
                                                        <h4 class="text-primary" id="count" style="line-height: 40px">{{$count}} kết quả</h4>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >
                                                <div class="breadcomb-report" style="display:block !important;">

                                                    <button data-toggle="modal" data-target="#myModalone" data-placement="top" title="Thêm mới" class="btn"><i class="fas fa-plus"></i></button>
                                                    <div class="modal fade" id="myModalone" role="dialog">
                                                        <div class="modal-dialog modals-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-example-wrap">
                                                                        <div class="cmp-tb-hd">
                                                                            <h2>Thêm mới</h2>
                                                                        </div>
                                                                        <form action="{{asset('/add-keyword')}}" method="post">
                                                                            {{csrf_field()}}
                                                                            <div class="row">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <div class="form-group ic-cmp-int">
                                                                                        <div class="form-ic-cmp">
                                                                                            <i class="far fa-address-card"></i>
                                                                                        </div>
                                                                                        <div class="chosen-select-act fm-cmp-mg">
                                                                                            <select name="id_domain" class="chosen" data-placeholder="Choose a Country...">
                                                                                                @foreach($domains as $domain)
                                                                                                    <option value="{{$domain->id}}">{{$domain->domain}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                                                                    <div class="form-group ic-cmp-int">
                                                                                        <div class="form-ic-cmp">
                                                                                            <i class="notika-icon notika-edit"></i>
                                                                                        </div>
                                                                                        <div class="nk-int-st">
                                                                                            <input name="keyword" type="text" class="form-control" placeholder="Nhập từ khóa">
                                                                                            @if($errors->has('keyword'))
                                                                                                <div class="text-danger text-small">{{ $errors->first('keyword') }}</div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-example-int mg-t-15">
                                                                                <button class="btn btn-success notika-btn-success">Thêm</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button data-toggle="modal" data-target="#myModaloneFilter" data-toggle="tooltip" data-placement="bottom" title="Lọc" class="btn"><i class="fas fa-filter"></i></button>

                                                    <div class="modal fade" id="myModaloneFilter" role="dialog">
                                                        <div class="modal-dialog modals-default">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-example-wrap">
                                                                        <div class="cmp-tb-hd">
                                                                            <h2>Lọc từ khóa</h2>
                                                                        </div>

                                                                            {{csrf_field()}}
                                                                            <div class="row">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                    <div class="form-group ic-cmp-int">
                                                                                        <div class="form-ic-cmp">
                                                                                            <i class="fab fa-adn"></i>
                                                                                        </div>
                                                                                        <div class="chosen-select-act fm-cmp-mg">
                                                                                            <select id="domain" class="chosen" data-placeholder="Choose a Country...">
                                                                                                <option value=""> Chọn domain </option>
                                                                                                @foreach($domains as $domain)
                                                                                                    <option value="{{$domain->id}}">{{$domain->domain}}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                                                                    <div class="form-group ic-cmp-int">
                                                                                        <div class="form-ic-cmp">
                                                                                            <i class="fab fa-hackerrank"></i>
                                                                                        </div>
                                                                                        <div class="chosen-select-act fm-cmp-mg">
                                                                                            <select id="rank" class="chosen" data-placeholder="Thứ hạng">
                                                                                                    <option value=""> Thứ hạng </option>
                                                                                                    <option value="0">Top 0</option>
                                                                                                    <option value="1">Top 1</option>
                                                                                                    <option value="2">Top 2</option>
                                                                                                    <option value="3">Top 3</option>
                                                                                                    <option value="4">Top 4</option>
                                                                                                    <option value="5">Top 5</option>
                                                                                                    <option value="6">Top 6</option>
                                                                                                    <option value="7">Top 7</option>
                                                                                                    <option value="8">Top 8</option>
                                                                                                    <option value="9">Top 9</option>
                                                                                                    <option value="10">Top 10</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-example-int mg-t-15">
                                                                                <button id="filter" class="btn btn-success notika-btn-success">Lọc</button>
                                                                            </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button id='refresh' data-toggle="tooltip" data-placement="bottom" title="Refresh" class="btn"><i class="notika-icon notika-refresh"></i></button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Breadcomb area End-->
                    <!-- Data Table area Start-->
                    <div class="data-table-area">
                        <div class="container1 " >
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="data-table-list">
                                        <div class="table-responsive">
                                            <table id="data-table-basic1" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Domain</th>
                                                    <th>Key word</th>
                                                    <th>Thứ hạng</th>
                                                    <th>Lần quét cuối cùng</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="list_keyword">
                                                    @foreach($keywords as $i => $keyword)
                                                    <tr>
                                                        <td>{{$i+1}}</td>
                                                        <td>{{$keyword->domain}}</td>
                                                        <td>
                                                            @if($keyword->rank == 0)
                                                                <a target="_blank" href="https://www.google.com.vn/search?hl=en&q={{$keyword->key_word}}">
                                                                    <span class="text-danger">{{$keyword->key_word}}</span>
                                                                </a>
                                                            @elseif($keyword->rank == 1)
                                                                <a target="_blank" href="https://www.google.com.vn/search?hl=en&q={{$keyword->key_word}}">
                                                                    <span class="text-success">{{$keyword->key_word}}</span>
                                                                </a>
                                                            @else
                                                                <a target="_blank" href="https://www.google.com.vn/search?hl=en&q={{$keyword->key_word}}">
                                                                    {{$keyword->key_word}}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($keyword->rank == 0)
                                                                <span class="text-danger">{{$keyword->rank}}</span>
                                                            @elseif($keyword->rank == 1)
                                                                <span class="text-success">{{$keyword->rank}}</span>
                                                            @else
                                                                {{$keyword->rank}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{date('H:i d/m/Y', strtotime($keyword->updated_at))}}
                                                        </td>
                                                        <td>
                                                            <button onclick="history_keyword({{$keyword->id}})" data-toggle="modal" data-target="#myModalone2" class="btn btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-menu"></i>Xem lịch sử</button>
                                                            <div class="modal fade" id="myModalone2" role="dialog">
                                                                <div class="modal-dialog modals-default">
                                                                    <div class="modal-content" style="padding: 0px 0px;">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" style="z-index: 999">&times;</button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="data-table-list">
                                                                                <div class="table-responsive">
                                                                                    <table id="data-table-basic-history" class="table table-striped">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>STT</th>
                                                                                            <th>Domain</th>
                                                                                            <th>Key word</th>
                                                                                            <th>Thứ hạng</th>
                                                                                            <th>Ngày quét</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody id="list_history">

                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <a href="{{asset("/delete-keyword/".$keyword->id)}}">
                                                                <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
