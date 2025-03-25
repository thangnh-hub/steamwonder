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
            <form action="{{ route('trial_classs.schedule') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('trial_classs.schedule') }}">
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
            @if (isset($list_lesson))
                @php
                    $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                @endphp
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">@lang('Danh sách buổi học lớp') {{ $this_class->name }} - Giảng viên:
                            {{ $teacher->name ?? '' }}</h3>
                    </div>
                    <div class="box-body table-responsive">
                        @if (count($list_lesson) == 0)
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
                                            <th>@lang('Lesson')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Date-time')</th>
                                            <th>@lang('Period')</th>
                                            <th>@lang('Room')</th>
                                            <th>@lang('Teacher')</th>
                                            <th>@lang('Thao tác')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_lesson as $key => $lesson)
                                            <tr
                                                class=" {{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} {{ $lesson->is_add_more == 1 ? 'bg-highlight' : '' }}">
                                                <td>
                                                    {{ ++$key }}
                                                    {{ $lesson->is_add_more == 1 ? '(Bổ sung)' : '' }}
                                                </td>
                                                <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR[$lesson->status] ?? '' }}">
                                                    {{ App\Consts::SCHEDULE_STATUS[$lesson->status] ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $lesson->date }}
                                                </td>
                                                <td>
                                                    ({{ $lesson->period->start_time }} - {{ $lesson->period->end_time }})
                                                </td>
                                                <td>
                                                    {{ $lesson->room->name }}
                                                </td>
                                                <td>
                                                    {{ $lesson->teacher->name }}
                                                </td>
                                                <td>
                                                    <a href="{{route('trial_classs.attendances',['schedule_id'=>$lesson->id])}}" target="_blank" class="btn btn-info">
                                                        <i class="fa fa-external-link"></i> @lang('Xem chi tiết')
                                                    </a>
                                                </td>
                                            </tr>
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
