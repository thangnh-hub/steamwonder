<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route($routeDefault . '.create')); ?>">
                <i class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?>
            </a>
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
            <form action="<?php echo e(route($routeDefault . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?></label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('keyword_note'); ?>"
                                    value="<?php echo e($params['keyword'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khu vực'); ?></label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
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
                                <label><?php echo app('translator')->get('Nhóm dịch vụ'); ?></label>
                                <select name="service_category_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_service_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['service_category_id']) && $params['service_category_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tính chất dịch vụ'); ?></label>
                                <select name="is_attendance" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_is_attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['is_attendance']) && $params['is_attendance'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e($item); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mặc định'); ?></label>
                                <select name="is_default" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_is_default; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['is_default']) && $params['is_default'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e($item); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại dịch vụ'); ?></label>
                                <select name="service_type" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_service_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['service_type']) && $params['service_type'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($item)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?></label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($item); ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div style="display:flex; gap:5px;">
                                    <button type="submit" class="btn btn-primary btn-sm"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route($routeDefault . '.index')); ?>">
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
                <?php if($rows->count() === 0): ?>
                    <div class="alert alert-warning"><?php echo app('translator')->get('not_found'); ?></div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                <th><?php echo app('translator')->get('Hệ đào tạo'); ?></th>
                                <th><?php echo app('translator')->get('Độ tuổi'); ?></th>
                                <th><?php echo app('translator')->get('Tính chất dịch vụ'); ?></th>
                                <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('Sắp xếp'); ?></th>
                                <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                <th><?php echo app('translator')->get('Kiểu tính phí'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration + ($rows->currentPage() - 1) * $rows->perPage()); ?></td>
                                    <td><?php echo e($row->area->name ?? ''); ?></td>
                                    <td><?php echo e($row->name ?? ''); ?></td>
                                    <td><?php echo e($row->service_category->name ?? ''); ?></td>
                                    <td><?php echo e($row->education_program->name ?? ''); ?></td>
                                    <td><?php echo e($row->education_age->name ?? ''); ?></td>
                                    <td><?php echo e($row->is_attendance == 0 ? 'Không theo điểm danh' : 'Tính theo điểm danh'); ?></td>
                                    <td><?php echo e(__($row->service_type ?? '')); ?></td>
                                    <td><?php echo app('translator')->get($row->status); ?></td>
                                    <td>
                                        <?php echo e($row->iorder ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php if(isset($row->serviceDetail) && $row->serviceDetail->count() > 0): ?>
                                            <?php $__currentLoopData = $row->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <ul>
                                                    <li>Số tiền:
                                                        <?php echo e(isset($detail->price) && is_numeric($detail->price) ? number_format($detail->price, 0, ',', '.') . ' đ' : ''); ?>

                                                    </li>
                                                    <li>Số lượng: <?php echo e($detail->quantity ?? ''); ?></li>
                                                    <li>Từ:
                                                        <?php echo e(isset($detail->start_at) ? \Illuminate\Support\Carbon::parse($detail->start_at)->format('d-m-Y') : ''); ?>

                                                    </li>
                                                    <li>Đến:
                                                        <?php echo e(isset($detail->end_at) ? \Illuminate\Support\Carbon::parse($detail->end_at)->format('d-m-Y') : ''); ?>

                                                    </li>
                                                </ul>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e(__($row->service_fee ?? '')); ?>

                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-warning"
                                            href="<?php echo e(route($routeDefault . '.edit', $row->id)); ?>">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <form action="<?php echo e(route($routeDefault . '.destroy', $row->id)); ?>" method="POST"
                                            style="display:inline;" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

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
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/services/index.blade.php ENDPATH**/ ?>