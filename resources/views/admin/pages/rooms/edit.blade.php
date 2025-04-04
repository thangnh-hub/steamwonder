@extends('admin.layouts.app')
@section('style')
  <style>
    #calendar {
      max-width: 100%;
      margin: 0 auto;
      background: #fff;
    }
  </style>
@endsection

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
    <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-lg-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Update form')</h3>
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
                  <button type="submit" class="btn btn-info btn-sm pull-right">
                    <i class="fa fa-save"></i> @lang('Save')
                  </button>
                </ul>

                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div class="d-flex-wap">

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>@lang('Title') <small class="text-red">*</small></label>
                          <input type="text" class="form-control" name="name" placeholder="@lang('Title')"
                            value="{{ old('name') ?? $detail->name }}" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>@lang('Slot')</label>
                          <input type="text" class="form-control" name="slot" placeholder="@lang('Slot')"
                            value="{{ $detail->slot ?? old('slot') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>@lang('Start date')</label>
                          <input type="datetime-local" class="form-control" name="start_date"
                            placeholder="@lang('Start_date')" value="{{ $detail->start_date ?? old('start_date') }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->

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
                    <option value="{{ $key }}"
                      {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}>
                      @lang($val)</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Area') <small class="text-red">*</small></h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <select name="area_id" class=" form-control select2" required>
                  <option value="">@lang('Area')</option>
                  @foreach ($areas as $val)
                    <option value="{{ $val->id }}"
                      {{ isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : '' }}>
                      {{ $val->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Type')</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <select name="type" class=" form-control select2">
                  @foreach ($type as $key => $val)
                    <option value="{{ $key }}"
                      {{ isset($detail->type) && $detail->type == $val ? 'selected' : '' }}>
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
@endsection
