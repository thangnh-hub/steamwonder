<!DOCTYPE html>
<html lang="{{ $locale ?? 'vi' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ $meta['seo_title'] }}
    </title>
    <link rel="icon" href="{{ $setting->favicon ?? '' }}" type="image/x-icon">
    {{-- Print SEO --}}
    <link rel="canonical" href="{{ Request::fullUrl() }}" />
    <meta name="description" content="{{ $meta['seo_description'] }}" />
    <meta name="keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta name="news_keywords" content="{{ $meta['seo_keyword'] }}" />
    <meta property="og:image" content="{{ env('APP_URL') . $meta['seo_image'] }}" />
    <meta property="og:title" content="{{ $meta['seo_title'] }}" />
    <meta property="og:description" content="{{ $meta['seo_description'] }}" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    {{-- End Print SEO --}}
    {{-- Include style for app --}}
    @include('frontend.panels.styles')
    {{-- Styles custom each page --}}
    @stack('style')

    @stack('schema')
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }
        .panel{
            margin-top: 150px;
        }
    </style>
</head>

<body>
    @if (\View::exists('frontend.widgets.header.default'))
        @include('frontend.widgets.header.default')
    @else
        {{ 'View: frontend.widgets.header.default  do not exists!' }}
    @endif

    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Reset Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">

                                <form id="register-form" action="{{route('frontend.password.reset.post')}}" role="form" autocomplete="off" class="form"
                                    method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="email" placeholder="email address"
                                                class="form-control" type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="password" placeholder="password new"
                                                class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="password_confirmation" placeholder="password confirmation"
                                                class="form-control" type="password">
                                        </div>
                                    </div>
                                    <input type="hidden" name="token" value="{{$token}}">
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block"
                                            value="Reset Password" type="submit">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (\View::exists('frontend.widgets.footer.default '))
        @include('frontend.widgets.footer.default ')
    @else
        {{ 'View: frontend.widgets.footer.default do not exists!' }}
    @endif

    @include('frontend.components.sticky.modal')
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')
    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')

</body>

</html>
