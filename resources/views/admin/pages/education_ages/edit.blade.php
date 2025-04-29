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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Update form')</h3>
            </div>
            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <!-- Custom Tabs -->
                    <div class="d-flex-wap">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Name') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="name" placeholder="@lang('Name')"
                                    value="{{ $detail->name ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Iorder')</label>
                                <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"
                                    value="{{ $detail->iorder }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($areas as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ $detail->area_id && $detail->area_id == $val->id ? 'selected' : '' }}>
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
                                            {{ $detail->status && $detail->status == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('From month') <small class="text-red">*</small></label>
                                <input type="number" class="form-control" name="from_month"
                                    placeholder="@lang('From month')" value="{{ $detail->from_month ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('To month') <small class="text-red">*</small></label>
                                <input type="number" class="form-control" name="to_month" placeholder="@lang('To month')"
                                    value="{{ $detail->to_month }}" required>
                            </div>
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
        </div>

    </section>
@endsection

@section('script')
    <script></script>
@endsection
