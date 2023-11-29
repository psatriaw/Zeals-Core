<?php

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

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Request;

date_default_timezone_set('Asia/Jakarta');

//////STORE MASTER DATA
////STORE MASTER DATA

//URUN MANDIRI

//dashboard
Route::get('generate2ndlink/{uniquelink}', 'AffiliatorController@generate2ndlink');
Route::get('testui', 'HomeController@testui');
// Route::get("api/dashboard", "DashboardController@dashboardIndex");

// Route::post("payment/release", "XenditController@release");
// Route::post("payment/charge/deviden", "XenditController@chargedeviden");
// Route::post("payment/pay", "XenditController@pay");
// Table tb_payment doesn't exist

//URUN MANDIRI

// banner
Route::get("master/banner", "BannerController@index");
Route::get("master/banner/detail/{id}", "BannerController@detail");
Route::get("master/banner/create", "BannerController@create");
Route::post("master/banner/store", "BannerController@store");
Route::get("master/banner/edit/{id}", "BannerController@edit");
Route::post("master/banner/update/{id}", "BannerController@update");
Route::post("master/banner/remove/{id}", "BannerController@remove");

// topup
Route::get("master/topup", "TopUpController@index");
Route::get("master/topup/detail/{id}", "TopUpController@detail");
Route::post("master/topup/approve/{id}", "TopUpController@approve");

//tagihan
// Route::get("master/tagihan", "TagihanController@index");
// Route::get("master/tagihan/payment/{id}", "TagihanController@payment");
// !!! Class "App\Http\Models\DevidenModel" not found

// Penerbit
// Route::get("master/rups", "RupsController@index");
// Route::get("master/rups/detail/{id}", "RupsController@detail");
// Route::get("master/rups/create", "RupsController@create");
// Route::get("master/rups/edit/{id}", "RupsController@edit");
// Route::get("master/rups/manage/{id}", "RupsController@manage");
// Route::post("master/rups/store", "RupsController@store");
// Route::post("master/rups/update/{id}", "RupsController@update");
// Route::post("master/rups/remove/{id}", "RupsController@remove");
// Route::post("master/rups/lockrups", "RupsController@lockrups");
// Route::post("master/rups/start/{id}", "RupsController@start");
// Route::post("master/rups/update-running/{id}", "RupsController@updaterunning");
// !! Controller doesn't exist

// Penerbit
Route::get("master/penerbit", "PenerbitController@index");
Route::get("master/penerbit/detail/{id}", "PenerbitController@detail");
Route::get("master/penerbit/create", "PenerbitController@create");
Route::get("master/penerbit/edit/{id}", "PenerbitController@edit");
Route::get("master/penerbit/manage/{id}", "PenerbitController@manage");
Route::post("master/penerbit/store", "PenerbitController@store");
Route::post("master/penerbit/update/{id}", "PenerbitController@update");
Route::post("master/penerbit/approve/{id}", "PenerbitController@approve");
Route::post("master/penerbit/deactivate/{id}", "PenerbitController@deactivate");
Route::post("master/penerbit/remove/{id}", "PenerbitController@remove");
Route::get("get-list-penerbit", "PenerbitController@findPenerbit");


// deviden
// Route::get("master/deviden", "DevidenController@index");
// Route::get("master/deviden/create/{id}", "DevidenController@create");
// Route::post("master/deviden/store", "DevidenController@store");
// Controller doesn't exist

// pra-penawaran
// Route::get("master/pra-penawaran", "PraPenawaranController@index");
// Route::get("master/pra-penawaran/detail/{id}", "PraPenawaranController@detail");
// Route::get("master/pra-penawaran/edit/{id}", "PraPenawaranController@edit");
// Route::post("master/pra-penawaran/update/{id}", "PraPenawaranController@update");
// Route::get("master/pra-penawaran/approve/{id}", "PraPenawaranController@approve");
// Route::post("master/pra-penawaran/comment/remove/{id_pra}/{id_comment}", "PraPenawaranController@removeComment");
// Controller doesn't exist

// tutorial videos
// Route::get("master/tutorial", "TutorialVideoController@index");
// Route::get("master/tutorial/detail/{id}", "TutorialVideoController@detail");
// Route::get("master/tutorial/create", "TutorialVideoController@create");
// Route::get("master/tutorial/edit/{id}", "TutorialVideoController@edit");
// Route::post("master/tutorial/update/{id}", "TutorialVideoController@update");
// Route::post("master/tutorial/remove/{id}", "TutorialVideoController@remove");
// Route::post("master/tutorial/store", "TutorialVideoController@store");
// !!!!!!! Table 'zeals.tb_tutorial_videos' doesn't exist



