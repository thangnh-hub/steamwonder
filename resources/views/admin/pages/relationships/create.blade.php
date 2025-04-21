@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        <form action="{{ route(Request::segment(2) . '.store') }}" method="POST">
                            @csrf
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin mối quan hệ <span class="text-danger">*</span></h5>
                                            </a>
                                        </li>
                                        <button type="submit" class="btn btn-info btn-sm pull-right">
                                            <i class="fa fa-save"></i> @lang('Save')
                                        </button>
                                    </ul>
            
                                    <div class="tab-content">
                                        <div class="tab-pane active" >
                                            <div class="d-flex-wap">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('Tiêu đề')<small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('Mô tả')</label>
                                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}">
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('Thứ tự')</label>
                                                        <input type="number" class="form-control" name="iorder" value="{{ old('iorder', 0) }}">
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>@lang('Trạng thái')</label>
                                                        <select name="status" class="form-control select2">
                                                            @foreach ($list_status as $key => $value)
                                                                <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                                                    {{ __($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- tab-pane -->
                                    </div> <!-- tab-content -->
                                </div>
                            </div>
                            <div class="box-footer">
                                <a href="{{ route(Request::segment(2) . '.index') }}">
                                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
       
    </script>
@endsection
