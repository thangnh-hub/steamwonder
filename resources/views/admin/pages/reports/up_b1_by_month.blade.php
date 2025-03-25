@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .background-warning-yellow {
            background: #f9e7a2;
        }

        .font-weight-bold {
            font-weight: bold;
            font-size: 16px
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        #alert-config {
            width: auto !important;
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section> --}}
@endsection
@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <!-- Main content -->
    <div id="alert-config">

    </div>
    <section class="content">

        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.class.up_b1_by_month') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Mã - Tên học viên') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập mã hoặc tên học viên')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lớp học')</label>
                                <select name="class_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_class_all as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trình độ')</label>
                                <select name="level_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lên trình từ ngày')</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ isset($params['from_date']) ? $params['from_date']->toDateString() : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lên trình đến ngày')</label>
                                <input type="date" name="to_date" class="form-control"
                                    value="{{ isset($params['to_date']) ? $params['to_date']->toDateString() : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('report.class.up_b1_by_month') }}">
                                        @lang('Reset')
                                    </a>
                                    <a id="printButton" onclick="window.print()"
                                        class="btn btn-sm btn-warning mr-10">@lang('In thông tin')</a>

                                    <button type="button" class="btn btn-sm btn-success btn_export" data-url="{{route('report.class.export_up_b1_by_month')}}"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @lang('Export Danh sách')</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang($module_name)</h3>

            </div>
            <div class="box-body">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('errorMessage') }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('successMessage') }}
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
                @if (count($list_class) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:50px">STT</th>
                                <th rowspan="2" style="width:100px">@lang('Mã học viên')</th>
                                <th rowspan="2">@lang('Họ và tên')</th>
                                <th rowspan="2" style="width:80px">@lang('CBTS')</th>
                                <th rowspan="2" style="width:120px">@lang('Lớp')</th>
                                <th rowspan="2" style="width:80px">@lang('Trình độ')</th>
                                <th rowspan="2" style="width:120px">@lang('Kết quả thi')</th>
                                <th colspan="2" style="width:200px">@lang('Ngày lên trình')</th>
                                <th rowspan="2">@lang('Hợp đồng')</th>
                            </tr>
                            <tr>
                                <th style="width:100px">@lang('Dự kiến')</th>
                                <th style="width:100px">@lang('Thực tế')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($list_class as $class)
                                @php
                                    $background_color = $loop->index % 2 == 0 ? 'background-color:#F5F6F7' : '';
                                @endphp
                                @foreach ($class->students as $item)
                                    <tr style="{!! $background_color !!}">
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td class="text-center">{{ $item->admin_code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->admission->admin_code ?? '' }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td class="text-center">{{ $class->level->name ?? '' }}</td>
                                        <td class="text-center">{{ __($item->xep_loai ?? 'Chưa thi') }}</td>
                                        <td class="text-center">{{ $class->day_end_level_expected }}</td>
                                        <td class="text-center">{{ $class->day_end_level }}</td>
                                        <td>{{ isset($item->json_params->contract_type) && $item->json_params->contract_type != null ? $item->json_params->contract_type : __('Chưa cập nhật') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $('.btn_export').click(function() {
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            show_loading_notification()
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: formData,
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'Danh_sach_hoc_vien.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }
                    hide_loading_notification()
                },
                error: function(response) {
                    hide_loading_notification()
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })
    </script>
@endsection
