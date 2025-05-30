

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
    .table-wrapper {
            max-height: 560px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
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
            <form action="<?php echo e(route('view_calculate_receipt_first_year')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name)); ?>

                                            (Mã: <?php echo e($value->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lớp'); ?></label>
                                <select name="current_class_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['current_class_id']) && $value->id == $params['current_class_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="<?php echo e(route('view_calculate_receipt_first_year')); ?>">
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
            <div class="box-body ">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('errorMessage'); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('successMessage'); ?>

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
                        <?php echo app('translator')->get('Vui lòng tìm kiếm theo từ khóa, khu vực hoặc lớp để xem danh sách học viên'); ?>
                    </div>
                <?php else: ?>
                <form action="<?php echo e(route('calculate_receipt_first_year')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày bắt đầu chu kỳ thu'); ?></label>
                                <?php
                                    $defaultDate = date('Y') . '-06-01';
                                ?>
                                <input class="form-control" type="date" name="enrolled_at" value="<?php echo e(old('enrolled_at', $defaultDate)); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button style="margin-top: 29px" type="submit" class="btn btn-primary">Tính toán đầu năm</button>
                        </div>
                    </div>
                    
                    <div class="table-wrapper table-responsive">
                        <table class="table table-hover table-bordered ">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('STT'); ?></th>
                                    <th><?php echo app('translator')->get('Avatar'); ?></th>
                                    <th><?php echo app('translator')->get('Student code'); ?></th>
                                    <th><?php echo app('translator')->get('Full name'); ?></th>
                                    <th><?php echo app('translator')->get('Tên thường gọi'); ?></th>
                                    <th><?php echo app('translator')->get('Gender'); ?></th>
                                    <th><?php echo app('translator')->get('Area'); ?></th>
                                    <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                    <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                    <th><?php echo app('translator')->get('Lớp đang học'); ?></th>
                                    <th><?php echo app('translator')->get('Ngày nhập học chính thức'); ?></th>
                                    <th>
                                        <label class="form-check-label" for="check_all"><?php echo app('translator')->get('Chọn'); ?></label>
                                        <input type="checkbox" class="form-check-input" id="check_all"  value="">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="valign-middle">
                                            <td><?php echo e($loop->index + 1); ?></td>

                                            <td>
                                                <?php if(!empty($row->avatar)): ?>
                                                    <a href="<?php echo e(asset($row->avatar)); ?>" target="_blank" class="image-popup">
                                                        <img src="<?php echo e(asset($row->avatar)); ?>" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">No image</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <a class="" href="<?php echo e(route('students.show', $row->id)); ?>"
                                                    data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết học sinh'); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Chi tiết học sinh'); ?>"
                                                    onclick="return openCenteredPopup(this.href)">
                                                    <i class="fa fa-eye"></i> <?php echo e($row->student_code); ?>

                                                </a>
                                            </td>
                                            <td>
                                                    <?php echo e($row->first_name ?? ''); ?> <?php echo e($row->last_name ?? ''); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->nickname ?? ''); ?> 
                                            </td>
                                            <td>
                                                <?php echo app('translator')->get($row->sex); ?>
                                            </td>
                                            <td>
                                                <?php echo e($row->area->code ?? ''); ?>

                                            </td>

                                            <td>
                                                <?php echo e($row->address ?? ''); ?>

                                            </td>

                                            <td>
                                                <?php echo e(__($row->status ?? '')); ?>

                                            <td>
                                                <?php echo e($row->currentClass->name ?? ''); ?>

                                            </td>

                                            <td>
                                                <?php echo e(isset($row->enrolled_at) &&  $row->enrolled_at !="" ?date("d-m-Y", strtotime($row->enrolled_at)): ''); ?>

                                            </td>
                                        
                                            <td>
                                                <?php if($row->is_calculate_year == 1): ?>
                                                    <span class="badge badge-success">Đã tồn tại biểu phí hàng năm trong năm nay</span>
                                                <?php else: ?>
                                                    <input type="checkbox" class="form-check-input" name="student[]" id="check_<?php echo e($row->id); ?>"  value="<?php echo e($row->id); ?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </form>
                            </tbody>
                        </table>
                    </div>
<br>
                    <button type="submit" class="btn btn-primary">Tính toán đầu năm</button>

                </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            $('#check_all').on('change', function () {
                $('.form-check-input:not(#check_all)').prop('checked', $(this).is(':checked'));
            });

            $('.form-check-input:not(#check_all)').on('change', function () {
                if (!$(this).is(':checked')) {
                    $('#check_all').prop('checked', false);
                } else {
                    const allChecked = $('.form-check-input:not(#check_all)').length === $('.form-check-input:not(#check_all):checked').length;
                    $('#check_all').prop('checked', allChecked);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/students/calculate_receipt_year.blade.php ENDPATH**/ ?>