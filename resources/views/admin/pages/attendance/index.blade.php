@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th,
        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .box_radio {
            width: 100%;
            height: 100%;
        }

        input[type="radio"] {
            transform: scale(1.5);
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
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
                                <label>@lang('Lớp') <small class="text-red">*</small></label>
                                <select required name="class_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->code ?? '' }} - {{ $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Ngày ') <small class="text-red">*</small></label>
                                <input type="date" name="tracked_at" class="form-control" required
                                    value="{{ isset($params['tracked_at']) && $params['tracked_at'] != '' ? $params['tracked_at'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
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
                {{-- <h3 class="box-title">@lang('List')</h3> --}}
            </div>
            <div class="box-body box_alert">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">@lang('STT')</th>
                                <th class="text-center" rowspan="2">@lang('Mã học sinh')</th>
                                <th class="text-center" rowspan="2">@lang('Tên học sinh')</th>
                                <th class="text-center" rowspan="2">@lang('Nickname')</th>
                                <th class="text-center" rowspan="2">@lang('Đi học')</th>
                                <th class="text-center" colspan="2">@lang('Nghỉ học')</th>
                                <th class="text-center" rowspan="2">@lang('Người đưa trẻ - Giáo viên đón - Thời gian - Ghi chú')</th>
                                <th class="text-center" rowspan="2">@lang('Dịch vụ')</th>
                                <th class="text-center" rowspan="2">@lang('Action')</th>
                            </tr>
                            <tr>
                                <th class="text-center">@lang('Có phép')</th>
                                <th class="text-center">@lang('Không phép')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->student->student_code ?? '' }}</td>
                                    <td class="text-center">{{ $item->student->first_name ?? '' }}
                                        {{ $item->student->last_name ?? '' }}</td>
                                    <td>{{ $item->student->nickname ?? '' }}</td>
                                    @foreach ($status as $k => $v)
                                        <td class="text-center">
                                            <label class="box_radio"
                                                for="student_{{ $item->student_id }}_{{ $k }}">
                                                <input id="student_{{ $item->student_id }}_{{ $k }}"
                                                    name="student[{{ $item->student_id }}][status]" class="radiobox"
                                                    type="radio" value="1">
                                            </label>
                                        </td>
                                    @endforeach
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-top:5px;">
                                                <select class="form-control w-100"
                                                    name="student_logtime[20][relative_login]">
                                                    <option selected="" value="">-Người đưa-</option>
                                                    @isset($item->student->studentParents)
                                                        @foreach ($item->student->studentParents as $parents)
                                                            <option value="{{ $parents->parent_id }}">
                                                                {{ $parents->relationship->title ?? '' }}:
                                                                {{ $parents->parent->first_name ?? '' }}
                                                                {{ $parents->parent->last_name ?? '' }}</option>
                                                        @endforeach
                                                    @endisset

                                                </select>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-top:5px;">
                                                <label class="select" disabled="" style="width: 100%"> <select
                                                        class="form-control" style="width: 100%"
                                                        name="student_logtime[20][member_login]"
                                                        id="select_20_member_login">
                                                        <option value="">-Giáo viên đón-</option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-bottom:5px;">
                                                <div class="input-group" style="width: 100%">
                                                    <input name="student_logtime[20][login_at]" class="form-control"
                                                        type="time" value="14:05">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-bottom:5px;">
                                                <input name="student_logtime[20][note]" type="text"
                                                    class="form-control" style="width: 100%" id="note_20"
                                                    placeholder="Nhập ghi chú" value="">
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @endif
            </div>


        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
