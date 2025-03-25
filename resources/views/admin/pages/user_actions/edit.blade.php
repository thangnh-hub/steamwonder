@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
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

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Thông tin ứng tuyển')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="d-flex-wap box-header">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Họ tên học viên: </strong></label>
                            <span>{{ $detail->json_params->name ?? 'Chưa cập nhật' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Link CV học viên: </strong></label>
                            <span>{{ $detail->json_params->link_cv ?? 'Chưa cập nhật' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>@lang('Tin ứng tuyển'): </strong></label>
                            <span><a href="{{route('jobs.detail', $jobs->id) }}">{{$jobs->job_title}}</a></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>@lang('Lịch phỏng vấn'): </strong></label>
                            <span>{{ isset($jobs->time_interview) && $jobs->time_interview != '' ? date('d-m-Y', strtotime($jobs->time_interview)) : __('Đang cập nhât') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>@lang('Kết quả phỏng vấn'): </strong></label>
                            <span>{{ isset($detail->result_interview) && $detail->result_interview != '' ? __($type_result[$detail->result_interview]) : __('Đang cập nhât') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách lịch sử Test - Ôn luyện')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body table-responsive">
                @if (count($schedule_test) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('Lịch chưa cập nhật')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Order')</th>
                                <th>@lang('Loại')</th>
                                <th>@lang('Ngày')</th>
                                <th>@lang('Kết quả')</th>
                                <th>@lang('Ghi chú')</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule_test as $row)
                                <tr class="valign-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        {{ isset($row->is_type) ? __($type_schedule_test[$row->is_type]) : 'Đang cập nhật' }}
                                    </td>
                                    <td>
                                        {{ isset($row->time) ? date('d-m-Y', strtotime($row->time)) : 'Đang cập nhật' }}
                                    </td>
                                    <td>
                                        {{ $row->result ?? 'Đang cập nhật' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->note ?? 'Đang cập nhật' }}

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
