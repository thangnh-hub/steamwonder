@extends('frontend.layouts.email')

@section('content')
    <div class="container" style="max-width:80%;margin:auto;background:#FBFBFB;">
        <div>
            <img src="{{ url('assets/img/email-banner.png') }}" alt="" style="width:100%">
        </div>
        <div style="padding:20px">
            <strong style="font-style: italic;">
                @lang('Dear')!
            </strong>
            <p>
                {{ __('You have successfully registered an account at DWN Viet Nam, we will assist you in accomplishing your goals with many perks and help you connect globally.') }}
            </p>
            <p>
                <strong style="font-style: italic;">
                    {{ __('And now is the time for us to start together and give each other opportunities.') }}
                </strong>
            </p>
            <p>
                {{ __('Click the below link to confirm that you agree to our terms of use and data protection and we can activate your account.') }}
            </p>
            <p>
                <a style="color:#0064aa" href="{{ route('frontend.verify_account') }}?code={{ $code }}">
                    {{ route('frontend.verify_account') }}?code={{ $code }}
                </a>
            </p>
            <p>
                <em>
                    {{ __('Note: If the link cannot be clicked, simply copy it into your browser.') }}
                </em>
            </p>
        </div>
    </div>
@endsection
