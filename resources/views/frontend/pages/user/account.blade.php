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
                                <i class="fa fa-user"></i> @lang('Thông tin sinh viên')
                                <button class="btn btn-sm btn-info pull-right btn_update">
                                    <i class="fa fa-edit"></i>
                                    @lang('Cập nhật')
                                </button>
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>@lang('Họ và tên'): </strong>{{ $detail->name }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('CCCD'):
                                        </strong>{{ $detail->json_params->cccd ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('Ngày sinh'):
                                        </strong>{{ $detail->birthday != '' ? date('d/m/Y', strtotime($detail->birthday)) : 'Chưa cập nhật' }}
                                    </p>
                                </div>

                                <div class="col-sm-6">
                                    <p><strong>@lang('SĐT'): </strong>{{ $detail->phone ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>@lang('Email'): </strong>{{ $detail->email ?? '' }}</p>
                                </div>

                                <div class="col-sm-6">
                                    <p><strong>@lang('Mã học viên'): </strong>{{ $detail->admin_code ?? '' }}</p>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <p><strong>@lang('Khóa học'): </strong>ONLINE001</p>
                                </div> --}}

                                <div class="col-sm-12">
                                    <p><strong>@lang('Địa chỉ'):
                                        </strong>{{ $detail->json_params->address ?? 'Chưa cập nhật' }}</p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-8 update_information" style="display: none">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user"></i> @lang('Cập nhật thông tin')
                            </h3>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('frontend.update.account') }}" method="post" class="form-update"
                                name="form-account-update">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Họ và tên') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="@lang('Họ và tên')" value="{{ $detail->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Ngày sinh') <small class="text-red">*</small></label>
                                            <input type="date" class="form-control" name="birthday"
                                                placeholder="@lang('Ngày sinh')"
                                                value="{{ $detail->birthday != '' ? date('Y-m-d', strtotime($detail->birthday)) : null }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('SĐT') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="@lang('SĐT')" value="{{ $detail->phone ?? '' }}"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Giới tính') <small class="text-red">*</small></label>
                                            <select name="gender" class="form-control">
                                                <option value="" disabled>@lang('Chọn giới tính')</option>
                                                @foreach ($gender as $key => $val)
                                                    <option value="{{ $key }}"
                                                        {{ $detail->gender == $val ? 'selected' : '' }}>
                                                        {{ __($val) }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Địa chỉ')</label>
                                            <textarea rows="3" class="form-control" name="address">{{ $detail->json_params->address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group password-wrapper">
                                            <label>@lang('Mật khẩu') <small class="text-muted"><i>(Bỏ qua nếu bạn
                                                        không
                                                        muốn đổi mật khẩu)</i></small></label>
                                            <input class="form-control single-input" type="password" name="password"
                                                value="" autocomplete="off">
                                            <span class="toggle-password">
                                                <i class="eye-icon fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button"
                                            class="btn btn-danger btn-sm text-white text-uppercase btn_cancel">
                                            Hủy</button>
                                        <button type="submit"
                                            class="btn btn-success btn-sm text-white text-uppercase">
                                            <i class="fa fa-save"></i> Lưu thông tin</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                @include('frontend.components.sticky.sidebar')

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
