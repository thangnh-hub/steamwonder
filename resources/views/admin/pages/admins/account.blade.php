@extends('admin.layouts.app')

@section('title')
  @lang($module_name)
@endsection
@push('style')
  <style>
    .box {
      border-top: 3px solid #d2d6de;
    }

    @media (max-width: 768px) {
      .pull-right {
        float: right !important;
      }
    }

    .label {
      font-size: 100%;
      font-weight: 400;
      line-height: 2;
    }
  </style>
@endpush
@section('content')

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

    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle"
              src="{{ asset($admin_auth->avatar ?? 'themes/admin/img/no_image.jpg') }}" alt="{{ $admin_auth->name }}">
            <h3 class="profile-username text-center">{{ $admin_auth->name }}</h3>
            <p class="text-muted text-center">
              {{ __($admin_auth->admin_type) }}
            </p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                Email <a class="pull-right">{{ $admin_auth->email }}</a>
              </li>
              <li class="list-group-item">
                Điện thoại <a class="pull-right">{{ $admin_auth->phone ?? 'Chưa cập nhật' }}</a>
              </li>
              <li class="list-group-item">
                Địa chỉ <a class="pull-right">{{ $admin_auth->json_params->address ?? 'Chưa cập nhật' }}</a>
              </li>
              <li class="list-group-item">
                Ngày sinh <a
                  class="pull-right">{{ $admin_auth->birthday ? \Carbon\Carbon::parse($admin_auth->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}</a>
              </li>
              <li class="list-group-item">
                Phòng ban <a class="pull-right">{{ $admin_auth->department->name }}</a>
              </li>
              <li class="list-group-item">
                Khu vực <a class="pull-right">{{ $admin_auth->area->name }}</a>
              </li>
              <li class="list-group-item">
                Người quản lý <a class="pull-right">{{ $admin_auth->direct_manager->name }}</a>
              </li>
              <li class="list-group-item">
                Cập nhật lúc <a class="pull-right">{{ $admin_auth->updated_at->format('H:i:s d/m/Y') }}</a>
              </li>
            </ul>

          </div>
        </div>

      </div>

      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-body">
            <p>
              <strong><i class="fa fa-book margin-r-5"></i> Quyền hệ thống: </strong>
              <span class="label label-primary">{{ $admin_auth->getRole->name }}</span>
            </p>
            <p>
              <strong><i class="fa fa-book margin-r-5"></i> Quyền mở rộng: </strong>
              @isset($admin_auth->role_extends)
                @foreach ($admin_auth->role_extends as $i)
                  <span class="label label-primary">{{ $i->name }}</span>
                @endforeach
              @endisset
            </p>
            <hr>
            @isset($admin_auth->area_extends)
              <p>
                <strong><i class="fa fa-map-marker margin-r-5"></i> Khu vực dữ liệu quản lý: </strong>
                @foreach ($admin_auth->area_extends as $i)
                  <span class="label label-success">{{ $i->name }}</span>
                @endforeach
              </p>
            @endisset
          </div>
        </div>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">@lang('Cập nhật mật khẩu')</h3>
          </div>

          <form role="form" action="{{ route('admin.account.change.post') }}" method="POST">
            @csrf
            @method('POST')
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">
                    @lang('Password Old'):
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-9 col-xs-12">
                    <input type="password" id="password_old" class="form-control" name="password_old" required
                      value="" autocomplete="off">
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
                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation"
                      required value="" autocomplete="off">
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

      </div>
    </div>






  </section>
@endsection
