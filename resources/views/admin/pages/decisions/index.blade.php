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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên hoặc mã học viên...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Loại đơn biến động')</label>
                                <select name="is_type" id="is_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_type']) && $value === $params['is_type'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Từ ngày')</label>
                                <input type="date" class="form-control from_date" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến ngày')</label>
                                <input type="date" class="form-control to_date" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm  mr-10" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                    <button type="button" data-toggle="modal" data-target="#create_decision"
                                    class="btn btn-success btn-sm mr-10">@lang('Import Excel')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
        {{-- End search form --}}
        <div id="create_decision" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import biến động</h4>
                    </div>
                    <form action="{{ route('decision_import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Chọn tệp') <a href="{{ url('data/images/decision.xlsx') }}"
                                            target="_blank">(@lang('Minh họa file excel'))</a></label>
                                    <small class="text-red">*</small>
                                    <div style="display: flex" class="d-flex">
                                        <input id="file" class="form-control" type="file" required name="file"
                                            placeholder="@lang('Select File')" value="">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"
                                                aria-hidden="true"></i> @lang('Import')</button>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
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
                                <th>@lang('STT')</th>
                                <th>@lang('Mã học viên')</th>
                                <th>@lang('Họ và tên')</th>
                                <th>@lang('Đơn biến động')</th>
                                <th>@lang('Nội dung')</th>
                                <th>@lang('File đơn đính kèm')</th>
                                <th>@lang('Ngày biến động')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Đơn')</th>
                                <th>@lang('Người tạo')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            <a target="_blank" data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                                data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route('students.show', $row->json_params->student->id ?? '') }}">
                                                {{ $row->json_params->student->admin_code ?? '' }}
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                                data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route('students.show', $row->json_params->student->id ?? '') }}">
                                                {{ $row->json_params->student->name ?? '' }}
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            {{ __($row->is_type) }}
                                        </td>

                                        <td>
                                            {{ $row->code }}
                                        </td>
                                        <td>
                                            @if ($row->file_name != '')
                                                <a href="{{ asset($row->file_name) }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($row->active_date)) }}
                                        </td>
                                        <td>
                                            {{ $row->note }}
                                        </td>
                                        <td>
                                            <input type="checkbox" name="is_sign" id="is_sign" value="1"
                                                {{ $row->is_sign == 1 ? 'checked' : '' }} disabled>
                                        </td>
                                        <td>
                                            {{ $row->signer }}
                                        </td>
                                        <td>
                                            {{ $row->updated_at }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
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


    </div>
@endsection
@section('script')
    <script></script>
@endsection
