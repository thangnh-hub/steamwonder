

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }
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
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên lớp'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Nhập tên lớp'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Level'); ?></label>
                                <select name="level_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Syllabus'); ?></label>
                                <select name="syllabus_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $syllabuss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['syllabus_id']) && $params['syllabus_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Course'); ?></label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $course; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['course_id']) && $params['course_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Room'); ?></label>
                                <select name="room_id" id="room_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['room_id']) && $params['room_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e($item); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Năm học'); ?></label>
                                <input type="text" class="form-control" name="year" placeholder="<?php echo app('translator')->get('Nhập năm học'); ?>"
                                    value="<?php echo e(isset($params['year']) ? $params['year'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
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
                <?php if(isset($languages)): ?>
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->is_default == 1 && $item->lang_locale != Request::get('lang')): ?>
                            <?php if(Request::get('lang') != ''): ?>
                                <a class="text-primary pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> <?php echo e(__($item->lang_name)); ?>

                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if(Request::get('lang') != $item->lang_locale): ?>
                                <a class="text-primary pull-right"
                                    href="<?php echo e(route(Request::segment(2) . '.index')); ?>?lang=<?php echo e($item->lang_locale); ?>"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> <?php echo e(__($item->lang_name)); ?>

                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

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
                                <th rowspan="2"><?php echo app('translator')->get('Title'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Syllabus'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Area'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Room'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Period'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Teacher'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Sĩ số'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Số buổi'); ?></th>
                                <th colspan="3"><?php echo app('translator')->get('Thời gian'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                            <tr>
                                <th style="width:120px"><?php echo app('translator')->get('Bắt đầu'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Dự kiến'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Thực tế'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $teacher = \App\Models\Teacher::where(
                                        'id',
                                        $row->json_params->teacher ?? 0,
                                    )->first();
                                    if (isset($row->schedules->first()->room_id)) {
                                        $room = \App\Models\Room::where(
                                            'id',
                                            $row->schedules->first()->room_id,
                                        )->first();
                                    }
                                    $quantity_student = \App\Models\UserClass::where('class_id', $row->id)
                                        ->get()
                                        ->count();
                                ?>

                                <tr class="valign-middle">
                                    <td>
                                        <strong
                                            style="font-size: 14px"><?php echo e($row->json_params->name->{$lang} ?? $row->name); ?></strong>
                                    </td>

                                    <td>
                                        <a
                                            href="<?php echo e(route('syllabuss.show', $row->syllabus_id)); ?>"><?php echo e($row->syllabus->name ?? ''); ?></a>
                                    </td>

                                    <td>
                                        <?php echo e($row->area->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($room->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->period->iorder ?? ''); ?> (<?php echo e($row->period->start_time ?? ''); ?> -
                                        <?php echo e($row->period->end_time ?? ''); ?>)
                                    </td>
                                    <td>
                                        <?php echo e($teacher->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(App\Consts::CLASS_STATUS[$row->status] ?? ''); ?>

                                        <?php echo e($row->status == 'huy' && $row->end_date != '' ? ' ( ' . date('d-m-Y', strtotime($row->end_date)) . ' )' : ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($quantity_student); ?>

                                    </td>

                                    <td>
                                        <?php echo e($row->total_attendance); ?>/<?php echo e($row->total_schedules); ?>

                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            <?php echo e(date('d-m-Y', strtotime($row->day_start))); ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            <?php echo e(date('d-m-Y', strtotime($row->day_end_expected))); ?></p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            <?php echo e(date('d-m-Y', strtotime($row->day_end))); ?></p>
                                    </td>

                                    <td style="width:150px">
                                        <?php if(!isset($row->syllabus->type) || (isset($row->syllabus->type) && $row->syllabus->type != 'online')): ?>
                                            <p>
                                                <a target="_blank"
                                                    href="<?php echo e(route('schedule_class.index', ['class_id' => $row->id])); ?>">
                                                    - <?php echo app('translator')->get('Lịch học'); ?>
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="<?php echo e(route('scores.index', ['class_id' => $row->id])); ?>">
                                                    - <?php echo app('translator')->get('Nhập điểm thi lần 1'); ?>
                                                </a>
                                            </p>
                                            <?php if(isset($row->syllabus->score_type) && $row->syllabus->score_type != 'telc'): ?>
                                                <p>
                                                    <a target="_blank"
                                                        href="<?php echo e(route('input_score_second.index', ['class_id' => $row->id])); ?>">
                                                        - <?php echo app('translator')->get('Nhập điểm thi lần 2'); ?>
                                                    </a>
                                                </p>
                                            <?php endif; ?>

                                            <p>
                                                <a target="_blank"
                                                    href="<?php echo e(route('evaluationclass.history', ['class_id' => $row->id])); ?>">
                                                    - <?php echo app('translator')->get('Nhận xét - Đánh giá'); ?>
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                    - <?php echo app('translator')->get('Chi tiết lớp'); ?>
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="<?php echo e(route('class.editByTeacher', ['id' => $row->id])); ?>">
                                                    - <?php echo app('translator')->get('Chỉnh sửa buổi học (Giáo viên)'); ?>
                                                </a>
                                            </p>
                                            <p><a target="_blank"
                                                    href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                                    - <?php echo app('translator')->get('Danh sách học viên'); ?>
                                                </a>
                                            </p>
                                        <?php endif; ?>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/classs/index.blade.php ENDPATH**/ ?>