<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                <h5 class="fw-bold">Thông tin chu kỳ</h5>
            </a>
        </li>
        <li class="">
            <a href="#tab_2" data-toggle="tab">
                <h5 class="fw-bold">Dịch vụ kèm theo</h5>
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
                                <label class="control-label"><strong><?php echo app('translator')->get('Mã giảm trừ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->code ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tên giảm trừ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>


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
                                <label class="control-label"><strong><?php echo app('translator')->get('Kiểu điều kiện'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e(__($detail->condition_type) ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Từ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->json_params->condition->start ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Đến'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->json_params->condition->end ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Cho phép giảm lũy kế'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch">
                                        <input class="about-banner" type="checkbox" value="1" disabled
                                            <?php echo e(isset($detail->is_cumulative) && $detail->is_cumulative == '1' ? 'checked' : ''); ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
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
                                <p><?php echo e($detail->admin_created->name ?? ''); ?></p>
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
                                <p><?php echo e($detail->admin_updated->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane " id="tab_2">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box" style="border-top: 3px solid #d2d6de;">
                        <div class="box-header">
                        </div>
                        <div class="box-body no-padding">
                            <table class="table table-hover sticky">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Loại giảm trừ'); ?></th>
                                        <th><?php echo app('translator')->get('Giảm trừ'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="box_policies">
                                    <?php if(isset($data_service)): ?>
                                        <?php $__currentLoopData = $data_service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="item_policies">
                                                <td><?php echo e($item->detail->name ?? ''); ?></td>
                                                <td><?php echo e(__($item->detail->service_type) ?? ''); ?></td>
                                                <td><?php echo e(__($item->type) ?? ''); ?></td>
                                                <td><?php echo e($item->value ?? 0); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/deductions/show.blade.php ENDPATH**/ ?>