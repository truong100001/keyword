
@if(session('message'))
    <script>
        Swal.fire({
            icon: 'success',
            text:"Thêm thành công",
            showConfirmButton: true,
            timer:3000
        });
    </script>
@endif

<div class="form-example-area" style="margin-top: 50px">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-example-wrap">
                    <div class="cmp-tb-hd">
                        <h2>Thêm mới</h2>
                    </div>
                    <form action="{{asset('/add-domain')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-tax"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="domain" type="text" class="form-control" placeholder="Domain">
                                        @if($errors->has('domain'))
                                            <div class="text-danger text-small">{{ $errors->first('domain') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-credit-card"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="link_to" type="text" class="form-control" placeholder="link_to">
                                        @if($errors->has('link_to'))
                                            <div class="text-danger text-small">{{ $errors->first('link_to') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-star"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="cdn" type="text" class="form-control" placeholder="CDN">
                                        @if($errors->has('cdn'))
                                            <div class="text-danger text-small">{{ $errors->first('cdn') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-ip-locator"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="ip" type="text" class="form-control"  placeholder="IP">
                                        @if($errors->has('ip'))
                                            <div class="text-danger text-small">{{ $errors->first('ip') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-support"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="name_register" type="text" class="form-control" placeholder="Nhà đăng ký tên miền">
                                        @if($errors->has('name_register'))
                                            <div class="text-danger text-small">{{ $errors->first('name_register') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">
                                <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                        <i class="notika-icon notika-mail"></i>
                                    </div>
                                    <div class="nk-int-st">
                                        <input name="email" type="text" class="form-control" placeholder="Email đăng ký">
                                        @if($errors->has('email'))
                                            <div class="text-danger text-small">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">--}}
                                {{--<div class="form-group ic-cmp-int">--}}
                                    {{--<div class="form-ic-cmp">--}}
                                        {{--<i class="notika-icon notika-calendar"></i>--}}
                                    {{--</div>--}}
                                    {{--<div class="nk-int-st">--}}
                                        {{--<input name="register_date" type="text" class="form-control" data-mask="99/99/9999" placeholder="Ngày đăng ký tên miền">--}}
                                        {{--@if($errors->has('register_date'))--}}
                                            {{--<div class="text-danger text-small">{{ $errors->first('register_date') }}</div>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">--}}
                                {{--<div class="form-group ic-cmp-int">--}}
                                    {{--<div class="form-ic-cmp">--}}
                                        {{--<i class="notika-icon notika-calendar"></i>--}}
                                    {{--</div>--}}
                                    {{--<div class="nk-int-st">--}}
                                        {{--<input name="expried_date" type="text" class="form-control" data-mask="99/99/9999" placeholder="Ngày hết hạn tên miền">--}}
                                        {{--@if($errors->has('expried_date'))--}}
                                            {{--<div class="text-danger text-small">{{ $errors->first('expried_date') }}</div>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="form-example-int mg-t-15">
                            <button class="btn btn-success notika-btn-success">Thêm</button>
                            <a href="{{asset('/')}}">
                                <button class="btn btn-primary notika-btn-primary" type="button">Quay lại</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Form Examples area End-->