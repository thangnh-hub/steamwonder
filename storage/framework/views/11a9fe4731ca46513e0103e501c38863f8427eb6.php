

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
  
<?php $__env->stopSection(); ?>

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

        <div class="box box-default">
            <div class="box-header">
                <h3 class="text-title"><?php echo app('translator')->get($module_name); ?></h3>
            </div>
            <div class="box-body table-responsive">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('Họ và tên'); ?>:</strong>
                                <?php echo e($detail->last_name ?? ''); ?> <?php echo e($detail->first_name ?? ''); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('Số điện thoại'); ?>:</strong>
                                <?php echo e($detail->phone ?? ''); ?> 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('Email'); ?>:</strong>
                                <?php echo e($detail->email ?? ''); ?> 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('Địa chỉ'); ?>:</strong>
                                <?php echo e($detail->address ?? ''); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('Khu vực'); ?>:</strong>
                                <?php echo e($detail->area->name ?? ''); ?> 
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="job"><strong><?php echo app('translator')->get('CBTS'); ?>:</strong>
                                <?php echo e($detail->admission->name ?? ''); ?> 
                            </p>
                        </div>
                    </div>
                    <hr style="border-top: dashed 2px #a94442; ">
                </div>
                

                <h3 class="box-title"><?php echo app('translator')->get('Danh sách lịch sử tư vấn'); ?></h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:20%"><?php echo app('translator')->get('Ngày tư vấn'); ?></th>
                            <th style="width:30%"><?php echo app('translator')->get('Nội dung'); ?></th>
                            <th style="width:10%"><?php echo app('translator')->get('Trạng thái'); ?></th>
                            <th style="width:10%"><?php echo app('translator')->get('Kết quả'); ?></th>
                            <th style="width:20%"><?php echo app('translator')->get('Ghi chú'); ?></th>
                            <th style="width:20%"><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="">
                        <?php if(isset($dataCrmLogs)): ?>
                            <?php $__currentLoopData = $dataCrmLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataCrmLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="valign-middle">
                                <td>
                                    <?php echo e($dataCrmLog->consulted_at ? \Carbon\Carbon::parse($dataCrmLog->consulted_at)->format('d/m/Y') : ''); ?>

                                </td>
                                <td >
                                    <p style="white-space: pre-line"><?php echo e($dataCrmLog->content ?? ''); ?></p>
                                </td>

                                <td>
                                    <?php echo app('translator')->get($dataCrmLog->status ?? ''); ?>
                                </td>

                                <td>
                                    <?php echo app('translator')->get($dataCrmLog->result ?? ''); ?>
                                </td>
                            
                                <td>
                                    <?php if($dataCrmLog->json_params && isset($dataCrmLog->json_params->note)): ?>
                                        <?php echo e($dataCrmLog->json_params->note); ?>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="<?php echo e(route(Request::segment(2) . '.destroy', $detail->id)); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <h3 class="box-title"><?php echo app('translator')->get('Thêm mới lịch sử tư vấn'); ?></h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="width:20%"><?php echo app('translator')->get('Ngày tư vấn'); ?></th>
                            <th style="width:30%"><?php echo app('translator')->get('Nội dung'); ?></th>
                            <th style="width:10%"><?php echo app('translator')->get('Trạng thái'); ?></th>
                            <th style="width:10%"><?php echo app('translator')->get('Kết quả'); ?></th>
                            <th style="width:20%"><?php echo app('translator')->get('Ghi chú'); ?></th>
                            <th style="width:20%"><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="<?php echo e(route('data_crms_log_store')); ?>" method="post" >
                            <input type="hidden" name="data_crm_id" value="<?php echo e($detail->id); ?>">
                            <?php echo csrf_field(); ?>
                            <tr class="valign-middle">
                                <td>
                                    <input required type="date" class="form-control" name="consulted_at" value="<?php echo e(\Carbon\Carbon::now()->format('Y-m-d')); ?>" placeholder="<?php echo app('translator')->get('Ngày tư vấn'); ?>">
                                </td>
                                <td>
                                    <textarea required class="form-control" name="content" placeholder="<?php echo app('translator')->get('Nội dung'); ?>"></textarea>
                                </td>
                                <td>
                                    <select class="form-control select2" name="status">
                                        <option value=""><?php echo app('translator')->get('Trạng thái'); ?></option>
                                        <?php $__currentLoopData = $status_crmlog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e(__($item)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                </td>
                                <td>
                                    <select class="form-control select2" name="result">
                                        <option value=""><?php echo app('translator')->get('Kết quả'); ?></option>
                                        <?php $__currentLoopData = $result_crmlog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e(__($item)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="json_params[note]" class="form-control" placeholder="<?php echo app('translator')->get('Ghi chú'); ?>">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-warning btn_submit"><?php echo app('translator')->get('Thêm lịch sử'); ?></button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>


            </div>
            <div class="box-footer clearfix">
                <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>

    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/data_crms/show.blade.php ENDPATH**/ ?>