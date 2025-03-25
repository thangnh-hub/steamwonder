@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .pd-0 {
            padding-left: 0px !important;
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
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('evaluations.class.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>@lang('Class'): <small class="text-red">*</small></strong></label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;"
                                    required>
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Cán bộ tuyển sinh')</label>
                                <select name="admission_id" id="admission_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_admission as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('evaluations.class.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
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
        {{-- Search form --}}
        @if (isset($this_class) && $this_class != null)
            @if (isset($list_evolution_class))
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">@lang('Lịch sử nhận xét đánh giá lớp') {{ $this_class->name }} - Giảng viên: {{$teacher->name??''}}</h3>
                    </div>
                    <div class="box-body table-responsive">
                        @if (count($list_evolution_class) == 0)
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                @lang('not_found')
                            </div>
                        @else
                            <form>
                                <table class="table table-hover table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Từ ngày')</th>
                                            <th>@lang('Đến ngày')</th>
                                            <th>@lang('Lớp')</th>
                                            <th>@lang('Xem')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_evolution_class as $row)
                                            @if ($row->from_date != '' && $row->to_date != '')
                                                <tr class="valign-middle">
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $row->from_date != '' ? date('d-m-Y', strtotime($row->from_date)) : 'Chưa nhập ngày bắt đầu' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->to_date != '' ? date('d-m-Y', strtotime($row->to_date)) : 'Chưa nhập ngày kết thúc' }}
                                                    </td>
                                                    <td>
                                                        {{ $this_class->name ?? '' }}
                                                    </td>

                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('evaluations.class.show', ['class_id' => $this_class->id,'admission_id' => $params['admission_id']??"", 'from_date' => $row->from_date, 'to_date' => $row->to_date]) }}
                                                        ">@lang('Xem chi tiết')</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        @endif

    </section>
    </div>
@endsection
