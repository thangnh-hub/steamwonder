

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        #alert-config{
            width: auto !important;
        }
        th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div id="alert-config">

</div>
    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('report.all.attendance.byday')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày'); ?> </label>
                                <input type="date" class="form-control" name="date" placeholder="<?php echo app('translator')->get('Nhập tên lớp'); ?>"
                                    value="<?php echo e(isset($params['date']) ? $params['date'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Học viên'); ?></label>
                                <input type="text" name="keyword" class="form-control"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>"
                                    placeholder="Nhập tên học viên, mã học viên">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái điểm danh'); ?></label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($key!="attendant"): ?>
                                            <option value="<?php echo e($key); ?>"<?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>><?php echo e(__($item)); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khu vực'); ?></label>
                                <select name="list_area_id[]" id="" class="form-control select2" style="width: 100%;" multiple>
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['list_area_id']) && in_array( $item->id ,$params['list_area_id']) ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('report.all.attendance.byday')); ?>">
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
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách học viên vắng mặt - đi muộn '); ?><?php echo e(isset($params['date'])? date('d-m-Y', strtotime($params['date'])) :""); ?></h3>
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
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã học viên</th>
                                <th>Tên</th>
                                <th>Lớp</th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th>Trạng thái</th>
                                <th>Ghi chú trạng thái</th>
                                <th>Ghi chú GV</th>
                                <th>Link điểm danh</th>
                                <th>Đã báo phụ huynh</th>
                                <th>Hình thức thông báo</th>
                                <th>Ghi chú (Phòng đào tạo)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="valign-middle">
                                <td><?php echo e($loop->index + 1); ?></td>
                                <td><a target="_blank" href="<?php echo e(route('students.show', $item->user_id)); ?>"><strong style="font-size: 14px;"><?php echo e($item->student->admin_code??""); ?></strong></a></td>
                                <td><a target="_blank" href="<?php echo e(route('students.edit', $item->user_id)); ?>"><?php echo e($item->student->name??""); ?></a></td>
                                <td><a target="_blank" href="<?php echo e(route('classs.show', $item->class_id)); ?>"><?php echo e($item->class->name??""); ?></a></td>
                                <td><?php echo e($item->student->area->name ?? ''); ?></td>
                                <td><?php echo e(__($item->status)); ?></td>
                                <td><?php echo e(__($item->json_params->value ?? "")); ?> <?php echo e($item->status=="late"?"phút":""); ?></td>
                                <td><?php echo e($item->note_teacher ?? ''); ?></td>
                                <td><a target="_blank" href="<?php echo e(route('attendances.index', ['schedule_id' => $item->schedule_id])); ?>">Link điểm danh</a></td>
                                <td><input type="checkbox" <?php echo e((isset($item->json_params->is_contact_to_parents) && $item->json_params->is_contact_to_parents =="1")?"checked":""); ?> class="is_contact_to_parents" value="<?php echo e($item->json_params->is_contact_to_parents??0); ?>"  onchange="updateCheckboxValue(this) "></td>
                                <td>
                                    <select class="form-control parents_method" >
                                        <?php $__currentLoopData = App\Consts::CONTACT_PARENTS_METHOD; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>" <?php echo e((isset($item->json_params->parents_method) && $item->json_params->parents_method == $key) ?"selected":""); ?>>
                                                <?php echo e(__($method)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input placeholder="Nhập ghi chú"   type="text" class="form-control note" value="<?php echo e($item->note??""); ?>" >
                                        <span data-id="<?php echo e($item->id); ?>" onclick="updateAjax(this)" class="input-group-btn">
                                            <a class="btn btn-primary">Lưu </a>
                                        </span>
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
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function updateCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }
        function updateAjax(th){
            let _id = $(th).attr('data-id');
            var _note=$(th).parents('tr').find('.note').val();
            var _is_contact_to_parents=$(th).parents('tr').find('.is_contact_to_parents').val();
            var _parents_method=$(th).parents('tr').find('.parents_method').val();
            let url = "<?php echo e(route('ajax.update.note')); ?>/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                    is_contact_to_parents: _is_contact_to_parents,
                    parents_method: _parents_method,
                },
                success: function(response) {
                    $("#alert-config").append('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>');
                    setTimeout(function() {
                        $(".alert-success").fadeOut(2000, function() {});
                    }, 800);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
        
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/reports/allattendacebyday.blade.php ENDPATH**/ ?>