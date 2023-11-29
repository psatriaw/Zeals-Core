<?php

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

//API xendit callback
Route::post('/xenditdisbursementcallback', [App\Http\Controllers\apis\PaymentCallBack::class, 'xenditdisbursementcallback']);

//API xendit callback

//API register from other platform
Route::post('/regis3rdparty', [App\Http\Controllers\apis\Registration::class, 'regist3rdparty']);
Route::post('/regiswithencrypted', [App\Http\Controllers\apis\RegistrationWithTransaction::class, 'regist3rdparty']);
//API register from other platform



//API search data
Route::get('inviteuser',[App\Http\Controllers\apis\User::class, 'inviteuser']);
Route::get('brand-resume',[App\Http\Controllers\apis\Brand::class, 'getresume']);
Route::get('withdrawal-resume',[App\Http\Controllers\apis\Withdrawal::class, 'getresume']);
Route::get('user-resume',[App\Http\Controllers\apis\User::class, 'getresume']);
Route::get('campaign-resume',[App\Http\Controllers\apis\Campaign::class, 'getcampaignresume']);
Route::get('departmentinfo',[App\Http\Controllers\apis\Department::class, 'getdepartmentinfo']);
Route::get('brand-all',[App\Http\Controller\apis\Brand::class, 'getDataAll']);
Route::get('trackcampaign/{id}',[App\Http\Controllers\apis\Tracker::class, 'getTrackList']);
Route::get('site-resume',[App\Http\Controllers\apis\Microsite::class, 'getResume']);
Route::get('check-user-source-pool/',[App\Http\Controllers\apis\UserTmp::class, 'checkUserPool']);
//API search data

//API Mailing
Route::post('apiv1/maildemo',[App\Http\Controllers\apis\EmailController::class,'personaldemo']);
Route::get('emailactivation/{email}',[App\Http\Controllers\apis\EmailController::class,'activation']);
//API Mailing

//API BDBA
Route::post('v1/regis-judger',[App\Http\Controllers\apis\BDBAController::class,'register']);
Route::post('v1/login-judger',[App\Http\Controllers\apis\BDBAController::class,'login']);
Route::get('v1/get-judger',[App\Http\Controllers\apis\BDBAController::class,'getUser']);
Route::get('v1/get-list-judger',[App\Http\Controllers\apis\BDBAController::class,'getListJudger']);
Route::post('v1/store-score',[App\Http\Controllers\apis\BDBAController::class,'StoreScore']);
Route::get('v1/get-scoring',[App\Http\Controllers\apis\BDBAController::class,'getScoring']);
Route::post('v1/reset-password',[App\Http\Controllers\apis\BDBAController::class,'resetPassword']);
Route::get('v1/get-detail/{id}',[App\Http\Controllers\apis\BDBAController::class,'getDetailScoring']);
Route::get('v1/get-total-score/{id}',[App\Http\Controllers\apis\BDBAController::class,'getDetailAdmin']);
Route::post('v1/add-company',[App\Http\Controllers\apis\BDBAController::class,'addCompany']);
Route::get('v1/get-company',[App\Http\Controllers\apis\BDBAController::class,'getCompany']);
Route::get('v1/delete-company/{id}', [App\Http\Controllers\apis\BDBAController::class,'destroy']);
Route::post('v1/deactive-jury/{id}', [App\Http\Controllers\apis\BDBAController::class,'destroyJury']);


//API Mobile
Route::post('v1/login', 'apis\Login@submit');
Route::post('v1/register', 'apis\UserController@register');


Route::get('v1/banner-list', 'apis\BannerController@list');
Route::get('v1/campaign-list', 'apis\CampaignController@list');
Route::get('v1/campaign-detail', 'apis\CampaignController@detail');

Route::get('v1/category-list', 'apis\CategoryController@list');
Route::post('v1/g-login', 'apis\Login@googleLoginV2');
Route::post('v1/g-regist', 'apis\Registration@gRegist');
Route::post('v1/resetpassword', 'apis\AuthController@resetPassword');

Route::get('v1/set_filter', 'apis\FilterController@setfilter');

//push notification subscribe
Route::post('v1/notification-subscribe','apis\PushNotificationController@subscribe');

//API Mobile


//API Mobile
// Route::middleware('auth:sanctum')->group( function () {
    //set of home data
    Route::get('v1/home-set', 'apis\HomeController@index');

    //profile user
    Route::get('v1/profile', 'apis\UserController@profile');
    Route::post('v1/profile-update', 'apis\UserController@update');

    //dasboard user
    Route::get('v1/dashboard-data', 'apis\DashboardController@performance');

    //withdrawal user
    Route::get('v1/withdrawal-list', 'apis\WithdrawalController@list');
    Route::post('v1/withdrawal-create', 'apis\WithdrawalController@create');

    //campaign user
    Route::post('v1/campaign-join', 'apis\CampaignController@join');

    //canpaign bookmark
    Route::post('v1/campaign-bookmark', 'apis\CampaignController@bookmark');
    Route::get('v1/campaign-removebookmark', 'apis\CampaignController@removebookmark');

    //notification user
    Route::get('v1/notification-list', 'apis\NotificationController@list');
    Route::post('v1/notification-read', 'apis\NotificationController@read');

    // fcm controller
    Route::get('v1/notification-fcm', 'apis\FcmController@list');
// });

