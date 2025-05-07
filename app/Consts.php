<?php

namespace App;

class Consts
{
    const PROMOTION_TYPE = [
        'percent' =>'percent',
        'fixed_amount' =>'fixed_amount',
        'add_month' =>'add_month',
    ];
    const STATUS_RECEIPT = [
        'pending' => 'pending',
        'paid' => 'paid',
        'completed' => 'completed',
    ];
    const CONDITION_TYPE = [
        'start_day_range' => 'start_day_range',
        'start_month_range' => 'start_month_range',
        'absent_days' => 'absent_days',
        'present_days' => 'present_days',
    ];
    const TYPE_POLICIES = [
        'percent' => 'percent',
        'fixed_amount' => 'fixed_amount'
    ];
    const TYPE_CLASS_STUDENT = [
        'hoc_thu' => 'hoc_thu',
        'giu_cho' => 'giu_cho',
        'dang_hoc' => 'dang_hoc',
        'tot_nghiep' => 'tot_nghiep',
    ];
    const STATUS_ACTIVE = 'active';
    const SERVICE_IS_ATTENDANCE = [
        '0' => 'Không theo điểm danh',
        '1' => 'Tính theo điểm danh',
    ];
    const SERVICE_IS_DEFAULT = [
        '0' => 'Không',
        '1' => 'Mặc đinh',
    ];
    const SERVICE_TYPE = [
        'monthly' => 'monthly',
        'yearly' => 'yearly',
        'once' => 'once',
        'auto_cancel' => 'auto_cancel',
    ];
    const STATUS_DATACRM = [
        'new' => 'new',
        'is_advise' => 'is_advise',
    ];
    const STATUS_DATACRMLOG = [
        'is_advise' => 'is_advise',
        'end_advise' => 'end_advise',
    ];
    const RESULT_DATACRMLOG = [
        'yes' => 'yes',
        'no' => 'no',
    ];
    const ROOM_TYPE = [
        'classroom' => 'classroom',
        'working room' => 'working room'
    ];

    const STATUS_LEAVE_REQUESTS = [
        'pending_confirmation' => 'pending_confirmation',
        'pending_approval' => 'pending_approval',
        'approved' => 'approved',
    ];
    const STATUS_ASSET = [
        'new' => 'new',
        'deliver' => 'deliver',
        'transfer' => 'transfer',
    ];
    const STATUS_STUDY = [
        'dang_hoc' => 'dang_hoc',
        'chuyen_di' => 'chuyen_di',
        'tot_nghiep' => 'tot_nghiep',
        'bao_luu' => 'bao_luu',
        'nghi_hoc' => 'nghi_hoc',
        'ghi_danh' => 'ghi_danh',
        'chua_di_hoc' => 'chua_di_hoc',
        'ra_truong' => 'ra_truong',
        'khac' => 'khac',
    ];
    const WAREHOUSE_TYPE_ASSET_HISTORY = [
        'xuatkho' => 'xuatkho',
        'nhapkho' => 'nhapkho',
        'dieuchuyen' => 'dieuchuyen',
        'chinhsua' => 'chinhsua',
        'kiemke' => 'kiemke',
        'thuhoi' => 'thuhoi',
    ];
    const STATE_WAREHOUSES_ASSET = [
        'new' => 'new',
        'using' => 'using',
        'unused' => 'unused',
        'miss' => 'miss',
    ];

    const STATUS_INVENTORY = [
        'Pending' => 'Pending',
        'Approve' => 'Approve',
    ];

    const WAREHOUSE_TYPE_ORDER = [
        'order' => 'order',
        'buy' => 'buy',
    ];
    const WAREHOUSE_TYPE_ENTRY = [
        'xuat_kho' => 'xuat_kho',
        'nhap_kho' => 'nhap_kho',
        'dieu_chuyen' => 'dieu_chuyen',
        'thu_hoi' => 'thu_hoi',
        'hoan_tra' => 'hoan_tra',
    ];
    const WAREHOUSE_STATUS_TRANSFER = [
        'new' => 'new',
        'received' => 'received',
        'approved' => 'approved',
    ];

