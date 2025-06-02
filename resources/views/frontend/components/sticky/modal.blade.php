<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4>@lang('Đăng nhập')</h4>
                </div>
                <div class="d-flex flex-column text-center form-login active">
                    <form id="login_form" method="post" class="login" action="{{ route('frontend.login.post') }}">
                        @csrf
                        @php
                            $referer = request()->headers->get('referer');
                            $current = url()->full();
                        @endphp
                        <input type="hidden" name="referer" value="{{ $referer }}">
                        <input type="hidden" name="current" value="{{ $current }}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="@lang('Username')"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="@lang('Mật khẩu')"
                                required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block btn-round">@lang('Đăng nhập')</button>
                        <div class="form-group login_result d-none mt-3">
                            <div class="alert alert-warning" role="alert">
                                @lang('Processing...')
                            </div>
                        </div>

                    </form>
                    <div class="text-center mt-3">
                        <a class="text-info" href="{{ route('frontend.password.forgot.get') }}">@lang('Quên mật khẩu')</a>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer d-flex justify-content-center">
                <div class="signup-section">@lang('Chưa phải là thành viên?') <a href="javascript:void(0)" class="text-info"
                        data-toggle="modal" data-target="#registernModal" data-dismiss="modal"> @lang('Đăng ký')</a>.
                </div>
            </div> --}}
        </div>
    </div>
</div>

{{-- Form đăng ký --}}
<div class="modal fade" id="registernModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4>@lang('Đăng ký')</h4>
                </div>
                <div class="d-flex flex-column text-center">
                    <form id="signup_form" method="post" class="login" action="{{ route('frontend.signup') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="@lang('Họ và tên của bạn ... ')"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="@lang('Địa chỉ Email của bạn ... ')"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="json_params[cccd]"
                                placeholder="@lang('Nhập CCCD ... ')" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="@lang('Mật khẩu')"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="repassword"
                                placeholder="@lang('Nhập lại mật khẩu')" required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block btn-round">@lang('Đăng ký')</button>
                        <div class="form-group signup_result d-none mt-3">
                            <div class="alert alert-warning" role="alert">
                                @lang('Processing...')
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <div class="signup-section">@lang('Đã là thành viên?') <a href="javascript:void(0)" class="text-info"
                        data-toggle="modal" data-target="#loginModal" data-dismiss="modal"> @lang('Đăng nhập')</a>.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Footer button --}}
<section class="navbar-size">
    <div class="navbar-size-wrapper">
        <div class="navbar-size-item {{ request()->server('REQUEST_URI') == '/' ? 'active' : '' }}">
            <a href="{{ route('home') }}">
                <div class="icon">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </div>
                <h2 class="text">@lang('Trang chủ')</h2>
            </a>
        </div>
        @isset($user_auth)
            <div class="navbar-size-item">
                <a href="{{ route('frontend.user.course') }}">
                    <div class="icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Khóa học</h2>
                </a>
            </div>
            <div class="navbar-size-item">
                <a href="{{ route('frontend.user') }}">
                    <div class="icon">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Tài khoản</h2>
                </a>
            </div>
        @else
            <div class="navbar-size-item">
                <a href="{{ route('frontend.course.list') }}">
                    <div class="icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Khóa học</h2>
                </a>
            </div>
            <div class="navbar-size-item">
                <a href="#loginModal" data-toggle="modal">
                    <div class="icon">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Đăng nhập</h2>
                </a>
            </div>
            @endif
        </div>
    </section>
