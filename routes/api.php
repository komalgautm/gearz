<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\AgentController;
use App\Http\Controllers\API\MagazineController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\MycarController;
use App\Http\Controllers\API\KnowledgeCenterController;
use App\Http\Controllers\API\OpenAIController;
use App\Http\Controllers\SpyneWebhookController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/registerUser',[ApiController::class,'registerUser']);
Route::post('/login',[ApiController::class,'login']);
Route::get('/welcometutorial',[ApiController::class,'welcometutorial']);
Route::post('/welcomeimage',[ApiController::class,'welcomeimage']);
Route::post('/device_verification',[ApiController::class,'device_verification']);
Route::post('/logout',[ApiController::class,'logout']);
Route::get('/isRepeatIntroVideo',[ApiController::class,'isRepeatIntroVideo']);
Route::post('/modelfueltypelist',[CarController::class,'modelfueltypelist']);
Route::get('/testNotification',[ApiController::class,'testNotification']);

Route::middleware('token.check')->group(function(){
    Route::post('/getServiceIconList',[ApiController::class,'getServiceIconList']);
    Route::post('/Category',[ApiController::class,'Category']);
    Route::post('/get_cityid',[ApiController::class,'get_cityid']);
    Route::post('/getUserProfile',[ApiController::class,'getUserProfile']);
    Route::post('/UpdateProfile',[ApiController::class,'UpdateProfile']);
    Route::post('/dashboard',[ApiController::class,'dashboard']);
    Route::post('/viewed',[ApiController::class,'viewed']);
    Route::post('/update_userlocation',[ApiController::class,'update_userlocation']);
    Route::post('/update_userlanguage',[ApiController::class,'update_userlanguage']);
    Route::post('/car_fillter',[ApiController::class,'car_fillter']);
    Route::post('/allBrand',[BrandController::class,'allBrand']);
    Route::post('/carMakeList',[BrandController::class,'carMakeList']);
    Route::post('/allaggent',[AgentController::class,'allaggent']);
    Route::post('/allmagazine',[MagazineController::class,'allmagazine']);
    Route::post('/magazinedata',[MagazineController::class,'magazinedata']);
    Route::post('/magazinetabdata',[MagazineController::class,'magazinetabdata']);
    Route::post('/addmagazineview',[MagazineController::class,'addmagazineview']);
    Route::post('/addnewsfavorites',[MagazineController::class,'addnewsfavorites']);
    Route::post('/newsfavorites_list',[MagazineController::class,'newsfavorites_list']);
    Route::post('/magazinedashboard',[MagazineController::class,'magazinedashboard']);
    Route::post('/branddetails',[BrandController::class,'branddetails']);
    Route::post('/carModelList',[BrandController::class,'carModelList']);
    Route::post('/carModelTrimList',[BrandController::class,'carModelTrimList']);
    Route::post('/addBrandreview',[BrandController::class,'addBrandreview']);
    Route::post('/agentdetails',[AgentController::class,'agentdetails']);
    Route::post('/branchservicetypes',[AgentController::class,'branchservicetypes']);
    Route::post('/agentbranchdetails',[AgentController::class,'agentbranchdetails']);
    Route::post('/addagentfavorites',[AgentController::class,'addagentfavorites']);
    Route::post('/agentfavorites_list',[AgentController::class,'agentfavorites_list']);
    Route::post('/AgentReview',[AgentController::class,'AgentReview']);
    Route::post('/notification',[NotificationController::class,'notification']);
    Route::post('/notificationdelete',[NotificationController::class,'notificationdelete']);
    Route::post('/notification_hide',[NotificationController::class,'notification_hide']);
    Route::post('/pngnotification',[NotificationController::class,'pngnotification']);
    Route::post('/notification_hidedata',[NotificationController::class,'notification_hidedata']);
    Route::post('/pngnotificationread',[NotificationController::class,'pngnotificationread']);
    Route::post('/car_details',[CarController::class,'car_details']);
    Route::post('getCarBrandDetails', [ApiController::class, 'getCarBrandDetails']);
    Route::post('/specificationAndFeature',[CarController::class,'specificationAndFeature']);
    Route::post('/addreview',[CarController::class,'addreview']);
    Route::post('/likedislike',[CarController::class,'likedislike']);
    Route::post('/modelslist',[CarController::class,'modelslist']);
    Route::post('/varientlist',[CarController::class,'varientlist']);
    Route::post('/carcompare',[CarController::class,'carcompare']);
    Route::post('/city',[ApiController::class,'city']);
    Route::post('/allevents',[MagazineController::class,'allevents']);
    Route::post('/addCarfavorites',[ApiController::class,'addCarfavorites']);
    Route::post('/favorites_list',[ApiController::class,'favorites_list']);
    Route::post('/addbrandfavorites',[ApiController::class,'addbrandfavorites']);
    Route::post('/brandfavorites_list',[ApiController::class,'brandfavorites_list']);
    Route::post('/allcar',[CarController::class,'allcar']);
    Route::post('/alldeals',[ApiController::class,'alldeals']);
    Route::post('/dealsdetail',[ApiController::class,'dealsdetail']);
    Route::post('/dealtype',[ApiController::class,'dealtype']);
    // Route::post('/logout',[ApiController::class,'logout']);
    Route::post('/home_search',[ApiController::class,'home_search']);
    Route::post('/save_click',[ApiController::class,'save_click']);
    Route::post('/yearsList',[MycarController::class,'yearsList']);
    Route::post('/getMyCarDetailByMMTY',[MycarController::class,'getMyCarDetailByMMTY']);
    Route::post('/mycar',[MycarController::class,'mycar']);
    Route::post('/edit_mycar',[MycarController::class,'edit_mycar']);
    Route::post('/my_carlist',[MycarController::class,'my_carlist']);
    Route::post('/my_cardetail',[MycarController::class,'my_cardetail']);
    Route::post('/addService',[MycarController::class,'addService']);
    Route::post('/editService',[MycarController::class,'editService']);
    Route::post('/addservicetask',[MycarController::class,'addservicetask']);
    Route::post('/editservicetask',[MycarController::class,'editservicetask']);
    Route::post('/servicetasklist',[MycarController::class,'servicetasklist']);
    Route::post('/servicetaskdetail',[MycarController::class,'servicetaskdetail']);
    Route::post('/servicelist',[MycarController::class,'servicelist']);
    Route::post('/servicedetail',[MycarController::class,'servicedetail']);
    Route::post('/deleteService',[MycarController::class,'deleteService']);
    Route::post('/deleteServicetask',[MycarController::class,'deleteServicetask']);
    Route::post('/addOdometer',[MycarController::class,'addOdometer']);
    Route::post('/editOdometer',[MycarController::class,'editOdometer']);
    Route::post('/deleteOdometer',[MycarController::class,'deleteOdometer']);
    Route::post('/last_odometer',[MycarController::class,'last_odometer']);
    Route::post('/deletemycar',[MycarController::class,'deletemycar']);
    Route::post('/deletemycarimage',[MycarController::class,'deletemycarimage']);
    Route::post('/car_detail_by_vin',[MycarController::class,'car_detail_by_vin']);
    Route::post('/getcarimage',[MycarController::class,'getcarimage']);
    Route::post('/car_draft',[MycarController::class,'car_draft']);
    Route::post('/addcarcheck',[MycarController::class,'addcarcheck']);
    Route::post('/cartransfer',[MycarController::class,'cartransfer']);
    //knowledge center
    Route::controller(KnowledgeCenterController::class)->group(function(){
        Route::post('/advice', 'advice');
        Route::post('/advice_details', 'advice_details');
        Route::post('/addadvicereview', 'addadvicereview');
        Route::post('/advicelikedislike', 'advicelikedislike');
        Route::post('/sponser_banner', 'sponser_banner');
        Route::post('/translation', 'translation');
        Route::post('/translationdetails', 'translationdetails');
        Route::post('/knowledge_image_section', 'knowledge_image_section');
        Route::post('/aichat', 'aichat');
        Route::post('/delete_advice', 'delete_advice');
    });
    Route::post('/ask', [OpenAIController::class, 'handleUserInput']);

});

