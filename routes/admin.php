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

    // Forgot
    Route::get('/forgot', 'LoginController@forgot')->name('admin.forgot');
    Route::post('/forgot', 'LoginController@forgotPass')->name('admin.forgot.post');
    // Reset pass
    Route::get('/resetpass/{token}', 'LoginController@resetPass')->name('admin.resetpass');
    Route::post('/resetpass', 'LoginController@resetPassPost')->name('admin.resetpass.post');


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

        Route::post('export-excel-score', 'ScoreController@export')->name('export_score');

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
                'reviews' => 'ReviewController',
                'comments' => 'CommentController',
                'contacts' => 'ContactController',
                //datdt new module
                'subjects' => 'SubjectController',
                'levels' => 'LevelController',
                'syllabuss' => 'SyllabusController',
                'syllabuss_online' => 'SyllabusOnlineController',
                'courses' => 'CourseController',
                'classs' => 'ClassController',
                'classs_elearning' => 'ClassElearningController',
                'class_process' => 'ClassProcessController',
                'trial_classs' => 'TrialClassController',
                'rooms' => 'RoomController',
                'periods' => 'PeriodController',
                'areas' => 'AreaController',
                'fields' => 'FieldController',
                'majors' => 'MajorController',
                'entry_levels' => 'EntryLevelController',
                'decisions' => 'DecisionController',
                'students' => 'StudentController',
                'staffadmissions' => 'StaffAdmissionController',
                'evaluations' => 'EvaluationController',
                'attendances' => 'AttendanceController',
                'schedules' => 'ScheduleController',
                'scores' => 'ScoreController',
                'question_answers' => 'QuestionAnswerController',
                'holiday' => 'HolidayController',
                'ranked_academics' => 'RankAcademicController',
                'jobs' => 'JobsController',
                'user_actions' => 'UserActionsController',
                'schedule_test' => 'ScheduleTestController',
                'topic' => 'TopicController',
                'exam_session' => 'ExamSessionController',
                'exam_session_user' => 'ExamSessionUserController',
                'student_test' => 'StudentTestController',
                'history_schedule_test' => 'HistoryScheduleTestController',
                'apply_job' => 'ApplyJobController',
                'profiles' => 'ProfilesController',
                'accountant' => 'AccountantController',
                'dormitory' => 'DormitoryController',
                'certificate' => 'CertificateController',
                'timekeeping_teacher' => 'TimekeepingTeacherController',
                'partners' => 'PartnerController',
                'vocabulary' => 'VocabularyController',
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
                'accounting_debt' => 'AccountingDebtController',
                'warehouse_inventory' => 'WereHouseInventoryController',
                // Module test GV
                'teacher_quizs' => 'TeacherQuizController',
                'hv_exam_session' => 'HvExamSessionController',
                'hv_exam_topic' => 'HvExamTopicController',
                'hv_exam_questions' => 'HvExamQuestionsController',
                'hv_exam_option' => 'HvExamOptionController',
                'hv_exam_result' => 'HvExamResultController',

                //đề nghị thanh toán
                'payment_request' => 'PaymentRequestController',
            ]);
            // reset phiên thi
            Route::post('hv_exam_result_reset', 'HvExamResultController@reset')->name('hv_exam_result.reset');

            // Import HV Exam Topic
            Route::post('import-hv-exam-toppic', 'HvExamTopicController@importExamTopic')->name('hv_exam_topic.import');
            Route::get('gift-distribute', 'GiftDistributionController@index')->name('gift_distribute');
            Route::get('gift-distribute-entry', 'GiftDistributionController@indexEntry')->name('gift_distribute_entry');
            Route::post('store-history', 'GiftDistributionController@store')->name('store_history');
            Route::post('store-history-entry', 'GiftDistributionController@storeEntry')->name('store_entry');
            Route::get('list-history-gift-distribution', 'GiftDistributionController@listHistory')->name('gift_distribution.list_history');
            Route::get('list-history-gift-distribution-detail/{id}', 'GiftDistributionController@listHistoryDetail')->name('gift_distribution.detail_history');
            Route::get('list-gift-distribution-student', 'GiftDistributionController@listGiftDistributionStudent')->name('gift_distribution.list_gift_distribution_student');
            // Import HV Exam Topic
            Route::post('import-hv-exam-toppic', 'HvExamTopicController@importExamTopic')->name('hv_exam_topic.import');
            Route::get('student-reserved', 'StudentController@getReserved')->name('student.reserved');
            //update kpi teacher
            Route::get('ajax-kpi-teacher-update', 'ReportController@AjaxkpiTeacher')->name('ajax_kpi_teacher_index');
            //duyệt
            Route::get('timekeeping_teacher.approve', 'TimekeepingTeacherController@approve')->name('timekeeping_teacher.approve');
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

            //edit lesson by teacher
            Route::get('edit-class-by-teacher', 'ClassController@editByTeacher')->name('class.editByTeacher');
            Route::put('update-class-by-teacher', 'ClassController@updateByteacher')->name('class.updateByTeacher');

            //Lesson_online
            Route::get('add_lesson', 'SyllabusOnlineController@formAddLesson')->name('ajax.syllabus_online.addlesson');
            Route::post('save_lesson', 'SyllabusOnlineController@saveLession')->name('ajax.syllabus_online.savelesson');
            Route::get('show-lesson', 'SyllabusOnlineController@formShowLesson')->name('ajax.syllabus_online.showlesson');
            Route::post('update_lesson', 'SyllabusOnlineController@updateLession')->name('syllabus_online.update_lesson');

            // Báo cáo điểm thi của học viên
            Route::get('report-score-student', 'ReportController@indexReportScoreStudent')->name('report.score.student');
            Route::get('update_json_score', 'ScoreController@updateJsonScore')->name('update.json.score');
            //Báo cáo điểm danh theo ngày
            Route::get('report-attendance-day', 'ReportController@indexReportAttendanceByDay')->name('report.attendance.byday');
            Route::get('report-all-attendance-day', 'ReportController@indexReportAllAttendanceByDay')->name('report.all.attendance.byday');
            Route::get('ajax-get-attendace-by-day', 'ReportController@ajaxReportAttendanceByDay')->name('ajax.report.attendance.byday');

            //Báo cáo điểm danh theo tháng của học viên
            Route::get('report-attendance-month', 'ReportController@indexReportAttendanceByMonth')->name('report.attendance.bymonth');

            //Báo cáo chấm công giáo viên
            Route::get('report-timekeeping-teacher', 'ReportController@indexReportTimekeepingTeacher')->name('report.timekeeping.teacher');
            Route::get('report-timekeeping-calender', 'ReportController@indexReportTimekeepingCalender')->name('report.timekeeping.calender');
            Route::get('detail-report-timekeeping-teacher', 'ReportController@detailReportTimekeepingTeacher')->name('detail.report.timekeeping.teacher');

            //Báo cáo công nợ học viên
            Route::get('report-student-is-debt', 'ReportController@indexReportStudentDept')->name('report.student.is.debt');
            Route::post('import-student-is-debt', 'ReportController@storeStudentDeptImport')->name('store.studentdept.import');
            Route::get('export-student-is-debt-version1', 'ReportController@exportStudentDeptVersion1')->name('export.studentdept.version1');
            Route::get('export-student-is-debt-version2', 'ReportController@exportStudentDeptVersion2')->name('export.studentdept.version2');
            //Import excel công nợ sinh viên


            //Thống kê số lần học lại của học viên theo lớp
            Route::get('report-student-learn-again', 'ReportController@studenLearnAgain')->name('report.student.learnAgain');
            //Thống kê số lớp sắp kết thúc
            Route::get('report-class-end', 'ReportController@classEnd')->name('report.class.end');
            // Thống kê lớp học chưa có nhận xét, điểm danh
            Route::get('report-class-null', 'MultiClassNullController@multiclassNull')->name('report.class.null');
            // Export class null
            Route::get('report-class-null-export', 'MultiClassNullController@exportClassNull')->name('report.class.null.export');

            //quiz
            Route::get('list-quiz', 'SyllabusOnlineController@quizLesson')->name('quiz.index');
            Route::post('list-quiz', 'SyllabusOnlineController@quizStore')->name('quiz.store');
            Route::get('get-layout-question', 'SyllabusOnlineController@getLayoutQuestion')->name('quiz.get_layout_question');
            // Route::get('get-info-question', 'SyllabusOnlineController@getInfoQuestion')->name('quiz.get_info_question');
            Route::post('update-quiz', 'SyllabusOnlineController@quizUpdate')->name('quiz.update');


            Route::get('add_quiz', 'SyllabusOnlineController@formAddQuiz')->name('ajax.syllabus_online.addquiz');
            Route::get('edit_quiz', 'SyllabusOnlineController@formEditQuiz')->name('ajax.syllabus_online.editquiz');
            Route::delete('delete-quiz', 'SyllabusOnlineController@quizDelete')->name('quiz.delete');

            Route::get('setting_theme', 'SettingController@settingTheme')->name('settings.themes');
            Route::get('setting_theme', 'SettingController@settingTheme')->name('settings.themes');
            // dtd report ratings
            //dtd Schedule class
            Route::get('schedule_class', 'AttendanceController@ScheduleClass')->name('schedule_class.index');
            Route::get('attendances_edit', 'AttendanceController@show_attendance')->name('attendances.edit');
            Route::post('attendances_update', 'AttendanceController@update')->name('attendances.update');
            Route::post('save_attendance', 'AttendanceController@SaveAttendance')->name('attendances.save');

            //dtd Score
            Route::post('save_score', 'ScoreController@SaveScore')->name('scores.save');
            Route::post('save_score_2nd', 'ScoreController@SaveScore_2nd')->name('scores.save_2nd');
            Route::get('input-score-second', 'ScoreController@scoreSecondIndex')->name('input_score_second.index');


            Route::get('evaluations_class/index', 'EvaluationController@EvaluationClassIndex')->name('evaluations.class.index');
            Route::get('evaluations_class/show', 'EvaluationController@EvaluationClassShow')->name('evaluations.class.show');
            //dtd Evaluation class
            Route::post('save_evaluation_class', 'EvaluationController@StoreSaveEvaluation')->name('evaluations.save');
            Route::get('export-excel-evaluation', 'EvaluationController@exportEvaluation')->name('export_evaluation');
            Route::get('history_evaluation_class', 'EvaluationController@historyEvaluation')->name('evaluationclass.history');
            //Import student
            Route::get('import_student_excel', 'StudentController@importStudent')->name('student.excel.import');
            Route::get('import_student_cbts', 'StudentController@importStudent_CBTS')->name('import.student.cbts');

            // Update 19/04/2024 by ThangNH
            Route::get('/admissions/dashboard', 'StaffAdmissionController@dashboard')->name('staffadmissions.dashboard');
            Route::get('/admissions/area', 'StaffAdmissionController@area')->name('staffadmissions.area');
            // All report (19/04/2024 by ThangNH)
            Route::get('reports/student/status', 'ReportController@reportStudentStatus')->name('reports.student.status');
            Route::get('reports/student/levels', 'ReportController@reportStudentLevels')->name('reports.student.levels');
            Route::get('reports/teacher/staff', 'ReportController@reportTeacher')->name('reports.teacher');
            Route::get('reports/class/exceeds', 'ReportController@reportClassExceeds')->name('reports.class.exceeds');

            //ajax update history schedule test
            Route::get('update_history_schedule_test', 'HistoryScheduleTestController@ajaxUpdate')->name('history_schedule_test.ajax.update');

            Route::post('active_result', 'JobsController@actionRessult')->name('jobs.resultt');
            Route::get('/admissions/student', 'StaffAdmissionController@admissions_student')->name('admissions.student');
            Route::get('ajax-destroy-lesson', 'ClassController@destroyLessonAjax')->name('ajax.lessonDestroy');

            Route::post('additional_class', 'StudentController@additionalClass')->name('additional_class');
            Route::post('additional_evaluation', 'StudentController@additionalEvaluation')->name('additional_evaluation');

            //xóa ajax câu hỏi topic
            Route::get('ajax-delete-question-topic', 'StudentTestController@destroyAjax')->name('ajax.topic.destroyquestion');
            //Học viên học thử
            Route::get('trial-student', 'StudentController@trialStudent')->name('trial_student.index');
            Route::get('change-admin-code', 'StudentController@changeAdminCode')->name('trial_student.change_admin_code');
            Route::post('import-trial-student', 'StudentController@importTrialStudent')->name('trial_student.import_trial_student');

            //import lessonSyllabuss
            Route::post('lesson-syllabuss-import', 'SyllabusController@importLessonSyllabuss')->name('syllabuss.import_lesson_syllabuss');

            //cập nhật thông tin kết quả test IQ ajax
            Route::get('exam-session-reset-status', 'ExamSessionUserController@resetStatus')->name('exam_session_user.resetStatus');
            Route::get('exam-session-reset-point', 'ExamSessionUserController@resetPoint')->name('exam_session_user.resetPoint');
            Route::get('exam-session-result', 'ExamSessionUserController@examResult')->name('exam_session_user.examResult');

            // Lịch học lớp học thử
            Route::get('schedule-trial-class', 'TrialClassController@scheduleTrialClass')->name('trial_classs.schedule');
            Route::get('attendances-trial-class', 'TrialClassController@attendancesTrialClass')->name('trial_classs.attendances');

            // Ký túc xá
            Route::get('dormitory-history', 'DormitoryController@history')->name('dormitory.history');
            Route::post('dormitory-edit-history', 'DormitoryController@editHistory')->name('dormitory.edithistory');
            Route::post('dormitory-add-student', 'DormitoryController@addStudent')->name('dormitory.addstudent');
            Route::post('dormitory-edit-student', 'DormitoryController@editStudent')->name('dormitory.editstudent');
            Route::post('dormitory-import-student', 'DormitoryController@importStudent')->name('dormitory.import_student');
            Route::post('dormitory-import-dormitory', 'DormitoryController@importDormitory')->name('dormitory.import_dormitory');
            Route::get('dormitory-export-student', 'DormitoryController@exportStudent')->name('dormitory.export_student');
            Route::get('dormitory-list-student', 'DormitoryController@listStudent')->name('dormitory.liststudent');
            Route::post('dormitory-delete-student', 'DormitoryController@deleteStudent')->name('dormitory.deletestudent');
            Route::get('dormitory-list-muster', 'DormitoryController@listMuster')->name('dormitory.listmuster');
            Route::get('dormitory-get-muster', 'DormitoryController@getMuster')->name('dormitory.getmuster');
            Route::post('dormitory-update-muster', 'DormitoryController@updateMuster')->name('dormitory.updatemuster');
            Route::get('dormitory-report-muster', 'DormitoryController@reportMuster')->name('dormitory.reportmuster');
            // Trả phòng và thuê lại phòng
            Route::get('dormitory-set-checkout', 'DormitoryController@setCheckOut')->name('dormitory.setcheckout');
            Route::get('dormitory-set-checkin', 'DormitoryController@setCheckIn')->name('dormitory.setcheckin');
            // Báo cáo tổng hợp KTX
            Route::get('report-dormitory', 'DormitoryController@ReportDormitory')->name('dormitory.report');
            Route::get('report-dormitory-month', 'DormitoryController@ReportDormitoryMonth')->name('dormitory.report_month');
            Route::get('report-dormitory-export-student', 'DormitoryController@exportReportStudent')->name('dormitory.export_report_student');
            // Học viên sắp hết hạn
            Route::get('dormitory-expired-student', 'DormitoryController@expiredStudent')->name('dormitory.expired_student');
            // Danh sách học viên đăng ký ở KTX
            Route::get('dormitory-list-student-register', 'DormitoryController@listStudentRegister')->name('dormitory.liststudentregister');
            // Danh sách học viên đã nộp tiền KTX và chờ xếp vào phòng
            Route::get('dormitory-list-student-paid', 'DormitoryController@listStudentPaid')->name('dormitory.liststudentpaid');
            Route::post('dormitory-update-student-paid', 'DormitoryController@updateStudentPaid')->name('dormitory.updatestudentpaid');
            // Danh sách học viên dành cho quản sinh
            Route::get('dormitory-list-student-overseer', 'DormitoryController@listStudentOverseer')->name('dormitory.liststudentoverseer');
            Route::post('dormitory-leave-change-room', 'DormitoryController@leaveOrChangeRoom')->name('dormitory.leave_or_change_room');
            Route::get('dormitory-update-quantity', 'DormitoryController@updateQuantity')->name('dormitory.updatequantity');

            // Xóa HV học thử dành cho CBTS
            Route::get('delete-student-cbts', 'StudentController@getDeleteStudentCBTS')->name('student.cbtsdelete');
            Route::post('delete-student-cbts-post', 'StudentController@postDeleteStudentCBTS')->name('student.cbtsdelete.post');

            Route::get('kpi-teacher-index', 'ReportController@kpiTeacher')->name('kpi_teacher_index');
            Route::get('ajax-update-note-exceed', 'ReportController@ajaxUpdateExceed')->name('ajax.update.note.exceed');
            //Báo cáo lớp thiếu điểm danh
            Route::get('report-class-attendance-empty', 'ReportController@reportClassAttendanceEmpty')->name('reports.class.attendance_empty');
            // Từ vựng
            Route::post('vocabulary-import-vocabulary', 'VocabularyController@importVocabulary')->name('vocabulary.import_vocabulary');
            // Danh sách HV dành cho cskh (Để cập nhật hợp đồng)
            Route::get('list-student-cskh', 'StudentController@listStudentCSKH')->name('student.cskh');
            Route::post('update-student-cskh', 'StudentController@cskhUpdateStudent')->name('student.cskh_update');

            // Tạo và sửa Lịch dạy cho GVNN
            Route::get('gvnn-schedule-class', 'ScheduleController@listScheduleGVNN')->name('gvnn.schedules');
            Route::get('ajax-destroy-lesson-gvnn', 'ClassController@destroyLessonAjaxGVNN')->name('ajax.lessonDestroy.gvnn');
            Route::post('gvnn-created-schedule-class', 'ScheduleController@createdScheduleGVNN')->name('gvnn.createdschedules');
            // Danh sách lớp của giáo viên nước ngoài
            Route::get('classs_gvnn', 'ClassController@index_gvnn')->name('classs.gvnn');
            // Điểm danh cho GVNN
            Route::get('schedule-class-gvnn', 'AttendanceController@scheduleClassGVNN')->name('schedule_class.index_gvnn');
            Route::get('attendances-gvnn', 'AttendanceController@indexGVNN')->name('attendances.index_gvnn');
            Route::post('attendance-save-gvnn', 'AttendanceController@SaveAttendanceGVNN')->name('attendances.save_gvnn');
            Route::get('attendances-edit-gvnn', 'AttendanceController@showAttendanceGVNN')->name('attendances.edit_gvnn');
            Route::post('attendances-update-gvnn', 'AttendanceController@updateGVNN')->name('attendances.update_gvnn');

            // Import học viên vào chứng chỉ B1
            Route::post('certificate-import-student', 'CertificateController@importStudent')->name('certificate.import_student');
            // Danh sách HV theo CBTS, Export và Import đổi mã CBTS cho HV
            Route::get('student-update-cbts', 'StudentController@listUpdateCBTS')->name('student.list_update_cbts');
            Route::get('export-student-update-cbts', 'StudentController@exportListUpdateCBTS')->name('export_student_update_cbts');
            Route::post('import-student-update-cbts', 'StudentController@importListUpdateCBTS')->name('import_student_update_cbts');

            // Lấy danh sách công nợ học viên
            Route::get('accounting-debt-list', 'AccountingDebtController@getListAccountingDebt')->name('accounting_debt.list_accounting_debt');
            Route::post('accounting-debt-create', 'AccountingDebtController@createAccountingDebt')->name('accounting_debt.create_accounting_debt');
            Route::get('accounting-debt-export', 'AccountingDebtController@exportStudent')->name('accounting_debt.export');
            Route::post('accounting-debt-update', 'AccountingDebtController@updateHistory')->name('accounting_debt.update_history');
            Route::post('accounting-debt-delete', 'AccountingDebtController@deleteHistory')->name('accounting_debt.delete_history');
            Route::post('accounting-debt-import', 'AccountingDebtController@importHistory')->name('accounting_debt.import_history');
            Route::post('accounting-debt-update-status-student', 'AccountingDebtController@updateStatusAccountingDebtStudent')->name('accounting_debt.update_status_student');

            // Cập nhật lịch sử trạng thái học viên
            Route::post('update-history-status-study', 'StudentController@updateHistoryStatusStudy')->name('student.update_history_statusstudy');
            Route::get('delete-history/{id}', 'StudentController@deleteHistory')->name('student.delete_history');

            // Import warehouse_product (Dùng khi import bằng file Excel, chỉ IT làm)
            Route::post('warehouse-product-import', 'WareHouseProductController@importProduct')->name('warehouse_product.import_product');
            Route::post('warehouse-product-importTS', 'WareHouseProductController@importAsset')->name('warehouse_product.import_asset');
            Route::post('warehouse-assset-import', 'WareHouseEntryController@importEntry')->name('warehouse_entry.import_entry');

            // Cấp phát sách
            Route::get('plan-book-distribution', 'BookDistributionController@planBookDistribution')->name('book_distribution.plan');
            Route::get('class-has-published', 'BookDistributionController@listClassHasPublished')->name('book_distribution.class_has_published');
            Route::get('eligible-students', 'BookDistributionController@listEligibleStudents')->name('book_distribution.eligible_students');
            Route::post('change-status-book-distribution', 'BookDistributionController@changeStatus')->name('book_distribution.change_status');
            Route::get('book-distribution', 'BookDistributionController@index')->name('book_distribution.index');
            Route::post('active-book-distribution', 'BookDistributionController@activeBookDistribution')->name('book_distribution.active');
            Route::post('create-order-product-buy', 'BookDistributionController@createOrderProductBuy')->name('book_distribution.create.order.product.buy');
            Route::get('list-history-book-distribution', 'BookDistributionController@listHistory')->name('book_distribution.list_history');
            Route::get('detail-history-book-distribution/{id}', 'BookDistributionController@detailHistory')->name('book_distribution.detail_history');
            Route::get('list-book-distribution-student', 'BookDistributionController@listBookDistribution')->name('book_distribution.list_book_distribution_student');

            // Báo cáo xếp loại của các lớp theo từng chương trình
            Route::get('report-ranking-level-class', 'ReportController@reportRankingLevelClass')->name('report.ranking.level.class');
            // Đồng bộ tài sản
            Route::post('synchronize-warehouse-asset', 'WereHouseInventoryController@synchronizeWarehouseAsset')->name('warehouse_inventory.synchronize_product');

            // ThangNH - báo cáo DANH SÁCH HỌC VIÊN DỰ KIẾN LÊN TRÌNH B1 trong tháng
            Route::get('report-class-up-b1-by-month', 'ReportController@reportClassUpB1ByMonth')->name('report.class.up_b1_by_month');

            // Thống kê tài sản
            Route::get('warehouse_asset_statistical', 'WarehouseAssetController@statistical')->name('warehouse_asset.statistical');
            // Trả tài sản cho nhà cung cấp
            Route::get('reimburse_warehouse_index', 'WareHouseRecallController@indexReimburse')->name('warehouse_reimburse.index');
            Route::get('reimburse_warehouse_create', 'WareHouseRecallController@createReimburse')->name('warehouse_reimburse.create');
            Route::post('reimburse_warehouse_store', 'WareHouseRecallController@storeReimburse')->name('warehouse_reimburse.store');
            Route::get('reimburse_warehouse_show/{id}', 'WareHouseRecallController@showReimburse')->name('warehouse_reimburse.show');
            // Giáo viên xác nhận đã nhận sách
            Route::post('book_distribution_teacher_confirm', 'BookDistributionController@confirmTeacher')->name('book_distribution.confirm_teacher');
            // Xác nhận nhận đơn order cho người đề xuất order
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
        // Lấy thông tin danh sách học viên
        Route::get('hv_exam_session_search_hv', 'HvExamSessionController@searchStudent')->name('hv_exam_session.search_student');
        // Đổi trạng thái nhận sách của lớp
        Route::post('book-distribution-change-class', 'BookDistributionController@changeClassBookDistribution')->name('book_distribution.change_class');
        // Export học viên đủ điều kiện phát sách
        Route::get('eligible-students-export', 'BookDistributionController@exportEligibleStudents')->name('book_distribution.export_eligible_students');
        // In phiếu đề nghị thanh toán
        Route::post('warehouse_order_product_print', 'WareHouseOrderBuyController@printPaymentRequest')->name('warehouse_order_product_buy.print_payment_request');
        // Export Danh sách học viên đã nhận sách
        Route::get('list-book-distribution-student-export', 'BookDistributionController@exportListBookDistribution')->name('book_distribution.export_list_book_distribution_student');
        // Export Thống kê số lượng tài sản
        Route::get('warehouse-asset-export-statistical', 'WarehouseAssetController@exportStatistical')->name('warehouse_asset.export_statistical');
        // Export báo cáo DANH SÁCH HỌC VIÊN DỰ KIẾN LÊN TRÌNH B1 trong tháng
        Route::get('export-report-class-up-b1-by-month', 'ReportController@exportReportClassUpB1ByMonth')->name('report.class.export_up_b1_by_month');
        // Export chứng chỉ B1
        Route::get('certificate-export', 'CertificateController@exportCSertificate')->name('certificate.export');
        // View chi tiết thống kê tài sản
        Route::get('warehouse_asset_detail_statistical', 'WarehouseAssetController@viewStatistical')->name('warehouse_asset.view_statistical');
        // Kiểm kê tài sản
        Route::get('get-view-product-inventory', 'WereHouseInventoryController@getViewListProduct')->name('warehouse_inventory.get_view_list_product');

        // Lấy view lớp cấp phát sách theo khu vực
        Route::get('get-view-book-distribution', 'BookDistributionController@getViewBookDistribution')->name('book_distribution.get_view_book_distribution');

        Route::get('gvnn-get-schedule-class', 'ScheduleController@getScheduleGVNN')->name('gvnn.getschedules');
        Route::get('ajax_get_product_order', 'WareHouseOrderController@ajaxGetProductOrder')->name('ajax_get_product_order');

        // Cập nhật lại ghi chú và trạng thái chuyển giao của buổi học đã điểm danh
        Route::post('update-schedule', 'AttendanceController@updateSchedule')->name('attendances.update.schedule');


        Route::get('export-exam-result', 'ExamSessionUserController@exportExamResultStudent')->name('exam_session_user.export_exam_result');
        Route::get('export-trial-student', 'StudentController@exportTrialStudent')->name('trial_student.export_trial_student');
        //topic
        Route::get('ajax-add-question-topic', 'TopicController@formAddQuestion')->name('ajax.topic.addquestion');
        Route::get('ajax-edit-question-topic', 'TopicController@formEditQuestion')->name('ajax.topic.editquestion');
        //ajax update note
        Route::get('ajax-update-note', 'AttendanceController@ajaxUpdate')->name('ajax.update.note');
        Route::get('ajax-update-note_keepingteacher', 'ReportController@ajaxUpdateNoteKeepingTeacher')->name('ajax.update.note_keepingteacher');
        //ajax update note teacher
        Route::get('ajax-update-note-teacher', 'AttendanceController@ajaxUpdateNoteTeacher')->name('ajax.attendance.update.note.teacher');
        //ajax xác nhận lớp sắp kết thúc
        Route::get('ajax-confirm-class-ending', 'ReportController@ajaxConfirmClassEnding')->name('ajax.confirm.class.ending');
        //ajax xác nhận lớp sắp kết thúc
        Route::get('ajax-confirm-student', 'ReportController@ajaxConfirmStudent')->name('ajax.confirm.student');
        //Search Student
        Route::get('search_students', 'DecisionController@search')->name('student.search');

        Route::get('search_evaluation', 'StudentController@searchEvaluation')->name('student.search.evaluation');
        Route::get('search_course', 'CourseController@search')->name('cms_course.search');
        Route::get('print_student', 'StudentController@printStudent')->name('print_student');
        Route::get('export-excel-student', 'StudentController@export')->name('export_student');
        Route::get('export-excel-user', 'AdminController@exportUser')->name('export_user');
        Route::get('get-history-status-student', 'StudentController@AjaxgetHistoryStatus')->name('ajax.get_history.status_student');
        Route::post('delete-history-status-student', 'StudentController@deleteHistoryStatusStudy')->name('student.delete_history_statusstudy');
        Route::get('update-day-change-status', 'StudentController@AjaxUpdateDayChangeStatus')->name('ajax.update.day_change_status');
        Route::get('add-history-statusstudy', 'StudentController@addHistoryStatusStudy')->name('student.add_history_statusstudy');
        Route::get('get-table-history-status-study', 'StudentController@getTableHistory')->name('student.get_table_history');


        //Ajax get sylabus by level
        Route::post('syllabus_by_level', 'CourseController@syllabusBylevel')->name('syllabus_by_level');
        Route::post('syllabus_online_by_level', 'ClassElearningController@syllabusOnlineBylevel')->name('syllabus_online_by_level');
        Route::post('class_by_syllabus', 'ClassProcessController@classBySyllabus')->name('class_by_syllabus');
        Route::post('calculator_time_end', 'ClassProcessController@calculatorTimeEnd')->name('calculator_time_end');
        Route::post('update_ajax_process', 'ClassProcessController@updateAjaxProcess')->name('update_ajax_process');
        Route::post('course_by_syllabus', 'CourseController@courseBysyllabus')->name('course_by_syllabus');
        Route::post('room_by_area', 'CourseController@roomByarea')->name('room_by_area');

        //Ajax get atend by shedule
        // Route::post('attendance_by_shedule', 'ClassController@getAttendance')->name('x');
        Route::get('export-excel-attendance', 'AttendanceController@exportAttendance')->name('export_attendance');
        //Ajax get class by area
        Route::post('class_by_area', 'AttendanceController@classByArea')->name('class_by_area');

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
        Route::get('search_student_byclass', 'CertificateController@studentByClass')->name('student.byclass');


        Route::get('get_component_config', 'ComponentConfigController@getComponentConfig')->name('component.config');
        Route::post('component/update_sort', 'ComponentController@updateSort')->name('component.update_sort');
        Route::post('component/delete', 'ComponentController@delete')->name('component.delete');

        Route::post('menus/update_sort', 'MenuController@updateSort')->name('menus.update_sort');
        Route::post('menus/delete', 'MenuController@delete')->name('menus.delete');

        Route::post('parameter/update_sort', 'ParameterController@updateSort')->name('parameter.update_sort');
        Route::post('parameter/delete', 'ParameterController@delete')->name('parameter.delete');

        // language
        Route::post('languages/set-language-default', 'LanguageController@setLanguageIsDefault')->name('languages.set_default');

        // Order Products
        Route::get('order_courses', 'OrderController@index')->name('order_courses.index');
        Route::get('order_courses/{order}', 'OrderController@show')->name('order_courses.show');
        Route::put('order_courses/{order}', 'OrderController@update')->name('order_courses.update');
        Route::delete('order_courses/{order}', 'OrderController@destroy')->name('order_courses.destroy');

        Route::get('detail_job/{id}', 'JobsController@detail')->name('jobs.detail');
        Route::post('apply_job', 'JobsController@apply_job')->name('apply.job');
        Route::get('ajax-update-evaluation', 'EvaluationController@ajaxUpdate')->name('ajax.update.evaluation');
        Route::get('ajax-update-score', 'ScoreController@ajaxUpdate')->name('ajax.update.score');
        Route::get('get_student_admissions', 'StaffAdmissionController@get_student')->name('student.admissions');
        Route::get('view_student_admissions', 'StaffAdmissionController@view_student')->name('staffadmissions.student');
        Route::get('view_student_learn_again', 'ReportController@view_student')->name('ajax.report.studentlearnAgain');

        Route::get('notify', 'NotifyController@index')->name('notify.index');
        Route::get('getnotify', 'NotifyController@getNotify')->name('get.notify');
        Route::get('notify_edit', 'NotifyController@edit')->name('notify.edit');
        Route::post('notify_destroy', 'NotifyController@destroy')->name('notify.destroy');
        Route::get('active_notify', 'NotifyController@activeNotify')->name('active.notify');

        Route::get('score-by-staff', 'StaffAdmissionController@ScoreByStaff')->name('score_by_staff');
        // HTML to PDF
        Route::post('generate-pdf', 'PdfController@generatePDF')->name('generate_pdf');
        Route::get('ajax-report-student-learnagain', 'ReportController@ajaxReportStudentlearnAgain')->name('ajax.report.student.learnAgain');
        Route::get('ajax-nameclass-unique', 'ClassController@nameclassUnique')->name('ajax.nameclass.unique');

        //Class by course exam
        Route::post('exam_class_by_course', 'ExamSessionController@classByCourse')->name('exam_class_by_course');
        Route::get('export-exam-session-user', 'ExamSessionUserController@exportExamResult')->name('export_exam_session_user');

        Route::get('dormitory-get-student', 'DormitoryController@getStudent')->name('dormitory.getstudent');
        Route::get('dormitory-get-area', 'DormitoryController@getStudentArea')->name('dormitory.area.get');
        Route::get('dormitory-get-gender', 'DormitoryController@getInforStudent')->name('dormitory.gender.student');

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
        Route::post('decision-import', 'DecisionController@importDecision')->name('decision_import');
        Route::get('ajax_update_vat_entry_detail', 'WareHouseEntryController@updateVAT')->name('ajax_update_vat_entry_detail');
    });

    // Test staff
    Route::get('test_staff', 'QuestionAnswerController@TestStaff')->name('test_staff.index');
    Route::post('check_active_staff', 'QuestionAnswerController@CheckActiveStaff')->name('check_active_staff.submit');
    // Test teacher
    Route::get('test_teacher', 'TeacherQuizController@testTeacher')->name('test_teacher.test');
    Route::post('/next-question', 'TeacherQuizController@nextQuestion')->name('next_question');
    Route::post('/previous-question', 'TeacherQuizController@previousQuestion')->name('previous_question');
    Route::get('/result-test-teacher', 'TeacherQuizController@resultTestTeacher')->name('result_test_teacher');
});
