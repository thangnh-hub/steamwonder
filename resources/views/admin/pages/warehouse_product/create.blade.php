@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã sản phẩm') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="code"
                                                        placeholder="@lang('Mã sản phẩm')" value="{{ old('code') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Tên sản phẩm') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Tên sản phẩm')" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Loại sản phẩm')<small class="text-red">*</small></label>
                                                    <select required name="warehouse_type" class=" form-control select2">
                                                        <option value="">Chọn</option>
                                                        @foreach ($list_type as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->warehouse_type) && $detail->warehouse_type == $key ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Danh mục')<small class="text-red">*</small></label>
                                                    <select required name="warehouse_category_id"
                                                        class=" form-control select2">
                                                        <option value="">Chọn</option>
                                                        @foreach ($list_category as $key => $val)
                                                            @if($val->category_parent==""||$val->category_parent==NULL)
                                                                <option value="{{ $val->id }}"
                                                                    {{ isset($detail->warehouse_category_id) && $detail->warehouse_category_id == $val->id ? 'selected' : '' }}>
                                                                    @lang($val->name ?? '')</option>
                                                                @foreach ($list_category as $row_child)
                                                                    @if($row_child->category_parent==$val->id)
                                                                        <option value="{{ $row_child->id }}">--- {{$row_child->code??"" }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Đơn vị tính') </label>
                                                    <input type="text" class="form-control" name="unit"
                                                        placeholder="@lang('Đơn vị tính')" value="{{ old('unit') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Đơn giá') </label>
                                                    <input type="text" class="form-control" name="price"
                                                        placeholder="@lang('price')" value="{{ old('price') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Quy cách') </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[specification]" placeholder="@lang('Quy cách')"
                                                        value="{{ old('json_params[specification]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Xuất xứ') </label>
                                                    <input type="text" class="form-control" name="json_params[origin]"
                                                        placeholder="@lang('Xuất xứ')"
                                                        value="{{ old('json_params[origin]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Hãng sx') </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[manufacturer]" placeholder="@lang('Hãng sx')"
                                                        value="{{ old('json_params[manufacturer]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Bảo hành') </label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[warranty]" placeholder="@lang('Bảo hành')"
                                                        value="{{ old('json_params[warranty]') }}">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái')</label>
                                                    <select name="status" class=" form-control select2">
                                                        @foreach ($status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->status) && $detail->status == $val ? 'checked' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Level')</label>
                                                    <select name="json_params[level]" class=" form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($level as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->level) && $detail->json_params->level == $val->id ? 'selected' : '' }}>
                                                                @lang($val->name)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Code Auto') </label>
                                                    {{-- <select name="code_auto" class=" form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($level as $val)
                                                            <option value="CC"
                                                                {{ isset($detail->code_auto) && $detail->code_auto == 'CC' ? 'selected' : '' }}>
                                                                CC - Công cụ, dụng cụ</option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="text" class="form-control" name="code_auto"
                                                        placeholder="@lang('Code Auto')"
                                                        value="{{ old('code_auto') }}">
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã sản phẩm') </label>
                                                    <input type="text" class="form-control" name="code_product"
                                                        placeholder="@lang('Mã sản phẩm')"
                                                        value="{{ old('code_product') }}">
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script></script>
@endsection