//dashboard API
// Route::middleware('auth:sanctum')->group( function () {
//     //USER
//     Route::get('v1/user-detail', 'apis\dashboard\UserController@profile');
//     Route::post('v1/profile-user-update', 'apis\dashboard\UserController@updateprofile');
//     //USER

//     //BRANDS
//     Route::get('v1/brand-list', 'apis\dashboard\BrandController@list');
//     Route::get('v1/brand-detail', 'apis\dashboard\BrandController@detail');
//     Route::post('v1/brand-create', 'apis\dashboard\BrandController@create');
//     Route::post('v1/brand-update', 'apis\dashboard\BrandController@update');
//     Route::delete('v1/brand-delete', 'apis\dashboard\BrandController@delete');
//     //BRANDS

//     //INDUSTRIES
//     Route::get('v1/industry-list', 'apis\dashboard\IndustryController@list');
//     Route::get('v1/industry-detail', 'apis\dashboard\IndustryController@detail');
//     Route::post('v1/industry-create', 'apis\dashboard\IndustryController@create');
//     Route::post('v1/industry-update', 'apis\dashboard\IndustryController@update');
//     Route::delete('v1/industry-delete', 'apis\dashboard\IndustryController@delete');
//     //INDUSTRIES

//     //WITHDRAWALS
//     Route::get('v1/withdrawal-list', 'apis\dashboard\WithdrawalController@list');
//     Route::get('v1/withdrawal-detail', 'apis\dashboard\WithdrawalController@detail');
//     Route::post('v1/withdrawal-create', 'apis\dashboard\WithdrawalController@create');
//     Route::post('v1/withdrawal-update', 'apis\dashboard\WithdrawalController@update');
//     Route::delete('v1/withdrawal-delete', 'apis\dashboard\WithdrawalController@delete');
//     Route::post('v1/withdrawal-approve', 'apis\dashboard\WithdrawalController@approve');
//     //WITHDRAWALS

//     //BANKS
//     Route::get('v1/bank-list', 'apis\dashboard\BankController@list');
//     Route::get('v1/bank-detail', 'apis\dashboard\BankController@detail');
//     Route::post('v1/bank-create', 'apis\dashboard\BankController@create');
//     Route::post('v1/bank-update', 'apis\dashboard\BankController@update');
//     Route::delete('v1/bank-delete', 'apis\dashboard\BankController@delete');
//     Route::post('v1/bank-approve', 'apis\dashboard\BankController@approve');
//     //BANKS

//     //BANNERS
//     Route::get('v1/banner-list', 'apis\dashboard\BannerController@list');
//     Route::get('v1/banner-detail', 'apis\dashboard\BannerController@detail');
//     Route::post('v1/banner-create', 'apis\dashboard\BannerController@create');
//     Route::post('v1/banner-update', 'apis\dashboard\BannerController@update');
//     Route::delete('v1/banner-delete', 'apis\dashboard\BannerController@delete');
//     Route::post('v1/banner-approve', 'apis\dashboard\BannerController@approve');
//     //BANNERS

//     //ACCOUNTS
//     Route::get('v1/account-list', 'apis\dashboard\AccountController@list');
//     Route::get('v1/account-detail', 'apis\dashboard\AccountController@detail');
//     Route::post('v1/account-create', 'apis\dashboard\AccountController@create');
//     Route::post('v1/account-update', 'apis\dashboard\AccountController@update');
//     Route::delete('v1/account-delete', 'apis\dashboard\AccountController@delete');
//     Route::delete('v1/account-cleanup', 'apis\dashboard\AccountController@cleanup');
//     Route::post('v1/account-approve', 'apis\dashboard\AccountController@approve');
//     //ACCOUNTS

//     //WILAYAH
//     Route::get('v1/wilayah-search', 'apis\dashboard\WilayahController@search');
//     Route::get('v1/wilayah-list', 'apis\dashboard\WilayahController@list');
//     Route::get('v1/wilayah-detail', 'apis\dashboard\WilayahController@detail');
//     Route::post('v1/wilayah-create', 'apis\dashboard\WilayahController@create');
//     Route::post('v1/wilayah-update', 'apis\dashboard\WilayahController@update');
//     Route::delete('v1/wilayah-delete', 'apis\dashboard\WilayahController@delete');
//     //WILAYAH

//     //JOB
//     Route::get('v1/job-search', 'apis\dashboard\JobController@search');
//     Route::get('v1/job-list', 'apis\dashboard\JobController@list');
//     Route::get('v1/job-detail', 'apis\dashboard\JobController@detail');
//     Route::post('v1/job-create', 'apis\dashboard\JobController@create');
//     Route::post('v1/job-update', 'apis\dashboard\JobController@update');
//     Route::delete('v1/job-delete', 'apis\dashboard\JobController@delete');
//     //JOB

//     Route::post('v1/ws-request','apis\EmailController@req');

//     //PG Payment
//     Route::get('v1/balance-xendit', 'apis\dashboard\XenditController@balance');
//     Route::get('v1/topup-history', 'apis\dashboard\XenditController@history');

//     Route::post('v1/topup-xendit', 'apis\dashboard\XenditController@topup');
//     //PG Payment

// });

Route::get('v1/wilayah', 'apis\WilayahController@get');

Route::get('v1/industry-search', 'apis\dashboard\IndustryController@search');
//dashboard API