@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>tbody>tr>td {
            vertical-align: middle;
        }

        .table-bordered>thead>tr>th {
            vertical-align: middle;
            text-align: center;
        }

        .name_student {
            font-weight: bold;
        }

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }

        .mr-5 {
            margin-right: 5px;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .btn-active {
            background-color: #dd4b39;
            border-color: #d73925;
            color: #fff;
        }

        .btn-active.active {
            background-color: #00a65a;
            border-color: #008d4c;
            color: #fff;
        }

        .d-flex {
            display: flex;
        }
        ul{padding-left:15px }
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
    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        {{-- Search form --}}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name ?? '') }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['course_id']) && $params['course_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trình độ hiện tại')</label>
                                <select name="level_id" id="level_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $val)
                                        @if ($val->id <= 6)
                                            @if ($val->id == 1)
                                                <option value="null"
                                                    {{ isset($params['level_id']) && $params['level_id'] == 'null' ? 'selected' : '' }}>
                                                    {{ __($val->name ?? '') }}</option>
                                            @else
                                                <option value="{{ $val->id - 1 }}"
                                                    {{ isset($params['level_id']) && $params['level_id'] == $val->id - 1 ? 'selected' : '' }}>
                                                    {{ __($val->name ?? '') }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('State')</label>
                                <select name="state" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['state']) && $key == $params['state'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['state']) && $params['state'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status Study')</label>
                                <select name="status_study" id="status_study" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status_study as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['status_study']) && $value->id == $params['status_study'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['status_study']) && $params['status_study'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Admissions')</label>
                                <select name="admission_id" id="admission_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($staffs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}
                                            (Mã: {{ $value->admin_code }})
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['admission_id']) && $params['admission_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['class_id']) && $params['class_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['area_id']) && $params['area_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại hợp đồng')</label>
                                <select name="contract_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_type as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['contract_type']) && $value == $params['contract_type'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['contract_type']) && $params['contract_type'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái hợp đồng')</label>
                                <select name="contract_status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_status as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['contract_status']) && $value == $params['contract_status'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['contract_status']) && $params['contract_status'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Ngành nghề')</label>
                                <select name="field_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($field as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['field_id']) && $value->id == $params['field_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Version')</label>
                                <select name="version" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($version as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['version']) && $key == $params['version'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['version']) && $params['version'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Hạn công nợ từ ngày') </label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Hạn công nợ đến ngày') </label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                    <button class="btn btn-sm btn-success btn_export mr-10" type="button"
                                        data-url="{{ route('accounting_debt.export') }}" style="margin-right: 5px"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @lang('Export DS')</button>

                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modal_import">
                                        <i class="fa fa-file-excel-o"></i>
                                        @lang('Import lịch sử giao dịch')</button>
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
                <h3 class="box-title">@lang('List')</h3>
                <div class="pull-right" style="display: flex; margin-left:15px ">
                    {{-- <button class="btn btn-sm btn-warning mr-5">
                        <i class="fa fa-commenting-o"></i>
                        @lang('Chú thích')</button>

                    <a href="{{ url('data/accounting_debt.xlsx') }}" class="btn btn-sm btn-default">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('File Mẫu')</a>

                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="@lang('Select File')"> --}}



                </div>

            </div>
            <div class="box-body table-responsive">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Order')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                {{-- <th>@lang('CCCD')</th> --}}
                                <th>@lang('Area')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Lớp đang học')</th>
                                <th>@lang('Khóa học')</th>
                                <th>@lang('Trình độ hiện tại')</th>
                                <th>@lang('Ngày học CT')</th>
                                <th>@lang('Số ngày đã học CT')</th>
                                <th>@lang('Ngày công nợ đến hạn')</th>
                                <th>@lang('Admissions')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Status Study')</th>
                                <th>@lang('Loại hợp đồng')</th>
                                <th>@lang('Hợp đồng')</th>
                                <th>@lang('Version')</th>
                                <th>@lang('Tài chính')</th>
                                <th>@lang('Ghi chú KT')</th>
                                <th>@lang('Sách đã lấy')</th>
                                {{-- <th>@lang('Trạng thái')</th> --}}
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                    $level = null; // Để tránh trường hợp $row->level_id == 6 nhận $level của vòng lặp trước đó
                                    if ($row->level_id == null || $row->level_id == '') {
                                        $level = \App\Models\Level::find(1);
                                    } elseif ($row->level_id < 6) {
                                        $level = \App\Models\Level::find($row->level_id + 1);
                                    }
                                    $status_accounting_debt = $row->json_params->status_accounting_debt ?? '';
                                @endphp
                                <tr class="valign-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                            title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                            href="{{ route('students.show', $row->id) }}">
                                            {{ $row->admin_code }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>
                                    {{-- <td>
                                        {{ $row->json_params->cccd ?? '' }}
                                    </td> --}}
                                    <td>
                                        {{ $row->area->code ?? '' }}
                                    </td>
                                    <td>
                                        @if (isset($row->classs))
                                            <ul>
                                                @foreach ($row->classs as $i)
                                                    <li>
                                                        {{ $i->name }}
                                                        ({{ __($i->pivot->status ?? '') }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($row->classs))
                                            <ul>
                                                @foreach ($row->classs as $i)
                                                    @if ($i->status == 'dang_hoc')
                                                        <li>{{ $i->name }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $row->course->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $level->name ?? ($row->level->name ?? '') }}
                                    </td>
                                    <td>
                                        {{ $row->day_official != '' ? date('d-m-Y', strtotime($row->day_official)) : '' }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($row->day_official)->diffInDays(Carbon\Carbon::today()) }}
                                        ngày
                                    </td>
                                    <td>
                                        {{ $row->day_official != ''? Carbon\Carbon::parse($row->day_official)->addDays(150)->format('d-m-Y'): '' }}
                                    </td>
                                    <td>
                                        {{ $staff->admin_code ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->state)
                                    </td>
                                    <td>
                                        @lang($row->status_study_name ?? 'Chưa cập nhật')
                                    </td>
                                    <td>
                                        {{ $row->json_params->contract_type ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->contract_status ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->version ?? '' }}
                                    </td>
                                    @if (isset($row->AccountingDebt) && count($row->AccountingDebt) > 0)
                                        <td>
                                            <ul>
                                                @foreach ($row->AccountingDebt as $val)
                                                    <li>@lang($val->type_revenue)</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($row->AccountingDebt as $val)
                                                    <li>{{ $val->json_params->note }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td>
                                        @if (isset($row->history_book_active) && count($row->history_book_active) > 0)
                                            <ul>
                                                @foreach ($row->history_book_active as $val)
                                                    <li>{{ $val->product->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    {{-- <td> <button class="btn btn-active {{ $status_accounting_debt == 1 ? 'active' : '' }}"
                                            data-id="{{ $row->id }}">
                                            <input type="checkbox" {{ $status_accounting_debt == 1 ? 'checked' : '' }}
                                                class="input_checkbox" style="pointer-events: none;">
                                            <span
                                                class="txt_btn">{{ $status_accounting_debt == 1 ? 'Đã thanh toán TC' : 'Chưa thanh toán TC' }}</span>
                                        </button></td> --}}
                                    <td>
                                        <button class="btn btn-sm btn-warning detail_accounting_debt"
                                            data-toggle="tooltip" title="@lang('Xem lịch sử')"
                                            data-original-title="@lang('Xem lịch sử')" data-id="{{ $row->id }}">
                                            <i class="fa fa-list-ul"></i>
                                        </button>

                                    </td>
                                </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_accounting_debt" data-backdrop="static" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lịch sử giao dịch học viên <span class="name_student"></span></h5>
                </div>
                <div class="modal-body ">
                    <div class="box_alert"></div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Khoảng thu ')</th>
                                <th>@lang('Số tiên')</th>
                                <th>@lang('Thời gian thanh toán')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="box_accounting_debt">
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning add_accounting_debt">Thêm mới</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_import" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import lịch sử giao dịch học viên</h5>
                </div>
                <div class="modal-body ">
                    <div class="d-flex">
                        <a href="{{ url('data/accounting_debt.xlsx') }}" class="btn btn-sm btn-default">
                            <i class="fa fa-file-excel-o"></i>
                            @lang('File Mẫu')</a>

                        <input class="form-control" type="file" name="files" id="fileImport"
                            placeholder="@lang('Select File')">
                    </div>
                    <div class="note mt-5">
                        <p><strong> Ghi chú:</strong></p>
                        <ul>
                            <li>Mã học viên là bắt buộc và phải có trên hệ thống</li>
                            <li>Ngày thanh toán là bắt buộc</li>
                            <li>Loại tài chính: (@lang('option:')
                                @foreach (\App\Consts::TYPE_REVENUE as $key => $item)
                                    <label class="label label-primary "
                                        style="text-transform: uppercase">{{ $key }}</label>
                                @endforeach
                                )
                            </li>
                            <li>Mỗi học viên chỉ có 1 giao dịch ứng với mỗi loại tài chính</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="importFile()">Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('script')
    <script>
        $('.detail_accounting_debt').click(function() {
            var student_id = $(this).data("id");
            list_accounting_debt(student_id);
            $('#modal_accounting_debt').modal('show');
            $('.add_accounting_debt').attr('data-id', student_id).show();
        })

        $('.btn-active').click(function() {
            var student_id = $(this).data('id');
            var _this = $(this);
            if ($(this).hasClass('active')) {
                var status = 0;
            } else {
                var status = 1;
            }
            var url = "{{ route('accounting_debt.update_status_student') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": '{{ csrf_token() }}',
                    'student_id': student_id,
                    'status': status,
                },
                success: function(response) {
                    if (response.data != null) {
                        var _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);

                        setTimeout(function() {
                            $(".alert").fadeOut(2000, function() {});
                        }, 800);

                        if (_this.hasClass('active')) {
                            _this.removeClass('active').find('.txt_btn').html('Chưa thanh toán CT');
                            _this.find('.input_checkbox').prop('checked', false);
                        } else {
                            _this.addClass('active').find('.txt_btn').html('Đã thanh toán CT');
                            _this.find('.input_checkbox').prop('checked', true);
                        }
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });

        })

        $(document).on('click', '.add_accounting_debt', function() {
            var student_id = $(this).data("id");
            var _html = `<tr>
                    <td>
                        <select style="width: 100%" class="form-control select2 select_type">
                            @foreach ($type_revenue as $key => $val)
                                <option value="{{ $key }}">
                                    @lang($val)
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control money" type="number"  value="">
                    </td>
                    <td>
                        <input class="form-control time" type="date"  value="">
                    </td>
                    <td>
                        <textarea class="form-control note" rows="3"></textarea>
                    </td>
                    <td>
                        <button type="button" onclick="create_accounting_debt(this,` + student_id + `)" class="btn btn-success">
                            Lưu
                        </button>
                    </td>
                </tr>`;
            $('#box_accounting_debt').append(_html);
            $('.select2').select2();
            $(this).hide();
        })

        $('.btn_export').click(function() {
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            $('#loading-notification').css('display', 'flex');
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
                        a.download = 'Student.xlsx';
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
                    $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })

        $(document).on('click', '.btn_show_edit', function() {
            var s = $(this).parents('tr').find('.box_hide');
            var h = $(this).parents('tr').find('.box_show');
            show_hide(s, h);
        })
        $(document).on('click', '.btn_cancel_edit', function() {
            var s = $(this).parents('tr').find('.box_show');
            var h = $(this).parents('tr').find('.box_hide');
            show_hide(s, h);
        })

        function list_accounting_debt(student_id) {
            var url = "{{ route('accounting_debt.list_accounting_debt') }}/";
            var _view = $('#box_accounting_debt');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    "student_id": student_id,
                },
                success: function(response) {
                    let student = response.data.student;
                    let _html = response.data.html;
                    $('.name_student').html(student.name + ' - ' + student.admin_code);
                    _view.html(_html);

                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        };

        function create_accounting_debt(_this, student_id) {
            var _type = $(_this).parents('tr').find('.select_type').val();
            var _money = $(_this).parents('tr').find('.money').val();
            var _time = $(_this).parents('tr').find('.time').val();
            var _note = $(_this).parents('tr').find('.note').val();
            var _url = "{{ route('accounting_debt.create_accounting_debt') }}";
            $.ajax({
                type: "POST",
                url: _url,
                data: {
                    "_token": '{{ csrf_token() }}',
                    "student_id": student_id,
                    "type": _type,
                    "money": _money,
                    "time": _time,
                    "note": _note,
                },
                success: function(response) {
                    if (response.data == 'success') {
                        list_accounting_debt(student_id);
                        $('.add_accounting_debt').attr('data-id', student_id).show();
                    }
                    _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                    $('.box_alert').html(_html);
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }

        function update_accounting_debt(_this, id, student_id) {
            var url = "{{ route('accounting_debt.update_history') }}";
            var _view = $(_this).parents('tr');
            var type_revenue = _view.find('.select_type').val();
            var amount_paid = _view.find('.money').val();
            var time_payment = _view.find('.time').val();
            var note = _view.find('.note').val();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": '{{ csrf_token() }}',
                    'id': id,
                    'student_id': student_id,
                    'type_revenue': type_revenue,
                    'amount_paid': amount_paid,
                    'time_payment': time_payment,
                    'note': note,
                },
                success: function(response) {
                    _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                    $('.box_alert').html(_html);
                    if (response.data == 'success') {
                        list_accounting_debt(student_id);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        function delete_accounting_debt(_this, id) {
            var _confirm = confirm('@lang('confirm_action')');
            if (_confirm) {
                var url = "{{ route('accounting_debt.delete_history') }}";
                var _view = $(_this).parents('tr');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        'id': id,
                    },
                    success: function(response) {
                        _view.remove();
                        _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                        $('.box_alert').html(_html);
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        }

        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }

        function importFile() {
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: '{{ route('accounting_debt.import_history') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
