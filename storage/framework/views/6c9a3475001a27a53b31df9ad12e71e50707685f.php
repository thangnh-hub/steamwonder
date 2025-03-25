<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>

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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Sửa phiên thi'); ?></h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Trình độ'); ?> <small class="text-red">*</small></label>
                                        <select required name="id_level" class="id_level form-control select2 w-100" style="width:100%">
                                            <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                            <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id ?? ''); ?>"
                                                    <?php echo e($detail->id_level == $val->id ? 'selected' : ''); ?>>
                                                    <?php echo e($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Kỹ năng thi'); ?> <small class="text-red">*</small></label>
                                        <select required name="skill_test" class="form-control select2" style="width:100%">
                                            <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                            <?php $__currentLoopData = $skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($val); ?>"<?php echo e($detail->skill_test == $val ? 'selected' : ''); ?>>
                                                    <?php echo app('translator')->get($val); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Tổ chức'); ?></label>
                                        <select name="organization" class="form-control select2" style="width:100%">
                                            <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                            <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($val); ?>"<?php echo e($detail->organization == $val ? 'selected' : ''); ?>>
                                                    <?php echo app('translator')->get($val); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Phòng thi'); ?></label>
                                        <input type="text" name="json_params[exam_room]" class="form-control"
                                            value="<?php echo e($detail->json_params->exam_room ?? old('json_params[exam_room]')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Ngày thi'); ?> <small class="text-red">*</small></label>
                                        <input type="date" class="form-control" name="day_exam"
                                            placeholder="<?php echo app('translator')->get('Ngày thi'); ?>"
                                            value="<?php echo e($detail->day_exam ?? old('day_exam')); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Thời gian bắt đầu'); ?> <small class="text-red">*</small></label>
                                        <input type="time" class="form-control" name="start_time"
                                            placeholder="<?php echo app('translator')->get('Thời gian bắt đầu'); ?>"
                                            value="<?php echo e($detail->start_time ?? old('time_exam')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Thời gian kết thúc'); ?> <small class="text-red">*</small></label>
                                        <input type="time" class="form-control" name="end_time"
                                            placeholder="<?php echo app('translator')->get('Thời gian kết thúc'); ?>"
                                            value="<?php echo e($detail->end_time ?? old('time_exam')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Giám thị coi thi'); ?> <small class="text-red">*</small></label>
                                        <select required name="id_invigilator" class="form-control select2" style="width:100%">
                                            <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                            <?php $__currentLoopData = $list_admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"
                                                    <?php echo e($detail->id_invigilator == $val->id ? 'selected' : ''); ?>>
                                                    <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get('Người chấm thi'); ?> <small class="text-red">*</small></label>
                                        <select required name="id_grader_exam" class="form-control select2" style="width:100%">
                                            <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                            <?php $__currentLoopData = $list_admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val->id); ?>"
                                                    <?php echo e($detail->id_grader_exam == $val->id ? 'selected' : ''); ?>>
                                                    <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="padding-bottom:10px;">Tìm học viên</h4>
                                            <div style="padding-bottom: 5px">
                                                <div style="padding-left: 0px" class="col-md-6">
                                                    <select class="form-control select2 w-100" name=""
                                                        id="search_code_post">
                                                        <option value="">Danh sách lớp học...</option>
                                                        <?php $__currentLoopData = $classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($class->id); ?>">
                                                                <?php echo e($class->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                <div class="input-group col-md-6">
                                                    <input type="text" id="search_title_post"
                                                        class="form-control pull-right"
                                                        placeholder="Tên học viên, mã học viên..." autocomplete="off">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn_search">
                                                            <i class="fa fa-search"></i> Lọc
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-config-overflow box-body table-responsive no-padding">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>STT</th>
                                                            <th>Mã HV</th>
                                                            <th>Tên HV</th>
                                                            <th>Trình độ</th>
                                                            <th>Chọn</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="post_available">

                                                    </tbody>
                                                </table>
                                            </div><!-- /.box-body -->
                                        </div>
                                        <div class="col-md-7">
                                            <h4 style="padding-bottom:10px;">Danh sách học viên được chọn</h4>
                                            <table id="myTable"
                                                class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                                        <th><?php echo app('translator')->get('Mã HV'); ?></th>
                                                        <th><?php echo app('translator')->get('Tên HV'); ?></th>
                                                        <th><?php echo app('translator')->get('CCCD'); ?></th>
                                                        <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                                        <th><?php echo app('translator')->get('Trình độ'); ?></th>
                                                        <th><?php echo app('translator')->get('Khóa'); ?></th>
                                                        <th><?php echo app('translator')->get('Lớp'); ?></th>
                                                        <th><?php echo app('translator')->get('Chọn'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="post_related">
                                                    <?php if($list_student->count() > 0): ?>
                                                        <?php $__currentLoopData = $list_student; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr class="valign-middle">
                                                                <td class="order-number"></td>
                                                                <td><?php echo e($item->student->admin_code ?? ''); ?></td>
                                                                <td><?php echo e($item->student->name ?? ''); ?></td>
                                                                <td><?php echo e($item->student->json_params->cccd ?? ''); ?>

                                                                </td>
                                                                <td><?php echo e($item->student->area->name ?? ''); ?></td>
                                                                <td><?php echo e($item->level->name ?? ''); ?></td>
                                                                <td><?php echo e($item->student->course->name ?? ''); ?></td>
                                                                <td><?php echo e($item->classs->name ?? ''); ?></td>
                                                                <td><input name= "student[]" onclick="deleteStudent(this)"
                                                                        checked type="checkbox"
                                                                        value="<?php echo e($item->id_user); ?>"
                                                                        class="mr-15 related_post_item2 cursor"
                                                                        autocomplete="off"></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(function() {
            change_stt('order-number');
        });
        var _data = '';
        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let class_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            _targetHTML.html('');
            let checked_post = [];
            $('input.related_post_item2:checked').each(function() {
                checked_post.push($(this).val());
            });
            let url = "<?php echo e(route('hv_exam_session.search_student')); ?>/";
            show_loading_notification();
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
                    class_id: class_id,
                    different_id: checked_post,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        hide_loading_notification();
                        let list = response.data || null;
                        _data = response.data;
                        console.log(_data);

                        let _item = '';
                        if (list.length > 0) {
                            var _i = 0;
                            list.forEach(item => {
                                _i++;
                                _item += `
                                <tr>
                                    <td>${_i}</td>
                                    <td>${item.admin_code??""}</td>
                                    <td>${item.name??""}</td>
                                    <td>${item.level?.name ?? ""??""}</td>
                                    <td><input onchange="selected_students(this)" type="checkbox" value="${item.id}" class="mr-15 cursor" autocomplete="off"></td>
                                </tr>
                                `;
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        hide_loading_notification();
                        _targetHTML.html('<tr><td colspan="5">' + response.message +
                            '</td></tr>');
                    }

                },
                error: function(response) {
                    // Get errors
                    hide_loading_notification();
                    let errors = response.responseJSON.message;
                    _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        });

        function selected_students(th) {
            let _id = $(th).val();
            let _item = _data.find(item => item.id == _id);
            $(th).closest('tr').remove();
            let _html = '';
            if (_item) {
                _html = `
                <tr class="valign-middle">
                    <td class="order-number"></td>
                    <td>${_item.admin_code??''}</td>
                    <td>${_item.name??''}</td>
                    <td>${_item.json_params.cccd??''}</td>
                    <td>${_item.area?.name??''}</td>
                    <td>${_item.level?.name??''}</td>
                    <td>${_item.course?.name??''}</td>
                    <td>${_item.class_detal?.name??''}</td>
                    <td><input name= "student[]" onclick="deleteStudent(this)" checked type="checkbox" value="${_item.id}" class="mr-15 related_post_item2 cursor" autocomplete="off"></td>
                    </tr>
                `;
            }
            $('#post_related').append(_html);
            change_stt('order-number');

        }

        function deleteStudent(th) {
            $(th).closest('tr').remove();
        }

        function change_stt(cl) {
            let _i = 0;
            $('.' + cl).each(function() {
                _i++;
                $(this).html(_i);
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_session/edit.blade.php ENDPATH**/ ?>