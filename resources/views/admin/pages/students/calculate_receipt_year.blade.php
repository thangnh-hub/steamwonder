@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .table-wrapper {
            max-height: 560px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }
    </style>
@endsection

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
        {{-- Search form --}}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('view_calculate_receipt_first_year') }}" method="GET">
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
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="current_class_id" id="area_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['current_class_id']) && $value->id == $params['current_class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Năm học')</label>
                                <select name="school_year" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($school_year as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['school_year']) && $key == $params['school_year'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
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
                                        href="{{ route('view_calculate_receipt_first_year') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body ">
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
                        @lang('Vui lòng tìm kiếm theo từ khóa, khu vực hoặc lớp để xem danh sách học viên')
                    </div>
                @else
                    <form action="{{ route('calculate_receipt_first_year') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Ngày bắt đầu chu kỳ thu')</label>
                                    @php
                                        $defaultDate = ($params['school_year'] ?? date('Y')) . '-06-01';
                                    @endphp
                                    <input class="form-control" type="date" name="enrolled_at" min="{{$defaultDate}}"
                                        value="{{ old('enrolled_at', $defaultDate) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button style="margin-top: 29px" type="submit" class="btn btn-primary">Tính toán đầu
                                    năm</button>
                            </div>
                        </div>

                        <div class="table-wrapper table-responsive">
                            <table class="table table-hover table-bordered ">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Avatar')</th>
                                        <th>@lang('Student code')</th>
                                        <th>@lang('Full name')</th>
                                        <th>@lang('Tên thường gọi')</th>
                                        <th>@lang('Gender')</th>
                                        <th>@lang('Area')</th>
                                        <th>@lang('Địa chỉ')</th>
                                        <th>@lang('Trạng thái')</th>
                                        <th>@lang('Lớp đang học')</th>
                                        <th>@lang('Ngày nhập học chính thức')</th>
                                        <th>
                                            <label class="form-check-label" for="check_all">@lang('Chọn')</label>
                                            <input type="checkbox" class="form-check-input" id="check_all" value="">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $row)
                                        <tr class="valign-middle">
                                            <td>{{ $loop->index + 1 }}</td>

                                            <td>
                                                @if (!empty($row->avatar))
                                                    <a href="{{ asset($row->avatar) }}" target="_blank"
                                                        class="image-popup">
                                                        <img src="{{ asset($row->avatar) }}" alt="Avatar" width="100"
                                                            height="100" style="object-fit: cover;">
                                                    </a>
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a class="" href="{{ route('students.show', $row->id) }}"
                                                    data-toggle="tooltip" title="@lang('Chi tiết học sinh')"
                                                    data-original-title="@lang('Chi tiết học sinh')"
                                                    onclick="return openCenteredPopup(this.href)">
                                                    <i class="fa fa-eye"></i> {{ $row->student_code }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $row->first_name ?? '' }} {{ $row->last_name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $row->nickname ?? '' }}
                                            </td>
                                            <td>
                                                @lang($row->sex)
                                            </td>
                                            <td>
                                                {{ $row->area->code ?? '' }}
                                            </td>

                                            <td>
                                                {{ $row->address ?? '' }}
                                            </td>

                                            <td>
                                                {{ __($row->status ?? '') }}
                                            <td>
                                                {{ $row->currentClass->name ?? '' }}
                                            </td>

                                            <td>
                                                {{ isset($row->enrolled_at) && $row->enrolled_at != '' ? date('d-m-Y', strtotime($row->enrolled_at)) : '' }}
                                            </td>

                                            <td>
                                                @if ($row->is_calculate_year == 1)
                                                    <span class="badge badge-success">Đã tồn tại biểu phí hàng năm trong
                                                        năm nay</span>
                                                @else
                                                    <input type="checkbox" class="form-check-input" name="student[]"
                                                        id="check_{{ $row->id }}" value="{{ $row->id }}">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                    </form>
                    </tbody>
                    </table>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Tính toán đầu năm</button>

            </form>
            @endif
        </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#check_all').on('change', function() {
                $('.form-check-input:not(#check_all)').prop('checked', $(this).is(':checked'));
            });

            $('.form-check-input:not(#check_all)').on('change', function() {
                if (!$(this).is(':checked')) {
                    $('#check_all').prop('checked', false);
                } else {
                    const allChecked = $('.form-check-input:not(#check_all)').length === $(
                        '.form-check-input:not(#check_all):checked').length;
                    $('#check_all').prop('checked', allChecked);
                }
            });
        });
    </script>
@endsection
