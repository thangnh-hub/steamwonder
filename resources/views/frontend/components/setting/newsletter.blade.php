<div class="col-lg-4 col-md-6 single-footer-widget">
    <h4>@lang('Newsletter')</h4>
    <p>@lang('You can trust us. we only send promo offers')</p>
    <div class="form-wrap" id="">
        <form action="{{ route('frontend.contact.store') }}" method="post" class="form-inline form_ajax">
            @csrf
            <input type="hidden" name="is_type" value="contact">
            <input class="form-control" name="email" placeholder="@lang('Your Email Address')" onfocus="this.placeholder = ''"
                onblur="this.placeholder = '@lang('Your Email Address') '" required="" type="email">
            <button class="click-btn btn btn-default text-uppercase btn_submit">@lang('Subscribe')</button>
        </form>
    </div>
</div>
