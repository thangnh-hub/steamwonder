

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route('leave.request.create')); ?>"><i class="fa fa-plus"></i>
                <?php echo app('translator')->get('Add'); ?></a>
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
            <form action="<?php echo e(route('leave.request.index')); ?>" method="GET">
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
                                <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                <select name="status" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('leave.request.index')); ?>">
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
                <div class="box_note">
                    <p><b><?php echo app('translator')->get('Ghi chú'); ?></b></p>
                    <ul>
                        <li> Màn hình chỉ hiển thị danh sách các đơn của bạn và của các bộ cấp dưới do bạn quản lý trực tiếp</li>
                        <li> Khi tạo đơn cần báo cho người quản lý trực tiếp của bạn vào xác nhận, sau đó báo cho lãnh đạo để duyệt</li>
                        <li> Người xác nhận mặc định sẽ là người quản lý trực tiếp và người duyệt là Giám đốc</li>
                    </ul>
                </div>
            </div>
            <div class="box-body table-responsive box-alert">
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
                                <th><?php echo app('translator')->get('Người đề xuất'); ?></th>
                                <th><?php echo app('translator')->get('Ngày nghỉ'); ?></th>
                                <th><?php echo app('translator')->get('Từ ngày'); ?></th>
                                <th><?php echo app('translator')->get('Đến ngày'); ?></th>
                                <th><?php echo app('translator')->get('Loại'); ?></th>
                                <th><?php echo app('translator')->get('Lý do'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>

                                <th><?php echo app('translator')->get('Q.Lý trực tiếp'); ?></th>
                                <th><?php echo app('translator')->get('Người duyệt'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Thao tác'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td> <?php echo e($loop->index + 1); ?></td>
                                    <td> <?php echo e($row->admins->name ?? ''); ?></td>
                                    <td> <?php echo e($row->total_days ?? ''); ?></td>
                                    <td> <?php echo e(date('d-m-Y', strtotime($row->start_date)) ?? ''); ?></td>
                                    <td> <?php echo e(date('d-m-Y', strtotime($row->end_date)) ?? ''); ?></td>
                                    <td> <?php echo e($row->is_type == 'paid' ? 'Có phép' : 'Không phép'); ?></td>
                                    <td> <?php echo e($row->reason ?? ''); ?></td>
                                    <td> <?php echo e($row->note ?? ''); ?></td>

                                    <td> <?php echo e($row->parent->name ?? ''); ?></td>
                                    <td> <?php echo e($row->approver->name ?? ''); ?></td>
                                    <td>
                                        <?php echo e(__($row->status)); ?>

                                    </td>

                                    
                                    <td>
                                        <div class="d-flex-wap">
                                            <a class="btn btn-sm btn-primary mr-10" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Xem chi tiết'); ?>" data-original-title="<?php echo app('translator')->get('Xem chi tiết'); ?>"
                                                href="<?php echo e(route('leave.request.show', $row->id)); ?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <?php if($row->status == 'pending_confirmation' && $row->user_id == $admin_auth->id): ?>
                                                <a class="btn btn-sm btn-warning mr-10" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Update'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>"
                                                    href="<?php echo e(route('leave.request.edit', $row->id)); ?>">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <form action="<?php echo e(route('leave.request.destroy', $row->id)); ?>"
                                                    method="POST" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button class="btn btn-sm btn-danger" type="submit"
                                                        data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                        data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if($row->status == 'pending_confirmation' && $row->parent_id == $admin_auth->id): ?>
                                                <a data-id="<?php echo e($row->id); ?>" href="javascript:void(0)"
                                                    data-type="parent"
                                                    class="btn btn-warning pull-right hide-print mr-10 approve_request">
                                                    <?php echo app('translator')->get('Xác nhận'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if($row->status == 'pending_approval' && $row->approver_id == $admin_auth->id): ?>
                                                <a data-id="<?php echo e($row->id); ?>" href="javascript:void(0)"
                                                    data-type="approve"
                                                    class="btn btn-success pull-right hide-print mr-10 approve_request">
                                                    <?php echo app('translator')->get('Duyệt'); ?>
                                                </a>
                                            <?php endif; ?>

                                        </div>
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

    </div>
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
                                        $(".alert-warning").fadeOut(3000,
                                            function() {});
                                    }, 800);

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
                                    $(".alert-warning").fadeOut(3000,
                                        function() {});
                                }, 800);
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/leaves/request_index.blade.php ENDPATH**/ ?>