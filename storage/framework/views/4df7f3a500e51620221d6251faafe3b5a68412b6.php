<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
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
    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="form_filter" action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Tên hoặc mã học viên'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lớp'); ?></label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Giáo viên'); ?></label>
                                <select name="teacher_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Số kỹ năng'); ?></label>
                                <select name="total_skill" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php for($i = 1; $i < 5; $i++): ?>
                                        <option value="<?php echo e($i); ?>"
                                            <?php echo e(isset($params['total_skill']) && $params['total_skill'] == $i ? 'selected' : ''); ?>>
                                            <?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Năm'); ?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" required name="year"
                                    placeholder="<?php echo app('translator')->get('Năm'); ?>"
                                    value="<?php echo e(isset($params['year']) ? $params['year'] : date('Y')); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>

                                    <button class="btn btn-sm btn-success btn_export mr-10" type="button"
                                        data-url="<?php echo e(route('certificate.export')); ?>" style="margin-right: 5px"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <?php echo app('translator')->get('Export DS'); ?></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('Tổng hợp kết quả thi chứng chỉ B1 toàn hệ thống'); ?></h3>

                <div class="pull-right" style="display: none; margin-left:15px ">
                    <a href="<?php echo e(url('data/certificate_student.xlsx')); ?>" class="btn btn-sm btn-default" download><i
                            class="fa fa-file-excel-o"></i>
                        <?php echo app('translator')->get('File Mẫu'); ?></a>
                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="<?php echo app('translator')->get('Select File'); ?>">
                    <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                        <i class="fa fa-file-excel-o"></i>
                        <?php echo app('translator')->get('Import học viên'); ?></button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('errorMessage'); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('successMessage'); ?>

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

                <div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Hình thức thi'); ?></th>
                                <th><?php echo app('translator')->get('Số HV đỗ 1 KN'); ?></th>
                                <th><?php echo app('translator')->get('Số HV đỗ 2 KN'); ?></th>
                                <th><?php echo app('translator')->get('Số HV đỗ 3 KN'); ?></th>
                                <th><?php echo app('translator')->get('Số HV đỗ 4 KN'); ?></th>
                                <th><?php echo app('translator')->get('Tổng: '); ?><?php echo e(count($rows_all)); ?></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $statistics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $total = 0;
                                ?>
                                <tr>
                                    <td><?php echo e($key); ?></td>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $total += $val;
                                        ?>
                                        <td><?php echo e($val); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td><?php echo e($total); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>


                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Mã HV'); ?></th>
                                <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                <th><?php echo app('translator')->get('Lớp'); ?></th>
                                <th><?php echo app('translator')->get('Cơ sở'); ?></th>
                                <th><?php echo app('translator')->get('Hình thức thi'); ?></th>
                                <th><?php echo app('translator')->get('Tổng KN'); ?></th>
                                <th><?php echo app('translator')->get('Nghe'); ?></th>
                                <th><?php echo app('translator')->get('Ngày báo điểm nghe'); ?></th>
                                <th><?php echo app('translator')->get('Nói'); ?></th>
                                <th><?php echo app('translator')->get('Ngày báo điểm nói'); ?></th>
                                <th><?php echo app('translator')->get('Đọc'); ?></th>
                                <th><?php echo app('translator')->get('Ngày báo điểm đọc'); ?></th>
                                <th><?php echo app('translator')->get('Viết'); ?></th>
                                <th><?php echo app('translator')->get('Ngày báo điểm viết'); ?></th>
                                <th><?php echo app('translator')->get('GVCN - GV phụ'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th><?php echo app('translator')->get('Thao tác'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($row->students->admin_code ?? ($row->json_params->admin_code ?? '')); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->students->name ?? ($row->json_params->student_name ?? '')); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->class->name ?? ($row->json_params->class_name ?? '')); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->class->area->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->type); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->total_skill ?? ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->score_listen ?? ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->day_score_listen != '' ? date('d/m/Y', strtotime($row->day_score_listen)) : ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->score_speak ?? ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->day_score_speak != '' ? date('d/m/Y', strtotime($row->day_score_speak)) : ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->score_read ?? ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->day_score_read != '' ? date('d/m/Y', strtotime($row->day_score_read)) : ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->score_write ?? ''); ?>

                                        </td>
                                        <td class="text-center">
                                            <?php echo e($row->day_score_write != '' ? date('d/m/Y', strtotime($row->day_score_write)) : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->teacher->name ?? ($row->json_params->teacher_name ?? '')); ?><?php echo e(isset($row->assistant_teacher) && !empty($row->assistant_teacher->name) ? ' - ' . $row->assistant_teacher->name : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->note ?? ''); ?>

                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Update'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
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
        $('.btn_export').click(function() {
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: url,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                data: formData,
                success: function(response) {
                    if (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'Chung_chi_b1.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }
                    $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })

        function importFile() {
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '<?php echo e(csrf_token()); ?>');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: '<?php echo e(route('certificate.import_student')); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                    // $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/certificate/index.blade.php ENDPATH**/ ?>