<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                <h5 class="fw-bold">Thông tin chính</h5>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Khu vực'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->area->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Loại phí dịch vụ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e(__($detail->type)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Áp dựng từ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e(optional(\Carbon\Carbon::parse($detail->time_start))->format('d/m/Y')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Áp dụng đến'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e(optional(\Carbon\Carbon::parse($detail->time_end))->format('d/m/Y')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Thời gian'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <?php if(isset($detail->json_params->time_range) && count((array) $detail->json_params->time_range) > 0): ?>
                                    <ul>
                                        <?php $__currentLoopData = (array) $detail->json_params->time_range; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                Từ:
                                                <?php echo e($val->block_start != '' ? optional(\Carbon\Carbon::parse($val->block_start))->format('H:i') : ''); ?>

                                                 - Đến:<?php echo e($val->block_end != '' ? optional(\Carbon\Carbon::parse($val->block_end))->format('H:i') : ''); ?>

                                                - Phí:<?php echo e(number_format($val->price, 0, ',', '.') ?? ''); ?> đ
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Ngày tạo'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e(date('H:i - d/m/Y', strtotime($detail->created_at))); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Người tạo'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e($detail->adminCreated->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Ngày cập nhật'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e(date('H:i - d/m/Y', strtotime($detail->updated_at))); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Người cập nhật'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e($detail->adminUpdated->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/service_config/show.blade.php ENDPATH**/ ?>