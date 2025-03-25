<div class="col-lg-4 my-account">
    <div class="sidebar sticky-sidebar">
        <div class="profile">
            {{-- <div class="image d-none"><img src="" alt=""></div> --}}
            <div class="body">
                <div class="title">
                    <span class="user-name">{{ $detail->name }}</span>
                </div>
            </div>
        </div>

        <div class="sidebar_links my-0 px-0 py-0">
            <ul>
                <li class="{{url()->current() == route('frontend.user') ?'active':''}}">
                    <a href="{{ route('frontend.user') }}">
                        <i class="fa fa-user mr-1" aria-hidden="true"></i>
                        @lang('Thông tin cá nhân')
                    </a>
                </li>
                <li class="{{url()->current() == route('frontend.user.course') ?'active':''}}">
                    <a href="{{ route('frontend.user.course') }}">
                        <i class="fa fa-graduation-cap mr-1" aria-hidden="true"></i>
                        @lang('Khóa học đã đăng ký')
                    </a>
                </li>
                <li class="{{url()->current() == '' ?'active':''}}">
                    <a href="#">
                        <i class="fa fa-list-alt mr-1" aria-hidden="true"></i>
                        @lang('Báo cáo học tập')
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.logout') }}">
                        <i class="fa fa-sign-out mr-1" aria-hidden="true"></i>
                        @lang('Đăng xuất')
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>
