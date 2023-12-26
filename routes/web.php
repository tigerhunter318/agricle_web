<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\BaseController;

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ReviewTemplateController;

use App\Http\Controllers\ProducerController;
use App\Http\Controllers\WorkerController;

use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\RecruitmentFavouriteController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\MatterManageController;

// Homepage Route
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Authentication Routes
Route::get('/signin/admin', [LoginController::class, 'adminLoginView']);
Route::post('/signin/admin', [LoginController::class, 'adminLogin'])->name('adminLogin');

Route::get('/signup/{role?}', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/register_code/{user_id?}', [RegisterController::class, 'register_code'])->name('register_code');
Route::post('/register_code/{user_id}', [RegisterController::class, 'register_code_check'])->name('register_code_check');
Route::post('/resend_code/{user_id}', [RegisterController::class, 'send_code'])->name('resend_code');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/get_city', [BaseController::class, 'get_city_by_prefecture'])->name('get_city');

// Dashboard Routes
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Review template routes
    Route::resource('review_templates', ReviewTemplateController::class);
    Route::delete('review_templates/destroy', [ReviewTemplateController::class, 'destroy'])->name('delete_template');
    Route::get('/producer/{producer_id}/detail', [ProducerController::class, 'detail_view'])->name('producer_detail_view');

    // All routes for producer
    Route::group(['prefix' => 'producer', 'middleware' => ['producer']], function() {
        // Reminder for close working matter
        Route::post('/recruitments/reminder', [RecruitmentController::class, 'remind_request'])->name('reminder');

        // Recruitment list views based on status && change status action in list
        Route::get('/recruitments/list/{status?}', [RecruitmentController::class, 'status_view'])->name('recruitment_status_view');
        Route::put('/recruitments/{id}/status/{status}', [RecruitmentController::class, 'set_recruitment_status'])->name('set_recruitment_status');
        Route::get('/recruitments/clone/{recruitment_id}', [RecruitmentController::class, 'clone'])->name('clone_view');

        // Recruitment detail views group
        //      1. view to append add-on information of contracted recruitments [ status: collecting ]
        //      2. view of applied workers of this collecting or working recruitments [ status: collecting or working ]
        //      3. view of applied worker of this collecting or working recruitments [ status: collecting or working ]
        //      4. view to mark review applied workers of this working recruitments [ status: working ]
        //      5. view of completed recruitement detail and participants list [ status: completed ]
        Route::group(['prefix' => '/recruitment/{recruitment_id}', 'middleware' => ['producer_has_recruitment']], function() {
            Route::get('/addon', [RecruitmentController::class, 'addon_view'])->name('recruitment_addon_view');
            Route::put('/addon', [RecruitmentController::class, 'add_postscript'])->name('add_recruitment_addon');
            Route::get('/applicants', [RecruitmentController::class, 'applicants_view'])->name('recruitment_applicants_view');
            Route::get('/detail', [RecruitmentController::class, 'recruitment_detail_view'])->name('recruitment_detail_view');
            Route::get('/applicant/{worker_id}', [RecruitmentController::class, 'applicant_view'])->name('recruitment_applicant_view');
            Route::put('/applicant/{worker_id}/status', [RecruitmentController::class, 'set_applicant_status'])->name('set_applicant_status');
            Route::get('/review', [RecruitmentController::class, 'review_view'])->name('recruitment_review_view');
            Route::put('/review', [RecruitmentController::class, 'set_review'])->name('set_applicant_review');
            Route::get('/result', [RecruitmentController::class, 'result_view'])->name('recruitment_result_view');
        });

        // Recruitment CRUD default routes
        Route::resource('recruitments', RecruitmentController::class);

        // Producer personal pages group
        Route::get('/farmers', [ProducerController::class, 'farmers_view'])->name('producer_farmers_view');
        Route::get('/profile', [ProducerController::class, 'profile_view'])->name('producer_profile_view');
        Route::put('/profile', [ProducerController::class, 'update'])->name('producer_profile_update');
        Route::post('/profile/avatar', [ProducerController::class, 'upload_avatar'])->name('upload_producer_avatar');
    });

    // All routes for worker
    Route::group(['prefix' => 'worker', 'middleware' => ['worker']], function() {
        Route::get('/matters', [RecruitmentController::class, 'matters_view'])->name('matters_view');
        Route::post('/matters/search', [RecruitmentController::class, 'search_matter'])->name('search_matter');

        Route::prefix('/matter/{matter_id}')->group(function () {
            Route::get('', [RecruitmentController::class, 'matter_detail_view'])->name('matter_detail_view');
            Route::post('/apply', [RecruitmentController::class, 'apply_matter'])->name('apply_matter');
            Route::get('/review', [ApplicantController::class, 'review_view'])->name('matter_review_view');
            Route::put('/finish', [ApplicantController::class, 'finish'])->name('finish_matter');
        });

        Route::get('/applications', [ApplicantController::class, 'index'])->name('applications_view');
        Route::get('/applications/detail/{applicant_id}', [ApplicantController::class, 'application_detail_view'])->name('application_detail_view');
        Route::post('/applications/search', [ApplicantController::class, 'search_application'])->name('search_application');
        Route::get('/applications/result/{applicant_id}', [ApplicantController::class, 'result_view'])->name('result_detail_view');

        Route::get('/favourites', [WorkerController::class, 'favourite_recruitments_view'])->name('favourite_recruitments_view');
        Route::get('/favourites/{recruitment_id}', [WorkerController::class, 'favourite_recruitment_view'])->name('favourite_recruitment_view');

        Route::get('/farms', [WorkerController::class, 'farms_view'])->name('worker_farms_view');
        Route::get('/detail', [WorkerController::class, 'detail_view'])->name('worker_detail_view');
        Route::get('/profile', [WorkerController::class, 'profile_view'])->name('worker_profile_view');
        Route::put('/profile', [WorkerController::class, 'update'])->name('worker_profile_update');
        Route::post('/profile/avatar', [WorkerController::class, 'upload_avatar'])->name('upload_worker_avatar');
    });

    Route::group(['prefix' => 'chat'], function() {
        Route::get('/recruitments', [ChatController::class, 'recruitments_view'])->name('chat_recruitments_view');
        Route::get('/recruitment/{recruitment_id}/{sender_id?}', [ChatController::class, 'recruitment_chat_view'])->name('recruitment_chat');
        Route::get('/favourites/{sender_id?}', [ChatController::class, 'favourites_chat_view'])->name('favourites_chat');
        Route::get('/message', [ChatController::class, 'fetchMessages'])->name('get_message');
        Route::post('/message', [ChatController::class, 'sendMessage'])->name('send_message');
        Route::post('/message/read', [ChatController::class, 'setRead'])->name('set_read');
        Route::post('/message/clear', [ChatController::class, 'clearMessage'])->name('clear_message');
    });

    Route::group(['prefix' => 'notification'], function() {
        Route::get('/news', [NotificationController::class, 'news_view'])->name('news_view');
        Route::get('/news/get', [NotificationController::class, 'getNews'])->name('get_news');
        Route::post('/news/read', [NotificationController::class, 'setReadNews'])->name('read_news');
        Route::post('/news/readAll', [NotificationController::class, 'setReadAllNews'])->name('read_all_news');
        Route::delete('/news/clear', [NotificationController::class, 'clearNews'])->name('clear_news');
        Route::delete('/news/clear', [NotificationController::class, 'clearAllNews'])->name('clear_all_news');

        Route::get('/msg', [NotificationController::class, 'messages_view'])->name('messages_view');
        Route::post('/msg/search', [NotificationController::class, 'search_messages'])->name('search_messages');
        Route::get('/msg/get', [NotificationController::class, 'getMsg'])->name('get_msg');
        Route::post('/msg/read', [NotificationController::class, 'setReadMsg'])->name('read_msg');
        Route::post('/msg/readAll', [NotificationController::class, 'setReadAllMsg'])->name('read_all_msg');
        Route::delete('/msg/clear', [NotificationController::class, 'clearAllMsg'])->name('clear_all_msg');
    });

    Route::group(['prefix' => 'favourites'], function() {
        Route::put('/set', [FavouriteController::class, 'set_favourite'])->name('set_favourite');
        Route::put('/unset', [FavouriteController::class, 'unset_favourite'])->name('unset_favourite');
    });

    Route::group(['prefix' => 'recruitment/favourites'], function() {
        Route::put('/set', [RecruitmentFavouriteController::class, 'set_favourite'])->name('set_recruitment_favourite');
        Route::put('/unset', [RecruitmentFavouriteController::class, 'unset_favourite'])->name('unset_recruitment_favourite');
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');

    Route::get('/users/list/{role?}/{approved?}', [UserManageController::class, 'view_user_list'])->name('view_user_list');
    Route::post('/users/approved', [UserManageController::class, 'set_user_approve'])->name('set_user_approve');
    Route::get('/users/detail/{id}', [UserManageController::class, 'view_user_detail'])->name('view_user_detail');
    Route::get('/users/delete/{id}', [UserManageController::class, 'delete_user'])->name('delete_user');

    Route::get('/matters', [MatterManageController::class, 'view_matter_list'])->name('view_matter_list');
    Route::post('/matters', [MatterManageController::class, 'search_matter_admin'])->name('search_matter_admin');
    Route::get('/matters/producer/{id}', [MatterManageController::class, 'view_matter_list_by_producer'])->name('view_matter_list_by_producer');
    Route::post('/matters/approve', [MatterManageController::class, 'set_matter_approve'])->name('set_matter_approve');
    Route::get('/matters/detail/{id}', [MatterManageController::class, 'view_matter_detail'])->name('view_matter_detail');
});
