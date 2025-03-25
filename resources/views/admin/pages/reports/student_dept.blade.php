@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .mr-1{
            margin-right: 1em;
        }
        th {
            text-align: center;
            vertical-align: middle !important;
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
        @if (session('errorMessage'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('errorMessage') }}
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
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.student.is.debt') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Version')</label>
                                <select name="version"  class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($version_dept as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['version']) && $key == $params['version'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại hợp đồng')</label>
                                <select name="contract_type"  class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_type as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['contract_type']) && $key == $params['contract_type'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name ?? '') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Xác nhận')</label>
                                <select name="ketoan_xacnhan" class="form-control select2" style="width: 100%;">
                                    @foreach ($ketoan as $key => $val)
                                        <option {{ isset($params['ketoan_xacnhan']) && $key== $params['ketoan_xacnhan'] ? 'selected' : '' }} value="{{ $key }}">{{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.student.is.debt') }}">
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
        @if(isset($params['version']) && $params['version'] == "version1")
            @include('admin.components.dept.version1')
        @endif
        @if(isset($params['version']) && $params['version'] == "version2")
            @include('admin.components.dept.version2')
        @endif
        <div id="import_excel" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('Cập nhật trạng thái bằng Excel')</h4>
                    </div>
                    <form  action="{{ route('store.studentdept.import') }}" method="post" enctype="multipart/form-data">
                       @csrf
                        <div class="modal-body row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Chọn tệp') <a href="{{ url('data/studentDept.xlsx') }}"
                                            download="">(@lang('Minh họa file excel'))</a></label>
                                    <small class="text-red">*</small>
                                    <input id="file" class="form-control" type="file" required name="file"
                                        placeholder="@lang('Select File')" value="">
                                        <input type="hidden" name="version" value="{{ isset($params['version']) ? $params['version'] : '' }}">
                                        <br>
                                    <img src="{{ url('data/studentDept.png') }}" alt="">    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o"
                                    aria-hidden="true"></i> @lang('Import')</button>
                        </div>
                    </form>
                </div>
        
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
    $('.confirmClass').click(function (e) { 
        if (confirm('Bạn có chắc chắn xác nhận học viên này không?')){
            let _id = $(this).attr('data-id');
            let _confirm = $(this).parents('td').find('.ketoan_xacnhan').val();
            let url = "{{ route('ajax.confirm.student') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    confirm: _confirm,
                },
                success: function(response) {
                    alert('Cập nhật thành công');
                    location.reload();
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
