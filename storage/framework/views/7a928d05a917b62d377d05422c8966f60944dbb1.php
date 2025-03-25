<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .item_answer {
            align-items: center;
        }

        .w-150 {
            width: 150px;
        }

        .h-60 {
            height: 60px;
        }

        .bd-b {
            border-bottom: 1px solid #000;
        }

        .bd-l {
            border-left: 1px solid #000;
        }

        .box_center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-dm btn-success pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
            </a>

        </h1>
    </section>
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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Phần thi thi'); ?></h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Lưu phần thi'); ?>
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Trình độ'); ?> <?php echo e($detail->id_level); ?><small
                                                        class="text-red">*</small></label>
                                                <select required name="id_level" class="id_level form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val->id ?? ''); ?>"
                                                            <?php echo e($detail->id_level == $val->id ? 'selected' : ''); ?>>
                                                            <?php echo e($val->name ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tổ chức'); ?></label>
                                                <select name="organization" class=" form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $organization; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key ?? ''); ?>"
                                                            <?php echo e($detail->organization == $key ? 'selected' : ''); ?>>
                                                            <?php echo e(__($val) ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Phần thi'); ?> <small class="text-red">*</small></label>
                                                <select required name="is_type" class="form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>"
                                                            <?php echo e($detail->is_type == $val ? 'selected' : ''); ?>>
                                                            <?php echo e($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Chọn hình thức'); ?> <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>"
                                                            <?php echo e($detail->skill_test == $val ? 'selected' : ''); ?>>
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Kiểu câu hỏi'); ?> <small class="text-red">*</small></label>
                                                <select disabled class="form-control select2 type_question">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>"
                                                            <?php echo e($detail->type_question == $val ? 'selected' : ''); ?>>
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('File Audio nếu có'); ?></label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="files_audio" class="btn btn-primary file">
                                                                <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Select'); ?>
                                                            </a>
                                                        </span>
                                                        <input id="files_audio" class="form-control" type="text"
                                                            name="audio" placeholder="<?php echo app('translator')->get('Files Audio'); ?>"
                                                            value="<?php echo e($detail->audio ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Content'); ?></label>
                                                    <textarea name="content" class="form-control" id="content_vi"><?php echo $detail->content ?? old('content'); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($detail->type_question == 'nhap_dap_an_dang_bang'): ?>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Demo'); ?></label>
                                                    <div class="d-flex-wap">
                                                        <div class="w-150">
                                                            <p class="box_center font-weight-bold h-60 bd-b">Person</p>
                                                            <p class="box_center font-weight-bold h-60">
                                                                Lösung</p>
                                                        </div>
                                                        <div class="w-150 bd-l">
                                                            <p class="box_center flex-column h-60 bd-b">
                                                                <input type="text" class="form-control text-center w-75"
                                                                    name="json_params[demo_question]"
                                                                    value="<?php echo e($detail->json_params->demo_question ?? ''); ?>"
                                                                    placeholder="Mẫu câu">
                                                            </p>
                                                            <p class="box_center h-60">
                                                                <input type="text"
                                                                    class="form-control text-center w-75"
                                                                    name="json_params[demo_answer]"
                                                                    value="<?php echo e($detail->json_params->demo_answer ?? ''); ?>"
                                                                    placeholder="Mẫu đáp án">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <button class="btn btn-primary mb-2 add_question_topic" style="margin-bottom: 10px;" type="button"><i
                class="fa fa-plus"></i>
            Thêm câu hỏi và đáp án cho phần này</button>
        <div class="row">
            <div class="col-md-12">
                <h4 style="padding-bottom:10px;">Danh sách câu hỏi</h4>

                <?php if(isset($detail->exam_questions) && count($detail->exam_questions) > 0): ?>
                    <?php $__currentLoopData = $detail->exam_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php switch($question_item->is_type):
                            case ('chon_dap_an'): ?>
                                <div class="box box-primary box-question-item">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Câu hỏi <?php echo e($loop->index + 1); ?></h3>
                                        <div class="box-tools pull-right">
                                            <form action="<?php echo e(route('hv_exam_questions.destroy', $question_item->id)); ?>"
                                                onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm ">Xóa câu hỏi</button>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="<?php echo e(route('hv_exam_questions.update', $question_item->id)); ?>"
                                        onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="box-body">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-12 textarea-question">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Câu hỏi'); ?></label>
                                                            <textarea id="question_<?php echo e($question_item->id); ?>" name="question" required class="form-control input-question"
                                                                cols="30" rows="3" placeholder="Nhập câu hỏi..."><?php echo e($question_item->question); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số điểm'); ?></label>
                                                            <input type="number" name="point" class="form-control"
                                                                min="0" value="<?php echo e($question_item->point ?? 0); ?>"
                                                                placeholder="Số điểm cho câu hỏi này">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 box_answers">
                                                        <div class="tab-content">
                                                            <div class="form-group ">
                                                                <label>Đáp án:</label>
                                                                <div class="more_answer">
                                                                    <?php if(isset($question_item->exam_answers) && count($question_item->exam_answers) > 0): ?>
                                                                        <?php $__currentLoopData = $question_item->exam_answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_answer => $item_answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <div class="d-flex-wap item_answer">
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control"
                                                                                            name="answer[<?php echo e($key_answer); ?>][value]"
                                                                                            placeholder="Đáp án"
                                                                                            value="<?php echo e($item_answer->answer ?? ''); ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <input type="checkbox" class="check_answer"
                                                                                        <?php echo e($item_answer->correct_answer == 1 ? 'checked' : ''); ?>

                                                                                        name="answer[<?php echo e($key_answer); ?>][boolean]"
                                                                                        value="<?php echo e($item_answer->correct_answer); ?>"
                                                                                        onchange="updateCheckboxValue(this)">
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <span onclick="delete_answer(this)"
                                                                                        class="input-group-btn">
                                                                                        <a class="btn btn-danger">
                                                                                            <i class="fa fa-trash"></i> Xóa </a>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <button class="form-group btn btn-primary mb-2" type="button"
                                                                onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                                                <?php echo app('translator')->get('Thêm câu trả lời'); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                                data-id ="<?php echo e($question_item->id); ?>">
                                                <i class="fa fa-floppy-o"></i>
                                                <?php echo app('translator')->get('Save'); ?></button>
                                        </div>
                                    </form>
                                </div>
                            <?php break; ?>

                            <?php default: ?>
                                
                                <div class="box box-primary box-question-item">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Câu hỏi <?php echo e($loop->index + 1); ?></h3>
                                        <div class="box-tools pull-right">
                                            <form action="<?php echo e(route('hv_exam_questions.destroy', $question_item->id)); ?>"
                                                onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm ">Xóa câu hỏi</button>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="<?php echo e(route('hv_exam_questions.update', $question_item->id)); ?>"
                                        onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="box-body">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-12 textarea-question">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Câu hỏi'); ?></label>
                                                            <textarea id="question_<?php echo e($question_item->id); ?>" required name="question" class="form-control input-question"
                                                                cols="30" rows="3" placeholder="Nhập câu hỏi..."><?php echo e($question_item->question ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số điểm'); ?></label>
                                                            <input type="number" name="point" class="form-control"
                                                                min="0" value="<?php echo e($question_item->point ?? 0); ?>"
                                                                placeholder="Số điểm cho câu hỏi này">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="tab-content fill ">
                                                            <div class="tab-pane active ">
                                                                <label>Đáp án:</label>
                                                                <div class="d-flex-wap list_answer_fill">
                                                                    <div class="col-md-3 more_answer pl-0">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" required
                                                                                name="answer"
                                                                                value="<?php echo e($question_item->exam_answers->first()->answer ?? ''); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                                data-id ="<?php echo e($question_item->id); ?>">
                                                <i class="fa fa-floppy-o"></i>
                                                <?php echo app('translator')->get('Save'); ?></button>
                                        </div>
                                    </form>
                                </div>
                            <?php break; ?>
                        <?php endswitch; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
        <button class="btn btn-primary mb-2 add_question_topic mt-15" style="margin-bottom: 10px;" type="button"><i
                class="fa fa-plus"></i>
            Thêm câu hỏi và đáp án cho phần này</button>
    </section>
    <div class="modal fade bd-modal-lg " data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Thêm mới câu hỏi
                        </h4>
                    </div>
                    <form action="<?php echo e(route('hv_exam_questions.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id_topic" value="<?php echo e($detail->id); ?>">
                        <input type="hidden" name="is_type" value="<?php echo e($detail->type_question); ?>">
                        <div class="modal-body">
                            <div class="box box-primary box-question-item">
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Câu hỏi'); ?></label>
                                                    <textarea id="question_textarea" class="form-control input-question" name="question" cols="30" rows="1"
                                                        placeholder="Nhập câu hỏi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Số điểm'); ?></label>
                                                    <input type="number" name="point" class="form-control"
                                                        min="0" value="<?php echo e($question_item->point ?? 0); ?>"
                                                        placeholder="Số điểm cho câu hỏi này">
                                                </div>
                                            </div>
                                            <div class="col-md-12 box_answers">
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                            <button class="btn btn-primary">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        CKEDITOR.replace('content_vi', ck_options);
        CKEDITOR.replace('question_textarea', ck_options);
        var list_question = <?php echo json_encode($detail->exam_questions ?? [], 15, 512) ?>;
        list_question.forEach(function(question_item) {
            CKEDITOR.replace(`question_${question_item.id}`, ck_options);
        });


        $(function() {
            $('.add_question_topic').click(function() {
                var _type = $('.type_question').val();
                var _html_answers = '';
                switch (_type) {
                    case "chon_dap_an":
                        _html_answers += `
                        <div class="tab-content">
                            <div class="form-group ">
                                <label>Đáp án:</label>
                                    <div class="more_answer">
                                        <div class="d-flex-wap item_answer">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="answer[1][value]" placeholder="Đáp án"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <input type="checkbox" class="check_answer" name="answer[1][boolean]" value="0"
                                                    onchange="updateCheckboxValue(this)">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <button class="form-group btn btn-primary mb-2" type="button" onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                <?php echo app('translator')->get('Thêm câu trả lời'); ?></button>
                        </div>
                        `;
                        break;
                    default:
                        // Nhập 1 đáp án đúng
                        _html_answers += `
                        <div class="col-md-3 pl-0">
                            <div class="form-group">
                                <label>Đáp án:</label>
                                <input type="text" name="answer"
                                    class="form-control" required placeholder="Đáp án" value="">
                            </div>
                        </div>
                        `;
                        break;
                }
                $('.modal-body .box_answers').html(_html_answers);
                $('.bd-modal-lg').modal('show');
            });
            $('.change_type').change(function() {
                var _type = $(this).val();
                var _html_answers = '';
                switch (_type) {
                    case "chon_dap_an":
                        _html_answers += `
                        <div class="tab-content">
                            <div class="form-group ">
                                <label>Đáp án:</label>
                                    <div class="more_answer">
                                        <div class="d-flex-wap item_answer">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="answer[1][value]" placeholder="Đáp án"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <input type="checkbox" class="check_answer" name="answer[1][boolean]" value="0"
                                                    onchange="updateCheckboxValue(this)">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <button class="form-group btn btn-primary mb-2" type="button" onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                <?php echo app('translator')->get('Thêm câu trả lời'); ?></button>
                        </div>
                        `;
                        break;
                    default:
                        // Nhập 1 đáp án đúng
                        _html_answers += `
                        <div class="col-md-3 pl-0">
                            <div class="form-group">
                                <label>Đáp án:</label>
                                <input type="text" name="answer"
                                    class="form-control" required placeholder="Đáp án" value="">
                            </div>
                        </div>
                        `;
                        break;
                }

            })
        });

        function add_answer_choice(th) {
            var currentTime = $.now();
            var _html = `
                        <div class="d-flex-wap item_answer">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input name="answer[` + currentTime + `][value]" type="text" class="form-control" placeholder="Đáp án"
                                     value="">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" class="check_answer" name="answer[` + currentTime + `][boolean]" value="0"  onchange="updateCheckboxValue(this) ">
                            </div>
                            <div class="col-md-1">
                                <span onclick="delete_answer(this)" class="input-group-btn">
                                        <a class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Xóa </a>
                                    </span>
                            </div>
                        </div>`;
            $(th).parents('.box_answers').find('.more_answer').append(_html);
        }

        function delete_answer(th) {
            $(th).parents('.item_answer').remove();
        }

        function updateCheckboxValue(th) {
            $('.check_answer').prop('checked', false).val(0);
            $(th).prop('checked', true).val(1);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_topic/edit.blade.php ENDPATH**/ ?>