Route::get("master/campaign", "CampaignController@index");
Route::get("master/campaign/manage/{id}", "CampaignController@manage");
Route::get("master/campaign/detail/{id}", "CampaignController@detail");
Route::get("master/campaign/boost/{campaign_link}", "CampaignController@boost");
Route::get("master/campaign/edit/{campaign_link}", "CampaignController@edit");
Route::post("master/campaign/update/{id}", "CampaignController@update");
Route::post("master/campaign/updatemanage/{id}", "CampaignController@updatemanage");
Route::get("master/campaign/choose/create", "CampaignController@choose");
Route::get("master/campaign/create/{kind}", "CampaignController@create");
Route::get("master/campaign/setcomponent/{campaign_link}", "CampaignController@setcomponent");
Route::get("master/campaign/setprogram/{campaign_link}", "CampaignController@setprogram");
Route::get("master/campaign/settarget/{campaign_link}", "CampaignController@setCampaignTarget");
Route::get("master/campaign/setoutlet/{campaign_link}", "CampaignController@setCampaignOutlet");
Route::get("master/campaign/editoutlet/{campaign_link}/{id_outlet}", "CampaignController@editoutlet");
Route::post("master/campaign/updateoutlet", "CampaignController@updateoutlet");
Route::post("master/campaign/removeoutlet/{campaign_link}/{id_outlet}", "CampaignController@removeoutlet");
Route::post("master/campaign/store", "CampaignController@store");
Route::post("master/campaign/setrun", "CampaignController@setrun");
Route::post("master/campaign/setclose", "CampaignController@setclose");
Route::get("master/campaign/report/outlet/{campaign_link}/{id_outlet}", "CampaignController@reportoutlet");
Route::get("master/campaign/report/outlet/{campaign_link}/{id_outlet}","CampaignController@reportoutlet");
Route::get("master/campaign/scan/outlet/{campaign_link}/{id_outlet}","CampaignController@outletscanner");
Route::get("master/campaign/report/{kind}", "CampaignController@report");
Route::get("master/campaign/export-redemption/{kind}", "CampaignController@exportredemption");
Route::get("master/campaign/export-o2o/{kind}", "CampaignController@export");
Route::get("master/campaign/resume/{kind}", "CampaignController@resume");
Route::post("master/campaign/storeoutlet", "CampaignController@storeoutlet");
Route::post("master/campaign/storecomponent", "CampaignController@storecomponent");
Route::post("master/campaign/storeprogram", "CampaignController@storeprogram");
Route::post("master/campaign/storetarget", "CampaignController@storetarget");
Route::post("master/campaign/remove/{id}", "CampaignController@remove");
Route::post("master/campaign/runcampaign", "CampaignController@runcampaign");
Route::post("master/campaign/closecampaign", "CampaignController@closecampaign");
Route::post("master/campaign/releasedana/{id}", "CampaignController@previewreleasedana");
Route::get("master/campaign/releasedana/{id}", "CampaignController@previewreleasedana");
Route::post("master/campaign/release/{id}", "CampaignController@releasedana");
// !!!!!! Base table or view not found: 1146 Table 'zeals.tb_voucher' doesn't exist

// withdrawal
Route::get("master/withdrawal", "WithdrawalController@index");
Route::post("master/withdrawal/approve/{id}", "WithdrawalController@approve");
// !!!!! Base table or view not found: 1146 Table 'zeals.tb_withdrawal' doesn't exist


// laporan campaign
Route::get("master/laporan-campaign", "LaporanCampaignController@index");
Route::post("master/laporan-campaign/approve/{id}", "LaporanCampaignController@approve");
Route::post("master/laporan-campaign/remove/{id}", "LaporanCampaignController@remove");
Route::get("master/laporan-campaign/detail/{id}", "LaporanCampaignController@detail");
Route::get("master/laporan-campaign/reject/{id}", "LaporanCampaignController@reject");
Route::post("master/laporan-campaign/changestatus/{id}", "LaporanCampaignController@changestatus");
Route::get("master/laporan-campaign/create/{id}", "LaporanCampaignController@create");
Route::post("master/laporan-campaign/create_laporan/{id}", "LaporanCampaignController@create_laporan");

// industri penerbit
Route::get("master/category", "SektorIndustriController@index");
Route::get("master/category/create", "SektorIndustriController@create");
Route::post("master/category/store", "SektorIndustriController@store");
Route::get("master/category/detail/{id}", "SektorIndustriController@detail");
Route::get("master/category/edit/{id}", "SektorIndustriController@edit");
Route::post("master/category/update/{id}", "SektorIndustriController@update");
Route::post("master/category/remove/{id}", "SektorIndustriController@remove");

