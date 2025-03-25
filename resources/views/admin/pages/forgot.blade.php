@extends('admin.layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <b>Administrator</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ route('admin.forgot.post') }}" method="post">
                @csrf

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
                        <p class="card-text py-2">
                            @lang("Enter your email address and we'll send you an email with instructions to reset your password.")
                        </p>
                        <div class="form-group">
                            <input type="email" id="typeEmail" required name="email" class="form-control my-3"
                                placeholder="@lang('Email input')" />
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
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
