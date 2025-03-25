

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php
  if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
      $lang = $languageDefault->lang_locale;
  } else {
      $lang = Request::get('lang');
  }
?>
<?php $__env->startPush('style'); ?>
  <style>
    table {
      max-width: unset !important;
      min-width: 0px !important;
    }

    table .btn {
      width: 100%;
    }

    .input-with-suffix {
      position: relative;
    }

    .input-suffix {
      position: absolute;
      right: 30px;
      top: 8px;
    }

    @media (max-width: 768px) {

      .table>tbody>tr>td,
      .table>tbody>tr>th,
      .table>tfoot>tr>td,
      .table>tfoot>tr>th,
      .table>thead>tr>td,
      .table>thead>tr>th {
        padding: 1px;
      }
    }
  </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content-header'); ?>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo app('translator')->get($module_name); ?>
    </h1>

  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <section class="content">
    <div class="box box-default hide-print">
      <form action="" method="GET">
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo app('translator')->get('Class'); ?></label>
                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                  <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                  <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value->id); ?>"
                      <?php echo e(isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : ''); ?>>
                      <?php echo e(__($value->name)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo app('translator')->get('Cán bộ tuyển sinh'); ?></label>
                <select name="admission_id" id="admission_id" class="form-control select2" style="width: 100%;">
                  <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                  <?php $__currentLoopData = $list_admission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value->id); ?>"
                      <?php echo e(isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : ''); ?>>
                      <?php echo e(__($value->name)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo app('translator')->get('Filter'); ?></label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    <?php if(isset($this_class)): ?>
      <?php
        $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
        $data['teacher'] = $teacher;
        $data['this_class'] = $this_class;
      ?>
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">
            <?php echo app('translator')->get('Danh sách học viên'); ?>
            - Lớp <?php echo e($this_class->name); ?>

            - Giáo viên <?php echo e($teacher->name); ?>

          </h3>
          <?php if(count($rows) > 0): ?>
            <?php
              $data['rows'] = $rows;
              $data['admission_id'] = $params['admission_id'] ?? '';
            ?>
            <div class="pull-right hide-print">
              <form action="<?php echo e(route('generate_pdf')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="view" value="admin.pages.staffadmissions.pdf">
                <input type="hidden" name="data" value="<?php echo e(json_encode($data)); ?>">
                <button type="submit" name="download" value="pdf" class="btn btn-sm btn-success"><i
                    class="fa fa-file-pdf-o"></i>
                  <?php echo app('translator')->get('Download bảng điểm'); ?></button>
              </form>
            </div>
          <?php endif; ?>
          <button id="printButton" onclick="window.print()"
            class="btn btn-primary btn-sm mb-2 pull-right mr-10 hide-print"><?php echo app('translator')->get('In thông tin'); ?></button>
        </div>
        <div class="box-body ">
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
            <table class="table table-hover table-bordered" style="min-width:0px !important;">
              <thead>
                <tr>
                  <th rowspan="2">#</th>
                  <th rowspan="2" style="width:50px"><?php echo app('translator')->get('Mã'); ?></th>
                  <th rowspan="2" style="width:150px"><?php echo app('translator')->get('Student'); ?></th>
                  <th rowspan="2" style="width:150px"><?php echo app('translator')->get('Loại hợp đồng'); ?></th>
                  <th rowspan="2" style="width:100px"><?php echo app('translator')->get('Lớp'); ?></th>
                  <th colspan="5" style="text-align: center; width: 250px"><?php echo app('translator')->get('Điểm'); ?> </th>
                  <th rowspan="2"><?php echo app('translator')->get('Nhận xét'); ?></th>
                  <th rowspan="2" style="width: 100px"><?php echo app('translator')->get('Xếp loại'); ?></th>
                </tr>
                <tr>
                  <th style="width: 50px"><?php echo app('translator')->get('Nghe'); ?> </th>
                  <th style="width: 50px"><?php echo app('translator')->get('Nói'); ?> </th>
                  <th style="width: 50px"><?php echo app('translator')->get('Đọc'); ?> </th>
                  <th style="width: 50px"><?php echo app('translator')->get('Viết'); ?> </th>
                  <th style="width: 50px"><?php echo app('translator')->get('TB'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="valign-middle">
                    <td rowspan="<?php echo e(count($row->userClasses) > 0 ? count($row->userClasses) : 1); ?>">
                      <?php echo e($loop->index + 1); ?>

                    </td>
                    <td rowspan="<?php echo e(count($row->userClasses) > 0 ? count($row->userClasses) : 1); ?>">
                      <?php echo e($row->student->admin_code ?? ''); ?>

                    </td>
                    <td rowspan="<?php echo e(count($row->userClasses) > 0 ? count($row->userClasses) : 1); ?>">
                      <a target="_blank"
                        href="<?php echo e(route('students.show', $row->student->id)); ?>"><?php echo e($row->student->name ?? ''); ?>

                      </a>
                    </td>
                    <td rowspan="<?php echo e(count($row->userClasses) > 0 ? count($row->userClasses) : 1); ?>">
                      <?php echo e(isset($row->student->json_params->contract_type) && $row->student->json_params->contract_type != null ? $row->student->json_params->contract_type : __('Chưa cập nhật')); ?>

                    </td>

                    <?php $__empty_1 = true; $__currentLoopData = $row->userClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userClass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <?php echo $loop->index > 0 ? '<tr>' : ''; ?>

                      <td class='<?php echo e($this_class->id == $userClass->class->id ? 'bg-gray' : ''); ?>'>
                        <?php echo e($userClass->class->name ?? ''); ?>

                        (<?php echo e(__($userClass->status)); ?>)
                      </td>
                      <td><?php echo e($userClass->score->score_listen ?? ''); ?></td>
                      <td><?php echo e($userClass->score->score_speak ?? ''); ?></td>
                      <td><?php echo e($userClass->score->score_read ?? ''); ?></td>
                      <td><?php echo e($userClass->score->score_write ?? ''); ?></td>
                      <td><?php echo e($userClass->score->json_params->score_average ?? ''); ?></td>
                      <td><?php echo e($userClass->score->json_params->note ?? ''); ?></td>
                      <td>
                        <?php echo e(isset($userClass->score->status) ? App\Consts::ranked_academic_total[$userClass->score->status] ?? $userClass->score->status : 'Chưa xác định'); ?>

                      </td>
                      <?php echo $loop->index > 0 ? '</tr>' : ''; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <td class="bg-gray">
                        <?php echo e($this_class->name ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->score_listen ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->score_speak ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->score_read ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->score_write ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->json_params->score_average ?? '0'); ?>

                      </td>
                      <td>
                        <?php echo e($row->json_params->note ?? ''); ?>

                      </td>
                      <td>
                        <?php echo e($row->status != '' ? App\Consts::ranked_academic_total[$row->status] ?? $row->status : 'Chưa xác định'); ?>

                      </td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <div class="box-footer clearfix">

        </div>

      </div>

    <?php endif; ?>

  </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/staffadmissions/score_by_staff.blade.php ENDPATH**/ ?>