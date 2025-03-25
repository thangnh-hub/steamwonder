@if ($block)
    @php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $des = $block->json_params->des->{$locale} ?? '';
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $image = $block->image != '' ? $block->image : null;
        $image_background = $block->image_background != '' ? $block->image_background : null;
        $url_link = $block->url_link != '' ? $block->url_link : '';
        $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
        // Filter all blocks by parent_id
        $block_childs = $blocks->filter(function ($item, $key) use ($block) {
            return $item->parent_id == $block->id;
        });
    @endphp
    <section class="contact-page-area section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 d-flex flex-column address-wrap">
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-home"></span>
                        </div>
                        <div class="contact-details">
                            <h5>{{ $locale == $lang_default ? $setting->address : $setting->{$locale . '-address'} ?? '' }}
                            </h5>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-phone-handset"></span>
                        </div>
                        <div class="contact-details">
                            <h5>{{ $locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? '' }}
                            </h5>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-envelope"></span>
                        </div>
                        <div class="contact-details">
                            <h5>{{ $locale == $lang_default ? $setting->email : $setting->{$locale . '-email'} ?? '' }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form class="form-area contact-form text-right form_ajax" id="myForm"
                        action="{{ route('frontend.contact.store') }}" method="post">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                @csrf
                                <input type="hidden" name="is_type" value="contact">

                                <input name="name" placeholder="@lang('Họ và tên') *" onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = '@lang('Họ và tên')*'"
                                    class="common-input mb-20 form-control" required="" type="text">

                                <input name="email" placeholder="@lang('Địa chỉ Email')*"
                                    pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$"
                                    onfocus="this.placeholder = ''" onblur="this.placeholder = '@lang('Địa chỉ Email')'"
                                    class="common-input mb-20 form-control" required="" type="email">

                                <input name="subject" placeholder="@lang('Nhập chủ đề')" onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = '@lang('Nhập chủ đề')'" class="common-input mb-20 form-control"
                                    required="" type="text">
                            </div>
                            <div class="col-lg-6 form-group">
                                <textarea class="common-textarea form-control" name="content" placeholder="@lang('Nội dung')"
                                    onfocus="this.placeholder = ''" onblur="this.placeholder = '@lang('Nội dung')'" =""></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert-msg" style="text-align: left;"></div>
                                <button class="btn" style="float: right;">@lang('Send Message')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endif
