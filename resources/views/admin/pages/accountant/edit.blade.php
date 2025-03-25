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
                {{-- <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div> --}}
            </div>
            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">

                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Họ tên học viên'):</label>
                            <label class="col-sm-9 col-xs-12">{{ $detail->json_params->name ?? '' }}</label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Link CV học viên'):</label>
                            <label class="col-sm-9 col-xs-12">
                                <a
                                    href="{{ $detail->json_params->link_cv ?? '#' }}">{{ $detail->json_params->link_cv ?? 'Chưa cập nhật' }}</a>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Lịch phỏng vấn'):</label>
                            <label class="col-sm-9 col-xs-12">
                                {{ isset($jobs->time_interview) && $jobs->time_interview != '' ? date('d-m-Y', strtotime($jobs->time_interview)) : __('Đang cập nhât') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Tin ứng tuyển'):</label>
                            <label class="col-sm-9 col-xs-12">
                                <a href="{{ route('jobs.detail', $jobs->id) }}">{{ $jobs->job_title }}</a>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Kết quả phỏng vấn'):</label>
                            <label
                                class="col-sm-9 col-xs-12">{{ isset($detail->result_interview) && $detail->result_interview != '' ? __($type_result[$detail->result_interview]) : __('Đang cập nhât') }}</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Trạng thái hồ sơ'):</label>
                            <label
                                class="col-sm-9 col-xs-12">{{ isset($detail->result_profile) && $detail->result_profile != '' ? $type_profile[$detail->result_profile] : __('Đang cập nhât') }}</label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 text-right text-bold">@lang('Ghi chú'):</label>
                            <div class="col-md-6 col-xs-12">
                                <textarea name="json_params[accountant_note]" class="form-control" rows="5">{{ $detail->json_params->accountant_note ?? old('json_params[accountant_note]') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm">
                        <i class="fa fa-floppy-o"></i>
                        @lang('Save')
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