// fee setting
Route::get("master/feesetting", "FeeSettingController@index");
// Route::post("master/feesetting/update", "FeeSettingController@update");
Route::post("master/feesetting/update/belisaham", "FeeSettingController@updateBeliSaham");
Route::post("master/feesetting/update/releasedeviden", "FeeSettingController@updateReleaseDeviden");
Route::post("master/feesetting/update/topup", "FeeSettingController@updateTopup");
Route::post("master/feesetting/update/pencairan", "FeeSettingController@updatePencairan");
Route::post("master/feesetting/update/releasependanaan", "FeeSettingController@updateReleasePendanaan");



// kelola data kyc
// Route::get("master/kyc", "KYCController@index");
// Route::get("master/kyc/detail/{id}", "KYCController@detail");
// Route::post("master/kyc/status/{id}", "KYCController@status");
// !!! Controller doesn't exist


// kelola custodian
// Route::get("custodian/user", "CustodianController@indexUser");
// Route::post("custodian/user/create", "CustodianController@createUser");
// !!! Class "App\Http\Models\CustodianModel" not found

// Route::get("custodian/purchase", "CustodianController@indexSaham");
// Route::post("custodian/purchase/create", "CustodianController@createSaham");
// !!! Class "App\Http\Models\CustodianModel" not found




Route::post('apiv1/checkCode', 'AffiliatorController@checkCode');
Route::post('apiv1/setTrackerTrue', 'AffiliatorController@setTrackerTrue');
// Route::post('cors/auth', 'apis\ApiV1Controller@getBanners');
Route::post('apiv1/AMPcallback', 'AffiliatorController@AMPcallback');

// Route::get('apiv1/getBanner', 'apis\ApiV1Controller@getBanners');
// Route::post('apiv1/auth', 'apis\ApiV1Controller@auth');
// Route::get('apiv1/getDisclaimer', 'apis\ApiV1Controller@getDisclaimer');
// Route::get('apiv1/getCampaigns', 'apis\ApiV1Controller@getCampaigns');
// Route::get('apiv1/getPraPemasaran', 'apis\ApiV1Controller@getPraPemasaran');
// Route::get('apiv1/getDetailCampaign/{id}/{token}', 'apis\ApiV1Controller@getDetailCampaign');
// Route::get('apiv1/getDetailPraPemasaran/{id}', 'apis\ApiV1Controller@getDetailPraPemasaran');
// Route::post('apiv1/getProfile/', 'apis\ApiV1Controller@getProfile');
// Route::post('apiv1/getProfileDanJenisPerusahaan/', 'apis\ApiV1Controller@getProfileDanJenisPerusahaan');
// Route::post('apiv1/createOTP', 'apis\ApiV1Controller@createOTP');
// Route::post('apiv1/getOTP', 'apis\ApiV1Controller@getOTP');
// Route::post("apiv1/passpasscode", "apis\ApiV1Controller@passingPasscode");
// Route::post('apiv1/checkOTP', 'apis\ApiV1Controller@checkOTP');
// Route::post('apiv1/updateProfile', 'apis\ApiV1Controller@updateProfile');
// Route::post('apiv1/requestWithdrawal', 'apis\ApiV1Controller@requestWithdrawal');
// Route::post('apiv1/getRiwayatPencairan', 'apis\ApiV1Controller@getRiwayatPencairan');
// Route::post('apiv1/getNotifications', 'apis\ApiV1Controller@getNotifications');
// Route::post('apiv1/prepareTopup', 'apis\ApiV1Controller@prepareTopup');
// Route::get('apiv1/getInvoiceDetail/{trx_code}/{token}', 'apis\ApiV1Controller@getInvoiceDetail');
// Route::post('apiv1/createva', 'apis\ApiV1Controller@crateVA');
// Route::post('apiv1/vapaid', 'apis\ApiV1Controller@vapaid');
// Route::post('apiv1/placeorder', 'apis\ApiV1Controller@placeorder');
// Route::post('apiv1/placeorderByVA', 'apis\ApiV1Controller@placeorderByVA');
// Route::post('apiv1/getMyInvestment', 'apis\ApiV1Controller@getMyInvestment');
// Route::post('apiv1/getDetailInvestment', 'apis\ApiV1Controller@getDetailInvestment');
// Route::post('apiv1/getMyHistories', 'apis\ApiV1Controller@getMyHistories');
// Route::post('apiv1/postComment', 'apis\ApiV1Controller@postComment');
// Route::post('apiv1/postDukungan', 'apis\ApiV1Controller@postDukungan');
// Route::post('apiv1/submitPesan', 'apis\ApiV1Controller@submitPesan');
// Route::post('apiv1/getVideos', 'apis\ApiV1Controller@getVideos');
// Route::post('apiv1/getInfoBantuan', 'apis\ApiV1Controller@getInfoBantuan');
// Route::post('apiv1/submitPerusahaan', 'apis\ApiV1Controller@submitPerusahaan');

