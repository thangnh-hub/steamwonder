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
        <h3 class="box-title">Thông tin đăng ký dịch vụ</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">

          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('fullname'):</label>
              <label class="col-sm-9 col-xs-12">{{ $detail->admin_name ?? '' }}</label>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('email'):</label>
              <label class="col-sm-9 col-xs-12">
                {{ $detail->admin_email ?? '' }}
              </label>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('phone'):</label>
              <label class="col-sm-9 col-xs-12">
                {{ $detail->admin_phone ?? '' }}
              </label>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('Courses name'):</label>
              <label class="col-sm-9 col-xs-12">
                <a target="_blank" href="{{ route('syllabuss_online.index').'?keyword='.$detail->syllabuss_name }}" data-toggle="tooltip" title="@lang('view')"
                  data-original-title="@lang('view')">
                  {{ $detail->syllabuss_name }}
                  <i class="fa fa-external-link"></i>
                </a>
              </label>
            </div>
            <div class="form-group">
                <label class="col-sm-3 text-right text-bold">@lang('Total money'):</label>
                <label class="col-sm-9 col-xs-12">
                    {{ isset($detail->syllabuss_json_params->price) ? number_format($detail->syllabuss_json_params->price, 0, ',', '.'):'' }} VNĐ
                </label>
              </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('status'):</label>
              <div class="col-sm-6 col-xs-12 ">
                <div class="form-control">
                  @foreach (App\Consts::ORDER_STATUS as $key => $value)
                    <label>
                      <input type="radio" name="status" value="{{ $key }}"
                        {{ $detail->status == $key ? 'checked' : '' }}>
                      <small class="mr-15">{{ __($value) }}</small>
                    </label>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">Nội dung ghi chú Admin:</label>
              <div class="col-md-6 col-xs-12">
                <textarea name="admin_note" class="form-control"
                  rows="5">{{ $detail->admin_note ?? old('admin_note') }}</textarea>
              </div>
            </div>
          </div>

        </div>

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('list')
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            @lang('save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection
