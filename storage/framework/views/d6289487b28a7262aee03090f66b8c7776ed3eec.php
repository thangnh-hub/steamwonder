

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khu vực'); ?></label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?></label>
                                <select name="status" class="form-control select2"style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($item)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm  mr-10"
                                        href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
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
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Avatar'); ?></th>
                                <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                <th><?php echo app('translator')->get('Học sinh'); ?></th>
                                <th><?php echo app('translator')->get('Số CMND/CCCD'); ?></th>
                                <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                <th><?php echo app('translator')->get('Email'); ?></th>
                                <th><?php echo app('translator')->get('Tên đăng nhập'); ?></th>
                                <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Thao tác'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->iteration + ($rows->currentPage() - 1) * $rows->perPage()); ?>

                                    </td>
                                    <td>
                                        <?php if(!empty($row->avatar)): ?>
                                            <img src="<?php echo e(asset($row->avatar)); ?>" alt="Avatar" width="100"
                                                height="100" style="object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($row->first_name ?? ''); ?> <?php echo e($row->last_name ?? ''); ?></td>
                                    <td>
                                        <?php if(isset($row->parentStudents)): ?>
                                            <?php $__currentLoopData = $row->parentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('students.show', optional($student->student)->id)); ?>"
                                                    data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết học sinh'); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Chi tiết học sinh'); ?>"
                                                    onclick="return openCenteredPopup(this.href)">
                                                    <?php echo e(optional($student->student)->student_code ?? ''); ?>

                                                    <?php echo e(optional($student->student)->first_name ?? ''); ?>

                                                    <?php echo e(optional($student->student)->last_name ?? ''); ?>

                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($row->identity_card ?? ''); ?></td>
                                    <td><?php echo e($row->phone ?? ''); ?></td>
                                    <td><?php echo e($row->email ?? ''); ?></td>
                                    <td>
                                        <?php if(isset($row->user)): ?>
                                            <a href="<?php echo e(route('users.edit', optional($row->user)->id)); ?>" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Chi tiết tài khoản'); ?>" onclick="return openCenteredPopup(this.href)">
                                                <?php echo e(optional($row->user)->username ?? ''); ?>

                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($row->address ?? ''); ?></td>
                                    <td><?php echo e($row->area->name ?? ''); ?></td>
                                    <td><?php echo app('translator')->get($row->status); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo app('translator')->get('Update'); ?>"
                                            href="<?php echo e(route('parents.edit', $row->id)); ?>">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <form action="<?php echo e(route('parents.destroy', $row->id)); ?>" method="POST"
                                            style="display:inline-block" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Delete'); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        <a class="btn btn-sm btn-primary" data-toggle="tooltip"
                                            title="<?php echo app('translator')->get('Chi tiết'); ?>" href="<?php echo e(route('parents.show', $row->id)); ?>"
                                            onclick="return openCenteredPopup(this.href)">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/parents/index.blade.php ENDPATH**/ ?>