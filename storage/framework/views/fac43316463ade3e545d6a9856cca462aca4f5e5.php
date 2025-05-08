

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

  <!-- Main content -->
  <section class="content">
    <?php if(session('errorMessage')): ?>
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo e(session('errorMessage')); ?>

      </div>
    <?php endif; ?>
    <?php if(session('successMessage')): ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo e(session('successMessage')); ?>

      </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <p><?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle"
              src="<?php echo e(asset($admin_auth->avatar ?? 'themes/admin/img/no_image.jpg')); ?>" alt="<?php echo e($admin_auth->name); ?>">
            <h3 class="profile-username text-center"><?php echo e($admin_auth->name); ?></h3>
            <p class="text-muted text-center">
              <?php echo e(__($admin_auth->admin_type)); ?>

            </p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                Email <a class="pull-right"><?php echo e($admin_auth->email); ?></a>
              </li>
              <li class="list-group-item">
                Điện thoại <a class="pull-right"><?php echo e($admin_auth->phone ?? 'Chưa cập nhật'); ?></a>
              </li>
              <li class="list-group-item">
                Địa chỉ <a class="pull-right"><?php echo e($admin_auth->json_params->address ?? 'Chưa cập nhật'); ?></a>
              </li>
              <li class="list-group-item">
                Ngày sinh <a
                  class="pull-right"><?php echo e($admin_auth->birthday ? \Carbon\Carbon::parse($admin_auth->birthday)->format('d/m/Y') : 'Chưa cập nhật'); ?></a>
              </li>
              <li class="list-group-item">
                Phòng ban <a class="pull-right"><?php echo e(optional($admin_auth->department)->name); ?></a>
              </li>
              <li class="list-group-item">
                Khu vực <a class="pull-right"><?php echo e($admin_auth->area->name); ?></a>
              </li>
              <li class="list-group-item">
                Người quản lý <a class="pull-right"><?php echo e(optional($admin_auth->direct_manager)->name); ?></a>
              </li>
              <li class="list-group-item">
                Cập nhật lúc <a class="pull-right"><?php echo e($admin_auth->updated_at->format('H:i:s d/m/Y')); ?></a>
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
              <span class="label label-primary"><?php echo e(optional($admin_auth->getRole)->name); ?></span>
            </p>
            <p>
              <strong><i class="fa fa-book margin-r-5"></i> Quyền mở rộng: </strong>
              <?php if(isset($admin_auth->role_extends)): ?>
                <?php $__currentLoopData = $admin_auth->role_extends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="label label-primary"><?php echo e($i->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
            </p>
            <hr>
            <?php if(isset($admin_auth->area_extends)): ?>
              <p>
                <strong><i class="fa fa-map-marker margin-r-5"></i> Khu vực dữ liệu quản lý: </strong>
                <?php $__currentLoopData = $admin_auth->area_extends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="label label-success"><?php echo e($i->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo app('translator')->get('Cập nhật mật khẩu'); ?></h3>
          </div>

          <form role="form" action="<?php echo e(route('admin.account.change.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">
                    <?php echo app('translator')->get('Password Old'); ?>:
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-9 col-xs-12">
                    <input type="password" id="password_old" class="form-control" name="password_old" required
                      value="" autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">
                    <?php echo app('translator')->get('New Password'); ?>:
                    <span class="text-danger">*</span>
                  </label>
                  <div class="col-sm-9 col-xs-12">
                    <input type="password" id="password" class="form-control" name="password" required value=""
                      autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">
                    <?php echo app('translator')->get('Confirm New Password'); ?>:
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
                <?php echo app('translator')->get('Save'); ?>
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>






  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/admins/account.blade.php ENDPATH**/ ?>