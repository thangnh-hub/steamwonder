@extends('admin.layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>Administrator</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ route('admin.login') }}" method="post">
                @csrf
                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>Alert!</h4>
                        {{ __(session('errorMessage')) }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ __(session('successMessage')) }}
                    </div>
                @endif

                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="text" name="email" required class="form-control" placeholder="Email hoặc Mã người dùng">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" required name="password" class="form-control" placeholder="Password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>
                <div class="form-group d-flex mb-3">
                    <div class="col-md-6 d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                            <label class="form-check-label" for="remember"> @lang('Remember me') </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Simple link -->
                        <a href="{{route('admin.forgot')}}">@lang('Forgot password?')</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    @lang('Login')
                </button>

                @php
                    $referer = request()->headers->get('referer');
                @endphp
                <input type="hidden" name="url" value="{{ $referer }}">
            </form>
        </div>
    </div>
@endsection
