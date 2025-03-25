<?php

use App\Http\Controllers\FrontEnd\StripePaymentController;
use Illuminate\Support\Facades\Route;

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

Route::namespace('FrontEnd')->group(function () {

    // Config to use ckfinder
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
            ->name('ckfinder_connector');
        Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
            ->name('ckfinder_browser');
    });
    Route::get('/language/{locale}', 'Controller@language')->name('frontend.language');

    Route::get('forgot-password', 'UserController@forgotPasswordForm')->name('frontend.password.forgot.get');
    Route::post('forgot-password', 'UserController@forgotPassword')->name('frontend.password.forgot.post');
    Route::get('reset-password/{token}', 'UserController@resetPasswordForm')->name('frontend.password.reset.get');
    Route::post('reset-password', 'UserController@resetPassword')->name('frontend.password.reset.post');

    Route::post('send_contact', 'ContactController@store')->name('frontend.contact.store');
    Route::get('order-courses/{id}', 'OrderController@storeOrderCourses')->name('frontend.order.courses');

    Route::prefix('user')->group(function () {
        Route::get('/login', 'UserController@index')->name('frontend.login');
        Route::post('/login', 'UserController@login')->name('frontend.login.post');
        Route::post('/signup', 'UserController@signup')->name('frontend.signup');
        Route::get('/verify-account', 'UserController@verifyAccount')->name('frontend.verify_account');
        Route::group(['middleware' => ['auth:web']], function () {
            Route::get('', 'UserController@index')->name('frontend.user');
            Route::get('/logout', 'UserController@logout')->name('frontend.logout');
            Route::get('/my-course', 'UserController@myCourse')->name('frontend.user.course');
            Route::get('/my-education', 'UserController@myEducation')->name('frontend.user.education');
            Route::post('/update-account', 'UserController@changeAccount')->name('frontend.update.account');
            Route::post('/update-password', 'UserController@changePassword')->name('frontend.update.password');
        });
    });
    Route::group(['middleware' => ['auth:web']], function () {
        // Route::get('khoa-hoc/{alias}/lesson/{id}', 'CourseController@lesson')->name('frontend.lesson.detail');
        Route::get('learning/{alias}', 'CourseController@lesson')->name('frontend.lesson.detail');
        Route::post('/lesson-user', 'CourseController@activeLessonUser')->name('lesson.user');
        Route::post('check-quiz', 'CourseController@checkQuiz')->name('frontend.check.quiz');
        Route::get('get-vocabulary', 'CourseController@getVocabulary')->name('frontend.get.vocabulary');
        Route::get('get-view-next-quiz', 'CourseController@getViewNextQuiz')->name('frontend.get.viewnextquiz');
        Route::post('update-point', 'CourseController@updatePoint')->name('frontend.update.point');

        // Test audio google API
        Route::get('transcribe', 'SpeechController@transcribeView')->name('frontend.transcribe.get');
        Route::post('transcribe', 'SpeechController@transcribe')->name('frontend.transcribe.post');
        Route::post('/upload-audio', 'SpeechController@upload')->name('frontend.transcribe.upload');
        Route::post('text-to-speech', 'SpeechController@textToSpeech')->name('frontend.text_to_speech.post');
    });
    // test IQ học viên
    Route::get('test-iq', 'TestIQController@testIqStudentIndex')->name('test_iq.student.index');
    Route::post('test-iq-post', 'TestIQController@testIqStudentPost')->name('test_iq.student.post');
    Route::get('test-iq-question', 'TestIQController@testIqStudentQuestion')->name('test_iq.student.question');
    // Route::post('test-iq-answer-post', 'TestIQController@testIqStudentAnswer')->name('test_iq.student.answer');
    Route::match(['get', 'post'], 'test-iq-answer-post','TestIQController@testIqStudentAnswer')->name('test_iq.student.answer');
    // test nghiệm thu
    Route::get('test-acceptance', 'TestIQController@testAcceptanceStudentIndex')->name('test_acceptance.student.index');
    Route::post('test-acceptance-post', 'TestIQController@testAcceptanceStudentPost')->name('test_acceptance.student.post');
    Route::get('test-acceptance-question', 'TestIQController@testAcceptanceStudentQuestion')->name('test_acceptance.student.question');
    // Route::post('test-acceptance-answer-post', 'TestIQController@testAcceptanceStudentAnswer')->name('test_acceptance.student.answer');
    Route::match(['get', 'post'], 'test-acceptance-answer-post','TestIQController@testAcceptanceStudentAnswer')->name('test_acceptance.student.answer');

    Route::post('/check-answer-quiz', 'CourseController@checkAnswerQuiz')->name('check_answer_quiz');
    Route::get('/', 'PageController@index')->name('home');
    Route::get('khoa-hoc', 'CourseController@index')->name('frontend.course.list');

    Route::get('khoa-hoc/{alias}', 'CourseController@detail')->name('frontend.course.detail');
    Route::get('tag/{alias}', 'PageController@index')->name('frontend.tag');
    Route::get('{taxonomy}/{alias?}', 'PageController@index')->name('frontend.page');
});