// // AUTH
// // register user
// Route::post('apiv1/registerUser', 'apis\ApiV1Controller@registerUser');
// Route::post('apiv1/sendEmailConfirmation', 'apis\ApiV1Controller@sendEmailConfirmation');
// Route::post('apiv1/emailCheckConfirmation', 'apis\ApiV1Controller@confirmEmailCode');
// Route::post('apiv1/setPasscode', 'apis\ApiV1Controller@setPassCode');
// !!!Controller doesn't exist

Route::post("apiv1/emailverification", 'EmailController@sendVerificationCode');


// kyc
// Route::get('apiv1/getkycpribadi', 'apis\ApiV1Controller@getKYCPribadi');
// Route::post('apiv1/postkycpribadi', 'apis\ApiV1Controller@postKYCPribadi');

// Route::post('apiv1/postkyckeluarga', 'apis\ApiV1Controller@postKYCKeluarga');
// Route::get('apiv1/getkyckeluarga', 'apis\ApiV1Controller@getKYCKeluarga');

// Route::post('apiv1/postkycalamat', 'apis\ApiV1Controller@postKYCAlamat');
// Route::get('apiv1/getkycalamat', 'apis\ApiV1Controller@getKYCAlamat');

// Route::post('apiv1/postkycpekerjaan', 'apis\ApiV1Controller@postKYCPekerjaan');
// Route::get('apiv1/getkycpekerjaan', 'apis\ApiV1Controller@getKYCPekerjaan');

// Route::post('apiv1/postkycpajak', 'apis\ApiV1Controller@postKYCPajak');
// Route::get('apiv1/getkycpajak', 'apis\ApiV1Controller@getKYCPajak');

// Route::post('apiv1/postkycbank', 'apis\ApiV1Controller@postKYCBank');
// Route::get('apiv1/getkycbank', 'apis\ApiV1Controller@getKYCBank');

// Route::post('apiv1/postsdank', 'apis\ApiV1Controller@postSyaratKetentuan');
// Route::get('apiv1/getsdank', 'apis\ApiV1Controller@getSyaratKetentuan');



// // coba xendit
// Route::post('apiv1/posttransaksi', 'apis\ApiV1Controller@createVA');

// //API

// Route::post('apiv1/getDashboardInfo', 'apis\ApiV1Controller@getDashboardInfo');
// Route::post('apiv1/getItemTransactions', 'apis\ApiV1Controller@getItemTransactions');
// Route::post('apiv1/removeItemTransaction', 'apis\ApiV1Controller@removeItemTransaction');
// Route::post('apiv1/searchItemAvailable', 'apis\ApiV1Controller@searchItemAvailable');
// Route::post('apiv1/addItemTransaction', 'apis\ApiV1Controller@addItemTransaction');
// Route::post('apiv1/hapusTransaction', 'apis\ApiV1Controller@hapusTransaction');
// Route::post('apiv1/payPayment', 'apis\ApiV1Controller@payPayment');
// Route::post('apiv1/getCategories', 'apis\ApiV1Controller@getCategories');
// Route::post('apiv1/setorModal', 'apis\ApiV1Controller@setorModal');
// Route::post('apiv1/getQR', 'apis\ApiV1Controller@getQR');

