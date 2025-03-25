<?php

namespace App;

class Consts
{
    const URL_HOCVIEN ='https://daotao.dwn.com.vn/cvs/';
    const CV_TYPE = [
        'default' => 'default',
    ];
    const GIFT_TYPE = [
        'hocvien' => 'hocvien',
    ];
    const GIFT_STATUS = [
        'danhan' => 'danhan',
        'daxuat' => 'daxuat',
    ];

    const CV_STEP = [
        'profile' => 'profile',// Thông tin cơ bản
        'learning_process' => 'learning_process', //Quá trình học tập
        'experience' => 'experience', // Kinh nghiệm làm việc
        'qualification' => 'qualification', // Trình độ và kỹ năng
        'hobby' => 'hobby', // Sở thích, phẩm chất
        'upload_image' => 'upload_image', // Ảnh
        'review_cv' => 'review_cv', // Review
    ];
    const CV_MARITAL = [
        'ledig' => 'ledig',
        'verheiratet' => 'verheiratet',
        'verwittet' => 'verwittet',
        'geschieden' => 'geschieden',
    ];
    const CV_COMPANY_POSITION = [
        'Aushilfe' => 'Aushilfe',
        'Praktikant' => 'Praktikant',
        'Angestellte' => 'Angestellte',
        'Teamleiter' => 'Teamleiter',
        'Supervisor' => 'Supervisor',
        'Manager' => 'Manager',
        'Servicekraft' => 'Servicekraft',
        'Verkäuferin' => 'Verkäuferin',
        'Küchenhilfe' => 'Küchenhilfe',
        'Rezeptionistin' => 'Rezeptionistin',
    ];
    const CV_GERMANY_LEVEL = [
        'Anfänger (A1)' => 'Anfänger (A1)',
        'Grundlegende Kenntnisse (A2)' => 'Grundlegende Kenntnisse (A2)',
        'Fortgeschrittene Sprachverwendung (B1)' => 'Fortgeschrittene Sprachverwendung (B1)',
        'Selbständige Sprachverwendung (B2)' => 'Selbständige Sprachverwendung (B2)',
        'Fachkundige Sprachkenntnisse (C1)' => 'Fachkundige Sprachkenntnisse (C1)',
        'Annähernd muttersprachliche Kenntnisse (C2)' => 'Annähernd muttersprachliche Kenntnisse (C2)',
    ];
    const CV_ENGLISH_LEVEL = [
        'Anfänger (A1)' => 'Anfänger (A1)',
        'Grundlegende Kenntnisse (A2)' => 'Grundlegende Kenntnisse (A2)',
        'Fortgeschrittene Sprachverwendung (B1)' => 'Fortgeschrittene Sprachverwendung (B1)',
        'Selbständige Sprachverwendung (B2)' => 'Selbständige Sprachverwendung (B2)',
        'Fachkundige Sprachkenntnisse (C1)' => 'Fachkundige Sprachkenntnisse (C1)',
        'Annähernd muttersprachliche Kenntnisse (C2)' => 'Annähernd muttersprachliche Kenntnisse (C2)',
    ];
    const CV_LANGUAGE = [
        'Chinesisch' => 'Chinesisch',
        'Japanisch' => 'Japanisch',
        'Koreanisch' => 'Koreanisch',
        'Französisch' => 'Französisch',
        'Spanisch' => 'Spanisch',
        'Russisch' => 'Russisch',
        'Italienisch' => 'Italienisch',
        'Thailändisch' => 'Thailändisch',
    ];

