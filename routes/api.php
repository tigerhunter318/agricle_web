<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RecruitmentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MatterController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\NoticeController;
use App\Http\Controllers\API\ContactController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('login', [AuthController::class, 'signIn']);
    Route::post('register', [AuthController::class, 'signUp']);

    Route::middleware(['auth:sanctum'])->group( function () {
        // get user data
        Route::get('user', [AuthController::class, 'user']);
        // get profile data
        Route::get('user/producer/{producer_id}', [UserController::class, 'get_producer_profile']);
        Route::get('user/worker/{worker_id}', [UserController::class, 'get_worker_profile']);
        Route::get('profile', [UserController::class, 'get_profile']);
        // update profile data
        Route::post('profile/update', [UserController::class, 'update']);
        // get history recruitments for both of producer and worker
        Route::get('recruitments/history', [RecruitmentController::class, 'get_history']);

        Route::middleware(['auth:sanctum', 'ability:producerRole'])->group( function () {
            Route::get('recruitments', [RecruitmentController::class, 'get_by_producer']);
            Route::put('recruitment/status', [RecruitmentController::class, 'set_recruitment_status']);
            Route::post('recruitment', [RecruitmentController::class, 'create']);
            Route::post('recruitment/update', [RecruitmentController::class, 'update']);
            Route::delete('recruitment/{id}', [RecruitmentController::class, 'delete']);
            Route::post('recruitment/addon', [RecruitmentController::class, 'add_postscript']);

            Route::get('applicants/{recruitment_id}', [RecruitmentController::class, 'get_applicants']);
            Route::get('applicant/{recruitment_id}/{worker_id}', [RecruitmentController::class, 'get_applicant']);
            Route::put('applicant/status', [RecruitmentController::class, 'set_applicant_status']);
            Route::put('applicant/evaluate_worker', [RecruitmentController::class, 'evaluate_worker']);

            Route::get('favourite/user', [UserController::class, 'get_favourite_user']);
            Route::put('favourite/user', [UserController::class, 'set_favourite_user']);
        });

        Route::middleware(['auth:sanctum', 'ability:workerRole'])->group( function () {
            Route::get('favourite/recruitment', [UserController::class, 'get_favourite_recruitment']);

            Route::post('matters', [MatterController::class, 'get']);
            Route::get('matters/recent', [MatterController::class, 'get_recent']);
            Route::post('matters/favourite', [MatterController::class, 'get_favourite_matters']);
            Route::put('matter/apply', [MatterController::class, 'apply']);
            Route::put('matter/favourite', [MatterController::class, 'set_favourite']);

            Route::post('applications/status', [ApplicationController::class, 'get_by_status']);
            Route::get('application/{applicant_id}', [ApplicationController::class, 'get_by_id']);
            Route::get('applications/events', [ApplicationController::class, 'getAll']);
            Route::post('applications/finish/{matter_id}', [ApplicationController::class, 'finish']);
        });

        Route::get('chat/recruitments', [ChatController::class, 'get_recruitments']);
        Route::get('chat/favourites', [ChatController::class, 'get_favourites']);
        Route::get('chat/unread', [ChatController::class, 'get_unread_messages']);
        Route::get('chat/applicants/{recruitment_id}', [ChatController::class, 'get_applicants']);
        Route::get('chat/info/{recruitment_id}/{receiver_id}', [ChatController::class, 'get_info']);
        Route::post('chat/messages', [ChatController::class, 'get_messages']);
        Route::post('chat/send', [ChatController::class, 'sendMessage']);
        Route::put('chat/read', [ChatController::class, 'readMessage']);
        Route::delete('chat/{recruitment_id}/{receiver_id}', [ChatController::class, 'deleteMessages']);

        Route::get('notice/all', [NoticeController::class, 'get_all_news']);
        Route::get('notice/unread', [NoticeController::class, 'get_unread_news']);
        Route::put('notice/read', [NoticeController::class, 'set_read']);
        Route::post('notice/delete', [NoticeController::class, 'delete_news']);

        Route::post('contact', [ContactController::class, 'send_contact']);
    });
});
