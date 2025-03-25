

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .box-body {
            width: 80%;
            margin: 0px auto;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
        <div class="box-alert">
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
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <?php echo app('translator')->get($module_name); ?>
                        </h3>
                        <a class="btn btn-primary pull-right hide-print" href="<?php echo e(route('leave.request.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('Danh sách đơn'); ?>
                        </a>
                        <?php if($detail->status == $status['pending_confirmation'] && $admin_auth->id == $detail->parent_id): ?>
                            <button data-id="<?php echo e($detail->id); ?>" type="button" data-type="parent"
                                class="btn btn-warning pull-right hide-print mr-10 approve_request"><?php echo app('translator')->get('Xác nhận phiếu xin nghỉ'); ?></button>
                        <?php endif; ?>
                        <?php if($detail->status == $status['pending_approval'] && $admin_auth->id == $detail->approver_id): ?>
                            <button data-id="<?php echo e($detail->id); ?>" type="button" data-type="approve"
                                class="btn btn-success pull-right hide-print mr-10 approve_request"><?php echo app('translator')->get('Duyệt phiếu xin nghỉ'); ?></button>
                        <?php endif; ?>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom: 10px"><?php echo app('translator')->get('Thông tin người tạo'); ?></h4>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Họ tên'); ?>:
                                    <?php echo e($balancy->user->name ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Năm'); ?>:
                                    <?php echo e($balancy->year ?? 0); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Tổng phép năm'); ?>:
                                    <?php echo e($balancy->total_leaves ?? 0); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Số phép chuyển giao'); ?>:
                                    <?php echo e($balancy->transfer_old ?? 0); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Số phép khả dụng'); ?>:
                                    <?php echo e($balancy->available ?? 0); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Số phép đã dùng'); ?>:
                                    <?php echo e($balancy->used_leaves ?? 0); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <?php echo app('translator')->get('Q.Lý trực tiếp'); ?>:
                                    <?php echo e($detail->parent->name ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Người duyệt'); ?>: <?php echo e($detail->approver->name ?? ''); ?></p>
                            </div>

                            <div class="col-md-12 mt-15">
                                <h4 class="box-title" style="padding-bottom: 10px"><?php echo app('translator')->get('Nội dung xin nghỉ'); ?></h4>
                            </div>
                            <div class="col-md-6">

                                <p>
                                    <?php echo app('translator')->get('Nghỉ từ ngày'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->start_date)->format('d/m/Y') ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Đến ngày'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->end_date)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Số ngày nghỉ'); ?>: <?php echo e($detail->total_days ?? ''); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Loại'); ?>: <?php echo e($detail->is_type == 'paid' ? 'Có phép' : 'Không phép'); ?></p>
                            </div>

                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Trạng thái'); ?>: <?php echo e(__($detail->status)); ?>

                            </div>

                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Ngày dạy bù (Nếu có)'); ?>:
                                    <?php echo e(isset($detail->json_params->teaching_day) && count($detail->json_params->teaching_day) > 0
                                        ? implode(
                                            ' - ',
                                            array_map(function ($date) {
                                                return \Carbon\Carbon::parse($date)->format('d-m-Y');
                                            }, $detail->json_params->teaching_day),
                                        )
                                        : ''); ?>

                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Ngày tạo'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Lý do'); ?>: <?php echo e($detail->reason ?? ''); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo app('translator')->get('Ghi chú'); ?>: <?php echo e($detail->note ?? ''); ?></p>
                            </div>

                        </div>
                    </div>
                    <div class="box-footer hide-print">

                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.approve_request').click(function() {
                if (confirm('Xác nhận duyệt đơn xin nghỉ ?')) {
                    var _id = $(this).data('id');
                    var _type = $(this).data('type');
                    let url = "<?php echo e(route('leave.request.approve')); ?>";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "_token": "<?php echo e(csrf_token()); ?>",
                            id: _id,
                            type: _type,
                        },
                        success: function(response) {
                            if (response.data != null) {
                                if (response.data == 'success') {
                                    location.reload();
                                } else {
                                    var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                        </div>`;
                                    $('.box-alert').prepend(_html);
                                    $('html, body').animate({
                                        scrollTop: $(".alert-warning").offset().top
                                    }, 1000);
                                    setTimeout(function() {
                                        $('.alert-warning').remove();
                                    }, 3000);
                                };
                            } else {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $('.alert-warning').remove();
                                }, 3000);
                            }
                        },
                        error: function(response) {
                            let errors = response.responseJSON.message;
                            alert(errors);
                        }
                    });
                }
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/leaves/request_show.blade.php ENDPATH**/ ?>