    //Phẩm chất, điểm mạnh
    const CV_QUALITY = [
        'Kommunikationsfreude' => 'Kommunikationsfreude',
        'Kreativität' => 'Kreativität',
        'Teamarbeit' => 'Teamarbeit',
        'Toleranz' => 'Toleranz',
        'Organisationstalent' => 'Organisationstalent',
        'Verantwortungsbewusstsein' => 'Verantwortungsbewusstsein',
        'Flexibilität' => 'Flexibilität',
        'Belastbarkeit' => 'Belastbarkeit',
        'Zuverlässigkeit' => 'Zuverlässigkeit',
        'Selbstbewusstsein' => 'Selbstbewusstsein',
        'Zeitmanagement' => 'Zeitmanagement',
        'Konfliktlösungskompetenz' => 'Konfliktlösungskompetenz',
        'Zielorientierung' => 'Zielorientierung',
        'Empathie' => 'Empathie',
        'Einfühlungsvermögen' => 'Einfühlungsvermögen',
        'Lernbereitschaft' => 'Lernbereitschaft',
        'Innovationsfreude' => 'Innovationsfreude',
        'Durchsetzungsvermögen' => 'Durchsetzungsvermögen',
        'Offenheit für Veränderungen' => 'Offenheit für Veränderungen',
    ];
    const CV_HOBBY = [
        'an Außenaktivität teilnehmen' => 'an Außenaktivität teilnehmen',
        'Freiwillige Arbeiten' => 'Freiwillige Arbeiten',
        'Kriminalgeschichten lesen' => 'Kriminalgeschichten lesen',
        'Romane lesen' => 'Romane lesen',
        'Fashion Zeitschriften lesen' => 'Fashion Zeitschriften lesen',
        'Pop Musik hören' => 'Pop Musik hören',
        'Fußball spielen' => 'Fußball spielen',
        'Schach spielen' => 'Schach spielen',
        'Badminton spielen' => 'Badminton spielen',
        'Basketball spielen' => 'Basketball spielen',
        'Gym treiben' => 'Gym treiben',
        'Camping' => 'Camping',
        'Fotografieren' => 'Fotografieren',
        'Reisen' => 'Reisen',
        'Klavier spielen' => 'Klavier spielen',
        'Musik komponieren' => 'Musik komponieren',
        'Geige spielen' => 'Geige spielen',
        'Gitarre spielen' => 'Gitarre spielen',
        'Flöte spielen' => 'Flöte spielen',
        'Gartenarbeit' => 'Gartenarbeit',
        'Film- oder Serienbetrachtung' => 'Film- oder Serienbetrachtung',
        'Münzensammlung' => 'Münzensammlung',
        'Yoga / Mediation' => 'Yoga / Mediation',
        'Boxen' => 'Boxen',
        'Reiten' => 'Reiten',
        'Tauchen' => 'Tauchen',
        'Kalligraphie' => 'Kalligraphie',
        'Brettspielen' => 'Brettspielen',
        'Nähen' => 'Nähen',
        'Langstreckenlauf' => 'Langstreckenlauf',
    ];
    const TYPE_EXAM = [
        'nhap_dap_an' => 'nhap_dap_an',
        'chon_dap_an' => 'chon_dap_an',
        'nhap_dap_an_dang_bang' => 'nhap_dap_an_dang_bang',
        'form_email' => 'form_email',
    ];
    const TYPE_SKILL = [
        'listen' => 'listen',
        'speak' => 'speak',
        'read' => 'read',
        'write' => 'write',
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
    const LIST_TUONG_TAC_NHAN_XET = [
        'Thường xuyên' => 'Thường xuyên',
        'Chủ động tương tác' => 'Chủ động tương tác',
        'Thỉnh thoảng' => 'Thỉnh thoảng',
        'Ít tương tác' => 'Ít tương tác',
        'Không chủ động tương tác' => 'Không chủ động tương tác'
    ];
    const STATUS_INVENTORY = [
        'Pending' => 'Pending',
        'Approve' => 'Approve',
    ];
    const STATUS_BOOK_DISTRIBUTION = [
        'danginsach' => 'danginsach',
        'dangphatsach' => 'dangphatsach',
        'daphatsach' => 'daphatsach',
    ];
    const STATUS_BOOK_DISTRIBUTION_STUDENT = [
        'dudieukien' => 'dudieukien',
        'khongdudieukien' => 'khongdudieukien',
        'daphatsach' => 'daphatsach',
        'danhansach' => 'danhansach',
    ];

    const TYPE_REVENUE = [
        'gd1' => 'gd1',
        'gd2' => 'gd2',
        'gd3' => 'gd3',
        'gd4' => 'gd4',
        'all' => 'all',
        'other' => 'other',
    ];

    const TRANSFER_STATUS = [
        'hoc_chinh' => 'hoc_chinh',
        'chuyen_giao' => 'chuyen_giao',
        'on_tap' => 'on_tap',
        'thi_lai' => 'thi_lai',
        'day_thay' => 'day_thay',
        'buoi_thi' => 'buoi_thi'
    ];


    const TAB_LESSON = ['learning', 'ngu_phap', 'tu_vung', 'luyen_tap', 'tai_lieu'];
    const KPI_CONFIG = [
        'learn_score' => [
            'total_percent_kpi' => 0.8,
            'A1.1' => [
                'density' => 25,
                'normal' => [
                    [
                        'score_min' => 80,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 75,
                        'percent_min' => 0.8,
                        'percent_receive' => 0.2
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.9,
                        'percent_receive' => 0.1
                    ]

                ],
                'special' => [
                    [
                        'score_min' => 75,
                        'percent_min' => 0.5,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.1
                    ]
                ]
            ],
            'A1.2' => [
                'density' => 25,
                'normal' => [
                    [
                        'score_min' => 80,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 75,
                        'percent_min' => 0.8,
                        'percent_receive' => 0.2
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.9,
                        'percent_receive' => 0.1
                    ]

                ],
                'special' => [
                    [
                        'score_min' => 75,
                        'percent_min' => 0.5,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.1
                    ]
                ]
            ],
            'A2.1' => [
                'density' => 25,
                'normal' => [
                    [
                        'score_min' => 80,
                        'percent_min' => 0.6,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 75,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.2
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.8,
                        'percent_receive' => 0.1
                    ]

                ],
                'special' => [
                    [
                        'score_min' => 75,
                        'percent_min' => 0.4,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.6,
                        'percent_receive' => 0.1
                    ]
                ]
            ],
            'A2.2' => [
                'density' => 25,
                'normal' => [
                    [
                        'score_min' => 80,
                        'percent_min' => 0.6,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 75,
                        'percent_min' => 0.7,
                        'percent_receive' => 0.2
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.8,
                        'percent_receive' => 0.1
                    ]

                ],
                'special' => [
                    [
                        'score_min' => 75,
                        'percent_min' => 0.4,
                        'percent_receive' => 0.25
                    ],
                    [
                        'score_min' => 60,
                        'percent_min' => 0.6,
                        'percent_receive' => 0.1
                    ]
                ]
            ],
            'B1.1' => [
                'density' => 50,
                'normal' => [
                    [
                        'percent_min' => 0.8,
                        'percent_receive' => 0.5
                    ],
                    [
                        'percent_min' => 0.7,
                        'percent_receive' => 0.42
                    ],
                    [
                        'percent_min' => 0.6,
                        'percent_receive' => 0.35
                    ],
                    [
                        'percent_min' => 0.5,
                        'percent_receive' => 0.20
                    ]
                ],
                'special' => [
                    [
                        'percent_min' => 0.6,
                        'percent_receive' => 0.5
                    ],
                    [
                        'percent_min' => 0.5,
                        'percent_receive' => 0.42
                    ],
                    [
                        'percent_min' => 0.4,
                        'percent_receive' => 0.35
                    ],
                    [
                        'percent_min' => 0.3,
                        'percent_receive' => 0.2
                    ]
                ]
            ],
            'B1.2' => [
                'density' => 50,
                'normal' => [
                    [
                        'percent_min' => 0.8,
                        'percent_receive' => 0.5
                    ],
                    [
                        'percent_min' => 0.7,
                        'percent_receive' => 0.42
                    ],
                    [
                        'percent_min' => 0.6,
                        'percent_receive' => 0.35
                    ],
                    [
                        'percent_min' => 0.5,
                        'percent_receive' => 0.20
                    ]
                ],
                'special' => [
                    [
                        'percent_min' => 0.6,
                        'percent_receive' => 0.5
                    ],
                    [
                        'percent_min' => 0.5,
                        'percent_receive' => 0.42
                    ],
                    [
                        'percent_min' => 0.4,
                        'percent_receive' => 0.35
                    ],
                    [
                        'percent_min' => 0.3,
                        'percent_receive' => 0.2
                    ]
                ]
            ]
        ],
        'learn_process' => [
            'total_percent_kpi' => 0.1,
            'percent_delay_max' => [
                '0' => 1,
                '0.02' => 0.8,
                '0.05' => 0.5,
                '0.1' => 0.3
            ]
        ]
    ];
    const REASON_DORMITORY = [
        'going_home',
        'going_out',
        'sick',
        'other',
    ];
    const STATUS_DORMITORY_MUSTER = [
        'present' => 'present',
        'absent' => 'absent',
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
    const WAREHOUSE_DEPARTMENT_ORDER = [
        'P.ĐT' => 'P.ĐT',
        'P.KT' => 'P.KT',
        'P.CSKH' => 'P.CSKH',
        'P.HC' => 'P.HC',
    ];
    const STATUS_DORMITORY_USER = [
        'already' => 'already',
        'leave' => 'leave',
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
    const INDUSTRY_GROUP = [
        'suc_khoe' => 'Sức khỏe',
        'phuc_vu' => 'Phục vụ',
        'congnghe_kythuat' => 'Công nghệ - Kỹ thuật',
        'giao_duc' => 'Giáo dục',
        'xay_dung' => 'Xây dựng',
        'hanh_chinh' => 'Hành chính',
        'moi_gioi' => 'Môi giới',
        'noi_bo' => 'Nội bộ',
        'khac' => 'Khác',
    ];
    const STATUS_DORMITORY = [
        'empty' => 'empty',
        'already' => 'already',
        'full' => 'full',
        'deactive' => 'deactive',
    ];
    const STATUS_PAYMENT_DORMITORY = [
        'paid' => 'paid',
        'not_paid' => 'not_paid',
    ];
    const TYPE_EXAM_SESSION = [
        'test_iq' => 'test_iq',
        'test_acceptance' => 'test_acceptance',
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
    const TYPE_STUDENT_TEST = [
        'text' => 'Dạng câu hỏi văn bản',
        'math' => 'Tính toán',
        'eye_training' => 'Luyện mắt',
        'logic' => 'Câu hỏi tư duy',
        'order_table' => '[Ngôn ngữ] Sắp xếp vào bảng ',
        'connect' => '[Ngôn ngữ] Nối từ tương ứng',
        'listen' => '[Ngôn ngữ] Nghe và điền từ',
        'fill_words' => '[Ngôn ngữ] Điền từ',

    ];
    const STATUS_EXAM_USER = [
        'new' => 'new',
        'is_exam' => 'is_exam',
        'done' => 'done',
    ];
    const DORMITORY = [
        'ktx' => 'Tại KTX',
        'homestay' => 'Homestay',
        'ngoai' => 'Ở Ngoài'
    ];

    const RESULT_PROFILE = [
        'dang_cho_hop_dong' => 'Đang chờ hợp đồng',
        'da_co_hop_dong_va_dang_hoan_thien_ho_so' => 'Đã có hợp đồng và đang hoàn thiện hồ sơ',
        'da_nop_ho_so_vao_vfs' => 'Đã nộp hờ sơ vào VFS',
        'da_co_visa' => 'Đã có visa',
        'truot_visa' => 'Trượt visa',
        'cho_xuat_canh' => 'Chờ xuất cảnh',
        'da_xuat_canh' => 'Đã xuất cảnh',
        'huy_xuat_canh' => 'Hủy xuất cảnh',
        'huy_hop_dong' => 'Hủy hợp đồng',
    ];
    const CLASS_STATUS = [
        'dang_hoc' => 'Đang học',
        'hoan_thanh' => 'Hoàn thành',
        'huy' => 'Hủy'
    ];
    const type_normal_special = [
        'normal' => 'normal',
        'special' => 'special'
    ];

    const RESULT_INTERVIEW = [
        'pass' => 'pass',
        'nopass' => 'nopass',
        'absent' => 'absent',
        'cancel' => 'cancel',
    ];
    const SCHEDULE_TEST = [
        'test' => 'Test',
        'training' => 'Training',
    ];
    // Phần trăm qua bài
    const PERCENT_PASS = '90';
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
    const CLASS_TYPE = [
        'lopchinh' => 'Lớp chính',
        'lopphu' => 'Lớp học thử',
        'elearning' => 'Elearning',
    ];
    const SCHEDULE_STATUS = [
        'chuahoc' => 'chuahoc',
        'dadiemdanh' => 'dadiemdanh'
    ];
    const SCHEDULE_STATUS_COLOR = [
        'chuahoc' => 'text-red',
        'dahoc' => 'text-green',
        'dadiemdanh' => 'text-green',
    ];
    const DAY_REPEAT = [
        '1' => 'Thứ 2',
        '2' => 'Thứ 3',
        '3' => 'Thứ 4',
        '4' => 'Thứ 5',
        '5' => 'Thứ 6',
        '6' => 'Thứ 7',
        '7' => 'Chủ nhật',
    ];
    const CONTACT_STATUS = [
        'new' => 'new',
        'processing' => 'processing',
        'processed' => 'processed',
        'cancel' => 'cancel'
    ];
    const ATTENDANCE_STATUS = [
        'attendant' => 'attendant',
        'absent' => 'absent',
        'late' => 'late'
    ];
    const CONTACT_PARENTS_METHOD = [
        'zalo' => 'zalo',
        'sms' => 'sms',
        'phone' => 'phone'
    ];
    const SCORE_STATUS = [
        'passed' => 'passed',
        'not passed' => 'not passed',
        'attendance failed' => 'attendance failed'
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
    const JOB_STATUS = [
        'active' => 'active',
        'deactive' => 'deactive',
        'pending' => 'pending'
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
    const USER_CLASS_STATUS = [
        'hocmoi' => 'Học mới',
        'hoclai' => 'Học lại',
        'chuyenlop' => 'Chuyển lớp',
        'chuyencoso' => 'Chuyển cơ sở',
        'hocsongsong' => 'Học song song',
        'xinhoctiep' => 'Xin học tiếp',
    ];
    const IS_HOMEWORK = [
        '0' => 'have',
        '1' => 'not have',
        '2' => 'did - incomplete'
    ];
    // Status for ship
    const TYPE_SHIPING = [
        'price' => 'Based on products price',
        // 'weight' => 'Based on products weight',
    ];
    // Status for discoutn
    const TYPE_DISCOUNT = [
        'money' => 'Money discount ($)',
        'pecent' => 'Percentage discount (%)',
        // 'free_ship' => 'Free Shipping',
    ];
    const APPLY_FOR = [
        'all-orders' => 'All orders',
        'amount-minimum-order' => 'Order amount from',
        'specific-product' => 'Product',
        'category-product' => 'Product Category',
        'customer' => 'Customer',
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
        'student' => 'student',
        'teacher' => 'teacher',
        'staff' => 'staff',
        'admission' => 'admission',
        'diplomatic' => 'diplomatic',
        'quanlyhoso' => 'quanlyhoso',
        'ketoan' => 'ketoan',
    ];

    const USER_TYPE = [
        'partner' => 'partner',
    ];

    const TARGET_SEARCH = [
        'Azubi' => 'Azubi',
        'Fachkräfte' => 'Fachkräfte',
        'Aupair' => 'Aupair',
        'FSJ' => 'FSJ',
        'Khác' => 'Khác',
    ];

    const TEACHER_TYPE = [
        'fulltime' => 'fulltime',
        'parttime' => 'parttime'
    ];
    const EVALUATION_TYPE = [
        'by date' => 'by date',
        'by week' => 'by week',
        'by month' => 'by month',
        'entire' => 'entire'
    ];
    const GRADED = [
        'good' => 'good',
        'excellent' => 'excellent',
        'quite' => 'quite',
        'quite average' => 'quite average',
        'average' => 'average',
        'weak' => 'weak',
        'least' => 'least',
    ];
    const GENDER = [
        'male' => 'male',
        'female' => 'female',
        'other' => 'other'
    ];
    const GENDER_JOB = [
        'male/female' => 'Nam/Nữ',
        'male_focus' => 'Ưu tiên Nam',
        'female_focus' => 'Ưu tiên Nữ',
    ];
    const OPTION_ABSENT = [
        'there reason' => 'there reason',
        'no reason' => 'no reason'
    ];
    const FORMS_TRAINING = [
        'offline' => 'offline',
        'online' => 'online'
    ];
    const VERSION_DEPT = [
        'version1' => 'version1',
        'version2' => 'version2'
    ];
    const SYLLABUS_TYPE = [
        'offline' => 'offline',
        'elearning' => 'elearning'
    ];
    const CONTRACT_TYPE = [
        'Hợp đồng trọn gói' => 'Hợp đồng trọn gói',
        'Hợp đồng dịch vụ' => 'Hợp đồng dịch vụ',
        'Hợp đồng đào tạo' => 'Hợp đồng đào tạo',
        'HĐ đào tạo khóa lẻ' => 'HĐ đào tạo khóa lẻ',
        'HĐ đào tạo Combo 1' => 'HĐ đào tạo Combo 1',
        'HĐ đào tạo Combo 2' => 'HĐ đào tạo Combo 2',
        'BBTT trọn gói du học nghề' => 'BBTT trọn gói du học nghề',
        'BBTT dịch vụ du học nghề' => 'BBTT dịch vụ du học nghề',
        'BBTT trọn gói đại học/thạc sĩ' => 'BBTT trọn gói đại học/thạc sĩ',
        'BBTT dịch vụ đại học/thạc sĩ' => 'BBTT dịch vụ đại học/thạc sĩ',
        'BBTT chuyển đổi điều dưỡng 0 đồng' => 'BBTT chuyển đổi điều dưỡng 0 đồng',
        'BBTT trọn gói chuyển đổi điều dưỡng 0 đồng' => 'BBTT trọn gói chuyển đổi điều dưỡng 0 đồng',
        'BBTT dịch vụ chuyển đổi điều dưỡng 0 đồng' => 'BBTT dịch vụ chuyển đổi điều dưỡng 0 đồng',
        'Hợp đồng AUPAIR' => 'Hợp đồng AUPAIR',
        'BBTT trọn gói tuyển chọn nhân sự' => 'BBTT trọn gói tuyển chọn nhân sự',
        'Hợp đồng tài trợ học bổng' => 'Hợp đồng tài trợ học bổng',
        'BBTT thẩm định văn bằng' => 'BBTT thẩm định văn bằng'
    ];
    const CONTRACT_STATUS = [
        'Đã ký' => 'Đã ký',
        'Chưa ký' => 'Chưa ký',
        'Khác' => 'Khác'
    ];
    const STUDENT_STATUS = [
        'try learning' => 'try learning',
        'main learning' => 'main learning',
        // 'stop learning' => 'stop learning'
    ];
    const CONTRACT_PERFORMANCE_STATUS = [
        'Đang xử lý' => 'Đang xử lý',
        'Bảo lưu' => 'Bảo lưu',
        'Thanh toán' => 'Thanh toán',
        'Xuất cảnh' => 'Xuất cảnh',
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
    const TYPE_TIMEKEEPING = [
        'examine' => 'examine',
        'foreign_teachers' => 'foreign_teachers',
        'teach_supplementary_classes' => 'teach_supplementary_classes',
        'other_work' => 'other_work'
    ];
    const SCORE_TYPE = [
        'goethe' => 'goethe',
        'telc' => 'telc'
    ];
    const ranked_academic = [
        'fail' => '0-59 -> Không đạt - Học lại',
        'level_up' => '60-69 -> Đơn lên trình',
        'need_try' => '0-59 -> Cần cố gắng',
        'pass' => 'Đạt - Lên trình',
        'no_rank' => 'Không xếp loại',
        'pass_write' => 'Đỗ Modul Viết',
        'pass_speak' => 'Đỗ Modul Nói',
        'pass_listen_read' => 'Đỗ Nghe và đọc',
        'pass_full' => 'Đỗ full',
    ];
    const ranked_academic_total = [
        'fail' => '0-59 -> Không đạt - Học lại',
        'level_up' => '60-69 -> Đơn lên trình',
        'need_try' => '0-59 -> Cần cố gắng',
        'pass' => 'Đạt - Lên trình',
        'no_rank' => 'Không xếp loại',
        'pass_write' => 'Đỗ Modul Viết',
        'pass_speak' => 'Đỗ Modul Nói',
        'pass_full' => 'Đỗ full',
        'pass_listen_read' => 'Đỗ Nghe và đọc',
    ];
    const ranked_academic_color = [
        'fail' => 'btn-danger',
        'level_up' => 'btn-warning',
        'need_try' => 'btn-primary',
        'pass' => 'btn-success',
        'no_rank' => '',
        'pass_write' => 'btn-success',
        'pass_speak' => 'btn-success',
        'pass_full' => 'btn-success',
        'pass_listen_read' => 'btn-success',
    ];

    const TYPE_QUIZ = [
        'tu_vung' => 'tu_vung',
        'ngu_phap' => 'ngu_phap',
        'nghe' => 'nghe',
        'noi' => 'noi',
        'doc' => 'doc',
        'viet' => 'viet',

        'choice' => 'Lựa chọn đáp án',
        'fill' => 'Điền từ',
        'order' => 'Sắp xếp',
        'connect' => 'Nối từ',
        'answer' => 'Nhập đáp án',
        'speak' => 'Phát âm'
    ];
    const FORM_QUIZ = [
        'default' => 'default',
        'nghe' => 'nghe',
        'noi' => 'noi'
    ];
    const STYLE_QUIZ = [
        'chon_dap_an' => 'chon_dap_an',
        'dien_tu_theo_tung_cau' => 'dien_tu_theo_tung_cau',
        'dien_tu_theo_doan_van' => 'dien_tu_theo_doan_van',
        'nhap_dap_an' => 'nhap_dap_an',
        'sap_xep_cau_hoan_chinh' => 'sap_xep_cau_hoan_chinh',
        'sap_sep_dap_an_phu_hop' => 'sap_sep_dap_an_phu_hop',
        'noi_dap_an' => 'noi_dap_an'

    ];

    const DECISION_TYPE = [
        'Biên bản xử lý vi phạm' => 'Biên bản xử lý vi phạm',
        'Biên bản xử lý kỷ luật' => 'Biên bản xử lý kỷ luật',
        'Tự ôn ở nhà' => 'Tự ôn ở nhà',
        'Học lại' => 'Học lại',
        'Bảo lưu' => 'Bảo lưu',
        'Lên trình' => 'Lên trình',
        'Xuống trình' => 'Xuống trình',
        'Chuyển lớp' => 'Chuyển lớp',
        'Hoàn thành khóa học' => 'Hoàn thành khóa học',
        'Song Song' => 'Song Song',
        'Cam kết' => 'Cam kết',
        'Chuyển cơ sở đào tạo' => 'Chuyển cơ sở đào tạo'
    ];

    const ROOM_TYPE = [
        'class room' => 'class room',
        'theoretical theatre' => 'theoretical theatre',
        'Computer lab' => 'Computer lab',
        'Meeting room' => 'Meeting room'
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
