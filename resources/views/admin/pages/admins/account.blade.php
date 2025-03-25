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
        <h3 class="box-title">@lang('Update information user')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route('admin.account.change.post') }}" method="POST">
        @csrf
        @method('POST')
        <div class="box-body">

          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">@lang('Role'):</label>
              <label class="col-sm-9 col-xs-12">
                @foreach ($roles as $item)
                  @if ($admin_auth->role == $item->id)
                    {{ $item->name }}
                  @endif
                @endforeach
              </label>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                @lang('Fullname'):
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" id="name" class="form-control" name="name" required
                  value="{{ old('name') ?? $admin_auth->name }}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                @lang('Email'):
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="email" id="email" class="form-control" name="email" readonly
                  value="{{ old('email') ?? $admin_auth->email }}">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                @lang('Password Old'):
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password_old" class="form-control" name="password_old" required value=""
                  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                @lang('New Password'):
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password" class="form-control" name="password" required value=""
                  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                @lang('Confirm New Password'):
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required
                  value="" autocomplete="off">
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
