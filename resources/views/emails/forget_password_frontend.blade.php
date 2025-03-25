@extends('frontend.layouts.email')

@section('content')
  <div class="container" style="max-width:80%;margin:auto;background:#FBFBFB;padding:20px">
    <h1>@lang('Forget Password Email')</h1>

    <p>@lang('You can reset password from bellow link:')</p>

    <a href="{{ route('frontend.password.reset.get', $token) }}">@lang('Reset Password')</a>
  </div>
@endsection
