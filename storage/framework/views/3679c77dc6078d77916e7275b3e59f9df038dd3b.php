

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .pd-0 {
            padding-left: 0px !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    
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
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo app('translator')->get('THÔNG TIN CHI TIẾT NHẬN XÉT - ĐÁNH GIÁ'); ?>
                </h3>
            </div>
            <?php if(isset($this_class) && $this_class != null): ?>
                <?php
                    $quantity_student = \App\Models\UserClass::where('class_id', $this_class->id)
                        ->get()
                        ->count();
                    $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                    if ($this_class->assistant_teacher !== null && $this_class->assistant_teacher !== '') {
                        $assistantTeacherArray = json_decode($this_class->assistant_teacher, true);
                    }
                    $list = '';
                    foreach ($list_teacher as $key => $val) {
                        if (isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray)) {
                            $list .= $val->name . '; ';
                        }
                    }
                ?>
                <div class=" box-header">
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Lớp học: </strong></label>
                            <span><?php echo e($this_class->name); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên: </strong></label>
                            <span><?php echo e($teacher->name ?? ''); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Sĩ số: </strong></label>
                            <span> <?php echo e($quantity_student); ?> </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ca học: </strong></label>
                            <span><?php echo e($this_class->period->iorder); ?> (<?php echo e($this_class->period->start_time ?? ''); ?> -
                                <?php echo e($this_class->period->end_time ?? ''); ?>)</span>
                        </div>
                    </div>
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Khóa học: </strong></label>
                            <span><?php echo e($this_class->course->name ?? ''); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Trình độ: </strong></label>
                            <span><?php echo e($this_class->level->name ?? ''); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên phụ: </strong></label>
                            <span><?php echo e($list); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Số buổi: </strong></label>
                            <span> <?php echo e($this_class->total_attendance); ?>/<?php echo e($this_class->total_schedules); ?> </span>
                        </div>
                    </div>
                    <div class="col-md-4 pd-0">
                        <div class="form-group">
                            <label><strong>Chương trình: </strong></label>
                            <span><?php echo e($this_class->syllabus->name ?? ''); ?></span>
                        </div>

                        <div class="form-group">
                            <label><strong>Phòng học: </strong></label>
                            <span><?php echo e($this_class->room->name ?? ''); ?> (Khu vực:
                                <?php echo e($this_class->area->name ?? ''); ?>)</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Bắt đầu | Kết thúc: </strong></label>
                            <span> <?php echo e(date('d-m-Y', strtotime($this_class->day_start))); ?> |
                                <?php echo e(date('d-m-Y', strtotime($this_class->day_end))); ?></span>
                        </div>
                        <div class="form-group">
                            <button data-toggle="modal" data-target="#import_excel" type="button"
                                class="btn btn-success btn-sm import_evaluation">
                                <i class="fa fa-file-excel-o"></i> <?php echo app('translator')->get('Nhập bằng excel'); ?></button>

                            <button name="creat_submit" type="button" data-toggle="modal" data-target="#create_evaluation"
                                class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Tạo nhận xét'); ?></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($list_evolution_class)): ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?php echo app('translator')->get('Lịch sử nhận xét đánh giá lớp'); ?> <?php echo e($this_class->name); ?></h3>
                </div>
                <div class="box-body table-responsive">
                    <?php if(count($list_evolution_class) == 0): ?>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo app('translator')->get('not_found'); ?>
                        </div>
                    <?php else: ?>
                        <form>
                            <table class="table table-hover table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                        <th><?php echo app('translator')->get('Từ ngày'); ?></th>
                                        <th><?php echo app('translator')->get('Đến ngày'); ?></th>
                                        <th><?php echo app('translator')->get('Lớp'); ?></th>
                                        <th><?php echo app('translator')->get('Xem'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $list_evolution_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($row->from_date != '' && $row->to_date != ''): ?>
                                            <tr class="valign-middle">
                                                <td>
                                                    <?php echo e($loop->index + 1); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->from_date != '' ? date('d-m-Y', strtotime($row->from_date)) : 'Chưa nhập ngày bắt đầu'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->to_date != '' ? date('d-m-Y', strtotime($row->to_date)) : 'Chưa nhập ngày kết thúc'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($this_class->name ?? ''); ?>

                                                </td>

                                                <td>
                                                    <a target="_blank"
                                                        href="<?php echo e(route('evaluations.index', ['class_id' => $this_class->id, 'from_date' => $row->from_date, 'to_date' => $row->to_date])); ?>

                                                        "><?php echo app('translator')->get('Xem chi tiết'); ?></a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <div id="import_excel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo app('translator')->get('Import Excel'); ?></h4>
                </div>
                <div class="modal-body">
                    <p class="text-danger">Để đảm bảo việc tính toán xử lý dữ liệu tự động và cập nhật mẫu Báo cáo đánh giá nhận xét mới. Chức năng import hiện tại sẽ tạm khóa. Vui lòng liên hệ bộ phận kỹ thuật nếu cần.</p>
                </div>
                
                
            </div>

        </div>
    </div>

    <div id="create_evaluation" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo app('translator')->get('Tạo nhận xét cho lớp'); ?> <?php echo e($this_class->name); ?></h4>
                </div>
                <form class="add-evolation-excel" action="<?php echo e(route('evaluations.create')); ?>" method="get"
                    enctype="multipart/form-data">
                    <div class="modal-body row">
                        <input type="hidden" name="class_id" value="<?php echo e($this_class->id); ?>">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong><?php echo app('translator')->get('From date'); ?>: <small class="text-red">*</small></strong></label>
                                <input required type="date" class="form-control start_date_excel" name="from_date"
                                    value="<?php echo e(isset($params['from_date']) ? $params['from_date'] : date('Y-m-d')); ?>">
                            </div>
                            <div class="form-group">
                                <label><strong><?php echo app('translator')->get('To date'); ?>: <small class="text-red">*</small> </strong></label>
                                <input required type="date" class="form-control end_date_excel" name="to_date"
                                    value="<?php echo e(isset($params['to_date']) ? $params['to_date'] : ''); ?>" max="<?php echo e(now()->toDateString()); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Tạo nhận xét'); ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        function updateAjax(th) {
            let _id = $(th).attr('data-id');
            var _ability = $(th).parents('tr').find('.ability').val();
            var _consciousness = $(th).parents('tr').find('.consciousness').val();
            var _knowledge = $(th).parents('tr').find('.knowledge').val();
            var _skill = $(th).parents('tr').find('.skill').val();
            let url = "<?php echo e(route('ajax.update.evaluation')); ?>/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    ability: _ability,
                    consciousness: _consciousness,
                    knowledge: _knowledge,
                    skill: _skill,
                },
                success: function(response) {

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        $(document).ready(function() {
            $(".add-evolation").submit(function(event) {
                var startDate = $(".start_date").val();
                var endDate = $(".end_date").val();

                if (startDate > endDate) {
                    alert("Ngày kết thúc phải lớn hơn ngày bắt đầu.");
                    event.preventDefault();
                    return;
                }
            });
            $(".add-evolation-excel").submit(function(event) {
                var startDate = $(".start_date_excel").val();
                var endDate = $(".end_date_excel").val();

                if (startDate > endDate) {
                    alert("Ngày kết thúc phải lớn hơn ngày bắt đầu.");
                    event.preventDefault();
                    return;
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/evaluations/history.blade.php ENDPATH**/ ?>