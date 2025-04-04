

<?php $__env->startSection('title'); ?>
  <?php echo e($module_name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo e($module_name); ?>

      <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
          class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
    </h1>
  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <!-- Main content -->
  <section class="content">
    
    <div class="box box-default">

      <div class="box-header with-border">
        <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
        <div class="box-body">
          <div class="row">

            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('keyword_note'); ?>"
                  value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo app('translator')->get('Status'); ?></label>
                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                  <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                  <?php $__currentLoopData = $postStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"
                      <?php echo e(isset($params['status']) && $key == $params['status'] ? 'selected' : ''); ?>>
                      <?php echo e(__($value)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo app('translator')->get('Filter'); ?></label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                  <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <?php echo app('translator')->get('Reset'); ?>
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    

    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>

      </div>
      <div class="box-body table-responsive">
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
        <?php if(count($rows) == 0): ?>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo app('translator')->get('not_found'); ?>
          </div>
        <?php else: ?>
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th><?php echo app('translator')->get('Title'); ?></th>
                <th><?php echo app('translator')->get('Code'); ?></th>
                <th><?php echo app('translator')->get('Updated at'); ?></th>
                <th><?php echo app('translator')->get('Status'); ?></th>
                <th><?php echo app('translator')->get('Action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                  onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                  <tr class="valign-middle">
                    <td>
                      <strong style="font-size: 14px"><?php echo e($row->name); ?></strong>
                    </td>
                    <td>
                      <?php echo e($row->code ?? ''); ?>

                    </td>
                    <td>
                      <?php echo e($row->updated_at); ?>

                    </td>
                    <td>
                      <?php echo app('translator')->get($row->status); ?>
                    </td>
                    <td>
                      <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo app('translator')->get('Update'); ?>"
                        data-original-title="<?php echo app('translator')->get('Update'); ?>"
                        href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                      <?php echo csrf_field(); ?>
                      <?php echo method_field('DELETE'); ?>
                      <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                        title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </form>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

      <div class="box-footer clearfix">
        <div class="row">
          <div class="col-sm-5">
            Tìm thấy <?php echo e($rows->total()); ?> kết quả
          </div>
          <div class="col-sm-7">
            <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

          </div>
        </div>
      </div>

    </div>
  </section>



  </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
  <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/areas/index.blade.php ENDPATH**/ ?>