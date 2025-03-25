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
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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

                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Mã chương trình')</th>
                                <th>@lang('Mã đối tác')</th>
                                <th>@lang('Số chương trình')</th>
                                <th>@lang('Loại chương trình')</th>
                                <th>@lang('Số lượng')</th>
                                <th>@lang('Nhóm ngành')</th>
                                <th>@lang('Ngành')</th>
                                <th>@lang('Bang')</th>
                                <th>@lang('Giới tính')</th>
                                <th>@lang('Kỳ xuất cảnh dự kiến')</th>
                                {{-- <th>@lang('Nội dung')</th> --}}
                                <th>@lang('Tổng CV ứng tuyển')</th>
                                <th>@lang('Đạt/ Không đạt/ Vắng mặt/ Bị loại')</th>
                                {{-- <th>@lang('Updated at')</th> --}}
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                            @isset($user_action)
                            @php
                                $total = $user_action->filter(function ($item, $key) use ($row) {
                                    return $item->job_id == $row->id;
                                });
                                $pass=$user_action->filter(function ($item, $key) use ($row) {
                                    return $item->job_id == $row->id && $item->result_interview=='pass';
                                });
                                $nopass=$user_action->filter(function ($item, $key) use ($row) {
                                    return $item->job_id == $row->id && $item->result_interview=='nopass';
                                });
                                $absent=$user_action->filter(function ($item, $key) use ($row) {
                                    return $item->job_id == $row->id && $item->result_interview=='absent';
                                });
                                $cancel=$user_action->filter(function ($item, $key) use ($row) {
                                    return $item->job_id == $row->id && $item->result_interview=='cancel';
                                });
                            @endphp
                            @endisset
                                <tr class="valign-middle">
                                    <td>
                                        <a href="{{route('jobs.detail',$row->id)}}" target="_blank">
                                            <strong style="font-size: 14px;">{{ $row->job_title ?? '' }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->job_code??""}}
                                    </td>
                                    <td>
                                        {{ $row->partner_code??""}}
                                    </td>
                                    <td>
                                        {{ $row->maijor_quantity??""}}
                                    </td>
                                    <td>
                                        {{ $row->maijor_code??""}} - {{ $row->maijor->name??""}}
                                    </td>
                                    <td>
                                        {{ $row->quantity??""}}
                                    </td>
                                    <td>
                                        {{ $row->industry->name??""}}
                                    </td>
                                    <td>
                                        {{ $row->profession??""}}
                                    </td>
                                    <td>
                                        {{ $row->state??""}}
                                    </td>
                                    <td>
                                        {{ App\Consts::GENDER_JOB[$row->gender_job]??""}}
                                    </td>
                                    <td>
                                        {{ $row->exit_period??""}}
                                    </td>
                                    {{-- <td>
                                        {!! $row->json_params->content??""!!}
                                    </td> --}}
                                    <td>
                                        {{ isset($total) ? count($total) : '0' }}
                                    </td>
                                    <td>
                                        {{ count($pass) }}/ {{ count($nopass) }}/ {{ count($absent) }}/ {{ count($cancel) }}
                                    </td>
                                    {{-- <td>
                                        {{ $row->updated_at }}
                                    </td> --}}
                                    <td>
                                        @lang($row->status)
                                    </td>
                                    <td>
                                        <div class="d-flex-wap">
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                                method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                    title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
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
@endsection
