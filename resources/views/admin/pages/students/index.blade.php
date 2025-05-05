@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)

            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới học viên')</a>
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
            <form action="{{ route(Request::segment(2) . '.index') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                       
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>

                                    <button type="button" data-toggle="modal" data-target="#create_crmdata_student"
                                    class="btn btn-success btn-sm">@lang('Import Excel')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}
        <div id="create_crmdata_student" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import học sinh</h4>
                    </div>
                    <form action="{{ route('data_student.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Chọn tệp') <a href="{{ url('themes\admin\img\data_student.xlsx') }}" target="_blank">(@lang('Minh họa file excel'))</a></label>
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
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
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
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>{{ $loop->index + 1 }}</td>

                                        <td>
                                            @if (!empty($row->avatar))
                                                <a href="{{ asset($row->avatar) }}" target="_blank" class="image-popup">
                                                    <img src="{{ asset($row->avatar) }}" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                                title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                <i class="fa fa-eye"></i> {{ $row->student_code  }}
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
                                            {{ isset($row->enrolled_at) &&  $row->enrolled_at !="" ?date("d-m-Y", strtotime($row->enrolled_at)): '' }}
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
@endsection
@section('script')
    <script>
        
    </script>
@endsection
