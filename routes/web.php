<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\AgentResellerController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\KnowledgeCenterController;
use App\Http\Controllers\JatoController;
use App\Http\Controllers\ServiceProviderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});
//Clear route cache:
Route::get('/route-cache', function() {
Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});
//Clear config cache:
Route::get('/config-cache', function() {
  Artisan::call('config:cache');
return 'Config cache has been cleared';
});
// Clear view cache:
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
return 'View cache has been cleared';
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/updatecountryincity',[DealController::class,'updatecountryincity']);
Route::get('/countrystatecityupdate',[AdminController::class,'countrystatecityupdate']);
Route::get('/',[AdminController::class,'login']);
Route::get('app/privacy',[AdminController::class,'privacyPolicy']);
Route::get('/admin',[AdminController::class,'login']);
Route::post('/admin/login',[AdminController::class,'admin_login']);
Route::middleware(['middleware' => 'prevent-back-history','checkLogin'])->group(function(){
    //AdminController;
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/permissiondenied','permissiondenied');
        Route::get('/admin/dashboard','dashboard');
        Route::get('/admin/profile/{admin_id}','profile');
        Route::post('/admin/update_profile','update_profile');
        Route::get('/admin/logout','logout');
        Route::get('/admin/banners','banners');
        Route::get('/fetchMerchandise','fetchMerchandise');
        
        Route::get('/admin/addBanner','addBanner');
        Route::post('updateBannerOrder','updateBannerOrder');
        Route::post('/admin/insertBanner','insertBanner');
        Route::get('/admin/update_status/{id}/{table}','update_status');
        Route::get('/admin/delete/{id}/{table}','delete');
        Route::get('/admin/popular_new_cars','addBrand');
        Route::get('/admin/intro_video','intro_video');
        Route::post('/admin/update_intro_video','updateIntroVideo');
        Route::get('/admin/welcome_images','welcome_images');
        Route::get('/admin/add_welcome_image','add_welcome_image');
        Route::post('/admin/add_popular_city','add_popular_city');
        Route::post('/admin/delete_city','delete_city');
        Route::get('/admin/addCity','addCity');
        Route::post('/admin/insertCity','insertCity');
        Route::post('/admin/insertWelcomeImage','insertWelcomeImage');
        Route::post('/admin/change_banner_status','change_banner_status');
        Route::post('/admin/change_welcomeimage_status','change_welcomeimage_status');
        Route::post('updateBannerOrders','updateBannerOrders');
        Route::get('/admin/image_setting','image_setting');
        Route::post('/admin/image_setting_insert','image_setting_insert');
        Route::get('/admin/welcomeimagebackground','welcomeimagebackground');
        //city
        Route::get('/admin/countryList','countryList');
        Route::get('/admin/stateList','stateList');
        Route::get('/admin/cityList','cityList');
        Route::post('/admin/change_city_status','change_city_status');
        //new
        Route::post('/admin/change_country_status','change_country_status');
        //CarCheck
        Route::get('/admin/CarCheck','CarCheck');
        Route::get('/admin/addCarCheck','addCarCheck');
        Route::post('/admin/insert_carcheck','insert_carcheck');
        Route::post('/admin/delete_carCheck','delete_carCheck');
        Route::post('/admin/change_carcheck_status','change_carcheck_status');
        Route::get('/admin/carcheckdetail','carcheckdetail');
        Route::post('/admin/delete_carChecklist','delete_carChecklist');
    });
    //CategoryController;
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/admin/category','category');
        Route::get('/admin/addCategory','addCategory');
        Route::post('/admin/insert_category','insert_category');
        Route::post('/admin/delete_category','delete_category');
        Route::post('/admin/change_category_status','change_category_status');
    });
    //BrandController;
    Route::controller(BrandController::class)->group(function(){
        Route::get('/admin/brands','brands');
        Route::get('/admin/addBrand','addBrand');
        Route::post('/admin/insert_brand','insert_brand');
        Route::post('/admin/delete_brand','delete_brand');
        Route::post('/admin/deleteimage','deleteimage');
        Route::post('/admin/change_brand_status','change_brand_status');
        Route::post('/admin/add_popular_brand','add_popular_brand');
        Route::get('/admin/popularBrands','popularBrands');
        Route::post('updateBrandOrder','updateBrandOrder');
        Route::get('/admin/add_allbrand','add_allbrand');
        Route::post('/admin/filter_Categorydata','filter_Categorydata');
        Route::get('/admin/brandsdetails','brandsdetails');
    });
    //ServiceTypeController;
    Route::controller(ServiceTypeController::class)->group(function(){
        Route::get('/admin/serviceType','serviceType');
        Route::get('/admin/addserviceType','addserviceType');
        Route::post('/admin/insert_serviceType','insert_serviceType');
        Route::post('/admin/delete_serviceType','delete_serviceType');
        Route::post('/admin/change_servicetype_status','change_servicetype_status');
        Route::get('/admin/service','service');
        Route::get('/admin/addservice','addservice');
        Route::post('/admin/insert_service','insert_service');
        Route::post('/admin/delete_service','delete_service');
        Route::post('/admin/change_service_status','change_service_status');
        Route::get('/admin/serviceIcons','serviceIcons');
        Route::get('/admin/addServiceIcon','addServiceIcon');
        Route::post('/admin/insert_service_icon','insert_service_icon');
        Route::post('/admin/delete_service_icon','delete_service_icon');
        Route::post('/admin/change_service_icon_status','change_service_icon_status');
    });
    //AgentResellerController;
    Route::controller(AgentResellerController::class)->group(function(){
        Route::get('/admin/agentBanners','agentBanners');
        Route::get('/admin/addAgentBanner','addAgentBanner');
        Route::post('updateAgentBannerOrder','updateAgentBannerOrder');
        Route::post('/admin/insertAgentBanner','insertAgentBanner');
        Route::get('/admin/update_Agentstatus/{id}/{table}','update_Agentstatus');
        Route::get('/admin/deleteAgent/{id}/{table}','deleteAgent');

        Route::get('/admin/agent','agent');
        Route::get('/admin/addAgent','addAgent');
        Route::post('/admin/insert_agent','insert_agent');
        Route::post('/admin/delete_agent','delete_agent');
        Route::post('/admin/change_agent_status','change_agent_status');
        Route::post('/admin/add_popular_agent','add_popular_agent');
        Route::get('/admin/popularAgent','popularAgent');
        Route::post('updateAgentOrder','updateAgentOrder');
        Route::get('/admin/branch','branch');
        Route::get('/admin/addBranch','addBranch');
        Route::post('/admin/import-branches','importBranches');
        Route::post('/getState','getState');
        Route::post('/getCity','getCity');
        Route::post('/insert_branch','insert_branch');
        Route::post('/admin/change_branch_status','change_branch_status');
        Route::post('/admin/delete_branch','delete_branch');
        Route::post('/admin/change_agentbanner_status','change_agentbanner_status');
    });
    //MagazineController;
    Route::controller(MagazineController::class)->group(function(){
        Route::get('/admin/magazine','magazine');
        Route::get('/admin/addMagazine','addMagazine');
        Route::post('/admin/insert_magazine','insert_magazine');
        Route::post('/admin/delete_magazine','delete_magazine');
        Route::post('/admin/change_magazine_status','change_magazine_status');
        Route::post('/admin/addhomemagazine','addhomemagazine');
        Route::post('/admin/addbannermagazine','addbannermagazine');
        Route::post('/updatemagzineOrder','updatemagzineOrder');
        Route::get('/admin/magazinedata','magazinedata');
        Route::get('/admin/magazinecategory','magazinecategory');
        Route::get('/admin/addmagazinecategory','addmagazinecategory');
        Route::post('/admin/insert_magazinecategory','insert_magazinecategory');
        Route::post('/admin/delete_magazinecategory','delete_magazinecategory');
        Route::post('/admin/change_magazinecategory_status','change_magazinecategory_status');
        Route::post('/updatemagazinecategory','updatemagazinecategory');
        Route::post('/admin/get_subcategoryid','get_subcategoryid');
        Route::post('/admin/changetotalview','changetotalview');
        Route::get('/admin/magazineBanners','magazineBanners');
        Route::get('/admin/addmagazineBanner','addmagazineBanner');
        Route::post('updatemagazineBannerOrder','updatemagazineBannerOrder');
        Route::post('/admin/insertmagazineBanner','insertmagazineBanner');
        Route::get('/admin/update_magazinestatus/{id}/{table}','update_magazinestatus');
        Route::get('/admin/deletemagazinebanner/{id}/{table}','deletemagazinebanner');
        Route::post('/admin/change_magazinebanner_status','change_magazinebanner_status');
        Route::get('/admin/magazinemaincategory','magazinemaincategory');
        Route::get('/admin/addmagazinemaincategory','addmagazinemaincategory');
        Route::post('/admin/insert_magazinemaincategory','insert_magazinemaincategory');
        Route::post('/admin/delete_magazinemaincategory','delete_magazinemaincategory');
        Route::post('/admin/change_magazinemaincategory_status','change_magazinemaincategory_status');
        Route::post('/admin/updatemagazinemaincategory','updatemagazinemaincategory');
        //new
        Route::post('/admin/filter_maincategorydata','filter_maincategorydata');
        Route::post('/admin/filter_subcategorydata','filter_subcategorydata');
        //news
        Route::get('/admin/news','news');
        Route::get('/admin/addnews','addnews');
        Route::post('/admin/insert_news','insert_news');
        Route::get('/admin/newsdata','newsdata');
        Route::post('/admin/delete_news','delete_news');
        Route::post('/admin/change_news_status','change_news_status');
        Route::post('/admin/addhomenews','addhomenews');
        Route::post('/admin/addbannernews','addbannernews');
        Route::post('/updatenewsOrder','updatenewsOrder');
    });
    //UserController;
    Route::controller(UserController::class)->group(function(){
        Route::get('/admin/userList','userList');
        Route::post('/admin/change_user_status','change_user_status');
        Route::get('/admin/userDetail','userDetail');
        Route::get('/admin/mycar','mycar');
        Route::get('/admin/mycardetail','mycarservice');
        Route::get('/admin/edituser','edituser');
        Route::post('/admin/insert_user','insert_user');
    });
    //NotificationController;
    Route::controller(NotificationController::class)->group(function(){
        Route::get('/admin/notification','notification');
        Route::get('/admin/sendnotification','sendnotification');
        Route::post('/admin/insertNotification','insertNotification');
        Route::post('/admin/delete_notification','delete_notification');
        Route::post('/admin/getuser','getuser');
        Route::get('/admin/notificationdetails','notificationdetails');
    });
    //CarController;
    Route::controller(CarController::class)->group(function(){
        Route::get('/admin/carmodel','carmodel');
        Route::post('/admin/insert_carmodel','insert_carmodel');
        Route::get('/admin/carlist','carlist');
        Route::post('/admin/delete_car','delete_car');
        Route::post('/admin/change_car_status','change_car_status');
        Route::post('/admin/add_popular_car','add_popular_car');
        Route::get('/admin/cardetail','cardetail');
        Route::post('/admin/upload_car_images','upload_car_images');
        Route::post('/admin/fetch_api_car_images','fetch_api_car_images')->name('fetch.api.car.images');
        Route::post('/admin/upload_car_videos','upload_car_videos');
        Route::post('/admin/upload_car_360_image','upload_car_360_image');
        Route::post('/admin/updateSequence','update_car_360_image_sequence');
        Route::get('/admin/addcar','addcar');
        Route::post('/admin/insert_car','insert_car');
        Route::post('/admin/deleteimg','deleteimg');
        Route::post('/admin/deletevideo','deletevideo');
        Route::get('/admin/pricelist','pricelist');
        Route::get('/admin/addprice','addprice');
        Route::post('/admin/insert_carprice','insert_carprice');
        Route::post('/admin/delete_carprice','delete_carprice');
        Route::get('/admin/popularCar','popularCar');
        Route::post('/updateCarOrder','updateCarOrder');
        Route::post('/admin/insertcar','insertcar');
        Route::post('/admin/insertbrochure','insertbrochure');
        Route::get('/admin/addModel','addModel');
        //new
        Route::post('/admin/change_carmodel_status','change_carmodel_status');
        Route::post('/admin/delete_carcolor','delete_carcolor');
        Route::post('/admin/delete_carmileage','delete_carmileage');
        Route::post('/admin/delete_carengine','delete_carengine');
        Route::post('/admin/updateimagetype','updateimagetype');
    });
    //DealController;
    Route::controller(DealController::class)->group(function(){
        Route::get('/admin/dealList','dealList');
        Route::get('/admin/addDeal','addDeal');
        Route::post('/getdealtype','getdealtype');
        Route::post('/admin/insertdealtype','insertdealtype');
        Route::post('/admin/delete_deal','delete_deal');
        Route::post('/admin/change_deal_status','change_deal_status');
        Route::post('/admin/add_popular_deal','add_popular_deal');
        Route::post('updateDealOrder','updateDealOrder');
        Route::get('/admin/dealtype','dealtype');
        Route::get('/admin/adddealtype','adddealtype');
        Route::post('/admin/insert_dealtype','insert_dealtype');
        Route::post('/admin/delete_dealtype','delete_dealtype');
        Route::post('/admin/change_dealtype_status','change_dealtype_status');
        Route::post('/getmultipleState','getmultipleState');
        Route::post('/getmultipleCity','getmultipleCity');
        Route::post('/admin/filter_dealtypedata','filter_dealtypedata');
        Route::post('/getmultiplepage','getmultiplepage');
    });
    //StaffController;
    Route::controller(StaffController::class)->group(function(){
        Route::get('/admin/stafflist','stafflist');
        Route::get('/admin/addStaff','addStaff');
        Route::post('/admin/insert_staff','insert_staff');
        Route::post('/admin/delete_staff','delete_staff');
        Route::post('/admin/change_staff_status','change_staff_status');
        Route::get('/admin/log','log');
        Route::get('/admin/logDetail','logDetail');
        Route::post('/admin/changestaffpassword','changestaffpassword');
    });
    //KnowledgeCenterController;
    Route::controller(KnowledgeCenterController::class)->group(function(){
        Route::get('/admin/sponserBanners', 'sponserBanners');
        Route::get('/admin/addSponserBanner', 'addSponserBanner');
        Route::post('/admin/insertSponserBanner', 'insertSponserBanner');
        Route::get('/admin/deleteSponser/{id}/{table}', 'deleteSponser');
        Route::post('/admin/updatesponserBannerOrder', 'updatesponserBannerOrder');
        Route::post('/admin/change_sponserbanner_status', 'change_sponserbanner_status');
        Route::get('/admin/advice', 'advice');
        Route::get('/admin/sendadvice', 'sendadvice');
        Route::post('/admin/getuserlist', 'getuserlist');
        Route::post('/admin/insertadvice', 'insertadvice');
        Route::post('/admin/delete_advice', 'delete_advice');
        Route::get('/admin/advicedetail', 'advicedetail');
        Route::get('/admin/translation', 'translation');
        Route::get('/admin/translationdetail', 'translationdetail');
        Route::get('/admin/addtranslation', 'addtranslation');
        Route::post('/admin/inserttranslation', 'inserttranslation');
        Route::post('/admin/delete_translation', 'delete_translation');
        Route::get('/admin/knowledgeimagesection', 'knowledgeimagesection');
        Route::get('/admin/addknowledgeimagesection', 'addknowledgeimagesection');
        Route::post('/admin/addknowledgeimagesectionupdate', 'addknowledgeimagesectionupdate');
        Route::post('/admin/change_advice_status', 'change_advice_status');
        Route::post('/updateadviceOrder', 'updateadviceOrder');
    });
    //JatoController
    Route::controller(JatoController::class)->group(function(){
        Route::get('/admin/market', 'market');
        Route::get('/admin/carmakes', 'carmakes');
        Route::get('/admin/carmodels', 'carmodels');
        Route::get('/admin/carbodies', 'carbodies');
        Route::get('/admin/carvehicles', 'carvehicles');
        Route::get('/admin/carphoto', 'carphoto');

    });
    //ServiceProviderController
    Route::controller(ServiceProviderController::class)->group(function(){
        Route::get('/admin/serviceprovidercategory','serviceprovidercategory');
        Route::get('/admin/addserviceprovidercategory','addserviceprovidercategory');
        Route::post('/admin/insert_serviceprovidercategory','insert_serviceprovidercategory');
        Route::post('/admin/delete_serviceprovidercategory','delete_serviceprovidercategory');
        Route::post('/admin/change_serviceprovidercategory_status','change_serviceprovidercategory_status');
        //service
        Route::get('/admin/serviceproviderservices','serviceproviderservices');
        Route::get('/admin/addserviceproviderservice','addserviceproviderservice');
        Route::post('/admin/insert_serviceproviderservice','insert_serviceproviderservice');
        Route::post('/admin/delete_serviceproviderservice','delete_serviceproviderservice');
        Route::post('/admin/change_serviceproviderservice_status','change_serviceproviderservice_status');
        //service provider
        Route::get('/admin/serviceProvider','serviceProvider');
        Route::get('/admin/addServiceProvider','addServiceProvider');
        Route::post('/admin/insert_service_provider','insert_service_provider');
        Route::post('/admin/delete_serviceprovider','delete_serviceprovider');
        Route::post('/admin/change_service_provider_status','change_service_provider_status');
        Route::post('/updateserviceproviderOrder','updateserviceproviderOrder');
        //branch
        Route::get('/admin/service_provider_branch','service_provider_branch');
        Route::get('/admin/add_service_provider_branch','add_service_provider_branch');
        Route::post('/admin/insert_service_provider_branch','insert_service_provider_branch');
        Route::post('/admin/change_service_provider_branch_status','change_service_provider_branch_status');
        Route::post('/admin/delete_service_provider_branch','delete_service_provider_branch');
    });
});
