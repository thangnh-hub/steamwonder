

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
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

        <form role="form" action="<?php echo e(route('leave.request.store')); ?>" method="POST"
            onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase"><?php echo app('translator')->get('Tạo mới đề xuất xin nghỉ'); ?></h3>
                        </div>
                        <?php echo csrf_field(); ?>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người đề xuất'); ?></label>
                                        <input type="text" class="form-control" readonly value="<?php echo e($admin_auth->name); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người quản lý trực tiếp'); ?></label>
                                        <input type="text" class="form-control" readonly
                                            value="<?php echo e($admin_auth->direct_manager->name ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người duyệt'); ?><small class="text-red">*</small></label>
                                        <select required name="approver_id" class="form-control" readonly
                                            style="width: 100%;">
                                            <option value="<?php echo e($approver_user->id); ?>" selected><?php echo e($approver_user->name); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Loại'); ?><small class="text-red">*</small></label>
                                        <select required name="is_type" class="form-control select2" style="width: 100%;">
                                            <option value="">Chọn</option>
                                            <option value="paid">Có lương</option>
                                            <option value="unpaid">Không lương </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Từ ngày'); ?> <small class="text-red">*</small></label>
                                        <input required type="date" class="start_date form-control" name="start_date"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Đến ngày'); ?> <small class="text-red">*</small></label>
                                        <input required type="date" class="end_date form-control" name="end_date"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Tổng số ngày nghỉ'); ?> <small class="text-red">*</small></label>
                                        <input required type="number" step="0.5" min="0" class="form-control"
                                            name="total_days" value="<?php echo e(old('total_days') ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Lý do xin nghỉ'); ?><small class="text-red">*</small></label>
                                        <textarea name="reason" required class="form-control" rows="3"><?php echo e(old('reason') ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ghi chú'); ?></label>
                                        <textarea name="note" class="form-control" rows="3"><?php echo e(old('note') ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ngày dạy bù'); ?><small>(Nếu có)</small></label>
                                        <div class="row box_day">
                                            <div class="col-md-3 d-flex-wap items_day mb-10">
                                                <input type="date" class="form-control mr-10"
                                                    name="json_params[teaching_day][]" style="width: calc(100% - 50px)">
                                                <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>"
                                                    onclick="delete_day(this)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning mt-15 add_day">Thêm ngày</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route('leave.request.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Lưu thông tin'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.start_date').on('change', function() {
                var start_date = $(this).val();
                $('.end_date').attr('min', start_date);
            })
            $('.end_date').on('change', function() {
                var end_date = $(this).val();
                $('.start_date').attr('max', end_date);
            })

            $('.add_day').click(function() {
                var _html = `
                <div class="col-md-3 d-flex-wap items_day mb-15">
                    <input type="date" class="form-control mr-10" name="json_params[teaching_day][]"
                        style="width: calc(100% - 50px)">
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                        title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>" onclick="delete_day(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                `;
                $('.box_day').append(_html);
            })

        });

        function delete_day(t) {
            $(t).parents('.items_day').remove();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/leaves/request_create.blade.php ENDPATH**/ ?>