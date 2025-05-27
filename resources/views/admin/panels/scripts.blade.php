<div id="loading-notification" class="loading-notification">
    <p>@lang('Please wait')...</p>
</div>
<!-- jQuery 3 -->
<script src="{{ asset('themes/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('themes/admin/js/jquery.validate.min.js') }}"></script>
<!-- CKEditor-->
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
{{-- <script src="//cdn.ckeditor.com/4.17.2/full/ckeditor.js"></script> --}}
<!-- ckfinder-->
{{-- <script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
<script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script> --}}
@include('ckfinder::setup')
<!-- Custom & config js -->
<script src="{{ asset('themes/admin/js/custom.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('themes/admin/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('themes/admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('themes/admin/plugins/select2/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('themes/admin/js/app.min.js') }}"></script>

<script src="{{ asset('themes/admin/plugins/nestable/jquery.nestable.min.js') }}"></script>

<script>
    $(".select2").select2();

    // Call single input
    function _lfm(th) {
        $(th).filemanager('Images', {
            prefix: route_prefix
            // prefix: '{{ route('ckfinder_browser') }}'
        });
    }

    $('.lfm').filemanager('Images', {
        prefix: '{{ route('ckfinder_browser') }}'
    });

    $('.file').filemanager('Files', {
        prefix: '{{ route('ckfinder_browser') }}'
    });

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
            url: "{{ route('get.notify') }}",
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
            url: "{{ route('get.notify') }}",
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
        let url = "{{ route('active.notify') }}";
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

        width = width || screenWidth * 0.8;
        height = height || screenHeight * 0.8;

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
</script>