// Route::post('apiv1/getlistorder', 'apis\ApiV1Controller@getlistorder');
// Route::post('apiv1/prepareorder', 'apis\ApiV1Controller@prepareorder');
// Route::post('apiv1/calculateordertobahan', 'apis\ApiV1Controller@calculateordertobahan');
// Route::post('apiv1/detailorder', 'apis\ApiV1Controller@detailorder');
// Route::post('apiv1/simpanbarangmasuk', 'apis\ApiV1Controller@simpanbarangmasuk');
// Route::post('apiv1/getlistmodal', 'apis\ApiV1Controller@getlistmodal');
// Route::post('apiv1/getlistpengeluaran', 'apis\ApiV1Controller@getlistpengeluaran');
// Route::post('apiv1/setorPengeluaran', 'apis\ApiV1Controller@setorPengeluaran');
// Route::post('apiv1/getlistbahanbaku', 'apis\ApiV1Controller@getlistbahanbaku');
// Route::post('apiv1/getlistProductAvailable', 'apis\ApiV1Controller@getlistProductAvailable');
// Route::post('apiv1/getlistproduction', 'apis\ApiV1Controller@getlistproduction');
// Route::post('apiv1/doProduction', 'apis\ApiV1Controller@doProduction');
// Route::post('apiv1/getlistrevenue', 'apis\ApiV1Controller@getlistrevenue');
// Route::post('apiv1/getlistrevenueOfTHeDay', 'apis\ApiV1Controller@getlistrevenueOfTHeDay');
// Route::post('apiv1/getlistrevenueOfTHeDayByProduct', 'apis\ApiV1Controller@getlistrevenueOfTHeDayByProduct');
// Route::post('apiv1/getlistwaste', 'apis\ApiV1Controller@getlistwaste');
// Route::post('apiv1/getlistreturtransaction', 'apis\ApiV1Controller@getlistreturtransaction');
// Route::post('apiv1/setorRetur', 'apis\ApiV1Controller@setorRetur');
// Route::post('apiv1/getlistbahanreturable', 'apis\ApiV1Controller@getlistbahanreturable');
// !!!Controller doesn't exist
//API

//STORE KATEGORI
// Route::get('store/kategori', 'Store\Master_data\KategoriController@index')->name('kategori.index');
// Route::post('store/kategori/store', 'Store\Master_data\KategoriController@store')->name('kategori.store');
// Route::get('store/kategori/{id}', 'Store\Master_data\KategoriController@edit')->name('kategori.edit');
// Route::post('store/kategori/update/{id}', 'Store\Master_data\KategoriController@update')->name('kategori.update');
// Route::post('store/kategori/delete/{kategori}', 'Store\Master_data\KategoriController@destroy')->name('kategori.destroy');
// Controller doesn't exist


//STORE BRAND
// Route::get('store/brand', 'Store\Master_data\BrandController@index')->name('brand.index');
// Route::post('store/brand/store', 'Store\Master_data\BrandController@store')->name('brand.store');
// Route::get('store/brand/{id}', 'Store\Master_data\BrandController@edit')->name('brand.edit');
// Route::post('store/brand/update/{id}', 'Store\Master_data\BrandController@update')->name('brand.update');
// Route::post('store/brand/delete/{brand}', 'Store\Master_data\BrandController@destroy')->name('brand.destroy');
// Controllers doesn't exist

Route::post('get-transaction-cost', 'TransactionController@getTransactionCost');
Route::get('/', 'UserController@login');

Route::get('register', 'AffiliatorController@register');
Route::get('register/google', 'UserController@register');
Route::get('register/{any}', 'AffiliatorController@register');
// Table 'zeals.tb_page' doesn't exist

Route::post('register-submit', 'UserController@registersubmit');
Route::post('register-custom-submit', 'UserController@customValidate');

Route::get('reglink', 'UserController@registerbylink');
// zeals.tb_page doesn't exist

Route::post('api/createaccount', 'UserController@registersubmit');
Route::get('activate-account/{code}', 'UserController@activateaccount');
Route::get('activation/{code}', 'UserController@activateaccountviahp');

Route::get('master-qr', 'CampaignController@masterQR');
Route::get('master-qr/{permalink}', 'CampaignController@masterQR');
// 404 not found

Route::get('login', 'UserController@login');
Route::get('admin', 'UserController@login');
Route::post('login-auth', 'UserController@auth');
Route::post('login-public-auth-aff', 'UserController@authpublicaff');
Route::post('login-public-auth', 'UserController@authpublic');
Route::get('auth/google/callback', 'UserController@authGooglePlus');
Route::get('startAuthGoogle', 'UserController@startAuthGoogle');

// Route::get('forgot-password', 'AffiliatorController@forgetpassword');
// Route::post('forgot-password-submit', 'UserController@forgetpasswordsubmit');
// !!! Base table or view not found: 1146 Table 'zeals.tb_page' doesn't exist

Route::get('logout', 'UserController@logout');

// Route::get('signout', 'UserController@signout');
// Base table or view not found: 1146 Table 'zeals.tb_page' doesn't exist

