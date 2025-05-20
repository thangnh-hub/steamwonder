<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * For check roles (permission access) for each route (function_code),
 * required each route have to a name which used to the
 * check in middleware permission and this is defined in Module, Function Management
 * @author: ThangNH
 * @created_at: 2021/10/01
 * @updated_at: 2025/04/04
 */
/**---------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN USER ROLE MANAGE
 * ----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['namespace' => 'Admin'], function () {
    // Languages
    Route::get('language/{locale}', 'LanguageController@language')->name('admin.language');
    // Login
    Route::get('/login', 'LoginController@index')->name('admin.login');
    Route::post('/login', 'LoginController@login')->name('admin.login.post');
    // Update info user and change or forget password
    Route::get('forgot-password', 'AdminController@forgotPasswordForm')->name('admin.password.forgot.get');
    Route::post('forgot-password', 'AdminController@forgotPassword')->name('admin.password.forgot.post');
    Route::get('reset-password/{token}', 'AdminController@resetPasswordForm')->name('admin.password.reset.get');
    Route::post('reset-password', 'AdminController@resetPassword')->name('admin.password.reset.post');
    // Authentication middleware
    Route::group(['middleware' => ['auth:admin']], function () {
        // Logout
        Route::get('/logout', 'LoginController@logout')->name('admin.logout');
        // Dashboard
        Route::get('/', 'HomeController@index')->name('admin.home');
        // Update account
        Route::get('account', 'AdminController@changeAccountForm')->name('admin.account.change.get');
        Route::post('change-account', 'AdminController@changeAccount')->name('admin.account.change.post');

        Route::group(['middleware' => ['permission']], function () {
            Route::resources([
                'admins' => 'AdminController',
                'admin_menus' => 'AdminMenuController',
                'modules' => 'ModuleController',
                'module_functions' => 'ModuleFunctionController',
                'roles' => 'RoleController',
                'blocks' => 'BlockController',
                'block_contents' => 'BlockContentController',
                'pages' => 'PageController',
                'menus' => 'MenuController',
                'options' => 'OptionController',
                'widgets' => 'WidgetController',
                'components' => 'ComponentController',
                'component_configs' => 'ComponentConfigController',
                'widget_configs' => 'WidgetConfigController',
                'cms_taxonomys' => 'CmsTaxonomyController',
                'cms_posts' => 'CmsPostController',
                'cms_products' => 'CmsProductController',
                'product_category' => 'ProductCategoryController',
                'post_category' => 'PostCategoryController',
                'settings' => 'SettingController',
                'languages' => 'LanguageController',
                'comments' => 'CommentController',
                'contacts' => 'ContactController',
                //datdt new module
                'courses' => 'CourseController',
                'classs' => 'ClassController',
                'rooms' => 'RoomController',
                'periods' => 'PeriodController',
                'areas' => 'AreaController',
                'students' => 'StudentController',
                'staffadmissions' => 'StaffAdmissionController',
                'holiday' => 'HolidayController',
                'warehouse' => 'WareHouseController',
                'warehouse_asset' => 'WarehouseAssetController',
                'warehouse_department' => 'WarehouseDepartmentController',
                'warehouse_position' => 'WareHousePositionController',
                'warehouse_product' => 'WareHouseProductController',
                'warehouse_category_product' => 'WareHouseCategoryProductController',
                'warehouse_order_product' => 'WareHouseOrderController',
                'warehouse_order_product_buy' => 'WareHouseOrderBuyController',
                'warehouse_transfer' => 'WarehouseTransferController',
                'warehouse_recall' => 'WareHouseRecallController',
                'warehouse_inventory' => 'WereHouseInventoryController',
                // Module test GV
                'teacher_quizs' => 'TeacherQuizController',
                //đề nghị thanh toán
                'payment_request' => 'PaymentRequestController',
                // For SteamWonder
                'users' => 'UserController',
                'data_crms' => 'DataCrmController',
                'parents' => 'ParentController',
                'relationships' => 'RelationshipController',
                'service_categorys' => 'ServiceCategoryController',
                'services' => 'ServiceController',
                'service_config' => 'ServiceConfigController',
                // End for SteamWonder
                'education_ages' => 'EducationAgesController',
                'education_programs' => 'EducationProgramsController',
                'policies' => 'PoliciesController',
                'payment_cycle' => 'PaymentCycleController',
                'deductions' => 'DeductionController',
                'receipt' => 'ReceiptController',
                'attendance' => 'AttendancesController',
                'promotions' => 'PromotionController',
            ]);
            Route::get('attendance/summary_by_month/{$id}', 'AttendancesController@attendanceSummaryByMonth')->name('attendance.summary_by_month');

            // Import Student Promotion
            Route::post('import_student_promotion', 'StudentController@importStudentPromotion')->name('student.import_promotion');
            // Import Student policy
            Route::post('import_student_policy', 'StudentController@importStudentPolicy')->name('student.import_policy');
            // Import Student service
            Route::post('import_student_service', 'StudentController@importStudentService')->name('student.import_service');
            // Import Student Receipt
            Route::post('import_student_receipt', 'StudentController@importStudentReceipt')->name('student.import_receipt');
            // Import Update Balance Receipt
            Route::post('import_student_balance_receipt', 'StudentController@importStudentBalanceReceipt')->name('student.import_balance_receipt');

            // Cập nhật lại service cho học sinh và tính lại phí
            Route::post('receipt/update_student_service_and_fee', 'ReceiptController@updateStudentServiceAndFee')->name('receipt.update_student_service_and_fee');

            //CBTS
            Route::get('admissions/students', 'AdmissionStudentController@index')->name('admission.student.index');
            Route::get('admissions/students/create', 'AdmissionStudentController@create')->name('admission.student.create');
            Route::post('admissions/students/store', 'AdmissionStudentController@store')->name('admission.student.store');
            Route::get('admissions/students/show/{id}', 'AdmissionStudentController@show')->name('admission.student.show');
            Route::get('admissions/students/edit/{id}', 'AdmissionStudentController@edit')->name('admission.student.edit');
            Route::put('admissions/students/update/{id}', 'AdmissionStudentController@update')->name('admission.student.update');
            Route::delete('admissions/students/delete/{id}', 'AdmissionStudentController@destroy')->name('admission.student.destroy');

            // Import Class và StudentClass
            Route::post('import_class', 'ClassController@importClassStudent')->name('class.import_class');
            Route::post('receipt/payment/{id}', 'ReceiptController@payment')->name('receipt.payment');
            Route::post('receipt/approved/{id}', 'ReceiptController@approved')->name('receipt.approved');
            Route::post('data_crms_log_store', 'DataCrmController@storeCRMLOG')->name('data_crms_log_store');

            // --- PHẦN HỌC SINH---
            //thêm ng thân cho bé
            Route::post('student/{id}/add-parent', 'StudentController@addParent')->name('student.addParent');
            //thêm dịch vụ cho bé
            Route::post('student/{id}/add-service', 'StudentController@addService')->name('student.addService');
            //xóa mqh ng thân
            Route::delete('student-parent/{id}', 'StudentController@removeParentRelation')->name('student.removeParentRelation');
            //xóa dịch vụ của bé
            Route::get('delete_student_service', 'StudentController@deleteStudentService')->name('delete_student_service');
            //xóa TBP của bé
            Route::get('delete_student_receipt', 'StudentController@deleteStudentReceipt')->name('student.deleteReceipt');
            //chỉnh sưa dịch vụ của bé
            Route::post('update-service-note', 'StudentController@updateServiceNoteAjax')->name('student.updateService.ajax');
            Route::post('receipts_calculate', 'StudentController@calculateReceiptStudent')->name('receipt.calculStudent');
            Route::post('receipts_calculate_renew', 'StudentController@calculateReceiptStudentRenew')->name('receipt.calculateStudent.renew');
            //TÍnh toán phí đầu năm
            Route::get('student/receipt/first_year', 'StudentController@viewCalculateReceiptStudentFirstYear')->name('view_calculate_receipt_first_year');
            Route::post('student/receipt/first_year', 'StudentController@calculateReceiptStudentFirstYear')->name('calculate_receipt_first_year');


            // ------


            // ---PHẦN ADMISSSION HỌC SINH---
            Route::get('admissions/students', 'AdmissionStudentController@index')->name('admission.student.index');
            Route::get('admissions/students/create', 'AdmissionStudentController@create')->name('admission.student.create');
            Route::post('admissions/students/store', 'AdmissionStudentController@store')->name('admission.student.store');
            Route::get('admissions/students/show/{id}', 'AdmissionStudentController@show')->name('admission.student.show');
            Route::get('admissions/students/edit/{id}', 'AdmissionStudentController@edit')->name('admission.student.edit');
            Route::put('admissions/students/update/{id}', 'AdmissionStudentController@update')->name('admission.student.update');
            Route::delete('admissions/students/delete/{id}', 'AdmissionStudentController@destroy')->name('admission.student.destroy');
            Route::post('asmission/student/{id}/add-parent', 'AdmissionStudentController@addParent')->name('admission.student.addParent');
            //thêm dịch vụ cho bé
            Route::post('asmission/student/{id}/add-service', 'AdmissionStudentController@addService')->name('admission.student.addService');
            //xóa mqh ng thân
            Route::delete('asmission/student-parent/{id}', 'AdmissionStudentController@removeParentRelation')->name('admission.student.removeParentRelation');
            //xóa dịch vụ của bé
            Route::get('asmission/delete_student_service', 'AdmissionStudentController@deleteStudentService')->name('admission.delete_student_service');
            //xóa TBP của bé
            Route::get('asmission/delete_student_receipt', 'AdmissionStudentController@deleteStudentReceipt')->name('admission.student.deleteReceipt');
            //chỉnh sưa dịch vụ của bé
            Route::post('asmission/update-service-note', 'AdmissionStudentController@updateServiceNoteAjax')->name('admission.student.updateService.ajax');
            Route::post('asmission/receipts_calculate', 'AdmissionStudentController@calculateReceiptStudent')->name('admission.receipt.calculateStudent');
            Route::post('asmission/receipts_calculate_summer', 'AdmissionStudentController@calculateReceiptStudentSummer')->name('admission.receipt.calculateStudent.summer');
            Route::post('asmission_import_data_student', 'AdmissionStudentController@importDataStudent')->name('admission.data_student.import');
            // -----

            // Import nguời dùng
            Route::post('import_user', 'AdminController@importUser')->name('admin.import_user');
            //update kpi teacher
            Route::get('ajax-kpi-teacher-update', 'ReportController@AjaxkpiTeacher')->name('ajax_kpi_teacher_index');
            //ĐỀ XUẤT TS
            Route::get('warehouse_order_approve', 'WareHouseOrderController@approve')->name('warehouse_order.approve');
            Route::get('warehouse_order_buy_approve', 'WareHouseOrderBuyController@approve')->name('warehouse_order_buy.approve');
            Route::get('payment_approve', 'PaymentRequestController@approve')->name('payment.approve');
            Route::post('order_detail_store', 'WareHouseOrderController@orderDetailStore')->name('order_detail_store');
            //tổng hợp phiếu order
            Route::get('report_order', 'WareHouseOrderController@reportOrder')->name('report_order');
            //báo cáo xuất nhập tồn
            Route::get('report_order_entry_deliver', 'WareHouseController@reportOrderEntryDeliver')->name('report_order_entry_deliver');

            //Nhập kho
            Route::get('entry_warehouse', 'WareHouseEntryController@entryWarehouse')->name('entry_warehouse');
            Route::get('entry_warehouse_create', 'WareHouseEntryController@entryWarehouseCreate')->name('entry_warehouse.create');
            Route::post('entry_warehouse_store', 'WareHouseEntryController@entryWarehouseStore')->name('entry_warehouse.store');
            Route::get('entry_warehouse_edit/{id}', 'WareHouseEntryController@entryWarehouseEdit')->name('entry_warehouse.edit');
            Route::get('entry_warehouse_show/{id}', 'WareHouseEntryController@entryWarehouseShow')->name('entry_warehouse.show');
            Route::post('entry_warehouse_update/{id}', 'WareHouseEntryController@entryWarehouseUpdate')->name('entry_warehouse.update');
            Route::delete('entry_warehouse_delete/{id}', 'WareHouseEntryController@entryWarehouseDelete')->name('entry_warehouse.delete');
            Route::post('payment_request_store_by_entry', 'WareHouseEntryController@entryWarehouseStorePayment')->name('payment_request_by_entry_store');

            //xuất kho
            Route::get('deliver_warehouse', 'WareHouseDeliverController@deliverWarehouse')->name('deliver_warehouse');
            Route::get('deliver_warehouse_create', 'WareHouseDeliverController@deliverWarehouseCreate')->name('deliver_warehouse.create');
            Route::post('deliver_warehouse_store', 'WareHouseDeliverController@deliverWarehouseStore')->name('deliver_warehouse.store');
            Route::get('deliver_warehouse_edit/{id}', 'WareHouseDeliverController@deliverWarehouseEdit')->name('deliver_warehouse.edit');
            Route::get('deliver_warehouse_show/{id}', 'WareHouseDeliverController@deliverWarehouseShow')->name('deliver_warehouse.show');
            Route::post('deliver_warehouse_update/{id}', 'WareHouseDeliverController@deliverWarehouseUpdate')->name('deliver_warehouse.update');
            Route::delete('deliver_warehouse_delete/{id}', 'WareHouseDeliverController@deliverWarehouseDelete')->name('deliver_warehouse.delete');

            // xác nhận nhận đơn điều chuyển
            Route::post('transfer_warehouse_received/{id}', 'WarehouseTransferController@receivedTransfer')->name('transfer_warehouse_received_update');
            // Duyệt đơn điều chuyển
            Route::post('transfer_warehouse_approved/{id}', 'WarehouseTransferController@approvedTransfer')->name('transfer_warehouse_approved');

            Route::get('setting_theme', 'SettingController@settingTheme')->name('settings.themes');
            Route::get('setting_theme', 'SettingController@settingTheme')->name('settings.themes');
            //Import student
            Route::get('import_student_excel', 'StudentController@importStudent')->name('student.excel.import');

            // Update 19/04/2024 by ThangNH
            Route::get('/admissions/dashboard', 'StaffAdmissionController@dashboard')->name('admissions.dashboard');
            Route::get('/admissions/area', 'StaffAdmissionController@area')->name('admissions.area');
            Route::get('/admissions/student', 'StaffAdmissionController@admissions_student')->name('admissions.student');

            // Import warehouse_product (Dùng khi import bằng file Excel, chỉ IT làm)
            Route::post('warehouse-product-import', 'WareHouseProductController@importProduct')->name('warehouse_product.import_product');
            Route::post('warehouse-product-importTS', 'WareHouseProductController@importAsset')->name('warehouse_product.import_asset');
            Route::post('warehouse-assset-import', 'WareHouseEntryController@importEntry')->name('warehouse_entry.import_entry');
            // Đồng bộ tài sản
            Route::post('synchronize-warehouse-asset', 'WereHouseInventoryController@synchronizeWarehouseAsset')->name('warehouse_inventory.synchronize_product');

            // Thống kê tài sản
            Route::get('warehouse_asset_statistical', 'WarehouseAssetController@statistical')->name('warehouse_asset.statistical');
            // Trả tài sản cho nhà cung cấp
            Route::get('reimburse_warehouse_index', 'WareHouseRecallController@indexReimburse')->name('warehouse_reimburse.index');
            Route::get('reimburse_warehouse_create', 'WareHouseRecallController@createReimburse')->name('warehouse_reimburse.create');
            Route::post('reimburse_warehouse_store', 'WareHouseRecallController@storeReimburse')->name('warehouse_reimburse.store');
            Route::get('reimburse_warehouse_show/{id}', 'WareHouseRecallController@showReimburse')->name('warehouse_reimburse.show');
            // Giáo viên xác nhận đã nhận sách
            Route::post('book_distribution_teacher_confirm', 'BookDistributionController@confirmTeacher')->name('book_distribution.confirm_teacher');
            // Xác nhận đã nhận đơn order cho người đề xuất order
            Route::post('warehouse_order_product_confirm', 'WareHouseOrderController@confirmOrder')->name('warehouse_order_product.confirm');

            // Quản lý đơn xin nghỉ
            Route::get('leave_requests', 'LeaveController@indexLeaveRequest')->name('leave.request.index');
            Route::get('leave_requests/create', 'LeaveController@createLeaveRequest')->name('leave.request.create');
            Route::post('leave_requests/store', 'LeaveController@storeLeaveRequest')->name('leave.request.store');
            Route::get('leave_requests/show/{id}', 'LeaveController@showLeaveRequest')->name('leave.request.show');
            Route::get('leave_requests/edit/{id}', 'LeaveController@editLeaveRequest')->name('leave.request.edit');
            Route::post('leave_requests/update/{id}', 'LeaveController@updateLeaveRequest')->name('leave.request.update');
            Route::post('leave_requests/destroy/{id}', 'LeaveController@destroyLeaveRequest')->name('leave.request.destroy');
            Route::post('leave_requests/approve', 'LeaveController@approveLeaveRequest')->name('leave.request.approve');
            // Quản lý ngày nghỉ phép
            Route::get('leave_balances', 'LeaveController@indexLeaveBalance')->name('leave.balance.index');
            Route::get('leave_balances/edit/{id}', 'LeaveController@editLeaveBalance')->name('leave.balance.edit');
            Route::post('leave_balances/update/{id}', 'LeaveController@updateLeaveBalance')->name('leave.balance.update');
            Route::get('leave_balances/create', 'LeaveController@createLeaveBalance')->name('leave.balance.create');
            Route::post('leave_balances/store', 'LeaveController@storeLeaveBalance')->name('leave.balance.store');
        });

        Route::get('receipt_view/{id}', 'ReceiptController@viewIndex')->name('receipt.view');
        Route::get('/camera', 'CameraController@index')->name('camera');
        Route::post('/save-image', 'CameraController@saveImage')->name('save.image');
        // in hóa đơn thanh toán
        Route::get('receipt/print/{id}', 'ReceiptController@print')->name('receipt.print');
        // Cập nhật nội dung truy thu/hoàn trả của kỳ trước trong json
        Route::post('receipt_update_json_explanation/{id}', 'ReceiptController@updateJsonExplanation')->name('receipt.update_json_explanation');

        //import data crm
        Route::post('import_data_crm', 'DataCrmController@importDataCrm')->name('data_crm.import');
        Route::post('import_data_student', 'StudentController@importDataStudent')->name('data_student.import');
        //get thông tin dvu ajax
        Route::get('get_student_service_info', 'StudentController@getStudentServiceInfo')->name('get_student_service_info');
        // In phiếu đề nghị thanh toán
        Route::post('warehouse_order_product_print', 'WareHouseOrderBuyController@printPaymentRequest')->name('warehouse_order_product_buy.print_payment_request');
        // Export Thống kê số lượng tài sản
        Route::get('warehouse-asset-export-statistical', 'WarehouseAssetController@exportStatistical')->name('warehouse_asset.export_statistical');
        // View chi tiết thống kê tài sản
        Route::get('warehouse_asset_detail_statistical', 'WarehouseAssetController@viewStatistical')->name('warehouse_asset.view_statistical');
        // Kiểm kê tài sản
        Route::get('get-view-product-inventory', 'WereHouseInventoryController@getViewListProduct')->name('warehouse_inventory.get_view_list_product');

        Route::post('add-block', 'BlockContentController@addBlock')->name('frontend.add_block');
        Route::post('block_contents/update_sort', 'BlockContentController@updateSort')->name('blocks.update_sort');

        Route::get('get_block_params', 'BlockController@getBlockParams')->name('blocks.params');
        Route::get('get_block_contents_by_template', 'BlockContentController@getBlockContentsByTemplate')->name('block_contents.get_by_template');
        Route::post('block/delete', 'BlockContentController@delete')->name('block.delete');

        // Call request
        Route::get('call_request', 'ContactController@listCallRequest')->name('call_request.index');
        Route::get('call_request/{contact}', 'ContactController@showCallRequest')->name('call_request.show');
        Route::put('call_request/{contact}', 'ContactController@update')->name('call_request.update');
        Route::delete('call_request/{contact}', 'ContactController@destroy')->name('call_request.destroy');

        // For related and tags
        Route::get('search_post', 'CmsPostController@search')->name('cms_posts.search');
        Route::get('search_product', 'CmsProductController@search')->name('cms_product.search');
        Route::get('search_student', 'StudentController@search')->name('cms_student.search');

        Route::get('get_component_config', 'ComponentConfigController@getComponentConfig')->name('component.config');
        Route::post('component/update_sort', 'ComponentController@updateSort')->name('component.update_sort');
        Route::post('component/delete', 'ComponentController@delete')->name('component.delete');

        Route::post('menus/update_sort', 'MenuController@updateSort')->name('menus.update_sort');
        Route::post('menus/delete', 'MenuController@delete')->name('menus.delete');

        // language
        Route::post('languages/set-language-default', 'LanguageController@setLanguageIsDefault')->name('languages.set_default');

        Route::get('notify', 'NotifyController@index')->name('notify.index');
        Route::get('getnotify', 'NotifyController@getNotify')->name('get.notify');
        Route::get('notify_edit', 'NotifyController@edit')->name('notify.edit');
        Route::post('notify_destroy', 'NotifyController@destroy')->name('notify.destroy');
        Route::get('active_notify', 'NotifyController@activeNotify')->name('active.notify');
        // HTML to PDF
        Route::post('generate-pdf', 'PdfController@generatePDF')->name('generate_pdf');

        //warehouse
        Route::get('search_warehouse_product', 'WareHouseOrderController@search')->name('cms_warehouse_product.search');
        Route::post('dep_by_warehouse', 'WareHouseOrderController@depByWarehouse')->name('dep_by_warehouse');
        Route::post('position_by_warehouse', 'WareHouseOrderController@getPositionByWarehouse')->name('position_by_warehouse');
        Route::post('warehouse_by_area', 'WareHouseOrderController@warehouseByArea')->name('warehouse_by_area');
        Route::post('order_by_warehouse', 'WareHouseOrderBuyController@getOrderByWarehouse')->name('order_by_warehouse');
        Route::post('order_deliver_by_warehouse', 'WareHouseDeliverController@getOrderDeliverByWarehouse')->name('order_deliver_by_warehouse');

        //ajax get order detail by order
        Route::post('warehouse_order_detail_by_order', 'WareHouseEntryController@orderDetailByOrder')->name('warehouse_order_detail_by_order');
        //ajax get list id product in ordder (Lấy id sản phẩm trong đơn hàng để lấy ra tài sản của đơn hàng đó theo kho)
        Route::post('warehouse_order_detail_list_id_product_by_order', 'WareHouseDeliverController@orderDetailProductIdByOrder')->name('warehouse_order_detail_list_id_product_by_order');
        //ajax lọc sản phẩm để thu hồi
        Route::post('warehouse_filter_asset_recall', 'WareHouseRecallController@getAssetFromFilter')->name('warehouse_filter_asset_recall');
        Route::get('export-excel-report-warhouse-entry-deliver', 'WareHouseController@export')->name('export_report_warhouse_entry_deliver');

        //import decision
        Route::get('ajax_update_vat_entry_detail', 'WareHouseEntryController@updateVAT')->name('ajax_update_vat_entry_detail');
    });
    // Test teacher
    Route::get('test_teacher', 'TeacherQuizController@testTeacher')->name('test_teacher.test');
    Route::post('/next-question', 'TeacherQuizController@nextQuestion')->name('next_question');
    Route::post('/previous-question', 'TeacherQuizController@previousQuestion')->name('previous_question');
    Route::get('/result-test-teacher', 'TeacherQuizController@resultTestTeacher')->name('result_test_teacher');

    Route::get('/qr-view', 'QrController@showQr')->name('qr.show'); // route to test QR code
});
