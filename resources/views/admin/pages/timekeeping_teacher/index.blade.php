@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
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
                                <label>@lang('Giáo viên') </label>
                                <select name="teacher_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($teacher as $key => $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : '' }}>
                                            {{ __($item->name??"")}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tháng') </label>
                                <input type="month" class="form-control" name="months" placeholder="Ngày làm việc"
                                    value="{{ isset($params['months']) ? $params['months'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại công việc')</label>
                                <select name="type" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['type']) && $params['type'] == $key ? 'selected' : '' }}>
                                            {{ __($item)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái duyệt')</label>
                                <select name="approve" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($approve as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['approve']) && $params['approve'] == $key ? 'selected' : '' }}>
                                            {{ __($item)}}</option>
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
                                <th>@lang('Mã giáo viên')</th>
                                <th>@lang('Giáo viên')</th>
                                <th>@lang('Ngày')</th>
                                <th>@lang('Ca làm việc')</th>
                                <th>@lang('Loại công việc')</th>
                                <th style="width:250px">@lang('Ghi chú')</th>
                                <th>@lang('Duyệt')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $row->teacher->admin_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->teacher->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->date!="" ? date("d-m-Y",strtotime($row->date)) : '' }}
                                        </td>
                                        <td>
                                            {{ $row->periods->iorder ?? '' }} ({{ $row->periods->start_time ?? '' }} - {{ $row->periods->end_time ?? '' }})
                                        </td>
                                        <td>
                                            {{ __($row->type) }}
                                        </td>
                                        <td>
                                            {{ __($row->note) }}
                                        </td>
                                        <td>
                                            {{ __($row->is_approve) }}
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
                                            <button data-id="{{ $row->id }}"  type="button" class="btn btn-sm btn-success approve">@lang('Duyệt')</button>
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
    $('.approve').click(function (e) { 
        if (confirm('Bạn có chắc chắn muốn duyệt chấm công cho giáo viên này ?')){
            let _id = $(this).attr('data-id');
            let url = "{{ route('timekeeping_teacher.approve') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                },
                success: function(response) {
                    if (response.message == 'success'){
                        alert('Cập nhật thành công');
                        location.reload();
                    }else{
                        alert("Thực hiện không thành công.Không có quyền truy cập")
                    }
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    });   
    </script>
@endsection
