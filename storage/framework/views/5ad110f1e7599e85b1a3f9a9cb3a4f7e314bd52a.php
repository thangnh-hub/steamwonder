


<?php $__env->startSection('content'); ?>
    <?php
        $title = $detail->name ?? '';
        $image =
            isset($detail->json_params->image) && $detail->json_params->image != ''
                ? $detail->json_params->image
                : url('themes/admin/img/no_image.jpg');
        $brief = $detail->json_params->brief ?? '';
        $target = $detail->json_params->target ?? '';
        $des = $detail->json_params->des ?? '';
        $count_order = $detail->json_params->count_order ?? 0;
        $price =
            isset($detail->json_params->price) && $detail->json_params->price != ''
                ? number_format($detail->json_params->price, 0, ',', '.')
                : '';
        $slot = $detail->json_params->slot ?? '';
        $bai_hoc = $detail->lessons->count() ?? 0;
        $thoi_luong = $detail->json_params->thoi_luong ?? '---';

    ?>
    <style>
        .percent_poid {
            color: #12db31;
            margin-right: 15px
        }

        .percent_poid i {
            margin-right: 3px
        }

        .li_item_lesson .title_lesson {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        h4 a {
            font-family: 'Montserrat', sans-serif;
            color: #44425a;
            -webkit-font-smoothing: antialiased;
            -webkit-text-shadow: rgba(0, 0, 0, .01) 0 0 1px;
            text-shadow: rgba(0, 0, 0, .01) 0 0 1px;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 50%
            }
        }

        @media  screen and (max-width: 767px) {
            .li_item_lesson .title_lesson {
                width: calc(100% - 65px);
                margin-bottom: 5px;
            }
        }
    </style>

    <div class="banner-breadcrums">
        <div class="breadcrums_background parallax_background parallax-window" data-parallax="scroll"
            data-image-src="<?php echo e($setting->background_breadcrumbs); ?>" data-speed="0.8"></div>
        <div class="breadcrums_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrums_content">
                            <div class="breadcrums_title"><?php echo e($title); ?></div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a></li>
                                    <li><a href="<?php echo e(route('frontend.course.list')); ?>"><?php echo app('translator')->get('Khóa học'); ?></a></li>
                                    <li><?php echo e($title); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course_detail">
        <div class="container">
            <div class="row">
                <!-- News Posts -->
                <div class="col-lg-8">
                    <div class="intro">
                        <h3><?php echo app('translator')->get('Nội dung khóa học'); ?></h3>
                        <div class="accordions">
                            <?php if(isset($detail->lessons)): ?>
                                <?php $__currentLoopData = $detail->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        if (isset($user_auth)) {
                                            $point = App\Models\LessonUser::select('percent_point as point')
                                                ->where('lesson_id', $items->id)
                                                ->where('user_id', $user_auth->id)
                                                ->first();
                                        }
                                    ?>
                                    <div class="accordion_container">
                                        <div class="accordion d-flex flex-row align-items-center justify-content-between">
                                            <h4>
                                                <?php echo e(Str::limit($items->title, 50)); ?></h4>
                                            <div class="btn_detail d-flex align-items-center mr-5">
                                                <?php if(isset($user_auth)): ?>
                                                    <?php if(isset($point->point) && $point->point == 100): ?>
                                                        <span class="percent_poid align-items-center"> <i class="fa fa-circle"
                                                                aria-hidden="true"></i><?php echo e($point->point ?? 0); ?>%</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="accordion_panel">
                                            <?php if(isset($items->content) && $items->content != ''): ?>
                                                <div class="intro-lesson">
                                                    <h5><?php echo app('translator')->get('Nội dung buổi học'); ?></h5>
                                                    <p><?php echo nl2br($items->content ?? ''); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(isset($items->target) && $items->target != ''): ?>
                                                <div class="intro-lesson">
                                                    <h5><?php echo app('translator')->get('Mục tiêu buổi học'); ?></h5>
                                                    <p><?php echo nl2br($items->target ?? ''); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(isset($items->teacher_mission) && $items->teacher_mission != ''): ?>
                                                <div class="intro-lesson">
                                                    <h5><?php echo app('translator')->get('Nhiệm vụ giảng viên'); ?></h5>
                                                    <p><?php echo nl2br($items->teacher_mission ?? ''); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(isset($items->student_mission) && $items->student_mission != ''): ?>
                                                <div class="intro-lesson">
                                                    <h5><?php echo app('translator')->get('Nhiệm vụ học viên'); ?></h5>
                                                    <p><?php echo nl2br($items->student_mission ?? ''); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="description mt-3">
                        <h3 class="mb-3"><?php echo app('translator')->get('Mô tả khóa học'); ?></h3>
                        <?php echo $brief; ?>

                    </div>

                </div>


                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar sticky-sidebar">
                        <div class="course">
                            <div class="course_image"><img src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>"></div>
                            <div class="course_body">
                                <div class="course_header d-flex flex-row align-items-center justify-content-start">
                                    <div class="course_tag"></div>
                                    <div class="course_price ml-auto"><span><?php echo e($price); ?> đ</span></div>
                                </div>
                                <div class="course_footer d-flex align-items-center justify-content-start">
                                    <div class="mr-3">
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                        <span class="ml-1"><?php echo e($bai_hoc); ?> <?php echo app('translator')->get('bài học'); ?></span>
                                    </div>
                                    <div class="mr-3">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <span class="ml-1"><?php echo e($thoi_luong); ?></span>
                                    </div>
                                    
                                </div>
                                <div class="enroll-course text-center">
                                    <div class="button">
                                        <?php if(isset($user_auth)): ?>
                                            <?php if($order != null): ?>
                                                <?php
                                                    $arr_id_lesson_syllabus = $detail->lessons->pluck('id')->toArray();
                                                    // lấy thông tin lesson_user mới nhất của chương trình hiện tại xem đã học tới đâu
                                                    $lesson_user_detail = $lesson_user
                                                        ->filter(function ($item, $key) use ($arr_id_lesson_syllabus) {
                                                            return in_array(
                                                                $item->lesson_id,
                                                                $arr_id_lesson_syllabus,
                                                            ) &&
                                                                isset($item->json_params->tab_active) &&
                                                                count($item->json_params->tab_active) > 0;
                                                        })
                                                        ->sortByDesc('id')
                                                        ->first();
                                                    if ($lesson_user_detail) {
                                                        $tab = $lesson_user_detail->json_params->tab_active ?? [
                                                            'learning',
                                                        ];
                                                        $tab_active = end($tab);
                                                        $alias = $helpers::getRouteLessonDetail(
                                                            $detail->name,
                                                            $detail->id,
                                                            $lesson_user_detail->lesson_id,
                                                            $tab_active,
                                                        );
                                                    } else {
                                                        $alias = $helpers::getRouteLessonDetail(
                                                            $detail->name,
                                                            $detail->id,
                                                            $detail->lessons->first()->id,
                                                            'learning',
                                                        );
                                                    }
                                                ?>
                                                <a href="<?php echo e($alias); ?>" class="text-white"><?php echo app('translator')->get('Vào học'); ?>
                                                    <div class="button_arrow"><i class="fa fa-angle-right"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </a>
                                            <?php else: ?>
                                                
                                                <a href="javascript:void(0)" class="text-white" data-toggle="modal"
                                                    data-target="#couserModal"><?php echo app('translator')->get('Đăng ký ngay'); ?>
                                                    <div class="button_arrow"><i class="fa fa-angle-right"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="javascript:void(0)" class="text-white" data-toggle="modal"
                                                data-target="#loginModal"><?php echo app('translator')->get('Đăng ký ngay'); ?>
                                                <div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.lesson', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/frontend/pages/courses/detail.blade.php ENDPATH**/ ?>