Route::post('profile/preferences/store', 'AffiliatorController@preferencesstore');
Route::get('profile/preferences', 'AffiliatorController@preferences');
Route::get('profile', 'AffiliatorController@profile');
Route::post('profile/update', 'AffiliatorController@profileupdate');
Route::get('dashboard', 'AffiliatorController@dashboard');
Route::get('campaign', 'AffiliatorController@campaign');
Route::get('campaign/{id_category}', 'AffiliatorController@campaign');
Route::get('logs', 'AffiliatorController@logs');
Route::get('faq', 'AffiliatorController@faq');
Route::get('tutorial', 'AffiliatorController@tutorial');
Route::get('my-wallet', 'AffiliatorController@mywallet');
Route::get('my-wallet/withdraw', 'AffiliatorController@withdraw');
Route::post('my-wallet/withdraw/store', 'AffiliatorController@dowithdraw');
Route::get('campaign/detail/{projectcode}', 'AffiliatorController@detailcampaign');
Route::get('campaign/landing/{projectcode}', 'AffiliatorController@campaign_landing');
Route::get("campaign/joincampaign/{id}", "AffiliatorController@joincampaign");

Route::get("getIPLocation/{ip}", "AffiliatorController@getIPLocation");
Route::post("uservoucherevent", "AffiliatorController@uservoucherevent");
Route::post("fillevent", "AffiliatorController@fillevent");
Route::post("usevoucer", "AffiliatorController@usevoucer");
Route::post("checkOutlet", "AffiliatorController@checkOutlet");
Route::post("checkOutletsarea", "AffiliatorController@checkOutletsarea");
Route::post("getvoucher/{encrypted_code}", "AffiliatorController@getvoucher");
Route::get("voucher", "AffiliatorController@voucher");
Route::get("link/{unique_id}", "AffiliatorController@translate");
Route::get("platform/api/AMPcallback/{unique_id}/{campaign_link}", "AffiliatorController@callbackACQ");
/// !!!!!!!!! Base table or view not found: 1146 Table 'zeals.tb_page' doesn't exist

Route::get('admin/wallet', 'WalletController@index');
Route::get('dashboard/view', 'DashboardController@index');
Route::get('dashboard/transaction', 'DashboardController@transaction');
// Table 'zeals.tb_voucher' doesn't exist

Route::get('dashboard/affiliator', 'DashboardController@affiliator');

// Route::get('master/locator', 'LocatorController@index');
// Class "App\Http\Models\MitraModel" not found

// Route::get("admin/master", "MasterController@index");
// Controllers doesn't exist

Route::post("get-address-coordinate", "LocationController@searchaddress");

//module master user
Route::get("admin/user", "UserController@index");
Route::get('admin/user/create', "UserController@create");
Route::get('admin/user/profile/{id}', "UserController@profile");


Route::post('admin/user/store', "UserController@store");
Route::get('admin/user/edit/{id}', "UserController@edit");
Route::post('admin/user/update/{id}', "UserController@update");
Route::post('admin/user/remove', "UserController@remove");
Route::get('admin/user/performance/{id}', "UserController@performance");
Route::get('admin/user/detail/{id}', "UserController@detail");
Route::get('admin/user/manage/{id}', "UserController@manage");
Route::post('admin/user/updatemanage/{id}', "UserController@updatemanage");
Route::post('admin/user/activate_group', "UserController@activateallaccount");
// Table 'zeals.tb_voucher' doesn't exist


// push notif
Route::get('admin/push-notification', "FcmNotificationController@create");
Route::post('/submit-fcm', 'FcmNotificationController@store');

//module master user

//module master account
Route::get("admin/account", "AccountController@index");
Route::post('admin/account/setinactive', "AccountController@setinactive");
Route::post('admin/account/setactive', "AccountController@setactive");
Route::get('admin/account/detail/{id}', "AccountController@detail");
//module master account

//module master module
Route::get("admin/module", "ModuleController@index");
Route::get('admin/module/create', "ModuleController@create");
Route::post('admin/module/store', "ModuleController@store");
Route::get('admin/module/edit/{id}', "ModuleController@edit");
Route::post('admin/module/update/{id}', "ModuleController@update");
Route::post('admin/module/remove', "ModuleController@remove");
Route::get('admin/module/detail/{id}', "ModuleController@detail");
Route::get('admin/module/manage/{id}', "ModuleController@manage");
Route::get('admin/module/restore/{id}', "ModuleController@restore");
Route::get('admin/module/method/create/{id}', "ModuleController@createmethod");
Route::post('admin/module/method/store/{id}', "ModuleController@storemethod");
Route::get('admin/module/method/edit/{id}/{id_method}', "ModuleController@editmethod");
Route::post('admin/module/method/update/{id}/{id_method}', "ModuleController@updatemethod");
Route::post('admin/module/method/remove/{id}', "ModuleController@removemethod");
//module master module

