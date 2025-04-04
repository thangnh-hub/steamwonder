@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection
@push('style')
  <style>
    .del_day {
      color: red;
      background: #eee;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      text-align: center;
      top: 20px;
      right: 5px;
      cursor: pointer;
    }
  </style>
@endpush

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
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

    <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
      @csrf
      <div class="row">
        <div class="col-lg-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Create form')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

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
                    <div class="d-flex-wap ">

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>@lang('Title') <small class="text-red">*</small></label>
                          <input type="text" class="form-control" name="name" placeholder="@lang('Title')"
                            value="{{ old('title') }}" required>
                        </div>
                      </div>

                      <div class="col-md-12 ">
                        <div class="box-day row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>@lang('Date') <small class="text-red">*</small></label>
                              <input type="date" class=" form-control " name="date[]" value="{{ date('Y-m-d') }}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 ">
                        <button type="button" class="btn btn-primary add_day">Thêm ngày</button>
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
    </form>
  </section>

@endsection

@section('script')
  <script>
    function del_day(th) {
      $(th).parents('.parrent_day').remove();
    }

    $('.add_day').click(function() {
      var _html = `<div class="col-md-3 parrent_day">
                        <span onclick="del_day(this)" class="position-absolute del_day">x</span>
                            <div class="form-group">
                                <label>@lang('Date') <small class="text-red">*</small></label>
                                <input type="date" class=" form-control " name="date[]" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>`;
      $('.box-day').append(_html);
    })
  </script>
@endsection
