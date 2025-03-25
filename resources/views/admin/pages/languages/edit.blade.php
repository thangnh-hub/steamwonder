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
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <div class="col-md-6">

            <div class="form-group">
              <label>
                @lang('Language name'):
              </label> <small class="text-red">*</small>
              <input type="text" class="form-control" name="lang_name" placeholder="@lang('Language name')"
                value="{{ old('lang_name') ?? $detail->lang_name }}" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>
                @lang('Locale'):
              </label> <small class="text-red">*</small>
              <input type="text" class="form-control" name="lang_locale" placeholder="@lang('Locale')"
                value="{{ old('lang_locale') ?? $detail->lang_locale }}" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>
                @lang('Language code'):
              </label> <small class="text-red">*</small>
              <input type="text" class="form-control" name="lang_code" placeholder="@lang('Language code')"
                value="{{ old('lang_code') ?? $detail->lang_code }}" required>
            </div>
          </div>
{{-- 
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Is default')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="is_default" value="1"
                    {{ $detail->is_default == '1' ? 'checked' : '' }}>
                  <small>@lang('true')</small>
                </label>
                <label>
                  <input type="radio" name="is_default" value="0" class="ml-15"
                    {{ $detail->is_default == '0' ? 'checked' : '' }}>
                  <small>@lang('false')</small>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Status')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="status" value="active" {{ $detail->status == 'active' ? 'checked' : '' }}>
                  <small>@lang('active')</small>
                </label>
                <label>
                  <input type="radio" name="status" value="deactive"
                    {{ $detail->status == 'deactive' ? 'checked' : '' }} class="ml-15">
                  <small>@lang('deactive')</small>
                </label>
              </div>
            </div>
          </div> --}}
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('iorder')</label>
              <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"
                value="{{ old('iorder') ?? $detail->iorder }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group box_img_right">
              <label>
                @lang('Flag image'):
              </label>
              <div id="image-holder" class="box_image {{ isset($detail->flag) ? 'active' : '' }}">
                <img class="img-width" src="{{ $detail->flag ?? url('themes/admin/img/no_image.jpg') }}">
                <input id="flag" class="form-control hidden list_image" type="text" name="flag"
                  value="{{ $detail->flag ?? '' }}">
                <span class="btn btn-sm btn-danger btn-remove" style="display: none"><i class="fa fa-trash"></i></span>
              </div>
              <span class="input-group-btn">
                <a data-input="flag" class="btn btn-primary lfm" data-type="cms-image">
                  <i class="fa fa-picture-o"></i> @lang('choose')
                </a>
              </span>
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