//module master group user
Route::get("admin/group", "DepartmentController@index");
Route::get('admin/group/create', "DepartmentController@create");
Route::post('admin/group/store', "DepartmentController@store");
Route::get('admin/group/edit/{id}', "DepartmentController@edit");
Route::post('admin/group/update/{id}', "DepartmentController@update");
Route::post('admin/group/remove', "DepartmentController@remove");
Route::get('admin/group/detail/{id}', "DepartmentController@detail");
Route::get('admin/group/manage/{id}', "DepartmentController@manage");
Route::post('admin/group/updatemanage/{id}', "DepartmentController@updatemanage");
Route::get('admin/group/exportexcel/{id}', "DepartmentController@exportExcel");
//module master group user

//module master category product
// Route::get("admin/category", "CategoryController@index");
// Route::get('admin/category/create', "CategoryController@create");
// Route::post('admin/category/store', "CategoryController@store");
// Route::get('admin/category/edit/{id}', "CategoryController@edit");
// Route::post('admin/category/update/{id}', "CategoryController@update");
// Route::post('admin/category/remove', "CategoryController@remove");
// Route::get('admin/category/detail/{id}', "CategoryController@detail");
// Route::get('admin/category/restore/{id}', "CategoryController@restore");
// Table 'zeals.tb_product_category' doesn't exist
//module master category product


//module PRODUCT RESTRICTION
// Route::get("master/product/restriction/{id}", "ProductRestrictionController@index");
// Route::get('master/product/restriction/{id}/create', "ProductRestrictionController@create");
// Route::post('master/product/restriction/{id}/store', "ProductRestrictionController@store");
// Route::get('master/product/restriction/{id}/edit/{id_product_restriction}', "ProductRestrictionController@edit");
// Route::post('master/product/restriction/{id}/update/{id_product_restriction}', "ProductRestrictionController@update");
// Route::post('master/product/restriction/remove', "ProductRestrictionController@remove");
// Controllers doesn't exist

//module PRODUCT RESTRICTION

//module master ticket
// Route::get("master/ticket", "TicketController@index");
// Route::get('master/ticket/create', "TicketController@create");
// Route::post('master/ticket/store', "TicketController@store");
// Route::get('master/ticket/edit/{id}', "TicketController@edit");
// Route::post('master/ticket/update/{id}', "TicketController@update");
// Route::post('master/ticket/remove', "TicketController@remove");
// Route::get('master/ticket/detail/{id}', "TicketController@detail");
// Route::get('master/ticket/manage/{id}', "TicketController@manage");
// Route::get('master/ticket/restore/{id}', "TicketController@restore");
// Route::post('master/ticket/storecomment/{id}', "TicketController@storecomment");
// Route::post('master/ticket/markdone/', "TicketController@markdone");
// Base table or view not found: 1146 Table 'zeals.tb_ticket' doesn't exist

//module master ticket

//module pengaturan
Route::get("master/pengaturan", "SettingController@index");
Route::post("master/pengaturan/update", "SettingController@update");
//module pengaturan

//module stock resep
// Route::get("master/stock-resep", "StockRecipeController@index");
// Route::get("master/stock-resep/manage/{id}", "StockRecipeController@manage");
// Route::get("master/stock-resep/manage/{id}/use-item/{id_sub}", "StockRecipeController@useitem");
// Route::get("master/stock-resep/manage/{id}/restock/{id_sub}", "StockRecipeController@restock");
// Route::post("master/stock-resep/manage/{id}/dorestock/{id_sub}", "StockRecipeController@dorestock");
// Route::post("master/stock-resep/manage/{id}/storejoin/{id_sub}", "StockRecipeController@storejoin");
// Target class [App\Http\Controllers\StockRecipeController] does not exist.

//module stock resep

//== API ==//
//internal affiliate
// Route::get('/booking', 'BookingController@index');
Route::get('', 'AffiliatorController@signin');
// Route::get('/shop', 'HomeController@shop');
// Route::get('/shop/{permalink}', 'HomeController@detail');
// Route::get('/confirmation/{cart_code}', 'ConfirmationController@index');
// Route::get('/confirmation', 'ConfirmationController@index');
// Route::post('do-confirmation', 'ConfirmationController@doconfirm');
Route::get('/signin', 'AffiliatorController@signin');
// Route::get('/cancel-integration', 'AffiliatorController@cancelviagoogle');
// Route::get('/cart', 'HomeController@cart');
// Route::get('/checkout/{cart_code}', 'HomeController@checkout');
// Route::get('/checkout', 'HomeController@checkout');
// Route::post('do-checkout', 'HomeController@docheckout');
// Route::post('do-registrasi', 'UserController@doregistrasipublic');
// Route::post('do-registrasi-warung', 'UserController@doregistrasipublicwarung');
// Harusnya ga dipake lagi

