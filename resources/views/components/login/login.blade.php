@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            text:"Tên đăng nhập hoặc mật khẩu không chính xác",
            showConfirmButton: true,
            timer:3000
        });
    </script>
@endif

<div class="login-content">
    <!-- Login -->
    <div class="nk-block toggled" id="l-login">
        <form action="{{asset('/login')}}" method="post">
            {{csrf_field()}}
            <div class="nk-form">
                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                    <div class="nk-int-st">
                        <input name="email" type="text" class="form-control" placeholder="Username">
                    </div>
                </div>
                @if($errors->has('email'))
                    <div class="text-danger text-small">{{ $errors->first('email') }}</div>
                @endif
                <div class="input-group mg-t-25" style="margin-top: 30px;">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                    <div class="nk-int-st">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                @if($errors->has('password'))
                    <div class="text-danger text-small">{{ $errors->first('password') }}</div>
                @endif

                <button data-ma-action="nk-login-switch" data-ma-block="#l-register" class="btn btn-login btn-success btn-float" style="top: 64%;">
                    <i class="notika-icon notika-right-arrow right-arrow-ant"></i>
                </button>
            </div>
        </form>
    </div>
</div>