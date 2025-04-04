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

    <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
      @csrf
      <div class="row">
        <div class="col-lg-8">
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
                    <div class="d-flex-wap">

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>@lang('Order') <small class="text-red">*</small></label>
                          <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                            value="{{ $detail->iorder ?? old('iorder') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>@lang('Start')</label>
                          <input type="time" class="form-control" name="start_time" placeholder="@lang('Start')"
                            value="{{ old('start_time') ?? '' }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>@lang('End')</label>
                          <input type="time" class="form-control" name="end_time" placeholder="@lang('End')"
                            value="{{ old('end_time') ?? '' }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- /.tab-content -->
              </div><!-- nav-tabs-custom -->

            </div>
            <!-- /.box-body -->


          </div>
        </div>
        <div class="col-lg-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Publish')</h3>
            </div>
            <div class="box-body">
              <div class="btn-set">
                <button type="submit" class="btn btn-info">
                  <i class="fa fa-save"></i> @lang('Save')
                </button>
                &nbsp;&nbsp;
                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                  <i class="fa fa-bars"></i> @lang('List')
                </a>
              </div>
            </div>
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Status')</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <select name="status" class=" form-control select2">
                  @foreach ($status as $key => $val)
                    <option value="{{ $key }}">
                      @lang($val)</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>

@endsection

@section('script')
  <script></script>
@endsection