//internal affiliate

//module Merchant
// Route::get("master/merchant", "MitraController@index");
// Route::get("master/merchant/create", "MitraController@create");
// Route::get("master/merchant/manage/{id}", "MitraController@manage");
// Route::post("master/merchant/store", "MitraController@store");
// Route::get("master/merchant/edit/{id}", "MitraController@edit");
// Route::post("master/merchant/update/{id}", "MitraController@update");
// Route::post("master/merchant/tolak", "MitraController@tolak");
// Route::post("master/merchant/terima", "MitraController@terima");
// Route::post("master/merchant/updateadmin/{id}", "MitraController@chooseadmin");
// Route::get("master/merchant/detail/{id}", "MitraController@detail");
// Route::post("master/merchant/remove", "MitraController@remove");
// Controller doesn't exist
//module Merchant


Route::get('test/{ip}', "DashboardController@testdata");
Route::get('grantall/{id}', "SetupController@grantall");

Route::get("get-unread-notifications", "NotificationController@getunread");
Route::post("read-notification", "NotificationController@readnotif");
Route::get("notifications", "NotificationController@index");
Route::get("load-more-notification", "NotificationController@loadmore");


Route::get("get-list-user", "UserController@findUser");
// Route::get("get-list-mitra", "MitraController@findMitra");
Route::get("get-list-material-puchased", "MaterialController@findMaterialPurchased");
Route::get("get-list-product", "ProductController@findProduct");
Route::get("get-list-of-bahan-setengah-jadi", "PPICController@findBahanSetengahJadi");
Route::get("get-list-of-bahan-siap-pakai", "PPICController@findBahanSiapPakai");
Route::post("update-order-data", "TransactionController@updateOrderNaikan");
Route::post("update-disetujui", "OrderOutletController@updateItem");
Route::post("update-purchase-pembelian", "PurchaseController@updateHargaDetail");
Route::get("get-list-campaign-by-penerbit", "CampaignController@searchcampaign");
Route::post("get-detail-rups", "RupsController@getdetailrups");
Route::post("get-laporan-campaign", "LaporanCampaignController@getlaporanscampaign");


#Home/Landing
// Route::get("policy", "HomeController@policy");
// Route::get("services", "HomeController@services");
// Route::get("card/{slug}", "NamecardsController@card");
Route::get("checkemail", "AffiliatorController@checkemail");
// Route::get("landing", "HomeController@landing");
#Home/Landing

//Demograph
// Route::get('admin/demograph', 'DemographController@index');
// Table 'zeals.tb_job' doesn't exist
//DEmograph

//Namecards
Route::get('admin/namecards', 'NamecardsController@index');
Route::get('admin/namecards/create', "NamecardsController@create");
Route::post('admin/namecards/save', "NamecardsController@save");
Route::get('admin/namecards/edit/{id}', "NamecardsController@edit");
Route::post('admin/namecards/update/{id}', "NamecardsController@update");
Route::post('admin/namecards/remove', "NamecardsController@remove");
//Namecards

//Bank List
Route::get('admin/bank', 'BankController@index');
Route::get('admin/bank/create', "BankController@create");
Route::post('admin/bank/save', "BankController@save");
Route::get('admin/bank/edit/{id}', "BankController@edit");
Route::post('admin/bank/update/{id}', "BankController@update");
Route::post('admin/bank/remove', "BankController@remove");
//Bank List
Route::get("master/microsite", "MicrositeController@index");
Route::get("master/microsite/create","MicrositeController@createMicrosite");
// Route::get("master/microsite/create/{id}","MicrositeController@createMicrosite");
Route::post("master/microsite/save","MicrositeController@saveMicrosite");
Route::get("master/microsite/setcomponent/{id}","MicrositeController@setComponent");
Route::post("master/microsite/savecomponent","MicrositeController@saveComponent");
Route::get("master/microsite/resume/{id}","MicrositeController@resume");
Route::get("master/microsite/report/{id}","MicrositeController@report");

Route::get("push-broaadcast","BroadcasterController@push");

//paling bawah
Route::get("{any}", "HomeController@notfound");
Route::get("{permalink}/{any}", "HomeController@notfound");


