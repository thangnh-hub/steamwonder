<div id="loading-notification" class="loading-notification">
    <p><?php echo app('translator')->get('Please wait'); ?>...</p>
</div>
<!-- Dialog sẽ load nội dung từ URL -->
<div id="popupDialog" style="display: none;"></div>
<!-- jQuery 3 -->
<script src="<?php echo e(asset('themes/admin/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('themes/admin/js/jquery.validate.min.js')); ?>"></script>
<!-- CKEditor-->
<script src="<?php echo e(asset('vendor/ckeditor/ckeditor.js')); ?>"></script>
<?php echo $__env->make('ckfinder::setup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- Custom & config js -->
<script src="<?php echo e(asset('themes/admin/js/custom.js')); ?>"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo e(asset('themes/admin/js/bootstrap.min.js')); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo e(asset('themes/admin/plugins/slimScroll/jquery.slimscroll.min.js')); ?>"></script>
<!-- Select2 -->
<script src="<?php echo e(asset('themes/admin/plugins/select2/select2.full.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('themes/admin/js/app.min.js')); ?>"></script>

<script src="<?php echo e(asset('themes/admin/plugins/nestable/jquery.nestable.min.js')); ?>"></script>

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<script>
    // Call single input
    function _lfm(th) {
        $(th).filemanager('Images', {
            prefix: route_prefix
            // prefix: '<?php echo e(route('ckfinder_browser')); ?>'
        });
    }

    const filterArray = (array, fields, value) => {
        fields = Array.isArray(fields) ? fields : [fields];
        return array.filter((item) => fields.some((field) => item[field] === value));
    };

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [day, month, year].join('/');
    }
    $('.completed').on('click', function() {
        return false
    })
    $('.view_more_notify').click(function(e) {
        e.stopPropagation()
        var page = Number($('#toggle_notify').data('id'));
        var itemHtml = '';
        $.ajax({
            url: "<?php echo e(route('get.notify')); ?>",
            data: {
                page: page + 1,
            },
            type: 'GET',
            success: function(response) {
                $('#toggle_notify').attr('data-id', page + 1);
                if (response.data.rows != '') {
                    if (response.data.rows.last_page <= page + 1) {
                        $('.view_more_notify').hide();
                    }
                    $.each(response.data.rows.data, function(index, item) {
                        // Process each item in the array
                        itemHtml +=
                            '<li class="item_notify ' + (response.data.user_notify.includes(
                                item
                                .id) ? '' : 'notify') + '" data-id="' + item.id + '">';
                        itemHtml += '<a href="javascript:void(0)" title="' + item.title +
                            '">';
                        itemHtml += '<i class="fa fa-newspaper-o text-red"></i>';
                        itemHtml += item.title;
                        itemHtml += '</a></li>';
                    });
                    $('.list_notify').append(itemHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    })

    function toggleNotify() {
        var itemHtml = '';
        var page = Number($('#toggle_notify').data('id'));
        $.ajax({
            url: "<?php echo e(route('get.notify')); ?>",
            type: 'GET',
            success: function(response) {
                $('#toggle_notify').attr('data-id', 1);
                if (response.data.rows != '') {
                    if (response.data.rows.last_page <= 1) {
                        $('.view_more_notify').hide();
                    } else {
                        $('.view_more_notify').show();
                    }
                    $.each(response.data.rows.data, function(index, item) {
                        // Process each item in the array
                        itemHtml +=
                            '<li class="item_notify ' + (response.data.user_notify.includes(item
                                .id) ? '' : 'notify') + '" data-id="' + item.id + '">';
                        itemHtml += '<a href="javascript:void(0)" title="' + item.title + '">';
                        itemHtml += '<i class="fa fa-newspaper-o text-red"></i>';
                        itemHtml += item.title;
                        itemHtml += '</a></li>';
                    });
                    $('.list_notify').html(itemHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    }
    $(document).on('click', '.item_notify', function() {
        var id = $(this).data('id');
        var _this = $(this);
        let url = "<?php echo e(route('active.notify')); ?>";
        $.ajax({
            type: "GET",
            url: url,
            data: {
                id: id,
            },
            success: function(response) {
                $('.modal_notify').attr('data-id', id)
                $('.modal_notify').modal('show');
                var detail = response.data;
                $('.modal_notify').find('.title_notify').html(detail.title);
                // $('.modal_notify').find('.link_notify').attr('href', detail.link).html(detail.link);
                if (response.message == 'true') {
                    // $('.notify_read').html(Number($('.notify_read').html()) - 1);
                    _this.removeClass('notify');
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    })

    $('form').on('focus', 'input[type=number]', function(e) {
        $(this).on('wheel.disableScroll', function(e) {
            e.preventDefault()
        })
    })
    $('form').on('blur', 'input[type=number]', function(e) {
        $(this).off('wheel.disableScroll')
    })

    function formatCurrency(amount) {
        if (!amount || isNaN(amount)) return "";
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount).replace('₫', 'đ');
    }

    function show_loading_notification() {
        $('#loading-notification').css('display', 'flex');
    }

    function hide_loading_notification() {
        $('#loading-notification').css('display', 'none');
    }

    // Mở popup ở giữa
    function openCenteredPopup(url, width, height) {
        const screenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const screenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const screenWidth = window.innerWidth || document.documentElement.clientWidth || screen.width;
        const screenHeight = window.innerHeight || document.documentElement.clientHeight || screen.height;

        width = width || screenWidth * 0.9;
        height = height || screenHeight * 0.9;

        const left = screenLeft + (screenWidth - width) / 2;
        const top = screenTop + (screenHeight - height) / 2;

        window.open(
            url,
            'popupWindow',
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
        );
        console.log(width, height);
        return false; // Ngăn mở link mặc định
    }

    function openDialogReload(url, width, height) {
        const screenWidth = window.innerWidth || document.documentElement.clientWidth || screen.width;
        const screenHeight = window.innerHeight || document.documentElement.clientHeight || screen.height;
        width = width || screenWidth * 0.9;
        height = height || screenHeight * 0.9;

        $('#popupDialog').dialog({
            modal: true,
            width: width,
            height: height,
            title: "Popup Dialog",
            open: function() {
                const dialog = $(this);
                dialog.load(url, function() {
                    initPlugins(dialog);
                });
            },
            close: function() {
                location.reload();
            }
        });
        return false;
    }

    function initPlugins(scope = document) {
        $(scope).find(".select2").select2();
        $(scope).find('.lfm').filemanager('Images', {
            prefix: '<?php echo e(route('ckfinder_browser')); ?>'
        });

        $(scope).find('.file').filemanager('Files', {
            prefix: '<?php echo e(route('ckfinder_browser')); ?>'
        });
        $(scope).find('.lfm').filemanager('Images', {
            prefix: '<?php echo e(route('ckfinder_browser')); ?>'
        });
    }

    initPlugins();
</script>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/panels/scripts.blade.php ENDPATH**/ ?>