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

      <!-- form start -->
      <form role="form" action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">

            <style>
              .nav-tabs {
                padding: 0px;
              }

              .nav-tabs li {
                width: 100%;
                background-color: #ECF0F5;
              }

              .nav-tabs li a {
                border: solid 1px #ECF0F5;
                padding-top: 20px;
                padding-bottom: 20px;
              }

              .tab-content {
                border: solid 1px #ECF0F5;
              }

              .nav-tabs-custom>.nav-tabs>li:first-of-type.active>a,
              .nav-tabs-custom>.nav-tabs>li.active>a {
                border-left-color: #ECF0F5;
                border-bottom-color: #ECF0F5;
              }

              .nav-tabs li a i {
                width: 20px;
              }

              .select2-container {
                width: 100% !important;
              }
            </style>

            <ul class="nav nav-tabs col-md-3">
              <li class="active">
                <a href="#tab_4" data-toggle="tab">
                  <h5>
                    <i class="fa fa-code"></i>
                    @lang('CSS and Javascript')
                  </h5>
                </a>
              </li>
              <li>
                <a href="#tab_5" data-toggle="tab">
                  <h5>
                    <i class="fa fa-object-group"></i>
                    @lang('Page & Layout default')
                  </h5>
                </a>
              </li>

            </ul>
            <div class="tab-content col-md-9">

              <div class="tab-pane active" id="tab_4">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Header code')</label>
                      <textarea name="header_code" id="header_code" class="form-control" rows="10">{{ old('header_code') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Footer code')</label>
                      <textarea name="footer_code" id="footer_code" class="form-control" rows="10">{{ old('footer_code') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>


              <div class="tab-pane" id="tab_5">
                <h3>Widget default</h3>
                <div class="row">
                  @foreach ($widgetConfig as $val)
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>@lang($val->name)</label>
                        <select name="widget[]" class=" form-control select2">
                          <option value="">@lang('Please select')</option>
                          @foreach ($widgets as $val_wg)
                            @if ($val_wg->widget_code == $val->widget_code)
                              <option value="{{ $val_wg->id }}"
                                {{ isset($setting->widget) && in_array($val_wg->id, json_decode($setting->widget)) ? 'selected' : '' }}>
                                @lang($val_wg->title)
                              </option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>
                  @endforeach


                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right btn-sm">
            <i class="fa fa-floppy-o"></i>
            @lang('Save')
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection
@section('script')
  <script>
    $(document).ready(function() {

    });
  </script>
@endsection
