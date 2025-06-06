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
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }

        .breadcrumb a {
            color: inherit;
        }

        .sidebar ul li {
            padding: 10px 0px
        }

        .sidebar ul li a {
            color: initial
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            bottom: -3px;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .eye-icon {
            font-size: 20px;
        }
    </style>
</head>

<body>
    @if (\View::exists('frontend.widgets.header.default'))
        @include('frontend.widgets.header.default')
    @else
        {{ 'View: frontend.widgets.header.default  do not exists!' }}
    @endif
    <div class="account">
        <div class="container">
            @if (session('errorMessage'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('errorMessage') !!}
                </div>
            @endif
            @if (session('successMessage'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('successMessage') !!}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>
            @endif

            <div class="row">
                <!-- News Posts -->
                <div class="col-lg-8 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user"></i> @lang('Thông tin tài khoản')

                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>
                                        <strong>@lang('Họ và tên'): </strong>
                                        {{ $detail->first_name ?? '' }}
                                        {{ $detail->last_name ?? '' }}
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('SĐT'): </strong>{{ $detail->phone ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('Email'): </strong>{{ $detail->email ?? '' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('Giới tính'):
                                        </strong>{{ __($detail->sex) ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('Ngày sinh'):
                                        </strong>{{ $detail->birthday != '' ? date('d/m/Y', strtotime($detail->birthday)) : 'Chưa cập nhật' }}
                                    </p>
                                </div>


                                <div class="col-sm-12">
                                    <p><strong>@lang('Địa chỉ'):
                                        </strong>{{ $detail->json_params->address ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Sidebar -->
                {{-- @include('frontend.components.sticky.sidebar') --}}

            </div>

        </div>
    </div>

    @if (\View::exists('frontend.widgets.footer.default '))
        @include('frontend.widgets.footer.default ')
    @else
        {{ 'View: frontend.widgets.footer.default do not exists!' }}
    @endif

    {{-- Include scripts --}}
    @include('frontend.components.sticky.modal')
    @include('frontend.panels.scripts')
    @include('frontend.components.sticky.alert')
    {{-- Include scripts --}}
    {{-- Scripts custom each page --}}
    @stack('script')
    <script>
        $('.btn_update').click(function() {
            $('.update_information').show();
            $('.information').hide();
        })
        $('.btn_cancel').click(function() {
            $('.update_information').hide();
            $('.information').show();
        })
    </script>
</body>

</html>
