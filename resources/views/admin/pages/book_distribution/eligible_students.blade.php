@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>tbody>tr>td {
            text-align: center;
            vertical-align: inherit;
        }

        .table>tbody>tr>td.text_left {
            text-align: left;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .btn_active,
        .btn_warning,
        .btn_deactive {
            background-color: #eeeeee;
            border-color: #878787;
            color: #000;
        }

        .btn_deactive.active {
            background-color: #dd4b39;
            border-color: #d73925;
            color: #fff;
        }

        .btn_active.active {
            background-color: #00a65a;
            border-color: #008d4c;
            color: #fff;
        }

        .btn_warning.active {
            background-color: #f39c12;
            border-color: #e08e0b;
            color: #fff;
        }

        .mb-2 {
            margin-bottom: 1.5rem;
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
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>

    </section>
@endsection

@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('book_distribution.eligible_students') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trình độ đang học')</label>
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
                                <label>@lang('Class')</label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('book_distribution.eligible_students') }}">
                                        @lang('Reset')
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="{{ route('book_distribution.export_eligible_students') }}"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @lang('Export')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách học viên đã được thêm vào lớp học và chưa nhận sách')</h3>
            </div>
            <div class="box-body box_alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        {!! session('successMessage') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>

                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach

                    </div>
                @endif
                @if (count($students) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã học viên')</th>
                                <th>@lang('Họ và tên')</th>
                                <th>@lang('CB tuyển sinh')</th>
                                <th>@lang('Loại hợp đồng')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Trình độ')</th>
                                <th>@lang('Lớp đã học')</th>
                                <th>@lang('Ngày vào lớp')</th>
                                <th>@lang('Sách đã lấy')</th>
                                <th>@lang('Sách chưa lấy')</th>
                                <th>@lang('Các GD nộp tiền')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $row)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $row->student->admin_code }}</td>
                                    <td>{{ $row->student->name ?? '' }} </td>
                                    <td>{{ $row->student->admission->admin_code ?? '' }} </td>
                                    <td>{{ $row->student->json_params->contract_type ?? '' }}</td>
                                    <td>{{ $row->student->area->code ?? '' }}</td>
                                    <td>{{ $row->level->name ?? '' }}</td>
                                    <td class="text_left">
                                        @if (isset($row->student->classs))
                                            @php
                                                $day_in_class = '';
                                            @endphp
                                            <ul>
                                                @foreach ($row->student->classs as $val)
                                                    <li class="{{ $row->class_id == $val->id ? 'font-weight-bold' : '' }}">
                                                        {{ $val->name }}
                                                        ({{ __($val->pivot->status) }})
                                                    </li>
                                                    @php
                                                        if (
                                                            $row->class_id == $val->id &&
                                                            isset($val->pivot->json_params)
                                                        ) {
                                                            $day_in_class =
                                                                json_decode($val->pivot->json_params)->day_in_class ??
                                                                '';
                                                        }
                                                    @endphp
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $day_in_class != '' ? \Carbon\Carbon::parse($day_in_class)->format('d/m/Y') : '' }}
                                    </td>
                                    <td class="text_left">
                                        @if (isset($row->student->history_book_active))
                                            <ul>
                                                @foreach ($row->student->history_book_active as $his)
                                                    <li>{{ $his->product->name ?? '' }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        {{-- Đoạn này ghi chú để note cứng học viên đã nhận đủ sách - ThangNH --}}
                                        @isset($row->student->json_params->note_book_history)
                                            <span class="text-bold text-danger">{{ $row->student->json_params->note_book_history }}</span>
                                        @endisset
                                    </td>
                                    <td>
                                        {{ $row->product->name ?? '' }}

                                    </td>

                                    <td class="text_left">
                                        @if (isset($row->student->AccountingDebt))
                                            <ul>
                                                @foreach ($row->student->AccountingDebt as $item)
                                                    <li>{{ __($item->type_revenue) }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        <button
                                            class=" mb-2 btn btn_change btn_active {{ $row->status == $status_book_distribution_student['dudieukien'] ? 'active' : '' }}"
                                            data-id="{{ $row->id }}"
                                            data-origin={{ $status_book_distribution_student['dudieukien'] }}
                                            data-status="{{ $row->status == $status_book_distribution_student['dudieukien'] ? null : $status_book_distribution_student['dudieukien'] }}">
                                            <input type="checkbox"
                                                {{ $row->status == $status_book_distribution_student['dudieukien'] ? 'checked' : '' }}
                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn">@lang('ĐỦ điều kiện phát sách')</span>
                                        </button>
                                        </br>
                                        <button
                                            class=" mb-2 btn btn_change btn_deactive {{ $row->status == $status_book_distribution_student['khongdudieukien'] ? 'active' : '' }}"
                                            data-id="{{ $row->id }}"
                                            data-origin={{ $status_book_distribution_student['khongdudieukien'] }}
                                            data-status="{{ $row->status == $status_book_distribution_student['khongdudieukien'] ? null : $status_book_distribution_student['khongdudieukien'] }}">
                                            <input type="checkbox"
                                                {{ $row->status == $status_book_distribution_student['khongdudieukien'] ? 'checked' : '' }}
                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn">@lang('KHÔNG đủ điều kiện phát sách')</span>
                                        </button>
                                        </br>
                                        <button
                                            class=" mb-2 btn btn_change btn_warning {{ $row->status == $status_book_distribution_student['danhansach'] ? 'active' : '' }}"
                                            data-id="{{ $row->id }}"
                                            data-origin={{ $status_book_distribution_student['danhansach'] }}
                                            data-status="{{ $row->status == $status_book_distribution_student['danhansach'] ? null : $status_book_distribution_student['danhansach'] }}">
                                            <input type="checkbox"
                                                {{ $row->status == $status_book_distribution_student['danhansach'] ? 'checked' : '' }}
                                                class="input_checkbox" style="pointer-events: none;">
                                            <span class="txt_btn">@lang('Đã nhận sách')</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-sm-5">
                                Tìm thấy {{ $students->total() }} kết quả
                            </div>
                            <div class="col-sm-7">
                                {{ $students->withQueryString()->links('admin.pagination.default') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
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
                            a.download = 'Danh sách học viên đủ điều kiện.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                            hide_loading_notification()
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
                            hide_loading_notification()
                        }

                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });

        $(document).on('click', '.btn_active, .btn_deactive, .btn_warning', function() {
            var _this = $(this);
            var id = _this.data('id');
            var status = _this.attr('data-status');

            // Reset trạng thái của các nút khác trong cùng ô
            _this.parents('td').find('.btn_active, .btn_deactive, .btn_warning')
                .not(_this) // Loại trừ nút hiện tại
                .removeClass('active')
                .each(function() {
                    var button = $(this);
                    if (button.hasClass('btn_active')) {
                        button.attr('data-status', 'dudieukien');
                    } else if (button.hasClass('btn_deactive')) {
                        button.attr('data-status', 'khongdudieukien');
                    } else if (button.hasClass('btn_warning')) {
                        button.attr('data-status', 'daphatsach');
                    }
                });

            // Bỏ chọn checkbox trong cùng ô
            _this.parents('td').find('.input_checkbox').prop('checked', false);
            // Gọi hàm xử lý trạng thái
            change_active_student(_this, id, status);
        });


        function change_active_student(element, id, status) {

            $.ajax({
                type: "POST",
                url: "{{ route('book_distribution.change_status') }}",
                data: {
                    "_token": '{{ csrf_token() }}',
                    'id': id,
                    'status': status,
                },
                success: function(response) {
                    if (response.data != null) {
                        if (element.hasClass('active')) {
                            element.removeClass('active');
                            element.attr('data-status', element.attr('data-origin'));
                            element.find('.input_checkbox').prop('checked', false);
                        } else {
                            element.addClass('active');
                            element.attr('data-status', '');
                            element.find('.input_checkbox').prop('checked', true);
                        }

                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="false">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $(".alert-warning").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
@endsection
