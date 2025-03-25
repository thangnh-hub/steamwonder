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
            <form action="{{ route('reports.class.attendance_empty') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <input type="text" name="keyword" class="form-control"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="area_id[]" id="" class="form-control select2" multiple
                                    style="width: 100%;" aria-placeholder="Chọn khu vực">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && in_array($item->id, $params['area_id']) ? 'selected' : '' }}>
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
                                    <a class="btn btn-default btn-sm" href="{{ route('reports.class.attendance_empty') }}">
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
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive">
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
                @if (count($class) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table  table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Stt')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Giáo viên')</th>
                                <th style="width:350px">@lang('Chưa điểm danh')</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($class as $item)
                            @php
                               $unAttendanceDates = $item->schedules->pluck('date')->map(function($date) {
                                    return \Carbon\Carbon::parse($date)->format('d/m/Y'); // Định dạng ngày tháng năm
                                })->toArray();
                                $teacher = \App\Models\Teacher::where('id',$item->json_params->teacher ?? 0,)->first();
                            @endphp
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td><a href="{{ route('classs.edit',$item->id) }}">{{ $item->name??"" }}</a></td>
                                    <td>{{ $item->area->name??"" }}</td>
                                    <td>{{ $teacher->name??"" }}</td>
                                    <td>{{ $item->unattendance_count??"" }} ({{ implode(', ', $unAttendanceDates) }})</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ count($class) }} kết quả
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script>
       
    </script>
@endsection
