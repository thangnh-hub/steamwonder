

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table-bordered>thead>tr>th {
            vertical-align: middle;
        }

        .show_detail .table>thead>tr {
            background-color: #758d9b;
        }

        .block_full_width {
            display: block;
            width: 100%;
            height: 100%;
        }

        .show_detail:hover,
        .td_detail:hover,
        .show_detail.active {
            background-color: #f39c12 !important;
            color: #fff;
        }

        .td_detail:hover a {
            color: #fff;
        }

        @media  print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }

            .show-print {
                display: block;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="loading-notification" class="loading-notification">
        <p><?php echo app('translator')->get('Please wait'); ?>...</p>
    </div>
    <section class="content">
        
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="form_filter" action="<?php echo e(route('warehouse_asset.statistical')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên tài sản ...'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Tên tài sản ...'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại tài sản'); ?></label>
                                <select name="product_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['product_type']) && $params['product_type'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Danh mục tài sản'); ?></label>
                                <select name="warehouse_category_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $category_product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($val->category_parent == null || $val->category_parent == ''): ?>
                                            <option value="<?php echo e($val->id); ?>"
                                                <?php echo e(isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $val->id ? 'selected' : ''); ?>>
                                                <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                            <?php $__currentLoopData = $category_product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($child->category_parent == $val->id): ?>
                                                    <option value="<?php echo e($child->id); ?>"
                                                        <?php echo e(isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $child->id ? 'selected' : ''); ?>>
                                                        - - - <?php echo app('translator')->get($child->name ?? ''); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('kho'); ?></label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="<?php echo e(route('warehouse_asset.statistical')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning " onclick="window.print()"><i
                                            class="fa fa-print"></i>
                                        <?php echo app('translator')->get('In danh sách'); ?></button>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="<?php echo e(route('warehouse_asset.export_statistical')); ?>"><i class="fa fa-file-excel-o"
                                            aria-hidden="true"></i>
                                        <?php echo app('translator')->get('Export'); ?></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header hide-print">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
            </div>
            <div class="box-body table-responsive box-alert">
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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center"><?php echo app('translator')->get('STT'); ?></th>
                            <th rowspan="2"><?php echo app('translator')->get('Mã tài sản'); ?></th>
                            <th rowspan="2"><?php echo app('translator')->get('Tên tài sản'); ?></th>
                            <th rowspan="2"><?php echo app('translator')->get('Loại tài sản'); ?></th>
                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center" colspan="<?php echo e(count($area->warehouse)); ?>"><?php echo e($area->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="2" class="text-center"><?php echo app('translator')->get('Tổng'); ?></th>
                        </tr>
                        <tr>
                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $area->warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center"><?php echo e($warehouse->name); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th class="text-center"><?php echo app('translator')->get('Trong kho'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('Đ.Sử dụng'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="valign-middle">
                                <td>
                                    <?php echo e($loop->index + 1); ?>

                                </td>
                                <td>
                                    <?php echo e($row->product_code ?? ''); ?>

                                </td>
                                <td>
                                    <?php echo e($row->name ?? ''); ?>

                                </td>
                                <td>
                                    <?php echo app('translator')->get($row->product_type ?? ''); ?>
                                </td>
                                <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $area->warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="position-relative text-center show_detail cursor"
                                            data-id="<?php echo e($warehouse->id); ?>" data-product = "<?php echo e($row->product_id); ?>"
                                            title="<?php echo app('translator')->get('Chi tiết'); ?>">
                                            <?php echo e($row->warehouse[$warehouse->id]['total'] ?? 0); ?>

                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="text-center">
                                    <?php echo e($row->total_warehouse_new); ?>

                                </td>
                                <td class="text-center">
                                    <?php echo e($row->total_warehouse_using); ?>

                                </td>
                            </tr>
                            <tr class="tr_detail tr_department_<?php echo e($row->product_id); ?>"></tr>
                            <tr class="tr_detail tr_position_<?php echo e($row->product_id); ?>"></tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.show_detail').on('click', function() {
                var _this = $(this);
                var _colspan = '<?php echo e($count_warehouse); ?>';
                // Lấy val()
                var warehouse = _this.data('id'); // Lấy ID kho
                var product = _this.data('product'); // Lấy ID sản phẩm
                // Box view
                var _view_department = $('.tr_department_' + product);
                var _view_position = $('.tr_position_' + product);
                _view_department.html('');
                _view_position.html('');
                // check active
                if (_this.hasClass('active')) {
                    _this.removeClass('active')
                    return;
                }
                // Thêm active và bỏ active cùng tr
                _this.parents('tr').find('.show_detail').removeClass('active');
                _this.addClass('active');
                // Gọi ajax
                var url = "<?php echo e(route('warehouse_asset.view_statistical')); ?>";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        warehouse_id: warehouse,
                        product_id: product,
                        colspan: _colspan,
                    },
                    success: function(response) {
                        _view_department.html(response.data.view_department);
                        _view_position.html(response.data.view_position);
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        alert(errors);
                    }
                });
            });

            $('.btn_export').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                show_loading_notification()
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
                            a.download = 'Thong_ke_tai_san.xlsx';
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
                        hide_loading_notification()
                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_asset/statistical.blade.php ENDPATH**/ ?>