    const WAREHOUSE_PRODUCT_TYPE = [
        'taisan' => 'taisan',
        'vattutieuhao' => 'vattutieuhao',
        'congcudungcu' => 'congcudungcu',
    ];
    const WAREHOUSE_ASSET_STATUS = [
        'new' => 'new',
        'old' => 'old',
        'deliver' => 'deliver',
        'transfer' => 'transfer',
        'hoan_tra' => 'hoan_tra',
    ];


    const KETOAN_XACNHAN = [
        'unpaid' => 'unpaid', //chưa thanh toán
        'collected_money' => 'collected_money', //đã thanh toán
        'liquidated' => 'liquidated', //tạm dừng học
    ];
    const TYPE_NOTIFY = [
        'point1' => 'point1',
        'point2' => 'point2',
        'absent' => 'absent',
        'late' => 'late'
    ];

    // For delete some data
    const STATUS_DELETE = 'delete';
    const APPROVE_DELETE = 'delete';
    const HISTORY_TYPE = [
        'change_status_student' => 'change_status_student',
        'change_class' => 'change_class',
        'out_class' => 'out_class',
    ];

    // user password default
    const USER_PW_DEFAULT = 'dwn@123456';
    // Loại liên hệ
    const CONTACT_TYPE = [
        'contact' => 'contact',
        'faq' => 'faq',
        'newsletter' => 'newsletter',
        'advise' => 'advise',
        'call_request' => 'call_request'
    ];

    const CONTACT_STATUS = [
        'new' => 'new',
        'processing' => 'processing',
        'processed' => 'processed',
        'cancel' => 'cancel'
    ];
    const ATTENDANCE_STATUS = [
        'attended' => 'attended',
        'absent_unexcused' => 'absent_unexcused',
        'absent_excused' => 'absent_excused'
    ];
    const CONTACT_PARENTS_METHOD = [
        'zalo' => 'zalo',
        'sms' => 'sms',
        'phone' => 'phone'
    ];

    const DAY_WEEK = [
        1 => 'Thứ Hai',
        2 => 'Thứ Ba',
        3 => 'Thứ Tư',
        4 => 'Thứ Năm',
        5 => 'Thứ Sáu',
        6 => 'Thứ Bảy',
        7 => 'Chủ Nhật'
    ];
    const DAY_WEEK_MINI = [
        1 => 'T2',
        2 => 'T3',
        3 => 'T4',
        4 => 'T5',
        5 => 'T6',
        6 => 'T7',
        7 => 'CN'
    ];
    // Status for users
    const USER_STATUS = [
        'pending' => 'pending',
        'active' => 'active',
        'deactive' => 'deactive',
        'delete' => 'delete'
    ];

    const ORDER_TYPE = [
        'product' => 'product',
        'service' => 'service',
        'courses' => 'courses'
    ];
    // Status for order
    const ORDER_STATUS = [
        '0' => 'Processing ',
        '1' => 'Completed',
        '2' => 'Cancel',
    ];
    const PAYMENT_STATUS = [
        '0' => 'Unpaid ',
        '1' => 'Paid'
    ];

    // Status for taxonomy
    const TAXONOMY_STATUS = [
        'active' => 'active',
        'deactive' => 'deactive'
    ];
    // Status for subject
    const SUBJECT_STATUS = [
        'active' => 'active',
        'deactive' => 'deactive'
    ];
    const MENU_TYPE = [
        'header' => 'Header',
        'footer' => 'Footer'
    ];
    // Style for header
    const STYLE_HEADER = [];

    // Status for taxonomy
    const POST_STATUS = [
        'published' => 'published',
        'draff' => 'draff',
        'approved' => 'approved',
    ];

    // Status for general
    const STATUS = [
        'active' => 'active',
        'deactive' => 'deactive'
    ];

    const ADMIN_TYPE = [
        'teacher' => 'teacher',
        'staff' => 'staff',
        'admission' => 'admission',
        'ketoan' => 'ketoan',
    ];

    const USER_TYPE = [
        'partner' => 'partner',
    ];

