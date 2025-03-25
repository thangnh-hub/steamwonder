
<?php $__env->startPush('style'); ?>
    <style>
        .invoice {
            margin: 10px 15px;
        }

        table {
            border: 1px solid #dddddd;
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;
            text-align: center;
        }

        .mb-2 {
            margin-bottom: 2rem;
        }

        .min-height {
            min-height: unset !important
        }

        @media  print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="content">
        
        <button id="printButton" onclick="window.print()" class="btn btn-primary mb-2"><?php echo app('translator')->get('In thông tin'); ?></button>
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
                <h2 class="box-title text-uppercase text-bold">
                    <i class="fa fa-user"></i> <?php echo app('translator')->get('Thông tin học viên'); ?>
                </h2>
            </div>
            <div class="box-body">
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <address>
                            <p><strong><?php echo app('translator')->get('Họ và tên'); ?>: </strong><?php echo e($detail->name ?? 'Chưa cập nhật'); ?></p>
                            
                            <p><strong><?php echo app('translator')->get('Mã học viên'); ?>: </strong><?php echo e($detail->admin_code ?? 'Chưa cập nhật'); ?></p>
                            
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <address>
                            <p><strong><?php echo app('translator')->get('Ngày nhập học'); ?>:
                                </strong><?php echo e($detail->day_official != '' ? date('d-m-Y', strtotime($detail->day_official)) : 'Chưa cập nhật'); ?>

                            </p>
                            <p><strong><?php echo app('translator')->get('Loại hợp đồng'); ?>:
                                </strong><?php echo e($detail->json_params->contract_type ?? 'Chưa cập nhật'); ?>

                            </p>
                            
                            
                            
                            
                        </address>
                    </div><!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <address>
                            
                            
                            
                            
                        </address>
                    </div><!-- /.col -->

                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-uppercase text-bold">
                    <i class="fa fa-graduation-cap"></i> <?php echo app('translator')->get('Quá trình học tập'); ?>
                </h3>
                <?php if($admin_auth->admin_type == 'staff'): ?>
                    <button type="button" class="btn btn-warning pull-right hide-print" data-toggle="modal"
                        data-target=".bd-example-modal-lg">
                        <?php echo app('translator')->get('Thêm lịch sử lớp học'); ?>
                    </button>
                <?php endif; ?>
            </div>
            <div style="padding-top:0px" class="box-body">
                <div class="d-flex-wap table-responsive">
                    <table style="border: 1px solid #dddddd;" class="table table-hover table-striped ">
                        <thead>
                            <tr>
                                <th rowspan="2"><?php echo app('translator')->get('Order'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Lớp'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Giáo viên'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Trình độ'); ?></th>
                                
                                <th rowspan="2"><?php echo app('translator')->get('Ngày vào lớp'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Hình thức'); ?></th>
                                <th colspan="2"><?php echo app('translator')->get('Lộ trình học'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Ngày thi'); ?></th>
                                <th colspan="7"><?php echo app('translator')->get('Điểm '); ?></th>
                                <th colspan="3"><?php echo app('translator')->get('Điểm danh'); ?></th>
                                <th colspan="2"><?php echo app('translator')->get('Bài tập về nhà'); ?></th>

                                
                            </tr>
                            <tr>
                                <th style="width: 70px"><?php echo app('translator')->get('Tiêu chuẩn'); ?></th>
                                <th style="width: 70px"><?php echo app('translator')->get('GVVN/GVNN'); ?></th>

                                <th style="width: 50px"><?php echo app('translator')->get('Nghe'); ?></th>
                                <th style="width: 50px"><?php echo app('translator')->get('Nói'); ?></th>
                                <th style="width: 50px"><?php echo app('translator')->get('Đọc'); ?></th>
                                <th style="width: 50px"><?php echo app('translator')->get('Viết'); ?></th>
                                <th style="width: 50px"><?php echo app('translator')->get('TB'); ?></th>
                                <th style="width: 100px"><?php echo app('translator')->get('Xếp loại'); ?></th>
                                <th style="width: 170px"><?php echo app('translator')->get('Nhận xét'); ?></th>
                                <th style="width: 70px"><?php echo app('translator')->get('Có'); ?></th>
                                <th style="width: 70px"><?php echo app('translator')->get('Vắng'); ?></th>
                                <th style="width: 70px"><?php echo app('translator')->get('Muộn'); ?></th>
                                
                                <th style="width: 90px"><?php echo app('translator')->get('Không làm'); ?></th>
                                <th style="width: 90px"><?php echo app('translator')->get('Làm thiếu'); ?></th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->teacher ?? ''); ?>

                                    </td>

                                    <td>
                                        <?php echo e($item->level->name ?? ''); ?>

                                    </td>

                                    


                                    <td>
                                        <?php echo e(isset($item->day_in_class) && $item->day_in_class != '' ? date('d-m-Y', strtotime($item->day_in_class)) : ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->status != '' ? App\Consts::USER_CLASS_STATUS[$item->status] ?? $item->status : ''); ?>

                                    </td>
                                    <td><?php echo e($item->lesson_number ?? ''); ?></td>
                                    <td><?php echo e($item->total_schedules_gv ?? ''); ?> / <?php echo e($item->total_schedules_gvnn ?? ''); ?>

                                    </td>

                                    <td>
                                        <?php echo e($item->day_exam ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->score_listen); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->score_speak); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->score_read); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->score_write); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->score_average); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->status_rank != '' ? App\Consts::ranked_academic[$item->status_rank] ?? $item->status_rank : 'Chưa xác định'); ?>

                                    </td>
                                    <td>
                                        <?php echo e($item->note_score); ?>

                                    </td>

                                    <td><?php echo e($item->attendant ?? ''); ?></td>

                                    <td>
                                        <?php echo e($item->absent); ?>

                                        <?php if($item->absent > 0): ?>
                                            (CP: <?php echo e($item->absent_has_reason); ?>, KP: <?php echo e($item->absent - $item->absent_has_reason); ?>)
                                            [<?php echo e($item->string_absent); ?>]
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($item->late); ?> lần
                                        <?php if($item->late > 0): ?>
                                            (Tổng: <?php echo e($item->count_late); ?> phút)
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td>
                                        <?php if($item->is_homework_not_have > 0): ?>
                                            <?php echo e($item->is_homework_not_have); ?> lần
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($item->is_homework_did_not_complete > 0): ?>
                                            <?php echo e($item->is_homework_did_not_complete); ?> lần
                                        <?php endif; ?>
                                    </td>


                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if(count($list_class) > 0): ?>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-star-half-empty"></i> <?php echo app('translator')->get('Đánh giá nhận xét'); ?>
                    </h3>
                    <?php if($admin_auth->admin_type == 'staff'): ?>
                        <button type="button" class="btn btn-warning pull-right hide-print" data-toggle="modal"
                            data-target=".bd-example-modal-lg-evoluation">
                            <?php echo app('translator')->get('Thêm đánh giá nhận xét cho lớp'); ?>
                        </button>
                    <?php endif; ?>
                </div>
                <?php if(count($list_evolution) > 0): ?>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:8%"><?php echo app('translator')->get('Từ ngày'); ?></th>
                                        <th style="width:8%"><?php echo app('translator')->get('Đến ngày'); ?></th>
                                        <th style="width:10%"><?php echo app('translator')->get('Lớp'); ?></th>
                                        <th style="width:10%"><?php echo app('translator')->get('Học lực'); ?></th>
                                        <th style="width:21%"><?php echo app('translator')->get('Ý thức'); ?></th>
                                        <th style="width:21%"><?php echo app('translator')->get('Kiến thức'); ?></th>
                                        <th style="width:21%"><?php echo app('translator')->get('Kỹ năng'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $__currentLoopData = $list_evolution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(
                                            $value->from_date &&
                                                $value->to_date &&
                                                ((isset($value->json_params->ability) && $value->json_params->ability != '') ||
                                                    (isset($value->json_params->consciousness) && $value->json_params->consciousness != '') ||
                                                    (isset($value->json_params->knowledge) && $value->json_params->knowledge != '') ||
                                                    (isset($value->json_params->skill) && $value->json_params->skill != ''))): ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($value->from_date ? date('d-m-Y', strtotime($value->from_date)) : 'Chưa cập nhật'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($value->to_date ? date('d-m-Y', strtotime($value->to_date)) : 'Chưa cập nhật'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($value->class->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo isset($value->json_params->ability) ? nl2br($value->json_params->ability) : ''; ?>

                                                </td>
                                                <td>
                                                    <?php echo isset($value->json_params->consciousness) ? nl2br($value->json_params->consciousness) : ''; ?>

                                                </td>
                                                <td>
                                                    <?php echo isset($value->json_params->knowledge) ? nl2br($value->json_params->knowledge) : ''; ?>

                                                </td>
                                                <td>
                                                    <?php echo isset($value->json_params->skill) ? nl2br($value->json_params->skill) : ''; ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($history_student) && count($history_student) > 0): ?>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-history"></i> <?php echo app('translator')->get('Lịch sử biến động'); ?>
                    </h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('STT'); ?></th>
                                    <th><?php echo app('translator')->get('Loại'); ?></th>
                                    <th><?php echo app('translator')->get('Trạng thái cũ'); ?></th>
                                    <th><?php echo app('translator')->get('Trạng thái mới'); ?></th>

                                    <th><?php echo app('translator')->get('Lớp cũ'); ?></th>
                                    <th><?php echo app('translator')->get('Lớp mới'); ?></th>
                                    <th><?php echo app('translator')->get('Trạng thái đổi lớp'); ?></th>
                                    <th><?php echo app('translator')->get('Ngày vào lớp'); ?></th>

                                    <th><?php echo app('translator')->get('Ngày cập nhật'); ?></th>
                                    <th><?php echo app('translator')->get('Người cập nhật'); ?></th>
                                    <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                    <th class="hide-print"><?php echo app('translator')->get('Action'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="">

                                <?php $__currentLoopData = $history_student; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-center">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>
                                        <td>
                                            <?php echo app('translator')->get($val->type); ?>
                                        </td>
                                        <td>
                                            <?php echo e($val->status_old->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($val->status_new->name ?? ''); ?>

                                        </td>

                                        <td>
                                            <?php echo e($val->class_old->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($val->class_new->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(isset($val->status_change_class) && $val->status_change_class != '' ? $user_class_status[$val->status_change_class] : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(isset($val->json_params->day_in_class) && $val->json_params->day_in_class != '' ? date('d-m-Y', strtotime($val->json_params->day_in_class)) : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(date('d-m-Y', strtotime($val->updated_at))); ?>

                                        </td>
                                        <td>
                                            <?php echo e($val->admin_updated->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($val->json_params->note_status_study ?? ''); ?>

                                        </td>
                                        <td class="hide-print">
                                            <button class="btn btn-sm btn-warning edit_history"
                                                data-id="<?php echo e($val->id); ?>" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Update'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            <a class="btn btn-sm btn-danger"
                                                onclick="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')"
                                                href="<?php echo e(route('student.delete_history', $val->id)); ?>"
                                                data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(isset($decisions) && count($decisions) > 0): ?>
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-history"></i> <?php echo app('translator')->get('Đơn biến động'); ?>
                    </h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('STT'); ?></th>
                                    <th><?php echo app('translator')->get('Đơn biến động'); ?></th>
                                    <th><?php echo app('translator')->get('Nội dung'); ?></th>
                                    <th><?php echo app('translator')->get('Ngày biến động'); ?></th>
                                    <th><?php echo app('translator')->get('Note'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="">

                                <?php $__currentLoopData = $decisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $decision): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>
                                        <td>
                                            <?php echo e(__($decision->is_type)); ?>

                                        </td>

                                        <td>
                                            <?php echo e($decision->code); ?>

                                        </td>
                                        <td>
                                            <?php echo e(date('d/m/Y', strtotime($decision->active_date))); ?>

                                        </td>
                                        <td>
                                            <?php echo e($decision->note); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </section>

    <div class="modal fade bd-example-modal-lg" data-backdrop="static" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text_change" id="myLargeModalLabel">Thêm lịch sử lớp học</h4>
                </div>
                <form action="<?php echo e(route('additional_class')); ?>" method="POST"
                    onsubmit="return confirm('<?php echo app('translator')->get('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn lưu thêm thông tin các lớp này?'); ?>')">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 270px" rowspan="2"><?php echo app('translator')->get('Lớp'); ?></th>
                                                <th rowspan="2"><?php echo app('translator')->get('Nhập ngày vào lớp'); ?></th>
                                                <th rowspan="2"><?php echo app('translator')->get('Hình thức'); ?></th>
                                                <th colspan="5"><?php echo app('translator')->get('Điểm'); ?></th>
                                                <th rowspan="2"><?php echo app('translator')->get('Chức năng'); ?></th>
                                            </tr>
                                            <tr>
                                                <th><?php echo app('translator')->get('Nghe'); ?></th>
                                                <th><?php echo app('translator')->get('Nói'); ?></th>
                                                <th><?php echo app('translator')->get('Đọc'); ?></th>
                                                <th><?php echo app('translator')->get('Viết'); ?></th>
                                                <th><?php echo app('translator')->get('Nhận xét'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_available box_available_history">
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="user_id" value="<?php echo e($detail->id); ?>">
                                                    <select required name="list_class[0][class_id]" style="width:100%"
                                                        class="form-control select2 select_class">
                                                        <?php $__currentLoopData = $all_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($class_item->id ?? ''); ?>">
                                                                <?php echo e($class_item->name ?? ''); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date"
                                                        name="list_class[0][day_in_class]" value="">
                                                </td>

                                                <td>
                                                    <select style="width:100%" class="form-control select2"
                                                        name="list_class[0][user_class_status]" id="">
                                                        <?php $__currentLoopData = App\Consts::USER_CLASS_STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $us_status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($k); ?>">
                                                                <?php echo e($us_status); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_listen]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_speak]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_read]" value="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number"
                                                        name="list_class[0][score_write]" value="0">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control note" name="list_class[0][note]"></textarea>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary add_class_history">
                                    Thêm lớp
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg-evoluation" data-backdrop="static" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text_change" id="myLargeModalLabel">Thêm lịch sử nhận xét đánh giá</h4>
                </div>
                <form action="<?php echo e(route('additional_evaluation')); ?>" method="POST"
                    onsubmit="return confirm('<?php echo app('translator')->get('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn lưu thêm thông tin các lớp này?'); ?>')">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><?php echo app('translator')->get('Lớp'); ?></th>
                                                <th><?php echo app('translator')->get('Từ ngày'); ?></th>
                                                <th><?php echo app('translator')->get('Đến ngày'); ?></th>
                                                <th><?php echo app('translator')->get('Học lực'); ?></th>
                                                <th><?php echo app('translator')->get('Ý thức'); ?></th>
                                                <th><?php echo app('translator')->get('Kiến thức'); ?></th>
                                                <th><?php echo app('translator')->get('Kỹ năng'); ?></th>
                                                <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_available box_available_history_evaluation">
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="user_id" value="<?php echo e($detail->id); ?>">
                                                    <select required name="list[0][class_id]" style="width:100%"
                                                        class="form-control select2 select_class">
                                                        <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($class_item->id ?? ''); ?>">
                                                                <?php echo e($class_item->name ?? ''); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date" name="list[0][from_date]"
                                                        value="">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="date" name="list[0][to_date]"
                                                        value="">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][ability]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][consciousness]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][knowledge]"></textarea>
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="form-control" name="list[0][skill]"></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-primary add_evaluation_history">
                                    Thêm lớp
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal_history" data-backdrop="static" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->get('Sửa lịch sử biến động'); ?></h4>
                </div>
                <form action="<?php echo e(route('student.update_history_statusstudy')); ?>" method="POST"
                    onsubmit="return confirm('<?php echo app('translator')->get('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn cập nhật thông tin lịch sử ?'); ?>')">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body ">
                        <div class="box-default">
                            <div class="box-body">
                                <div class="table-responsive table_history">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><?php echo app('translator')->get('Lớp'); ?></th>
                                                <th><?php echo app('translator')->get('Từ ngày'); ?></th>
                                                <th><?php echo app('translator')->get('Đến ngày'); ?></th>
                                                <th><?php echo app('translator')->get('Học lực'); ?></th>
                                                <th><?php echo app('translator')->get('Ý thức'); ?></th>
                                                <th><?php echo app('translator')->get('Kiến thức'); ?></th>
                                                <th><?php echo app('translator')->get('Kỹ năng'); ?></th>
                                                <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="box_history">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        function del_class_history(th) {
            $(th).parents('tr').fadeOut(500, function() {
                $(th).parents('tr').remove();
            });
        }

        $('.add_class_history').click(function() {
            var currentTime = $.now();
            var _html = `<tr>
                    <td>
                        <select required name="list_class[` + currentTime + `][class_id]" style="width: 100%" class="form-control select2 select_class">
                            <?php $__currentLoopData = $all_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class_item->id ?? ''); ?>">
                                    <?php echo e($class_item->name ?? ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <input name="list_class[` + currentTime + `][day_in_class]" class="form-control" type="date" value="">
                    </td>
                    <td>
                        <select style="width:100%" class="form-control select2"
                            name="list_class[` + currentTime + `][user_class_status]">
                            <?php $__currentLoopData = App\Consts::USER_CLASS_STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $us_status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option

                                    value="<?php echo e($k); ?>">
                                    <?php echo e($us_status); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_listen]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_speak]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_read]" value="0">
                    </td>
                    <td>
                        <input class="form-control" type="number" name="list_class[` + currentTime + `][score_write]" value="0">
                    </td>
                    <td>
                        <textarea  rows="1" class="form-control note" name="list_class[` + currentTime + `][note]"></textarea>
                    </td>

                    <td>
                        <button type="button" onclick="del_class_history(this)" class="btn btn-danger">
                            Xóa
                        </button>
                    </td>
                </tr>`;
            $('.box_available_history').append(_html);
            $('.select2').select2();
        })

        $('.add_evaluation_history').click(function() {
            var currentTime = $.now();
            var _html = `<tr>
                        <td>
                            <input type="hidden" name="user_id" value="<?php echo e($detail->id); ?>">
                            <select required name="list[` + currentTime + `][class_id]" style="width:100%" class="form-control select2 select_class">
                                <?php $__currentLoopData = $list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class_item->id ?? ''); ?>">
                                        <?php echo e($class_item->name ?? ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <td>
                            <input class="form-control" type="date" name="list[` + currentTime + `][from_date]" value="">
                        </td>
                        <td>
                            <input class="form-control" type="date" name="list[` + currentTime + `][to_date]" value="">
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][ability]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][consciousness]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][knowledge]"></textarea>
                        </td>
                        <td>
                            <textarea rows="1" class="form-control" name="list[` + currentTime + `][skill]"></textarea>
                        </td>
                        <td>
                        <button type="button" onclick="del_class_history(this)" class="btn btn-danger">
                            Xóa
                        </button>
                    </td>
                </tr>`;
            $('.box_available_history_evaluation').append(_html);
            $('.select2').select2();
        })

        $('.edit_history').click(function() {
            var _id = $(this).data('id');
            let _url = "<?php echo e(route('student.get_table_history')); ?>";
            var _html = $('.table_history');
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "id": _id,
                },
                success: function(response) {
                    _view = response.data.html;
                    _html.html(_view);
                    $('.modal_history').modal('show');
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = translations.csrf_mismatch;
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $('.box_alert').html(
                        elementErrors);
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/students/detail.blade.php ENDPATH**/ ?>