<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table-bordered>tbody>tr>td {
            vertical-align: middle;
        }

        .table-bordered>thead>tr>th {
            vertical-align: middle;
            text-align: center;
        }

        .name_student {
            font-weight: bold;
        }

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

        .mr-5 {
            margin-right: 5px;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .btn-active {
            background-color: #dd4b39;
            border-color: #d73925;
            color: #fff;
        }

        .btn-active.active {
            background-color: #00a65a;
            border-color: #008d4c;
            color: #fff;
        }

        .d-flex {
            display: flex;
        }
        ul{padding-left:15px }
    </style>
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khóa học'); ?></label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $course; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name ?? '')); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['course_id']) && $params['course_id'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trình độ hiện tại'); ?></label>
                                <select name="level_id" id="level_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($val->id <= 6): ?>
                                            <?php if($val->id == 1): ?>
                                                <option value="null"
                                                    <?php echo e(isset($params['level_id']) && $params['level_id'] == 'null' ? 'selected' : ''); ?>>
                                                    <?php echo e(__($val->name ?? '')); ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo e($val->id - 1); ?>"
                                                    <?php echo e(isset($params['level_id']) && $params['level_id'] == $val->id - 1 ? 'selected' : ''); ?>>
                                                    <?php echo e(__($val->name ?? '')); ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('State'); ?></label>
                                <select name="state" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['state']) && $key == $params['state'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['state']) && $params['state'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status Study'); ?></label>
                                <select name="status_study" id="status_study" class="form-control select2"
                                    style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status_study; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['status_study']) && $value->id == $params['status_study'] ? 'selected' : ''); ?>>
                                            <?php echo e($value->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['status_study']) && $params['status_study'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Admissions'); ?></label>
                                <select name="admission_id" id="admission_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : ''); ?>>
                                            <?php echo e($value->name); ?>

                                            (Mã: <?php echo e($value->admin_code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['admission_id']) && $params['admission_id'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Class'); ?></label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['class_id']) && $params['class_id'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value->name)); ?>

                                            (Mã: <?php echo e($value->code); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['area_id']) && $params['area_id'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại hợp đồng'); ?></label>
                                <select name="contract_type" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $contract_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>"
                                            <?php echo e(isset($params['contract_type']) && $value == $params['contract_type'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['contract_type']) && $params['contract_type'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái hợp đồng'); ?></label>
                                <select name="contract_status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $contract_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>"
                                            <?php echo e(isset($params['contract_status']) && $value == $params['contract_status'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['contract_status']) && $params['contract_status'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Version'); ?></label>
                                <select name="version" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $version; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['version']) && $key == $params['version'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="null"
                                        <?php echo e(isset($params['version']) && $params['version'] == 'null' ? 'selected' : ''); ?>>
                                        <?php echo app('translator')->get('Chưa cập nhật'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Hạn công nợ từ ngày'); ?> </label>
                                <input type="date" class="form-control" name="from_date"
                                    value="<?php echo e(isset($params['from_date']) ? $params['from_date'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Hạn công nợ đến ngày'); ?> </label>
                                <input type="date" class="form-control" name="to_date"
                                    value="<?php echo e(isset($params['to_date']) ? $params['to_date'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                    <button class="btn btn-sm btn-success btn_export mr-10" type="button"
                                        data-url="<?php echo e(route('accounting_debt.export')); ?>" style="margin-right: 5px"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        <?php echo app('translator')->get('Export DS'); ?></button>

                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#modal_import">
                                        <i class="fa fa-file-excel-o"></i>
                                        <?php echo app('translator')->get('Import lịch sử giao dịch'); ?></button>
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
                <div class="pull-right" style="display: flex; margin-left:15px ">
                    



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
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Order'); ?></th>
                                <th><?php echo app('translator')->get('Student code'); ?></th>
                                <th><?php echo app('translator')->get('Full name'); ?></th>
                                
                                <th><?php echo app('translator')->get('Area'); ?></th>
                                <th><?php echo app('translator')->get('Class'); ?></th>
                                <th><?php echo app('translator')->get('Lớp đang học'); ?></th>
                                <th><?php echo app('translator')->get('Khóa học'); ?></th>
                                <th><?php echo app('translator')->get('Trình độ hiện tại'); ?></th>
                                <th><?php echo app('translator')->get('Ngày học CT'); ?></th>
                                <th><?php echo app('translator')->get('Số ngày đã học CT'); ?></th>
                                <th><?php echo app('translator')->get('Ngày công nợ đến hạn'); ?></th>
                                <th><?php echo app('translator')->get('Admissions'); ?></th>
                                <th><?php echo app('translator')->get('State'); ?></th>
                                <th><?php echo app('translator')->get('Status Study'); ?></th>
                                <th><?php echo app('translator')->get('Loại hợp đồng'); ?></th>
                                <th><?php echo app('translator')->get('Hợp đồng'); ?></th>
                                <th><?php echo app('translator')->get('Version'); ?></th>
                                <th><?php echo app('translator')->get('Tài chính'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú KT'); ?></th>
                                <th><?php echo app('translator')->get('Sách đã lấy'); ?></th>
                                
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                    $level = null; // Để tránh trường hợp $row->level_id == 6 nhận $level của vòng lặp trước đó
                                    if ($row->level_id == null || $row->level_id == '') {
                                        $level = \App\Models\Level::find(1);
                                    } elseif ($row->level_id < 6) {
                                        $level = \App\Models\Level::find($row->level_id + 1);
                                    }
                                    $status_accounting_debt = $row->json_params->status_accounting_debt ?? '';
                                ?>
                                <tr class="valign-middle">
                                    <td><?php echo e($loop->index + 1); ?></td>
                                    <td>
                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                            title="<?php echo app('translator')->get('Xem chi tiết'); ?>" data-original-title="<?php echo app('translator')->get('Xem chi tiết'); ?>"
                                            href="<?php echo e(route('students.show', $row->id)); ?>">
                                            <?php echo e($row->admin_code); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <?php echo e($row->name ?? ''); ?>

                                    </td>
                                    
                                    <td>
                                        <?php echo e($row->area->code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php if(isset($row->classs)): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <?php echo e($i->name); ?>

                                                        (<?php echo e(__($i->pivot->status ?? '')); ?>)
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(isset($row->classs)): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($i->status == 'dang_hoc'): ?>
                                                        <li><?php echo e($i->name); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->course->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($level->name ?? ($row->level->name ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->day_official != '' ? date('d-m-Y', strtotime($row->day_official)) : ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(Carbon\Carbon::parse($row->day_official)->diffInDays(Carbon\Carbon::today())); ?>

                                        ngày
                                    </td>
                                    <td>
                                        <?php echo e($row->day_official != ''? Carbon\Carbon::parse($row->day_official)->addDays(150)->format('d-m-Y'): ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($staff->admin_code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo app('translator')->get($row->state); ?>
                                    </td>
                                    <td>
                                        <?php echo app('translator')->get($row->status_study_name ?? 'Chưa cập nhật'); ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->json_params->contract_type ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->json_params->contract_status ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->version ?? ''); ?>

                                    </td>
                                    <?php if(isset($row->AccountingDebt) && count($row->AccountingDebt) > 0): ?>
                                        <td>
                                            <ul>
                                                <?php $__currentLoopData = $row->AccountingDebt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo app('translator')->get($val->type_revenue); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                <?php $__currentLoopData = $row->AccountingDebt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($val->json_params->note); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </td>
                                    <?php else: ?>
                                        <td></td>
                                        <td></td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if(isset($row->history_book_active) && count($row->history_book_active) > 0): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->history_book_active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($val->product->name); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-warning detail_accounting_debt"
                                            data-toggle="tooltip" title="<?php echo app('translator')->get('Xem lịch sử'); ?>"
                                            data-original-title="<?php echo app('translator')->get('Xem lịch sử'); ?>" data-id="<?php echo e($row->id); ?>">
                                            <i class="fa fa-list-ul"></i>
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

    <div class="modal fade" id="modal_accounting_debt" data-backdrop="static" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lịch sử giao dịch học viên <span class="name_student"></span></h5>
                </div>
                <div class="modal-body ">
                    <div class="box_alert"></div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Khoảng thu '); ?></th>
                                <th><?php echo app('translator')->get('Số tiên'); ?></th>
                                <th><?php echo app('translator')->get('Thời gian thanh toán'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="box_accounting_debt">
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning add_accounting_debt">Thêm mới</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_import" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import lịch sử giao dịch học viên</h5>
                </div>
                <div class="modal-body ">
                    <div class="d-flex">
                        <a href="<?php echo e(url('data/accounting_debt.xlsx')); ?>" class="btn btn-sm btn-default">
                            <i class="fa fa-file-excel-o"></i>
                            <?php echo app('translator')->get('File Mẫu'); ?></a>

                        <input class="form-control" type="file" name="files" id="fileImport"
                            placeholder="<?php echo app('translator')->get('Select File'); ?>">
                    </div>
                    <div class="note mt-5">
                        <p><strong> Ghi chú:</strong></p>
                        <ul>
                            <li>Mã học viên là bắt buộc và phải có trên hệ thống</li>
                            <li>Ngày thanh toán là bắt buộc</li>
                            <li>Loại tài chính: (<?php echo app('translator')->get('option:'); ?>
                                <?php $__currentLoopData = \App\Consts::TYPE_REVENUE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="label label-primary "
                                        style="text-transform: uppercase"><?php echo e($key); ?></label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                )
                            </li>
                            <li>Mỗi học viên chỉ có 1 giao dịch ứng với mỗi loại tài chính</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="importFile()">Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.detail_accounting_debt').click(function() {
            var student_id = $(this).data("id");
            list_accounting_debt(student_id);
            $('#modal_accounting_debt').modal('show');
            $('.add_accounting_debt').attr('data-id', student_id).show();
        })

        $('.btn-active').click(function() {
            var student_id = $(this).data('id');
            var _this = $(this);
            if ($(this).hasClass('active')) {
                var status = 0;
            } else {
                var status = 1;
            }
            var url = "<?php echo e(route('accounting_debt.update_status_student')); ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": '<?php echo e(csrf_token()); ?>',
                    'student_id': student_id,
                    'status': status,
                },
                success: function(response) {
                    if (response.data != null) {
                        var _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);

                        setTimeout(function() {
                            $(".alert").fadeOut(2000, function() {});
                        }, 800);

                        if (_this.hasClass('active')) {
                            _this.removeClass('active').find('.txt_btn').html('Chưa thanh toán CT');
                            _this.find('.input_checkbox').prop('checked', false);
                        } else {
                            _this.addClass('active').find('.txt_btn').html('Đã thanh toán CT');
                            _this.find('.input_checkbox').prop('checked', true);
                        }
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
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });

        })

        $(document).on('click', '.add_accounting_debt', function() {
            var student_id = $(this).data("id");
            var _html = `<tr>
                    <td>
                        <select style="width: 100%" class="form-control select2 select_type">
                            <?php $__currentLoopData = $type_revenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>">
                                    <?php echo app('translator')->get($val); ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td>
                        <input class="form-control money" type="number"  value="">
                    </td>
                    <td>
                        <input class="form-control time" type="date"  value="">
                    </td>
                    <td>
                        <textarea class="form-control note" rows="3"></textarea>
                    </td>
                    <td>
                        <button type="button" onclick="create_accounting_debt(this,` + student_id + `)" class="btn btn-success">
                            Lưu
                        </button>
                    </td>
                </tr>`;
            $('#box_accounting_debt').append(_html);
            $('.select2').select2();
            $(this).hide();
        })

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
                        a.download = 'Student.xlsx';
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

        $(document).on('click', '.btn_show_edit', function() {
            var s = $(this).parents('tr').find('.box_hide');
            var h = $(this).parents('tr').find('.box_show');
            show_hide(s, h);
        })
        $(document).on('click', '.btn_cancel_edit', function() {
            var s = $(this).parents('tr').find('.box_show');
            var h = $(this).parents('tr').find('.box_hide');
            show_hide(s, h);
        })

        function list_accounting_debt(student_id) {
            var url = "<?php echo e(route('accounting_debt.list_accounting_debt')); ?>/";
            var _view = $('#box_accounting_debt');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    "student_id": student_id,
                },
                success: function(response) {
                    let student = response.data.student;
                    let _html = response.data.html;
                    $('.name_student').html(student.name + ' - ' + student.admin_code);
                    _view.html(_html);

                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        };

        function create_accounting_debt(_this, student_id) {
            var _type = $(_this).parents('tr').find('.select_type').val();
            var _money = $(_this).parents('tr').find('.money').val();
            var _time = $(_this).parents('tr').find('.time').val();
            var _note = $(_this).parents('tr').find('.note').val();
            var _url = "<?php echo e(route('accounting_debt.create_accounting_debt')); ?>";
            $.ajax({
                type: "POST",
                url: _url,
                data: {
                    "_token": '<?php echo e(csrf_token()); ?>',
                    "student_id": student_id,
                    "type": _type,
                    "money": _money,
                    "time": _time,
                    "note": _note,
                },
                success: function(response) {
                    if (response.data == 'success') {
                        list_accounting_debt(student_id);
                        $('.add_accounting_debt').attr('data-id', student_id).show();
                    }
                    _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                    $('.box_alert').html(_html);
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }

        function update_accounting_debt(_this, id, student_id) {
            var url = "<?php echo e(route('accounting_debt.update_history')); ?>";
            var _view = $(_this).parents('tr');
            var type_revenue = _view.find('.select_type').val();
            var amount_paid = _view.find('.money').val();
            var time_payment = _view.find('.time').val();
            var note = _view.find('.note').val();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": '<?php echo e(csrf_token()); ?>',
                    'id': id,
                    'student_id': student_id,
                    'type_revenue': type_revenue,
                    'amount_paid': amount_paid,
                    'time_payment': time_payment,
                    'note': note,
                },
                success: function(response) {
                    _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                    $('.box_alert').html(_html);
                    if (response.data == 'success') {
                        list_accounting_debt(student_id);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        function delete_accounting_debt(_this, id) {
            var _confirm = confirm('<?php echo app('translator')->get('confirm_action'); ?>');
            if (_confirm) {
                var url = "<?php echo e(route('accounting_debt.delete_history')); ?>";
                var _view = $(_this).parents('tr');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": '<?php echo e(csrf_token()); ?>',
                        'id': id,
                    },
                    success: function(response) {
                        _view.remove();
                        _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ` + response.message + `
                    </div>`;
                        $('.box_alert').html(_html);
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        }

        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }

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
                url: '<?php echo e(route('accounting_debt.import_history')); ?>',
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/accounting_debt/index.blade.php ENDPATH**/ ?>