    const TEACHER_TYPE = [
        'fulltime' => 'fulltime',
        'parttime' => 'parttime'
    ];

    const GENDER = [
        'male' => 'male',
        'female' => 'female',
        'other' => 'other'
    ];

    const CONTRACT_STATUS = [
        'Đã ký' => 'Đã ký',
        'Chưa ký' => 'Chưa ký',
        'Khác' => 'Khác'
    ];

    const APPROVE = [
        '1' => 'approved',
        '0' => 'not approved'
    ];
    const APPROVE_WAREHOUSE_ORDER = [
        'not approved' => 'not approved',
        'approved' => 'approved',
        'in order_buy' => 'in order_buy',
        'out warehouse' => 'out warehouse'
    ];
    const PAYMENT_REQUEST_STATUS = [
        'new' => 'new',
        'paid' => 'paid'
    ];
    const PAYMENT_REQUEST_TYPE = [
        'thanhtoan' => 'thanhtoan',
        'hoantra' => 'hoantra'
    ];
    const APPROVE_WAREHOUSE_ORDER_BUY = [
        'not approved' => 'not approved',
        'approved' => 'approved',
        'in warehouse' => 'in warehouse'
    ];

    const PAGINATE = [
        'post' => 6,
        'product' => 9,
        'sidebar' => 4,
        'search' => 8,
        'tag' => 9,
        'notify' => 10,
    ];
    const LIMIT_TAXONOMY = [
        'post' => 3,
        'sidebar' => 4,
    ];
    const DEFAULT_PAGINATE_LIMIT = 20;

    const TITLE_BOOLEAN = [
        '1' => 'true',
        '0' => 'false'
    ];

    const TAXONOMY = [
        'post' => 'post',
        'product' => 'product',
        'tag' => 'tag',

    ];
    // Define all route for taxonomy
    const ROUTE_TAXONOMY = [
        'post' => 'category',
        'product' => 'product-category',

    ];
    // Define all route for post
    const ROUTE_POST = [
        'post' => '',
        'product' => '',
    ];

    // Tạo danh sách chức năng định tuyến để gọi khi tạo trang trong admin -> người dùng có thể tùy chọn
    const ROUTE_NAME = [
        [
            "title" => "Home Page",
            "name" => "home",
            "template" => [
                [
                    "title" => "Home Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true,
            "show_route" => true
        ],
        [
            "title" => "Product Page",
            "name" => "product.detail",
            "template" => [
                [
                    "title" => "Product Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true
        ],
        [
            "title" => "Product Category Page",
            "name" => "product.category",
            "template" => [
                [
                    "title" => "Product Category Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true
        ],
        [
            "title" => "Post Page",
            "name" => "post.detail",
            "template" => [

                [
                    "title" => "Post Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true,
        ],
        [
            "title" => "Post Category Page",
            "name" => "post.category",
            "template" => [

                [
                    "title" => "Post Category Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true
        ],
        [
            "title" => "Custom Page",
            "name" => "page",
            "template" => [
                [
                    "title" => "Page Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true,
            "show_route" => true
        ],
        [
            "title" => "Courses Page",
            "name" => "frontend.course.list",
            "template" => [
                [
                    "title" => "Courses Default",
                    "name" => "default"
                ]
            ],
            "is_config" => true

        ],
        [
            "title" => "Courses Detail",
            "name" => "frontend.course.detail",
            "template" => [
                [
                    "title" => "Courses Detail",
                    "name" => "default"
                ],
            ],
            "is_config" => true,
        ],
        [
            "title" => "Lesson Detail",
            "name" => "frontend.lesson.detail",
            "template" => [
                [
                    "title" => "Lesson Detail",
                    "name" => "default"
                ]
            ],
            "is_config" => true,
        ],
        [
            "title" => "Profile Page",
            "name" => "frontend.user",
            "template" => [
                [
                    "title" => "Profile User",
                    "name" => "default"
                ]
            ],
            "is_config" => true,
        ],

    ];
    const DEFAULT_TEACHER_TEST_LIMIT = 40;
}
