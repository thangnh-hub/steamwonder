

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
<?php $__env->startPush('style'); ?>
    <style>
        table {
            max-width: unset !important;
            min-width: 1200px;
        }

        table .btn {
            width: 100%;
        }

        .input-with-suffix {
            position: relative;
        }

        .input-suffix {
            position: absolute;
            right: 30px;
            top: 8px;
        }
    </style>
<?php $__env->stopPush(); ?>
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
        
        <div class="box box-default">
            <div style="display: none" class="col-md-2">
                <div class="form-group">
                    <label><?php echo app('translator')->get('Class'); ?></label>
                    <input name="class_id" type="text" value="<?php echo e(isset($this_class) ? $this_class->id : ''); ?>">
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo e(isset($this_class) ? 'Thông tin lớp ' . $this_class->name : 'Thông tin lớp'); ?></h3>
            </div>
            <?php if(isset($this_class) && $this_class != null): ?>
                <?php
                    $quantity_student = \App\Models\UserClass::where('class_id', $this_class->id)->get()->count();
                    $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                    if ($this_class->assistant_teacher !== null && $this_class->assistant_teacher !== ' ') {
                        $assistantTeacherArray = json_decode($this_class->assistant_teacher, true);
                    }
                    $list = '';
                ?>
                <div class="d-flex-wap box-header">
                    <div class="col-md-3">
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
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Trình độ: </strong></label>
                            <span><?php echo e($this_class->level->name ?? ''); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên phụ: </strong></label>
                            <?php $__currentLoopData = $list_teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray)
                                        ? ($list .= $val->name . ',')
                                        : '';
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <span><?php echo e($list); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Số buổi: </strong></label>
                            <span> <?php echo e($this_class->total_attendance); ?>/<?php echo e($this_class->total_schedules); ?> </span>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Khóa học: </strong></label>
                            <span><?php echo e($this_class->course->name ?? ''); ?></span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ca học: </strong></label>
                            <span><?php echo e($this_class->period->iorder); ?> (<?php echo e($this_class->period->start_time ?? ''); ?> -
                                <?php echo e($this_class->period->end_time ?? ''); ?>)</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ngày thi: </strong></label>
                            <span><?php echo e($this_class->day_exam != '' ? date('d-m-Y', strtotime($this_class->day_exam)) : ''); ?>

                            </span>
                        </div>
                        <?php if(count($rows) > 0): ?>
                            <div class="form-group">
                                <div class="pull-left">
                                    <form style="margin-right: 10px" class="" action="<?php echo e(route('export_score')); ?>"
                                        method="post" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="class_id"
                                            value="<?php echo e(isset($params['class_id']) ? $params['class_id'] : ''); ?>">
                                        <button type="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-file-excel-o"></i>
                                            <?php echo app('translator')->get('Export bảng điểm'); ?></button>
                                    </form>
                                </div>
                                <?php
                                    $data['rows'] = $rows;
                                    $data['teacher'] = $teacher;
                                    $data['this_class'] = $this_class;
                                ?>
                                <div class="pull-right">
                                    <form action="<?php echo e(route('generate_pdf')); ?>" method="post"
                                        enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="view" value="admin.pages.staffadmissions.pdf">
                                        <input type="hidden" name="data" value="<?php echo e(json_encode($data)); ?>">
                                        <button type="submit" name="download" value="pdf"
                                            class="btn btn-sm btn-primary"><i class="fa fa-file-pdf-o"></i>
                                            <?php echo app('translator')->get('Download bảng điểm PDF'); ?></button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách học viên'); ?> (Điểm thi lần 1)
                    <?php if($this_class->is_score == 'dachamdiemlan2'): ?>
                        <span class="text-danger">
                            (<?php echo app('translator')->get('Lớp này đã chấm điểm lần 2, không thể chỉnh sửa lần 1!'); ?>)
                        </span>
                    <?php endif; ?>
                </h3>
                <?php if(isset($languages)): ?>
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->is_default == 1 && $item->lang_locale != Request::get('lang')): ?>
                            <?php if(Request::get('lang') != ''): ?>
                                <a class="text-primary pull-right" href="<?php echo e(route('evaluation_class.index')); ?>"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> <?php echo e(__($item->lang_name)); ?>

                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if(Request::get('lang') != $item->lang_locale): ?>
                                <a class="text-primary pull-right"
                                    href="<?php echo e(route('evaluation_class.index')); ?>?lang=<?php echo e($item->lang_locale); ?>"
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
                    <?php if($this_class->is_score == 'dachamdiemlan2'): ?>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Order'); ?></th>
                                    <th><?php echo app('translator')->get('Code'); ?></th>
                                    <th><?php echo app('translator')->get('Student'); ?></th>
                                    <th><?php echo app('translator')->get('Main class'); ?></th>
                                    <th><?php echo app('translator')->get('Score listen'); ?> (Trọng số:
                                        <?php echo e($this_class->syllabus->json_params->score->listen->weight ?? ''); ?>)</th>
                                    <th><?php echo app('translator')->get('Score speak'); ?> (Trọng số:
                                        <?php echo e($this_class->syllabus->json_params->score->speak->weight ?? ''); ?>)</th>
                                    <th><?php echo app('translator')->get('Score read'); ?> (Trọng số:
                                        <?php echo e($this_class->syllabus->json_params->score->read->weight ?? ''); ?>)</th>
                                    <th><?php echo app('translator')->get('Score write'); ?> (Trọng số:
                                        <?php echo e($this_class->syllabus->json_params->score->write->weight ?? ''); ?>)</th>
                                    <th><?php echo app('translator')->get('Average'); ?></th>
                                    <th><?php echo app('translator')->get('Evaluations'); ?></th>
                                    <th><?php echo app('translator')->get('Xếp loại'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $syllabus_id = $row->class->syllabus_id;
                                        $syllabus = \App\Models\Syllabus::find($syllabus_id);
                                        if (isset($syllabus->json_params)) {
                                            $listen_weight = $syllabus->json_params->score->listen->weight ?? 25;
                                            $speak_weight = $syllabus->json_params->score->speak->weight ?? 25;
                                            $read_weight = $syllabus->json_params->score->read->weight ?? 25;
                                            $write_weight = $syllabus->json_params->score->write->weight ?? 25;
                                            $listen_min = $syllabus->json_params->score->listen->min ?? 60;
                                            $speak_min = $syllabus->json_params->score->speak->min ?? 60;
                                            $read_min = $syllabus->json_params->score->read->min ?? 60;
                                            $write_min = $syllabus->json_params->score->write->min ?? 60;
                                        }
                                    ?>
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->student->admin_code ?? ''); ?>

                                        </td>
                                        <td>
                                            <a
                                                href="<?php echo e(route('students.show', $row->student->id)); ?>"><?php echo e($row->student->name ?? ''); ?></a>
                                        </td>
                                        <td>
                                            <a
                                                href="<?php echo e(route('classs.edit', $row->class->id)); ?>"><?php echo e($row->class->name ?? ''); ?></a>
                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->exam_1st->score_listen ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->exam_1st->score_speak ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->exam_1st->score_read ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->exam_1st->score_write ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->json_params->score_average ?? '0'); ?>

                                        </td>

                                        <td>
                                            <textarea rows="5" class="form-control note"><?php echo e($row->json_params->note ?? ''); ?></textarea>
                                        </td>
                                        <td>
                                            <label
                                                class="btn <?php echo e($row->status != '' ? App\Consts::ranked_academic_color[$row->status] ?? '' : 'Chưa xác định'); ?>">
                                                <?php echo e($row->status != '' ? App\Consts::ranked_academic_total[$row->status] ?? $row->status : 'Chưa xác định'); ?>

                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <form action="<?php echo e(route('scores.save')); ?>" method="POST"
                            onsubmit="return confirm('<?php echo app('translator')->get('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn thực hiện thao tác này?'); ?>')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="class_id"
                                value="<?php echo e(isset($params['class_id']) ? $params['class_id'] : ''); ?>">

                            <div style="padding-left: 0px" class="form-group col-md-4">
                                <label> Ngày thi <span class="text-danger">*</span></label>
                                <input required type="date" name="day_exam" class="form-control"
                                    value="<?php echo e($this_class->day_exam ?? ''); ?>">
                            </div>

                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Order'); ?></th>
                                        <th><?php echo app('translator')->get('Code'); ?></th>
                                        <th><?php echo app('translator')->get('Student'); ?></th>
                                        <th><?php echo app('translator')->get('Main class'); ?></th>
                                        <th><?php echo app('translator')->get('Score listen'); ?> (Trọng số:
                                            <?php echo e($this_class->syllabus->json_params->score->listen->weight ?? ''); ?>)</th>
                                        <th><?php echo app('translator')->get('Score speak'); ?> (Trọng số:
                                            <?php echo e($this_class->syllabus->json_params->score->speak->weight ?? ''); ?>)</th>
                                        <th><?php echo app('translator')->get('Score read'); ?> (Trọng số:
                                            <?php echo e($this_class->syllabus->json_params->score->read->weight ?? ''); ?>)</th>
                                        <th><?php echo app('translator')->get('Score write'); ?> (Trọng số:
                                            <?php echo e($this_class->syllabus->json_params->score->write->weight ?? ''); ?>)</th>
                                        <th><?php echo app('translator')->get('Average'); ?></th>
                                        <th><?php echo app('translator')->get('Evaluations'); ?></th>
                                        <th><?php echo app('translator')->get('Xếp loại'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $syllabus_id = $row->class->syllabus_id;
                                            $syllabus = \App\Models\Syllabus::find($syllabus_id);
                                            if (isset($syllabus->json_params)) {
                                                $listen_weight = $syllabus->json_params->score->listen->weight ?? 25;
                                                $speak_weight = $syllabus->json_params->score->speak->weight ?? 25;
                                                $read_weight = $syllabus->json_params->score->read->weight ?? 25;
                                                $write_weight = $syllabus->json_params->score->write->weight ?? 25;
                                                $listen_min = $syllabus->json_params->score->listen->min ?? 60;
                                                $speak_min = $syllabus->json_params->score->speak->min ?? 60;
                                                $read_min = $syllabus->json_params->score->read->min ?? 60;
                                                $write_min = $syllabus->json_params->score->write->min ?? 60;
                                            }
                                        ?>
                                        <tr class="valign-middle">
                                            <td>
                                                <?php echo e($loop->index + 1); ?>

                                                <input type="hidden" name="list[<?php echo e($row->id); ?>][id]"
                                                    value="<?php echo e($row->id); ?>">
                                                <input type="hidden" name="list[<?php echo e($row->id); ?>][level]"
                                                    value="<?php echo e($this_class->level->id ?? ''); ?>">
                                            </td>
                                            <td>
                                                <?php echo e($row->student->admin_code ?? ''); ?>

                                            </td>
                                            <td>
                                                <a
                                                    href="<?php echo e(route('students.show', $row->student->id)); ?>"><?php echo e($row->student->name ?? ''); ?></a>
                                            </td>
                                            <td>
                                                <a
                                                    href="<?php echo e(route('classs.edit', $row->class->id)); ?>"><?php echo e($row->class->name ?? ''); ?></a>
                                            </td>
                                            <td>
                                                <div class="input-with-suffix">
                                                    <input data-class-id="<?php echo e($row->class->id); ?>"
                                                        onchange="updateAjax(this)" data-id="<?php echo e($row->id); ?>"
                                                        type="number" class="form-control score-input listen"
                                                        name="list[<?php echo e($row->id); ?>][score_listen]"
                                                        value="<?php echo e($row->json_params->exam_1st->score_listen ?? $row->score_listen); ?>"
                                                        min="0" max="1000"
                                                        data-weight="<?php echo e($listen_weight ?? 25); ?>">
                                                    <input type="hidden"
                                                        name="list[<?php echo e($row->id); ?>][score_listen_weight]"
                                                        value="<?php echo e($listen_weight ?? 25); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-with-suffix">
                                                    <input data-class-id="<?php echo e($row->class->id); ?>"
                                                        onchange="updateAjax(this)" data-id="<?php echo e($row->id); ?>"
                                                        type="number" class="form-control score-input speak"
                                                        name="list[<?php echo e($row->id); ?>][score_speak]"
                                                        value="<?php echo e($row->json_params->exam_1st->score_speak ?? $row->score_speak); ?>"
                                                        min="0" max="1000"
                                                        data-weight="<?php echo e($speak_weight ?? 25); ?>">
                                                    <input type="hidden"
                                                        name="list[<?php echo e($row->id); ?>][score_speak_weight]"
                                                        value="<?php echo e($speak_weight ?? 25); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-with-suffix">
                                                    <input data-class-id="<?php echo e($row->class->id); ?>"
                                                        onchange="updateAjax(this)" data-id="<?php echo e($row->id); ?>"
                                                        type="number" class="form-control score-input read"
                                                        name="list[<?php echo e($row->id); ?>][score_read]"
                                                        value="<?php echo e($row->json_params->exam_1st->score_read ?? $row->score_read); ?>"
                                                        min="0" max="1000"
                                                        data-weight="<?php echo e($read_weight ?? 25); ?>">
                                                    <input type="hidden"
                                                        name="list[<?php echo e($row->id); ?>][score_read_weight]"
                                                        value="<?php echo e($read_weight ?? 25); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-with-suffix">
                                                    <input data-class-id="<?php echo e($row->class->id); ?>"
                                                        onchange="updateAjax(this)" data-id="<?php echo e($row->id); ?>"
                                                        type="number" class="form-control score-input write"
                                                        name="list[<?php echo e($row->id); ?>][score_write]"
                                                        value="<?php echo e($row->json_params->exam_1st->score_write ?? $row->score_write); ?>"
                                                        min="0" max="1000"
                                                        data-weight="<?php echo e($write_weight ?? 25); ?>">
                                                    <input type="hidden"
                                                        name="list[<?php echo e($row->id); ?>][score_write_weight]"
                                                        value="<?php echo e($write_weight ?? 25); ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-with-suffix">
                                                    <input type="text" class="form-control"
                                                        name="list[<?php echo e($row->id); ?>][json_params][score_average]"
                                                        value="<?php echo e($row->json_params->score_average ?? '0'); ?>"
                                                        min="0" max="1000" id="average_<?php echo e($row->id); ?>"
                                                        readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <textarea required data-class-id="<?php echo e($row->class->id); ?>" onchange="updateAjax(this)" data-id="<?php echo e($row->id); ?>"
                                                    rows="5" class="form-control note" name="list[<?php echo e($row->id); ?>][json_params][note]"><?php echo e($row->json_params->note ?? ''); ?></textarea>
                                            </td>
                                            <td>
                                                <label
                                                    class="btn <?php echo e($row->status != '' ? App\Consts::ranked_academic_color[$row->status] ?? '' : 'Chưa xác định'); ?>">
                                                    <?php echo e($row->status != '' ? App\Consts::ranked_academic_total[$row->status] ?? $row->status : 'Chưa xác định'); ?>

                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php if($this_class->is_score == 'dachamdiemlan2'): ?>
                                <button type="button" class="btn btn-danger">
                                    <i class="fa fa-save"></i>
                                    <?php echo app('translator')->get('Đã chấm điểm lần 2.Không thể sửa'); ?>
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i>
                                    <?php echo app('translator')->get('Lưu và xếp loại'); ?>
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="box-footer clearfix">
                
            </div>

        </div>
    </section>




<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var scoreInputs = document.querySelectorAll('.score-input');
        scoreInputs.forEach(function(input) {
            input.addEventListener("change", function() {
                var selectedScore = parseFloat(this.value); // Lấy giá trị số từ phần tử đang thay đổi

                var minScore = 0;
                var maxScore = 1000;

                if (selectedScore < minScore) {
                    this.value = minScore;
                } else if (selectedScore > maxScore) {
                    this.value = maxScore;
                }
            });
        });

        function updateAjax(th) {
            let _id = $(th).attr('data-id');
            let _class_id = $(th).attr('data-class-id');
            var _listen = $(th).parents('tr').find('.listen').val();
            var _speak = $(th).parents('tr').find('.speak').val();
            var _read = $(th).parents('tr').find('.read').val();
            var _write = $(th).parents('tr').find('.write').val();
            var _note = $(th).parents('tr').find('.note').val();
            let url = "<?php echo e(route('ajax.update.score')); ?>/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    class_id: _class_id,
                    listen: _listen,
                    speak: _speak,
                    read: _read,
                    write: _write,
                    note: _note,
                },
                success: function(response) {

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/scores/index.blade.php ENDPATH**/ ?>