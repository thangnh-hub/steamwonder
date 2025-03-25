@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .input-group-btn {
            vertical-align: bottom !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
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
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Tiêu đề') </label>
                                                    <input required type="text" name="json_params[title]"
                                                        class="form-control" placeholder="Nhập tiêu đề">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Kiểu') <small class="text-red">*</small></label>
                                                    <select name="is_type" class="form-control select2" required>
                                                        @foreach ($type as $key => $item)
                                                            <option value="{{ $key }}">@lang($item)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Người thực hiện') <small class="text-red">*</small></label>
                                                    <select name="id_admin_action" class=" form-control select2">
                                                        @foreach ($admin_action as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{$val->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày thực hiện') <small class="text-red">*</small></label>
                                                    <input type="datetime-local" required
                                                        min="{{ date('Y-m-d', time()) }}T00:00" class="form-control"
                                                        name="list[0][time]" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số lượng') <small class="text-red">*</small></label>
                                                    <input type="number" required min="1" class="form-control"
                                                        name="list[0][slot]" value="1">
                                                </div>
                                            </div>
                                            <div class="box-append">

                                            </div>


                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary add-option"><i
                                                        class="fa fa-plus"></i>@lang(' Thêm ngày và sức chứa')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
                <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                    @lang('Save')</button>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        function delete_this(th) {
            $(th).parents('.more-item').remove()
        }
        $('.add-option').click(function() {
            currentTime = $.now();
            var _html = `<div class="more-item"><div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày thực hiện') <small class="text-red">*</small></label>
                                <input type="datetime-local" required
                                    min="{{ date('Y-m-d', time()) }}T00:00" class="form-control"
                                    name="list[` + currentTime + `][time]" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group ">
                                    <label>@lang('Sức chứa') <small class="text-red">*</small></label>
                                    <input type="number" required min="1" class="form-control"
                                    name="list[` + currentTime + `][slot]" value="1">
                                    <span onclick="delete_this(this)" class="input-group-btn">
                                        <a class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Xóa </a>
                                    </span>
                                </div>
                            </div>
                        </div></div>`;
            $('.box-append').append(_html);
        })
    </script>
@endsection
