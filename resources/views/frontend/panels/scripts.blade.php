<!-- Dependency Scripts -->
<script src="{{ asset('themes/frontend/dwn/js/vendor/jquery-2.2.4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
    integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="{{ asset('themes/frontend/dwn/js/vendor/bootstrap.min.js') }}"></script>
{{-- <script src="{{ asset('themes/frontend/dwn/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/jquery.magnific-popup.min.js') }}"></script>
<script script src="{{ asset('themes/frontend/dwn/js/parallax.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/jquery.sticky.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/hexagons.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/waypoints.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/main.js') }}"></script> --}}
<!-- Site Scripts -->

<!-- New Scripts -->
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script> --}}
<script src="{{ asset('themes/frontend/education/plugins/greensock/TweenMax.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/greensock/TimelineMax.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/greensock/animation.gsap.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/greensock/ScrollToPlugin.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/progressbar/progressbar.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/plugins/parallax-js-master/parallax.min.js') }}"></script>
<script src="{{ asset('themes/frontend/education/js/custom.js') }}"></script>

<script src="{{ asset('themes/frontend/dwn/js/lazysizes.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dwn/js/sweetalert2.all.min.js') }}"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    (function($) {
        // Form ajax default
        $(".form_ajax").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            form.find('.btn_submit').html("@lang('Submitting')...")
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    form[0].reset();
                    location.reload();
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.errors;
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                }
            });
        });

        $("#login_form").submit(function(e) {
            e.preventDefault();
            $(".login_result .alert").text("@lang('Đang xử lý...')");
            $(".login_result").removeClass('d-none');
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    form[0].reset();
                    if (response.message === 'success') {
                        $(".login_result").addClass('d-none');
                        if (response.data.url != '') {
                            window.location.href = response.data.url;
                        } else {
                            location.reload();
                        }
                    } else {
                        $(".login_result .alert").html(response.message);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.message;
                    console.log(errors);
                    if (errors === 'CSRF token mismatch.') {
                        $(".login_result .alert").html("@lang('CSRF token mismatch.')");
                    } else if (errors === 'The given data was invalid.') {
                        $(".login_result .alert").html("@lang('The given data was invalid.')");
                    } else {
                        $(".login_result .alert").html(errors);
                    }
                }
            });
        });
        $("#signup_form").submit(function(e) {
            $(".signup_result .alert").text("@lang('Processing...')");
            $(".signup_result").removeClass('d-none');
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (response.data == 'error') {
                        var elementErrors = '';
                        elementErrors += '<p>' + response.message + '</p>';
                        $(".signup_result .alert").html(elementErrors);
                    } else {
                        form[0].reset();
                        $(".signup_result").addClass('d-none');
                        location.reload();
                    }
                },
                error: function(response) {
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        var errors = response.responseJSON.errors;
                        // Foreach and show errors to html
                        var elementErrors = '';
                        $.each(errors, function(index, item) {
                            if (item === 'CSRF token mismatch.') {
                                item = "@lang('CSRF token mismatch.')";
                            }
                            elementErrors += '<p>' + item + '</p>';
                        });
                        $(".signup_result .alert").html(elementErrors);
                    } else {
                        var errors = response.responseJSON.errors;
                        if (errors === 'CSRF token mismatch.') {
                            $(".signup_result .alert").html("@lang('CSRF token mismatch.')");
                        } else if (errors === 'The given data was invalid.') {
                            $(".signup_result .alert").html("@lang('The given data was invalid.')");
                        } else {
                            $(".signup_result .alert").html(errors);
                        }
                    }
                }
            });
        });
        $("#form_quiz").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    form[0].reset();
                    Swal.fire({
                        toast: true,
                        icon: response.data,
                        title: response.message,
                        animation: true,
                        position: 'top-center',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                    })
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                }

            });
        });

        $('.toggle-password').on('click', function() {
            var passwordField = $(this).parents('.password-wrapper').find('.single-input');
            var passwordFieldType = passwordField.attr('type');
            var eyeIcon = $(this).find('.eye-icon');
            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                eyeIcon.removeClass('fa-eye');
                eyeIcon.addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                eyeIcon.removeClass('fa-eye-slash');
                eyeIcon.addClass('fa-eye');
            }
        });
        $('.question table td').click(function() {
            $(this).addClass('bg_red');
        })

    })(jQuery);
</script>
