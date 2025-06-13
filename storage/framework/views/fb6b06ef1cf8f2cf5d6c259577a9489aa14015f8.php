<input type="hidden" name="id" value="<?php echo e($detail->id ?? ''); ?>">
<input type="hidden" name="student_id" value="<?php echo e($student_id ?? ''); ?>">
<input type="hidden" name="class_id" value="<?php echo e($class_id ?? ''); ?>">
<input type="hidden" name="date" value="<?php echo e($date ?? ''); ?>">
<div class="row">
    <div class="col-md-6">
        <div class="form-group attendance_arrival">
            <label class="text-center w-100 fw-bold mb-15"><?php echo app('translator')->get('Điểm danh đến'); ?></label>
            <div class="d-flex-wap justify-content-around mb-10">
                <div class="d-flex box_checked">
                    <input id="student_checkin" name="status" required
                        <?php echo e(isset($detail->status) && $detail->status == 'checkin' ? 'checked disabled' : ''); ?>

                        class="radiobox mr-10 checkin" data-id="" type="radio" value="checkin">
                    <label class="box_radio" for="student_checkin">
                        <?php echo app('translator')->get('Đi học'); ?>
                    </label>
                </div>
                <div class="d-flex box_checked">
                    <input id="student_absent_unexcused" name="status"
                        <?php echo e(isset($detail->status) && $detail->status == 'absent_unexcused' ? 'checked' : ''); ?>

                        class="radiobox mr-10 absent_unexcused" data-id="" type="radio" value="absent_unexcused">
                    <label class="box_radio" for="student_absent_unexcused">
                        <?php echo app('translator')->get('Nghỉ không phép'); ?>
                    </label>
                </div>
                <div class="d-flex box_checked">
                    <input id="student_absent_excused" name="status" readonly
                        <?php echo e(isset($detail->status) && $detail->status == 'absent_excused' ? 'checked' : ''); ?>

                        class="radiobox mr-10 absent_excused" data-id="" type="radio" value="absent_excused">
                    <label class="box_radio" for="student_absent_excused">
                        <?php echo app('translator')->get('Nghỉ có phép'); ?>
                    </label>
                </div>
            </div>
            <div class="d-flex-wap align-items-center">
                <div class="box_image text-center">
                    <div class="box_capture" data-type="arrival"><i class="fa fa-camera" aria-hidden="true"></i>
                    </div>
                    <img class="photo" id="photo_arrival"
                        src="<?php echo e(isset($detail->json_params->img) && $detail->json_params->img != '' ? asset($detail->json_params->img) : url('themes/admin/img/no_image.jpg')); ?>">
                    <input type="hidden" class="img check_disable" id="image_arrival" name="image_arrival" disabled
                        value="<?php echo e($detail->json_params->img ?? ''); ?>">
                </div>
                <div class="box_content information">
                    <div class="form-group">
                        <select class="form-control select2 w-100  check_disable" disabled name="checkin_parent_id">
                            <option selected="" value="">-Người đưa-</option>
                            <?php if(isset($detail->student->studentParents) && count($detail->student->studentParents) > 0): ?>
                                <?php $__currentLoopData = $detail->student->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->parent_id); ?>"
                                        <?php echo e(isset($detail->checkin_parent_id) && $detail->checkin_parent_id == $item->parent_id ? 'selected' : ''); ?>>
                                        <?php echo e($item->relationship->title ?? ''); ?>:
                                        <?php echo e($item->parent->first_name ?? ''); ?>

                                        <?php echo e($item->parent->last_name ?? ''); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control select2 w-100 check_disable" disabled name="checkin_teacher_id">
                            <option value="">-Giáo viên đón-</option>
                            <?php $__currentLoopData = $list_teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"
                                    <?php echo e(isset($detail->checkin_teacher_id) && $detail->checkin_teacher_id == $item->id ? 'selected' : ''); ?>>
                                    <?php echo e($item->name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input name="json_params[note]" type="text" class="form-control check_disable" disabled
                            id="note" placeholder="Nhập ghi chú"
                            value="<?php echo e(isset($detail->json_params->note) ? $detail->json_params->note : ''); ?>">
                    </div>
                    <div class="form-group">
                        <input type="datetime-local" class="form-control check_disable" name="checkin_at" disabled
                            value="<?php echo e(isset($detail->checkin_at) && $detail->checkin_at != '' ? Carbon\Carbon::parse($detail->checkin_at)->format('Y-m-d H:i') : ''); ?>"
                            required>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group attendance_return">
            <label class="text-center w-100 fw-bold mb-15"><?php echo app('translator')->get('Điểm danh về'); ?></label>
            <div class="div_h"></div>
            <div class="d-flex-wap align-items-center">
                <div class="box_image text-center">
                    <div class="box_capture" data-type="return"><i class="fa fa-camera" aria-hidden="true"></i>
                    </div>
                    <img class="photo" id="photo_return"
                        src="<?php echo e(isset($detail->json_params->img_return) && $detail->json_params->img_return != '' ? asset($detail->json_params->img_return) : url('themes/admin/img/no_image.jpg')); ?>">
                    <input type="hidden" class="img" id="image_return" name="image_return"
                        value="<?php echo e($detail->json_params->img_return ?? ''); ?>">
                </div>
                <div class="box_content information">
                    <div class="form-group">
                        <select class="form-control select2 w-100  return_parent" name="checkout_parent_id">
                            <option selected="" value="">-Người đón-</option>
                            <?php if(isset($detail->student->studentParents) && count($detail->student->studentParents) > 0): ?>
                                <?php $__currentLoopData = $detail->student->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->parent_id); ?>"
                                        <?php echo e(isset($detail->checkout_parent_id) && $detail->checkout_parent_id == $item->parent_id ? 'selected' : ''); ?>>
                                        <?php echo e($item->relationship->title ?? ''); ?>:
                                        <?php echo e($item->parent->first_name ?? ''); ?>

                                        <?php echo e($item->parent->last_name ?? ''); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control select2 w-100 return_teacher" name="checkout_teacher_id">
                            <option value="">-Giáo viên đưa-</option>
                            <?php $__currentLoopData = $list_teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"
                                    <?php echo e(isset($detail->checkout_teacher_id) && $detail->checkout_teacher_id == $item->id ? 'selected' : ''); ?>>
                                    <?php echo e($item->name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input name="json_params[note_return]" type="text" class="form-control note_return"
                            placeholder="Nhập ghi chú" value="<?php echo e(isset($detail->json_params->note_return) ? $detail->json_params->note_return : ''); ?>">
                    </div>
                    <div class="form-group">
                        <input type="datetime-local" class="form-control" name="checkout_at"
                            value="<?php echo e(isset($detail->checkout_at) && $detail->checkout_at != '' ? Carbon\Carbon::parse($detail->checkout_at)->format('Y-m-d H:i') : ''); ?>"
                            >
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/attendance/show_summary_by_month.blade.php ENDPATH**/ ?>