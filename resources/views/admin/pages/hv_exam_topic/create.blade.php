@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>

    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-dm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>
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
                            <h3 class="box-title">@lang('Tạo phiên thi')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Trình độ') <small class="text-red">*</small></label>
                                                <select required name="id_level" class="id_level form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($levels as $val)
                                                        <option value="{{ $val->id ?? '' }}">
                                                            {{ $val->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Tổ chức')</label>
                                                <select name="organization" class=" form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($organization as $key => $val)
                                                        <option value="{{ $key ?? '' }}">
                                                            {{ __($val) ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Phần thi') <small class="text-red">*</small></label>
                                                <select required name="is_type" class="form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($group as $val)
                                                        <option value="{{ $val }}">
                                                            {{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Chọn hình thức') <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($skill as $val)
                                                        <option value="{{ $val }}">
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Kiểu câu hỏi') <small class="text-red">*</small></label>
                                                <select required name="type_question" class="form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($type as $val)
                                                        <option value="{{ $val }}">
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('File Audio nếu có')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="files_audio" class="btn btn-primary file">
                                                                <i class="fa fa-picture-o"></i> @lang('Select')
                                                            </a>
                                                        </span>
                                                        <input id="files_audio" class="form-control" type="text"
                                                            name="audio"
                                                            placeholder="@lang('Files Audio')" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('Content')</label>
                                                    <textarea name="content" class="form-control" id="content_vi">{{ old('content') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);
    </script>
@endsection
