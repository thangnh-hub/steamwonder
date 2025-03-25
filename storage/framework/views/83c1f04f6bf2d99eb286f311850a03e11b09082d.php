<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .ml-5 {
            margin-left: 5rem;
        }

        .mt-4 {
            margin-top: 4rem;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Kết quả: '); ?>
                                <?php echo e($row->student->name ?? ''); ?>-<?php echo e($row->student->admin_code ?? ''); ?>


                            ---  Số điểm: <?php echo e($row->score ?? ''); ?>/100
                            </h3>
                        </div>
                        <div class="box-body">
                            <?php
                                $stt_question = 0;
                            ?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $is_type => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p style="width:100%">
                                    <strong>Teil <?php echo e($is_type); ?>:
                                        <?php echo e($option['content_option'] ?? ''); ?></strong>
                                </p>
                                <?php $__currentLoopData = $option['topic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="more_question mt-3 ml-5">
                                        <?php echo $topic->content; ?>

                                    </div>
                                    <?php if(!empty($topic->audio)): ?>
                                        <audio class="audio" src="<?php echo e($topic->audio ?? ''); ?>" controls
                                            controlslist="nodownload noremoteplayback" type="audio/mp3">
                                        </audio>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $topic->exam_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $stt_question++;
                                        ?>
                                        <?php switch($row->is_type):
                                            case ('nhap_dap_an'): ?>
                                                <div class="tab-pane active mt-4 ml-5">
                                                    <div class="content_answer" style="margin-bottom: 15px">
                                                        <p>Câu <?php echo e($stt_question); ?>:
                                                            <?php echo $row->question; ?>

                                                        </p>
                                                    </div>
                                                    <div class=" more_answer">
                                                        <label for="">Đáp án:</label>
                                                        <input type="text" class="form-control"
                                                            name="answer[<?php echo e($val->id); ?>][<?php echo e($row->id); ?>]"
                                                            value="<?php echo e($his_answer->{$val->id}->{$row->id} ?? ''); ?>">
                                                    </div>
                                                </div>
                                            <?php break; ?>

                                            <?php case ('chon_dap_an'): ?>
                                                <div class="tab-pane active mt-4 ml-5">
                                                    <div class="content_answer" style="margin-bottom: 15px">
                                                        <p> <strong class="mr-2"><?php echo e($stt_question); ?>:</strong>
                                                            <?php echo $row->question; ?>

                                                        </p>
                                                    </div>
                                                    <?php $__currentLoopData = $row->exam_answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex-wap more_answer">
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="form-group input_text <?php echo e(in_array($answer->id, $arr_correct_answer) ? 'bg-success' : ''); ?>">
                                                                    <input type="radio" disabled
                                                                        <?php echo e(isset($his_answer->{$topic->id}->{$row->id}) && $his_answer->{$topic->id}->{$row->id} == $answer->answer ? 'checked' : ''); ?>

                                                                        value="<?php echo e($answer->answer ?? ''); ?>">
                                                                    <label
                                                                        for="text_answer_<?php echo e($row->id . '_' . $k); ?>"><?php echo e(old('answer') ?? ($answer->answer ?? '')); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php break; ?>

                                            <?php default: ?>
                                        <?php endswitch; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_result/show.blade.php ENDPATH**/ ?>