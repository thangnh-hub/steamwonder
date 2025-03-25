@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .font-bold{
            font-weight: bold;
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Họ tên học viên')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái hồ sơ')</label>
                                <select name="is_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type_profile as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_type']) && $params['is_type'] == $key ? 'selected' : '' }}>
                                            @lang($item)
                                        </option>
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
                                <th>@lang('Học viên')</th>
                                <th style="width:120px">@lang('Ảnh CV')</th>
                                <th>@lang('Tiêu đề CV')</th>
                                <th>@lang('Mục đã điền')</th>
                                <th>@lang('Trạng thái hồ sơ')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px;">{{ $row->user->name ?? '' }}</strong>
                                    </td>
                                    <td>
                                        <img style="width:80px;height:120px" src="{{ isset($row->json_params->upload_image->avatar)?$row->json_params->upload_image->avatar:asset('themes/admin/img/no_image.jpg') }}" >
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route(Request::segment(2) . '.show', $row->id) }}"> {{ $row->cv_title ?? '' }}</a>    
                                    </td>
                                    
                                    <td>
                                        <ul>
                                            @php
                                                $text_anh = isset($row->json_params->upload_image->avatar) ? "text-success" :"text-danger";
                                                $icon_anh = isset($row->json_params->upload_image->avatar) ? "fa-check-square" :"fa-window-close";

                                                $text_cv = isset($row->json_params->profile) ? "text-success" :"text-danger";
                                                $icon_cv = isset($row->json_params->profile) ? "fa-check-square" :"fa-window-close";

                                                $text_thudongluc = isset($row->json_params->hobby->letter) ? "text-success" :"text-danger";
                                                $icon_thudongluc = isset($row->json_params->hobby->letter) ? "fa-check-square" :"fa-window-close";

                                                $text_hochieu = isset($row->json_params->upload_image->passport_images) ? "text-success" :"text-danger";
                                                $icon_hochieu = isset($row->json_params->upload_image->passport_images) ? "fa-check-square" :"fa-window-close";

                                                $text_cap3 = isset($row->json_params->upload_image->diploma_image) ? "text-success" :"text-danger";
                                                $icon_cap3 = isset($row->json_params->upload_image->diploma_image) ? "fa-check-square" :"fa-window-close";

                                                $text_tiengduc = isset($row->json_params->upload_image->germany_images) ? "text-success" :"text-danger";
                                                $icon_tiengduc = isset($row->json_params->upload_image->germany_images) ? "fa-check-square" :"fa-window-close";

                                                $text_video = isset($row->json_params->upload_image->other_file) ? "text-success" :"text-danger";
                                                $icon_video = isset($row->json_params->upload_image->other_file) ? "fa-check-square" :"fa-window-close";

                                                $text_chuky = isset($row->json_params->upload_image->signature_image) ? "text-success" :"text-danger";
                                                $icon_chuky = isset($row->json_params->upload_image->signature_image) ? "fa-check-square" :"fa-window-close";
                                            @endphp
                                            <li class="{{ $text_anh }}">@lang('Ảnh') <i class="fa {{ $icon_anh }}"></i> </li>
                                            <li class="{{ $text_cv }}">@lang('CV') <i class="fa {{ $icon_cv }}"></i> </li>
                                            <li class="{{ $text_thudongluc }}">@lang('Thư động lực') <i class="fa {{ $icon_thudongluc }}"></i> </li>
                                            <li class="{{ $text_hochieu }}">@lang('Hộ chiếu') <i class="fa {{ $icon_hochieu }}"></i> </li>
                                            <li class="{{ $text_cap3 }}">@lang('Bằng THPT') <i class="fa {{ $icon_cap3 }}"></i> </li>
                                            <li class="{{ $text_tiengduc }}">@lang('Chứng chỉ tiếng Đức') <i class="fa {{ $icon_tiengduc }}"></i> </li>
                                            <li class="{{ $text_video }}">@lang('Video') <i class="fa {{ $icon_video }}"></i> </li>
                                            <li class="{{ $text_chuky }}">@lang('Chữ ký') <i class="fa {{ $icon_chuky }}"></i> </li>
                                            
                                        </ul>
                                    </td>
                                    <td>
                                        {{ __($row->status ?? '') }}
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex-wap" style="gap:5px">
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Edit')" data-original-title="@lang('Edit')"
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
