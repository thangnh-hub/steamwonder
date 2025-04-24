@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
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

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Create form')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
                @csrf
                <div class="box-body d-flex-wap">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Name') <small class="text-red">*</small></label>
                            <input type="text" class="form-control" name="name" placeholder="@lang('Name')"
                                value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Iorder')</label>
                            <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"
                                value="{{ old('iorder') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Area')</label>
                            <select name="area_id" class="form-control select2 w-100">
                                <option value="">@lang('Please choose')</option>
                                @foreach ($areas as $key => $val)
                                    <option value="{{ $val->id }}"
                                        {{ old('area_id') && old('area_id') == $val->id ? 'selected' : '' }}>
                                        {{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control select2 w-100">
                                @foreach ($status as $key => $val)
                                    <option value="{{ $key }}"
                                        {{ old('status') && old('status') == $val ? 'selected' : '' }}>
                                        @lang($val)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                        @lang('Save')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
