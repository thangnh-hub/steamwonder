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
    @if ($image_background != null)
        <style>
            .registration-area {
                background: url({{ $image_background }}) no-repeat center;
            }

            @media (max-width: 991px) {
                .registration-area {
                    padding: 60px 0px;
                    background: #7c32ff;
                }
            }
        </style>
    @endif
    <section class="registration-area">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-lg-5">
                    <div class="section-title text-left text-white">
                        <h2 class="text-white">
                            {!! $title !!}
                        </h2>
                        <p>
                            {{ $brief }}
                        </p>
                    </div>
                </div>
                <div class="offset-lg-3 col-lg-4 col-md-6">
                    <div class="course-form-section">
                        {!! $content !!}
                        <form class="course-form-area contact-page-form course-form text-right form_ajax" id="myForm"
                            action="{{ route('frontend.contact.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="is_type" value="contact">
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="@lang('Họ và tên *')" onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = '@lang('Họ và tên *')'" required>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control" id="subject" name="phone"
                                    placeholder="@lang('Số ĐT *')" onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = '@lang('Số ĐT *')'" required>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="@lang('Email *')" onfocus="this.placeholder = ''"
                                    onblur="this.placeholder = '@lang('Email *')'" required>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button class="btn text-uppercase btn_submit">{{ $url_link_title }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
