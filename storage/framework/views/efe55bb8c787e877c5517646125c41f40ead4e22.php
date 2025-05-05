<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                <h5 class="fw-bold">Thông tin hóa đơn</h5>
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
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Mã hóa đơn'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->receipt_code ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tên hóa đơn'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->receipt_name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Khu vực'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->area->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Hoc sinh'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->student->student_code ?? ('' . ' - ' . $detail->student->last_name ?? ('' . ' ' . $detail->student->first_name ?? ''))); ?>(<?php echo e($detail->student->nickname); ?>)
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Chu kỳ thanh toán'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->payment_cycle->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Số tiên cần thu'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_amount, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tổng giảm trừ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_discount, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tổng các truy thu'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_adjustment, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tổng tiền thực tế'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_final, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Đã thu'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_paid, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Số tiền còn phải thu (+) hoặc thừa (-)'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e(number_format($detail->total_due, 0, ',', '.') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Trạng thái'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->status); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong><?php echo app('translator')->get('Ghi chú'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p><?php echo e($detail->note); ?></p>
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
                                        <th><?php echo app('translator')->get('Tháng áp dụng'); ?></th>
                                        <th><?php echo app('translator')->get('Số lượng dự kiến'); ?></th>
                                        <th><?php echo app('translator')->get('Số lượng thực tế'); ?></th>
                                        <th><?php echo app('translator')->get('Đơn giá dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Số tiền dịch vụ trong tháng'); ?></th>
                                        <th><?php echo app('translator')->get('Giảm trừ'); ?></th>
                                        <th><?php echo app('translator')->get('Truy thu (+) / Hoàn trả (-)'); ?></th>
                                        <th><?php echo app('translator')->get('Tổng số tiền cuối cùng'); ?></th>
                                        <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="box_policies">
                                    <?php if(isset($detail->receipt_detail)): ?>
                                        <?php $__currentLoopData = $detail->receipt_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="item_policies">
                                                <td><?php echo e($item->service->name ?? ''); ?></td>
                                                <td><?php echo e(__($item->service->service_type) ?? ''); ?></td>
                                                <td><?php echo e(date('m-Y', strtotime($item->month))); ?></td>
                                                <td><?php echo e($item->by_number ?? 0); ?></td>
                                                <td><?php echo e($item->spent_number ?? 0); ?></td>
                                                <td><?php echo e(number_format($item->unit_price,0,',','.')); ?></td>
                                                <td><?php echo e(number_format($item->amount,0,',','.')); ?></td>
                                                <td><?php echo e(number_format($item->discount_amount,0,',','.')); ?></td>
                                                <td><?php echo e(number_format($item->adjustment_amount,0,',','.')); ?></td>
                                                <td><?php echo e(number_format($item->final_amount,0,',','.')); ?></td>
                                                <td><?php echo e(__($item->status)); ?></td>
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
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/view.blade.php ENDPATH**/ ?>