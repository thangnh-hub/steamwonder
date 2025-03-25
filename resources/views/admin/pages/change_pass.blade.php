@extends('admin.layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>Administrator</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ route('admin.resetpass.post') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>Alert!</h4>
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
                    </div>
                @endif

                <div class="card text-center">
                    <div class="card-body px-5">

                        <div class="form-group">
                            <input type="password" required name="password" class="form-control my-3"
                                placeholder="@lang('New Password')" />
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" required name="confirm_password" class="form-control my-3"
                                placeholder="@lang('Confirm Password')" />
                            @if ($errors->has('confirm_password'))
                                <span class="help-block">
                                    {{ $errors->first('confirm_password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block btn-flat" value="@lang('Reset password')">
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <a class="" href="{{ route('admin.login') }}">@lang('Login')</a>
                        </div>
                    </div>
                </div>
                @php
                    $referer = request()->headers->get('referer');
                @endphp
                <input type="hidden" name="url" value="{{ $referer }}">
            </form>
        </div>
    </div>
@endsection
