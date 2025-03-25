

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .table>tbody>tr>td {
            text-align: center;
            /* vertical-align: inherit; */
        }

        @media  print {
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
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
        </h1>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('report.ranking.level.class')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên lớp'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Nhập tên lớp'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Level'); ?></label>
                                <select name="level_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $list_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Teacher'); ?></label>
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
                                <label><?php echo app('translator')->get('Ngày thi từ'); ?></label>
                                <input type="date" name="from_day_exam" class="form-control"
                                    value="<?php echo e($params['from_day_exam'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Đến'); ?></label>
                                <input type="date" name="to_day_exam" class="form-control"
                                    value="<?php echo e($params['to_day_exam'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('report.ranking.level.class')); ?>">
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
            <div class="box-header hide-print">
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách theo trình độ A1, A2'); ?></h3>
                <button onclick="window.print()" class="btn btn-primary mb-2 pull-right"><?php echo app('translator')->get('In thông tin'); ?></button>
            </div>
            <div class="box-body box-alert">
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
                <?php if(count($list_level) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Trình độ'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Lớp'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Giáo viên'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Sĩ số'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Ngày thi'); ?></th>
                                <th colspan="3"><?php echo app('translator')->get('Tỉ lệ (%)'); ?></th>
                                <th colspan="3"><?php echo app('translator')->get('Tổng (%)'); ?></th>
                            </tr>

                            <tr>
                                <th style="width:120px"><?php echo app('translator')->get('Đạt - Lên trình'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Không đạt - đơn lên trình'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Không đạt - Học lại'); ?></th>

                                <th style="width:120px"><?php echo app('translator')->get('Đạt - Lên trình'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Không đạt - đơn lên trình'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Không đạt - Học lại'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($list_level)): ?>
                                <?php $__currentLoopData = $list_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $stt = $loop->index + 1;
                                        $i = 1;
                                    ?>
                                    <?php $__currentLoopData = $level->class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $items->json_params->teacher ?? 0,
                                            )->first();
                                        ?>
                                        <?php if($i == 1): ?>
                                            <tr>
                                                <td rowspan="<?php echo e(count($level->class)); ?>"><?php echo e($stt); ?></td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>"><?php echo e($level->name ?? ''); ?></td>
                                                <td>
                                                    <?php echo e($items->json_params->name->{$lang} ?? $items->name); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->area->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($teacher->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->total_student ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_level_up ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_fail ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_pass ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_level_up ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_fail ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($items->json_params->name->{$lang} ?? $items->name); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->area->name ?? ''); ?>

                                                </td>

                                                <td>
                                                    <?php echo e($teacher->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->total_student ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_level_up ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_fail ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php $i++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <div class="box">
            <div class="box-header hide-print">
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách theo trình độ B1'); ?></h3>
            </div>
            <div class="box-body box-alert">
                <?php if(count($list_level_B) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Trình độ'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Lớp'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Khu vực'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Giáo viên'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Sĩ số'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Ngày thi'); ?></th>
                                <th colspan="5"><?php echo app('translator')->get('Tỉ lệ (%)'); ?></th>
                                <th colspan="5"><?php echo app('translator')->get('Tổng (%)'); ?></th>
                            </tr>

                            <tr>
                                <th style="width:120px"><?php echo app('translator')->get('Đạt'); ?></th>
                                
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Modul Viết'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Modul Nói'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Full'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Cần cố gắng'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Đạt'); ?></th>
                                
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Modul Viết'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Modul Nói'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Đỗ Full'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Cần cố gắng'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($list_level_B)): ?>
                                <?php $__currentLoopData = $list_level_B; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $stt = $loop->index + 1;
                                        $i = 1;
                                    ?>
                                    <?php $__currentLoopData = $level->class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $items->json_params->teacher ?? 0,
                                            )->first();
                                        ?>
                                        <?php if($i == 1): ?>
                                            <tr>
                                                <td rowspan="<?php echo e(count($level->class)); ?>"><?php echo e($stt); ?></td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>"><?php echo e($level->name ?? ''); ?></td>
                                                <td>
                                                    <?php echo e($items->json_params->name->{$lang} ?? $items->name); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->area->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($teacher->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->total_student ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass ?? ''); ?>

                                                </td>
                                                
                                                <td>
                                                    <?php echo e($items->person_pass_write ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass_speak ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass_full ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_need_try ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_pass ?? ''); ?>

                                                </td>
                                                
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_pass_write ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_pass_speak ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_pass_full ?? ''); ?>

                                                </td>
                                                <td rowspan="<?php echo e(count($level->class)); ?>">
                                                    <?php echo e($level->total_person_need_try ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td>
                                                    <?php echo e($items->json_params->name->{$lang} ?? $items->name); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->area->name ?? ''); ?>

                                                </td>

                                                <td>
                                                    <?php echo e($teacher->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->total_student ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass ?? ''); ?>

                                                </td>
                                                
                                                <td>
                                                    <?php echo e($items->person_pass_write ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass_speak ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_pass_full ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($items->person_need_try ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php $i++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/reports/ranking_level_class.blade.php ENDPATH**/ ?>