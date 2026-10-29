<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\VehicleExteriorColors;
use App\Models\VehicleInteriorColors;
use App\Models\VehicleTrimBody;
use App\Models\VehicleTrimEngine;
use App\Models\VehicleTrimMileage;
use App\Models\AgentBrand;
use App\Models\Magazine;
use App\Models\CarReview;
use App\Models\CarVideo;
use App\Models\CarImage;
use App\Models\User;
use App\Models\CarFavorite;
use App\Models\CarPrice;
use App\Models\CarReviewHelpfull;
use App\Models\NewsFavorite;
use App\Models\Agent;
use App\Models\AgentBanner;
use App\Models\AgentBranch;
use App\Models\BranchBrand;
use App\Models\ServiceType;
use App\Models\ServiceBranch;
use App\Models\Viewed;
use App\Models\CarClasses;
use App\Models\VehicleType;
use App\Models\CarCompareByUser;
use App\Models\CarJatoSpecification;
use App\Models\CarColor;
use App\Models\CarEngine;
use App\Models\CarMileage;
use App\Modules\ExternalSystem;
use App\Models\ApiTrimList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use DB;

class CarController extends Controller
{
    //car detail
    public function car_details(Request $request)
    {
        $userID = $request->userID;
        $modelID = $request->modelID;
        $locationID = $request->locationID;
        $vehicleID = $request->vehicleID;
        $language = $request->language;
        $year = $request->year;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($modelID == null) {
            $err_array[] = 'modelID';
        }
        if ($locationID == null) {
            $err_array[] = 'locationID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //overview
            if ($vehicleID == '') {
                $vehicle = CarJatoSpecification::where('modelID', $modelID)->where('year', $year)->where('status', 1)->orderBy('id', 'ASC');
            } else {
                $vehicle = CarJatoSpecification::where('id', $vehicleID)->where('modelID', $modelID)->where('year', $year)->where('status', 1)->orderBy('id', 'ASC');
            }
            if ($vehicle->count() == 0) {
                return response()->json(['success' => 'false', 'message' => "No Data Found"], 200);
            } else {
                $row = $vehicle->first();
                $dataover['vehicleID'] = $row->id;
                $dataover['modelID'] = $row->modelID;
                $dataover['brochure'] = $row->brochure;
                $CarImage = CarImage::where('vehicleID', $row->id);
                if ($CarImage->count() != 0) {
                    if ($CarImage->first()->image_type == 1) {
                        $dataover['image'] = url('public/images/car/' . $CarImage->first()->image);
                    } else {
                        $dataover['image'] = $CarImage->first()->image;
                    }
                    //$dataover['image'] =$CarImage->first()->image;
                } else {
                    $dataover['image'] = "";
                }
                if ($language == 2) {
                    $dataover['name'] = $row->name;
                } else {
                    $dataover['name'] = $row->name;
                }
                $dataover['year'] = $row->year;
                $dataover['numberOfDoors'] = $row->numberOfDoorsToDisplay;
                $dataover['shortBodyName'] = $row->shortBodyName;
                $dataover['bodyCode'] = $row->bodyCodeToDisplay;
                $dataover['drivenWheels'] = $row->drivenWheelsToDisplay;
                $dataover['fuelType'] = $row->fuelTypeToDisplay;
                $dataover['secondaryFuelType'] = $row->secondaryFuelTypeToDisplay;
                $dataover['transmission'] = $row->transmissionToDisplay;
                $dataover['derivative'] = $row->derivativeToDisplay;
                $dataover['trimLevel'] = $row->trimLevelToDisplay;
                $dataover['wheelbaseType'] = $row->wheelbaseTypeToDisplay;
                $dataover['derivativeGlobal'] = $row->derivativeGlobal;
                $dataover['derivativeLocal'] = $row->derivativeLocal;
                $dataover['engine'] = $row->engine;
                $dataover['seats'] = $row->seats;
                $dataover['liters'] = $row->liters;
                $dataover['maximumPowerKw'] = $row->maximumPowerKw;
                $dataover['maximumPowerhpPs'] = $row->maximumPowerhpPs;
                $dataover['vehicleType'] = $row->vehicleType;
                $dataover['cylinderCapacity'] = $row->cylinderCapacity;
                $dataover['roofType'] = $row->roofType;
                //CarColor
                $color_array = array();
                $carcolor = CarColor::where('vehicleID', $row->id)->get();
                foreach ($carcolor as $color_value) {
                    $data_color['id'] = $color_value->id;
                    $data_color['color'] = $color_value->value;
                    array_push($color_array, $data_color);
                }
                $dataover['color'] = $color_array;
                //CarEngine
                $car_engine_array = array();
                $car_engine = CarEngine::where('vehicleID', $row->id)->get();
                foreach ($car_engine as $car_engine_value) {
                    $data_engine['id'] = $car_engine_value->id;
                    if ($language == 2) {
                        $data_engine['key'] = $car_engine_value->name_ar;
                        $data_engine['value'] = $car_engine_value->value_ar;
                    } else {
                        $data_engine['key'] = $car_engine_value->name;
                        $data_engine['value'] = $car_engine_value->value;
                    }
                    array_push($car_engine_array, $data_engine);
                }
                $dataover['car_engine'] = $car_engine_array;
                //CarMileage
                $car_mileage_array = array();
                $car_mileage = CarMileage::where('vehicleID', $row->id)->get();
                foreach ($car_mileage as $car_mileage_value) {
                    $data_mileage['id'] = $car_mileage_value->id;
                    if ($language == 2) {
                        $data_mileage['key'] = $car_mileage_value->name_ar;
                        $data_mileage['value'] = $car_mileage_value->value_ar;
                    } else {
                        $data_mileage['key'] = $car_mileage_value->name;
                        $data_mileage['value'] = $car_mileage_value->value;
                    }
                    array_push($car_mileage_array, $data_mileage);
                }
                $dataover['car_mileage'] = $car_mileage_array;
                //price
                $carprice = CarPrice::select('price')->where('modelID', $row->modelID)->where('CityID', $locationID)->where('year', $year)->OrderBy('onroadprice', 'DESC');
                if ($carprice->count() != 0) {
                    $price = $carprice->get();
                    $pricearray = array();
                    foreach ($price as $pval) {
                        array_push($pricearray, $pval->price);
                    }
                    $dataover['price_range'] = "$" . current($pricearray) . ' - $' . end($pricearray);
                } else {
                    $dataover['price_range'] = "";
                }
                $avgStar = CarReview::where('vehicleID',$row->id)->avg('rate');
                $dataover['rating'] = "".number_format($avgStar,1);
                $CarReview = CarReview::where('vehicleID',$row->id);
                $dataover['reviews'] = $CarReview->count();
                //photo
                $photo_array = array();
                $photo = CarImage::where('vehicleID', $row->id)->get();
                foreach ($photo as $photovalue) {
                    if ($photovalue->image_type == 1) {
                        $dataphoto['image'] = url('public/images/car/' . $photovalue->image);
                    } else {
                        $dataphoto['image'] = $photovalue->image;
                    }
                    array_push($photo_array, $dataphoto);
                }
                $dataover['photo'] = $photo_array;

                $dataover['video'] = CarVideo::select('video', 'discription')->where('vehicleID', $row->id)->get();
                //review
                $review = CarReview::where('vehicleID', $row->id)->orderBy('id', 'DESC')->limit(4)->offset(0)->get();
                $reviewArray = array();
                foreach ($review as $revValue) {
                    if ($language == 2) {
                        $datarev['name'] = $row->name;
                    } else {
                        $datarev['name'] = $row->name;
                    }
                    $datarev['id'] = $revValue->id;
                    $datarev['userID'] = $revValue->userID;
                    $datarev['username'] = User::find($revValue->userID)->name;
                    $datarev['rate'] = $revValue->rate;
                    $datarev['title'] = $revValue->title;
                    $datarev['message'] = $revValue->message;
                    $datarev['create_date'] = date('M d,Y', strtotime($revValue->created_at));
                    $datarev['like'] = CarReviewHelpfull::where('likedislike', 1)->where('reviewID', $revValue->id)->count();
                    $datarev['dislike'] = CarReviewHelpfull::where('likedislike', 0)->where('reviewID', $revValue->id)->count();
                    array_push($reviewArray, $datarev);
                }
                $dataover['reviewlist'] = $reviewArray;



                //popularcar
                $vehicle = CarJatoSpecification::where('status', 1)->where('popular', 1)->orderBy('position', 'ASC')->get();
                $vehicleArray = array();
                foreach ($vehicle as $carvalue) {
                    $datavehicle['id'] = $carvalue->id;
                    $CarImage = CarImage::where('vehicleID', $carvalue->id);
                    if ($CarImage->count() != 0) {
                        if ($CarImage->first()->image_type == 1) {
                            $datavehicle['image'] = url('public/images/car/' . $CarImage->first()->image);
                        } else {
                            $datavehicle['image'] = $CarImage->first()->image;
                        }
                    } else {
                        $datavehicle['image'] = "";
                    }
                    if ($language == 2) {

                        $datavehicle['name'] = $carvalue->name;
                    } else {
                        $datavehicle['name'] = $carvalue->name;
                    }

                    $datavehicle['modelID'] = $carvalue->modelID;
                    $datavehicle['year'] = $carvalue->year;

                    $datavehicle['kmpl'] = $carvalue->maximumPowerhpPs . " HP";
                    $datavehicle['cc'] = $carvalue->maximumPowerKw . " KW";
                    $datavehicle['geartype'] = $carvalue->transmissionToDisplay;
                    $checkfav = CarFavorite::where('userID', $userID)->where('vehicleID', $carvalue->id)->count();
                    if ($checkfav != 0) {
                        $datavehicle['favorite'] = 1;
                    } else {
                        $datavehicle['favorite'] = 0;
                    }
                    $avgStar = CarReview::where('vehicleID', $carvalue->id)->avg('rate');
                    $datavehicle['rating'] = "" . $avgStar;
                    $CarReview = CarReview::where('vehicleID', $carvalue->id);
                    $datavehicle['reviews'] = $CarReview->count();
                    //$datavehicle['geartypes'] = $VehicleTrimEngine->transmission;
                    array_push($vehicleArray, $datavehicle);
                }
                $dataover['recommended'] = $vehicleArray;
                //delar id and number
                $agentid = '';
                $agentnumber = '';
                $brandID = $row->brandID;
                $AgentBrand = AgentBrand::where('brandID', $brandID);
                if ($AgentBrand->count() != 0) {
                    foreach ($AgentBrand->get() as $agentbrand) {
                        $checkagent = AgentBranch::where('agentID', $agentbrand->agentID)->where('city', $locationID);
                        if ($checkagent->count() != 0) {
                            $checkagentid = Agent::where('id', $agentbrand->agentID)->where('type', 1);
                            if ($checkagentid->count() != 0) {
                                $agentid = $agentbrand->agentID;
                                $agentnumber = $checkagent->first()->contact;
                            }
                        }
                    }

                    $dataover['dealerID'] = "".$agentid;
                    $dataover['dealernumber'] = $agentnumber;
                } else {
                    $dataover['dealerID'] = "";
                    $dataover['dealernumber'] = "";
                }
                //hotnews
                $news = Magazine::where('home', 1)->where('status', 1)->orderBy('postedDate', 'DESC')->get();
                $newsarray = array();
                foreach ($news as $nvalue) {
                    $datanews['id'] = $nvalue->id;
                    $datanews['image'] = $nvalue->image;
                    $datanews['videourl'] = $nvalue->videourl;
                    $datanews['postedBy'] = $nvalue->postedBy;
                    $datanews['postedDate'] = $nvalue->postedDate;
                    $datanews['tag_icon'] = $nvalue->tag_icon;
                    if ($language == 2) {
                        $datanews['heading'] = $nvalue->heading_ar;
                        $datanews['description'] = $nvalue->description_ar;
                        $datanews['tag_name'] = $nvalue->tag_name_ar;
                    } else {
                        $datanews['heading'] = $nvalue->heading;
                        $datanews['description'] = $nvalue->description;
                        $datanews['tag_name'] = $nvalue->tag_name;
                    }
                    $checknewsfav = NewsFavorite::where('userID', $userID)->where('newsID', $nvalue->id)->count();
                    if ($checknewsfav != 0) {
                        $datanews['favorite'] = 1;
                    } else {
                        $datanews['favorite'] = 0;
                    }
                    $datanews['totalviews'] = Viewed::where('menu_type', 2)->where('menuID', $nvalue->id)->count();
                    array_push($newsarray, $datanews);
                }
                $dataover['news'] =  mb_convert_encoding($newsarray, 'UTF-8', 'UTF-8');
                //overview

                //price and varyant
                $vehicledata = CarJatoSpecification::where('modelID', $modelID)->where('year', $year)->where('status', 1)->orderBy('id', 'ASC')->get();
                $pricearray = array();
                foreach ($vehicledata as $value) {
                    $pricedata['vehicle_id'] = $value->id;
                    if ($language == 2) {
                        $pricedata['name'] = CarModel::where('id', $value->modelID)->first()->name_ar;
                    } else {
                        $pricedata['name'] = CarModel::where('id', $value->modelID)->first()->name;
                    }
                    //$pricedata['name'] = $value->name;
                    $pricedata['year'] = $value->year;
                    $pricedata['model_id'] = $value->modelID;
                    $car_price = CarPrice::where('vehicleID', $value->id)->where('CityID', $locationID);
                    if ($car_price->count() != 0) {
                        $pricedata['ex-showroomprice'] = $car_price->first()->price;
                        $pricedata['RTO'] = $car_price->first()->rto;
                        $pricedata['insurance'] = $car_price->first()->insurance;
                        $pricedata['other'] = $car_price->first()->other;
                        $pricedata['onroadprice'] = $car_price->first()->onroadprice;
                    } else {
                        $pricedata['ex-showroomprice'] = "";
                        $pricedata['RTO'] = "";
                        $pricedata['insurance'] = "";
                        $pricedata['other'] = "";
                        $pricedata['onroadprice'] = "";
                    }
                    $pricedata['feature1key'] = "Engine";
                    $pricedata['feature1'] = $value->engine;
                    $pricedata['feature2key'] = "Transmission";
                    $pricedata['feature2'] = $value->transmissionToDisplay;
                    $pricedata['feature3key'] = "Driven Wheels";
                    $pricedata['feature3'] = $value->drivenWheelsToDisplay;
                    $pricedata['feature4key'] = "PowerKw";
                    $pricedata['feature4'] = $value->maximumPowerKw;
                    $pricedata['feature5key'] = "PowerhpPs";
                    $pricedata['feature5'] = $value->maximumPowerhpPs;
                    $pricedata['feature6key'] = "Fuel Type";
                    $pricedata['feature6'] = $value->fuelTypeToDisplay;
                    array_push($pricearray, $pricedata);
                }
                $pricev['price'] = $pricearray;
                $pricev['brandID'] = $row->brandID;
                $pricev['total_dealer'] = AgentBrand::where('brandID', $row->brandID)->count();
                $arr['overview'] = $dataover;
                $arr['price_and_variant'] = $pricev;
                //price and varyant
                $reviews = CarReview::where('vehicleID', $row->id)->orderBy('id', 'DESC')->get();
                $reviewArrays = array();
                foreach ($reviews as $revValues) {
                    if ($language == 2) {
                        $datarevs['name'] = $row->name;
                    } else {
                        $datarevs['name'] = $row->name;
                    }
                    $datarevs['id'] = $revValues->id;
                    $datarevs['userID'] = $revValues->userID;
                    $datarevs['username'] = User::find($revValues->userID)->name;
                    $datarevs['rate'] = number_format($revValues->rate, 1);
                    $datarevs['title'] = $revValues->title;
                    $datarevs['message'] = $revValues->message;
                    $datarevs['create_date'] = date('M d,Y', strtotime($revValues->created_at));
                    $datarevs['like'] = CarReviewHelpfull::where('likedislike', 1)->where('reviewID', $revValues->id)->count();
                    $datarevs['dislike'] = CarReviewHelpfull::where('likedislike', 0)->where('reviewID', $revValues->id)->count();
                    array_push($reviewArrays, $datarevs);
                }
                $arr['reviews'] = $reviewArrays;
                //total review on start
                $arr['1star'] = CarReview::where('vehicleID', $row->id)->whereRaw('ROUND(rate) = 1')->count();
                $arr['2star'] = CarReview::where('vehicleID', $row->id)->whereRaw('ROUND(rate) = 2')->count();
                $arr['3star'] = CarReview::where('vehicleID', $row->id)->whereRaw('ROUND(rate) = 3')->count();
                $arr['4star'] = CarReview::where('vehicleID', $row->id)->whereRaw('ROUND(rate) = 4')->count();
                $arr['5star'] = CarReview::where('vehicleID', $row->id)->whereRaw('ROUND(rate) = 5')->count();
                $image36 = CarImage::where('vehicleID',$row->id)->where('type',4);
                if($image36->count()!=0){
                    $arr['360'] =$image36->first()->image;
                }else{
                    $arr['360'] ="";
                }

                return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
            }
        }
    }

    //car specification
    public function specificationAndFeature(Request $request)
    {
        $userID = $request->userID;
        $vehicleID = $request->vehicle_id;
        $language = $request->language;
        $locationID = $request->locationID;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($vehicleID == null) {
            $err_array[] = 'vehicle_id';
        }
        if ($locationID == null) {
            $err_array[] = 'locationID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {

            $vehicle = CarJatoSpecification::where('id', $vehicleID);

            if ($vehicle->count() == 0) {
                return response()->json(['success' => 'false', 'message' => "No Data Found"], 200);
            } else {
                $row = $vehicle->first();
                $dataover['vehicleID'] = $row->id;
                $dataover['modelID'] = $row->modelID;
                $dataover['brochure'] = $row->brochure;
                $CarImage = CarImage::where('vehicleID', $row->id);
                if ($CarImage->count() != 0) {
                    if ($CarImage->first()->image_type == 1) {
                        $dataover['image'] = url('public/images/car/' . $CarImage->first()->image);
                    } else {
                        $dataover['image'] = $CarImage->first()->image;
                    }
                    //$dataover['image'] =$CarImage->first()->image;
                } else {
                    $dataover['image'] = "";
                }
                if ($language == 2) {
                    $dataover['name'] = $row->name;
                } else {
                    $dataover['name'] = $row->name;
                }
                $dataover['year'] = $row->year;
                $dataover['numberOfDoors'] = $row->numberOfDoorsToDisplay;
                $dataover['shortBodyName'] = $row->shortBodyName;
                $dataover['bodyCode'] = $row->bodyCodeToDisplay;
                $dataover['drivenWheels'] = $row->drivenWheelsToDisplay;
                $dataover['fuelType'] = $row->fuelTypeToDisplay;
                $dataover['secondaryFuelType'] = $row->secondaryFuelTypeToDisplay;
                $dataover['transmission'] = $row->transmissionToDisplay;
                $dataover['derivative'] = $row->derivativeToDisplay;
                $dataover['trimLevel'] = $row->trimLevelToDisplay;
                $dataover['wheelbaseType'] = $row->wheelbaseTypeToDisplay;
                $dataover['derivativeGlobal'] = $row->derivativeGlobal;
                $dataover['derivativeLocal'] = $row->derivativeLocal;
                $dataover['engine'] = $row->engine;
                $dataover['seats'] = $row->seats;
                $dataover['liters'] = $row->liters;
                $dataover['maximumPowerKw'] = $row->maximumPowerKw;
                $dataover['maximumPowerhpPs'] = $row->maximumPowerhpPs;
                $dataover['vehicleType'] = $row->vehicleType;
                $dataover['cylinderCapacity'] = $row->cylinderCapacity;
                $dataover['roofType'] = $row->roofType;
                //CarColor
                $color_array = array();
                $carcolor = CarColor::where('vehicleID', $row->id)->get();
                foreach ($carcolor as $color_value) {
                    $data_color['id'] = $color_value->id;
                    $data_color['color'] = $color_value->value;
                    array_push($color_array, $data_color);
                }
                $dataover['color'] = $color_array;
                //CarEngine
                $car_engine_array = array();
                $car_engine = CarEngine::where('vehicleID', $row->id)->get();
                foreach ($car_engine as $car_engine_value) {
                    $data_engine['id'] = $car_engine_value->id;
                    if ($language == 2) {
                        $data_engine['key'] = $car_engine_value->name_ar;
                        $data_engine['value'] = $car_engine_value->value_ar;
                    } else {
                        $data_engine['key'] = $car_engine_value->name;
                        $data_engine['value'] = $car_engine_value->value;
                    }
                    array_push($car_engine_array, $data_engine);
                }
                $dataover['car_engine'] = $car_engine_array;
                //CarMileage
                $car_mileage_array = array();
                $car_mileage = CarMileage::where('vehicleID', $row->id)->get();
                foreach ($car_mileage as $car_mileage_value) {
                    $data_mileage['id'] = $car_mileage_value->id;
                    if ($language == 2) {
                        $data_mileage['key'] = $car_mileage_value->name_ar;
                        $data_mileage['value'] = $car_mileage_value->value_ar;
                    } else {
                        $data_mileage['key'] = $car_mileage_value->name;
                        $data_mileage['value'] = $car_mileage_value->value;
                    }
                    array_push($car_mileage_array, $data_mileage);
                }
                $dataover['car_mileage'] = $car_mileage_array;
                //price
                $carprice = CarPrice::select('price')->where('modelID', $row->modelID)->where('CityID', $locationID)->OrderBy('onroadprice', 'DESC');
                if ($carprice->count() != 0) {
                    $price = $carprice->get();
                    $pricearray = array();
                    foreach ($price as $pval) {
                        array_push($pricearray, $pval->price);
                    }
                    $dataover['price_range'] = "$" . current($pricearray) . ' - $' . end($pricearray);
                } else {
                    $dataover['price_range'] = "";
                }
                //photo
                $photo_array = array();
                $photo = CarImage::where('vehicleID', $row->id)->get();
                foreach ($photo as $photovalue) {
                    if ($photovalue->image_type == 1) {
                        $dataphoto['image'] = url('public/images/car/' . $photovalue->image);
                    } else {
                        $dataphoto['image'] = $photovalue->image;
                    }
                    array_push($photo_array, $dataphoto);
                }
                $dataover['photo'] = $photo_array;

                $dataover['video'] = CarVideo::select('video', 'discription')->where('vehicleID', $row->id)->get();
                ///////////////////////////////////////

                return response()->json(['success' => 'true', 'message' => '', 'result' => $dataover], 200);
            }
        }
    }

    public function addreview(Request $request)
    {
        $userID = $request->userID;
        $vehicle_id = $request->vehicle_id;
        $rate = $request->rate;
        $title = $request->title;
        $message = $request->message;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($vehicle_id == null) {
            $err_array[] = 'vehicle_id';
        }
        if ($rate == null) {
            $err_array[] = 'rate';
        }
        if ($title == null) {
            $err_array[] = 'title';
        }
        if ($message == null) {
            $err_array[] = 'message';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "شكرا لك ";
                $msg_failed = "تم تقييم السيارة مسبقا";
            } else {
                $msg_success = "Thank you for your rating.";
                $msg_failed = "A rating has already been given for this vehicle.";
            }
            $check = CarReview::where('userID', $userID)->where('vehicleID', $vehicle_id)->count();
            $modelID = CarJatoSpecification::where('id', $vehicle_id)->first()->modelID;
            if ($check == 0) {
                $review = new CarReview;
                $review->vehicleID = $vehicle_id;
                $review->userID = $userID;
                $review->rate = $rate;
                $review->modelID = $modelID;
                $review->title = $title;
                $review->message = $message;
                $review->like = 0;
                $review->dislike = 0;
                $review->save();
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            } else {
                return response()->json(['success' => 'false', 'message' => $msg_failed], 400);
            }
        }
    }

    //All car list
    // public function allcar(Request $request)
    // {
    //     $userID = $request->userID;
    //     $language = $request->language;
    //     $locationID = $request->locationID;
    //     $brand_ids = $request->brand_ids;
    //     $vehicle_type_ids = $request->vehicle_type_ids;
    //     $fule_ids = $request->fule_ids;
    //     $seats = $request->seats;
    //     $cylinder = $request->cylinder;
    //     $transmission = $request->transmission;
    //     $maxprice = $request->maxprice;
    //     $filter = $request->filter;
    //     $page = $request->input('page', 1);
    //     $limit = $request->input('limit', 20);
    //     // $searchKey = $request->search;
    //     $searchKey = strtolower(trim($request->search ?? ''));
    //     // $filter = 1;
    //     // $brand_ids = [37];
    //     // $vehicle_type_ids = [];
    //     // $fule_ids = [];
    //     // $seats = [];
    //     // $cylinder = [];
    //     // $transmission = [];
    //     // $maxprice = 0;

    //     $err_array = array();
    //     if ($userID == null) {
    //         $err_array[] = 'userID';
    //     }
    //     if (count($err_array) > 0) {
    //         $er = implode(",", $err_array);
    //         return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
    //     } else {
    //         $vehicleArray = array();
    //         $vehiclevalArray = array();
    //         $fule_idArray = array();
    //         //$current_year = date('Y');
    //         $current_year = "2023";

    //         $brands = Brand::all();
            
    //         foreach ($brands as $key => $brand) {

    //             if (empty($brand->excel)) {
    //                 continue;
    //             }

    //             $filePath = public_path('brand/excel/' . ($brand->excel ?? ''));

    //             if (!file_exists($filePath)) {
    //                 continue;
    //             }

    //             $filteredRows = [];

    //             if (($handle = fopen($filePath, "r")) !== false) {
    //                 $sample = fgets($handle);
    //                 $sample = preg_replace('/^\xEF\xBB\xBF/', '', $sample); // remove BOM
    //                 rewind($handle);
    //                 $delimiter = strpos($sample, ';') !== false ? ';' : ',';

    //                 $rawHeaders = fgetcsv($handle, 0, $delimiter);
    //                 if (!$rawHeaders) {
    //                     fclose($handle);
    //                     continue;
    //                 }

    //                 $headers = array_map(function ($header) {
    //                     $header = trim($header);
    //                     $header = preg_replace('/\s+/', '_', $header);
    //                     $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header);
    //                     return $header;
    //                 }, $rawHeaders);

    //                 while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
    //                     if (count($row) !== count($headers)) {
    //                         continue;
    //                     }

    //                     $row = array_map('trim', $row);
    //                     $assocRow = array_combine($headers, $row);

    //                     if (!empty($assocRow['Make'])) {

    //                         if ($searchKey !== '') {
    //                             $match = false;

    //                             if (
    //                                 isset($assocRow['Make']) && stripos($assocRow['Make'], $searchKey) !== false ||
    //                                 isset($assocRow['Model']) && stripos($assocRow['Model'], $searchKey) !== false ||
    //                                 isset($assocRow['Trim']) && stripos($assocRow['Trim'], $searchKey) !== false
    //                             ) {
    //                                 $match = true;
    //                             }

    //                             if (!$match) {
    //                                 continue; // Skip if none of the fields match
    //                             }
    //                         }

    //                         $price = '';
    //                         foreach ($assocRow as $key => $value) {
    //                             if (stripos($key, 'price') !== false && !empty($value)) {
    //                                 $price = $value;
    //                                 break;
    //                             }
    //                         }

    //                         $formatted = [
    //                             'make' => $assocRow['Make'] ?? '',
    //                             'model' => $assocRow['Model'] ?? '',
    //                             'trim' => $assocRow['Trim'] ?? '',
    //                             'model_year' => $assocRow['Model_Year'] ?? '',
    //                             'kmpl' => $assocRow['Horsepower_(hp)'] ?? '',
    //                             'cc' => $assocRow['Engine_Displacement_(cc)'] ?? '',
    //                             'geartype' => $assocRow['Transmission_Type'] ?? '',
    //                             'rating' => $assocRow['Safety_Rating'] ?? '',
    //                             'price' => $price,
    //                         ];
    //                         $filteredRows[] = $formatted;
    //                     }
    //                 }

    //                 fclose($handle);
    //             }

    //             foreach ($filteredRows as $carvalue) {
    //                 $checkfav = CarFavorite::where(['userID' => $userID, 'make' => $carvalue['make'], 'model' => $carvalue['model'], 'trim' => $carvalue['trim'], 'year' => $carvalue['model_year']])->count();
    //                 if ($checkfav != 0) {
    //                     $favorite = 1;
    //                 } else {
    //                     $favorite = 0;
    //                 }
    //                 $vehicleArray[] = [
    //                     'id' => $brand->id,
    //                     'make' => $carvalue['make'],
    //                     'model' => $carvalue['model'],
    //                     'trim' => $carvalue['trim'],
    //                     'year' => $carvalue['model_year'],
    //                     'kmpl' => $carvalue['kmpl'] . " HP",
    //                     'cc' => $carvalue['cc'] . " KW",
    //                     'geartype' => $carvalue['geartype'],
    //                     'favorite' => $favorite,
    //                     'rating' => $carvalue['rating'],
    //                     'reviews' => 0,
    //                     'image' => "",
    //                     'price' => $carvalue['price'],
    //                 ];
    //             }
    //         }

    //         $total = count($vehicleArray);
    //         $page = (int) $request->input('page', 1);
    //         $limit = (int) $request->input('limit', 20);
    //         if ($limit <= 0) {
    //             $limit = 20; // fallback to default to prevent division by zero
    //         }
    //         $offset = ($page - 1) * $limit;

    //         $paginatedData = array_slice($vehicleArray, $offset, $limit);

    //         // Paginated Response
    //         return response()->json([
    //             'success' => 'true',
    //             'message' => '',
    //             'result' => $paginatedData,
    //             'pagination' => [
    //                 'total' => $total,
    //                 'page' => $page,
    //                 'limit' => $limit,
    //                 'total_pages' => ceil($total / $limit),
    //                 'has_next' => $offset + $limit < $total,
    //                 'has_previous' => $page > 1,
    //             ]
    //         ], 200);

    //     }
    // }

    public function allcar(Request $request)
    {
        $userID = $request->userID;
        $page = max((int) $request->input('page', 1), 1);
        $limit = max((int) $request->input('limit', 20), 1);
        $offset = ($page - 1) * $limit;
        $searchKey = strtolower(trim($request->search ?? ''));

        if (!$userID) {
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing userID"], 400);
        }

        $vehicleArray = [];
        $brands = Brand::all();
        $collected = 0;
        $serial = $offset + 1;

        foreach ($brands as $brand) {
            if (empty($brand->excel)) continue;

            $filePath = public_path('brand/excel/' . $brand->excel);
            if (!file_exists($filePath)) continue;

            if (($handle = fopen($filePath, "r")) !== false) {
                $sample = fgets($handle);
                $sample = preg_replace('/^\xEF\xBB\xBF/', '', $sample);
                rewind($handle);
                $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                $rawHeaders = fgetcsv($handle, 0, $delimiter);
                if (!$rawHeaders) {
                    fclose($handle);
                    continue;
                }

                $headers = array_map(function ($header) {
                    $header = trim($header);
                    $header = preg_replace('/\s+/', '_', $header);
                    return str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header);
                }, $rawHeaders);

                $rowsRead = 0;
                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if (count($row) !== count($headers)) continue;

                    $row = array_map('trim', $row);
                    $assocRow = array_combine($headers, $row);

                    if (empty($assocRow['Make'])) continue;

                    if ($searchKey !== '') {
                        $match = false;
                        if (
                            (isset($assocRow['Make']) && stripos($assocRow['Make'], $searchKey) !== false) ||
                            (isset($assocRow['Model']) && stripos($assocRow['Model'], $searchKey) !== false) ||
                            (isset($assocRow['Trim']) && stripos($assocRow['Trim'], $searchKey) !== false)
                        ) {
                            $match = true;
                        }
                        if (!$match) continue;
                    }

                    if ($collected >= $offset + $limit) {
                        break 2; // collected all needed
                    }

                    if ($collected >= $offset) {
                        $price = '';
                        foreach ($assocRow as $k => $v) {
                            if (stripos($k, 'price') !== false && !empty($v)) {
                                $price = $v;
                                break;
                            }
                        }

                        $checkfav = CarFavorite::where([
                            'userID' => $userID,
                            'make' => $assocRow['Make'],
                            'model' => $assocRow['Model'],
                            'trim' => $assocRow['Trim'],
                            'year' => $assocRow['Model_Year'] ?? ''
                        ])->count();

                        $vehicleArray[] = [
                            'serial' => $serial++,
                            'id' => $brand->id,
                            'make' => $assocRow['Make'] ?? '',
                            'model' => $assocRow['Model'] ?? '',
                            'trim' => $assocRow['Trim'] ?? '',
                            'year' => $assocRow['Model_Year'] ?? '',
                            'kmpl' => ($assocRow['Horsepower_(hp)'] ?? '') . " HP",
                            'cc' => ($assocRow['Engine_Displacement_(cc)'] ?? '') . " KW",
                            'geartype' => $assocRow['Transmission_Type'] ?? '',
                            'favorite' => $checkfav ? 1 : 0,
                            'rating' => $assocRow['Safety_Rating'] ?? '',
                            'reviews' => 0,
                            'image' => '',
                            'price' => $price,
                        ];
                    }
                    $collected++;
                }
                fclose($handle);
            }
        }

        $total = $collected;

        return response()->json([
            'success' => 'true',
            'message' => '',
            'result' => $vehicleArray,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit),
                'has_next' => $offset + $limit < $total,
                'has_previous' => $page > 1,
            ]
        ], 200);
    }

    //car like dislike
    public function likedislike(Request $request)
    {
        $userID = $request->userID;
        $reviewID = $request->reviewID;
        $status = $request->status;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($reviewID == null) {
            $err_array[] = 'reviewID';
        }
        if ($status == null) {
            $err_array[] = 'status';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الإرسال بنجاح";
            } else {
                $msg_success = "Submitted successfully";
            }
            $check = CarReviewHelpfull::where('userID', $userID)->where('reviewID', $reviewID)->count();
            if ($check == 0) {
                $review = new CarReviewHelpfull;
                $review->userID = $userID;
                $review->reviewID = $reviewID;
                $review->likedislike = $status;
                $review->save();
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            } else {
                $likeid = CarReviewHelpfull::where('userID', $userID)->where('reviewID', $reviewID)->first()->id;
                $review = CarReviewHelpfull::find($likeid);
                $review->likedislike = $status;
                $review->save();
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            }
        }
    }

    //car model fuel type list
    public function modelfueltypelist(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $modelID = $request->modelID;
        $year = $request->year;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($brandID == null) {
            $err_array[] = 'brandID';
        }
        if ($modelID == null) {
            $err_array[] = 'modelID';
        }
        if ($year == null) {
            $err_array[] = 'year';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $brand = Brand::find($brandID);
            
            if (!$brand || empty($brand->excel)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Data not found.'
                ]);
            }

            $filePath = public_path('brand/excel/' . $brand->excel);
            
            // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Excel file does not exist in the specified path.'
                ]);
            }

            $filteredRows = [];
            $fullsheetRows = [];

            if (($handle = fopen($filePath, "r")) !== false) {
                $sample = fgets($handle);
                rewind($handle);
                $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                $rawHeaders = fgetcsv($handle, 0, $delimiter);
                $headers = array_map(function ($header) {
                    $header = trim($header);
                    $header = preg_replace('/\s+/', '_', $header); // spaces to _
                    $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                    return $header;
                }, $rawHeaders);

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if (count($row) !== count($headers)) {
                        continue;
                    }

                    $row = array_map('trim', $row);
                    $assocRow = array_combine($headers, $row);

                    $fullsheetRows[] = $assocRow;

                    if (
                        isset($assocRow['Model'], $assocRow['Model_Year']) &&
                        $assocRow['Model'] == $modelID &&
                        $assocRow['Model_Year'] == $year 
                    ) {
                        $formatted = [
                            'fueltype' => $assocRow['Fuel_Type']
                        ];

                        $filteredRows[] = $formatted;
                    }
                }

                fclose($handle);
            }

            $fueltypeArray = [];
            $uniqueFuelTypes = [];

            foreach ($filteredRows as $row) {
                $fueltype = $row['fueltype'];
                if (!in_array($fueltype, $uniqueFuelTypes) && $fueltype != "") {
                    $uniqueFuelTypes[] = $fueltype;
                    $fueltypeArray[] = [
                        'fueltype' => $fueltype
                    ];
                }
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $fueltypeArray], 200);
        }
    }

    //car model list
    public function modelslist(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $year = $request->year ?? date('Y');
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($brandID == null) {
            $err_array[] = 'brandID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            // $model = CarModel::where('brandID', $brandID)->where('status', 1)->get();
            // $recordArray = array();
            // foreach ($model as $value) {
            //     $data['id'] = $value->id;
            //     $data['brandID'] = $value->brandID;
            //     $data['makeID'] = $value->makeID;
            //     $data['modelID'] = $value->id;

            //     if ($language == 2) {
            //         $data['name'] = $value->name_ar;
            //     } else {
            //         $data['name'] = $value->name;
            //     }
            //     // $Vehicle = CarImage::where('modelID',$value->id);
            //     // if($Vehicle->count()!=0){
            //     //     $data['image'] = $Vehicle->first()->image;
            //     // }else{
            //     //     $data['image'] = "";
            //     // }
            //     $CarImage = CarImage::where('modelID', $value->id);
            //     if ($CarImage->count() != 0) {
            //         if ($CarImage->first()->image_type == 1) {
            //             $data['image'] = url('public/images/car/' . $CarImage->first()->image);
            //         } else {
            //             $data['image'] = $CarImage->first()->image;
            //         }
            //         //$dataover['image'] =$CarImage->first()->image;
            //     } else {
            //         $data['image'] = "";
            //     }
            //     $data['price'] = "";
            //     array_push($recordArray, $data);
            // }
            $brand = Brand::find($brandID);

            
            if (!$brand || empty($brand->excel)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Data not found.'
                ]);
            }

            $filePath = public_path('brand/excel/' . $brand->excel);
            
            // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Excel file does not exist in the specified path.'
                ]);
            }

            $filteredRows = [];
            $fullsheetRows = [];

            if (($handle = fopen($filePath, "r")) !== false) {
                $sample = fgets($handle);
                rewind($handle);
                $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                $rawHeaders = fgetcsv($handle, 0, $delimiter);
                $headers = array_map(function ($header) {
                    $header = trim($header);
                    $header = preg_replace('/\s+/', '_', $header); // spaces to _
                    $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                    return $header;
                }, $rawHeaders);

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if (count($row) !== count($headers)) {
                        continue;
                    }

                    $row = array_map('trim', $row);
                    $assocRow = array_combine($headers, $row);

                    $fullsheetRows[] = $assocRow;

                    if (
                        isset($assocRow['Model_Year']) &&
                        $assocRow['Model_Year'] == $year
                    ) {
                        $make = $assocRow['Make'];
                        $model = $assocRow['Model'];
                        $trim = $assocRow['Trim'];
                        // $existApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

                        // if ($existApiTrimList->isEmpty()) {
                        //     $responseData = ExternalSystem::trim_list($year, $make, $model);
                        //     $trims = $responseData['data'] ?? [];

                        //     foreach ($trims as $apitrim) {
                        //         $saveTrim = new ApiTrimList;
                        //         $saveTrim->year = '';
                        //         $saveTrim->make = $make;
                        //         $saveTrim->model = $model;
                        //         $saveTrim->trim = $apitrim;
                        //         $saveTrim->save();
                        //     }
                        // }

                        // $ApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

                        // $matchedTrim = null;
                        // foreach ($ApiTrimList as $singleapitrim) {
                        //     if (stripos($singleapitrim->trim, $trim) !== false) {
                        //         $matchedTrim = $singleapitrim->trim;
                        //         break;
                        //     }
                        // }

                        $photo = [];

                        // if (!$matchedTrim) {
                        //     $dbImages = CarImage::where([
                        //         'brand_id'=>$brandID,
                        //         'make'=>$assocRow['Make'],
                        //         'model'=>$assocRow['Model'],
                        //         'trim'=>$assocRow['Trim'],
                        //         'year'=>$assocRow['Model_Year']
                        //         ])->whereIn('type',[1,2,3])->get();

                        //     foreach ($dbImages as $img) {
                        //         $photo[] = ['image' => url('public/images').'/'. $img->image];
                        //     }
                        // }else{

                        //     $trimFolder = urlencode($matchedTrim);
                        //     $uploadPath = public_path("images/car/$make/$model/$trimFolder");

                        //     if (!file_exists($uploadPath)) {
                        //         mkdir($uploadPath, 0755, true);
                        //     }

                        //     $files = File::files($uploadPath);

                        //     if (count($files) == 0) {
                        //         $responseData = ExternalSystem::vehicle_image($make, $model, $matchedTrim, $year);
                        //         $allImages = [];

                        //         foreach (['exterior', 'interior', 'colors'] as $category) {
                        //             if (!empty($responseData['data']['images'][$category])) {
                        //                 foreach ($responseData['data']['images'][$category] as $imageUrl) {
                        //                     $allImages[] = $imageUrl;
                        //                     if (count($allImages) == 10) break 2;
                        //                 }
                        //             }
                        //         }

                        //         foreach ($allImages as $imageUrl) {
                        //             try {
                        //                 $contents = file_get_contents($imageUrl);
                        //                 $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        //                 $filename = 'img_' . time() . '_' . Str::random(5) . '.' . $ext;
                        //                 file_put_contents($uploadPath . '/' . $filename, $contents);
                        //             } catch (\Exception $e) {
                        //                 \Log::error("Failed to download image: $imageUrl - " . $e->getMessage());
                        //             }
                        //         }
                        //     }

                        //     $files = File::files($uploadPath);

                        //     if (count($files) > 0) {
                        //         foreach ($files as $file) {
                        //             $relativePath = str_replace(public_path(), '', $file->getPathname());
                        //             $photo[] = ['image' => url('public' . $relativePath)];
                        //         }
                        //     } else {
                                
                        //         $dbImages = CarImage::where([
                        //             'brand_id'=>$brandID,
                        //             'make'=>$assocRow['Make'],
                        //             'model'=>$assocRow['Model'],
                        //             'trim'=>$assocRow['Trim'],
                        //             'year'=>$assocRow['Model_Year']
                        //             ])->whereIn('type',[1,2,3])->get();

                        //         foreach ($dbImages as $img) {
                        //             $photo[] = ['image' => url('public/images').'/'. $img->image];
                        //         }
                        //     }
                        // }

                        $formatted = [
                            'make' => $assocRow['Make'],
                            'model' => $assocRow['Model'],
                            'body_style' => $assocRow['Body_Style'],
                            'model_year' => $assocRow['Model_Year'],
                            'photo' => count($photo) > 0 ? $photo[0]['image'] : ''
                        ];

                        $filteredRows[] = $formatted;
                    }

                }

                fclose($handle);
            }

            $modelArray = [];
            $uniqueModels = [];

            foreach ($filteredRows as $row) {
                $model = $row['model'];
                if (!in_array($model, $uniqueModels) && $model != "") {
                    $uniqueModels[] = $model;
                    $modelArray[] = [
                        'brand_id' => $brandID, 
                        'make' => $row['make'], 
                        'model' => $row['model'], 
                        'body_style' => $row['body_style'],
                        'model_year' => $row['model_year'],
                        'image' => $row['photo'] ?: (url("public/images/".$brand->logo) ?? url("public/img/image-preview.png"))
                    ];
                }
            }

            $yearArray = [];
            $uniqueYears = [];

            foreach ($fullsheetRows as $row) {
                $modelyear = $row['Model_Year'];

                if (!in_array($modelyear, $uniqueYears) && $modelyear != "") {
                    $uniqueYears[] = $modelyear;
                    $yearArray[] = ['year' => $modelyear];
                }
            }

            // Sort by 'year' in ascending order
            usort($yearArray, function ($a, $b) {
                return $a['year'] <=> $b['year'];
            });
            return response()->json(['success' => 'true', 'message' => "", 'year' => $year, 'yearList' => $yearArray, 'result' => $modelArray], 200);
        }
    }

    //car varientlist
    public function varientlist(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $modelID = $request->modelID;
        $year = $request->year;
        $fueltype = $request->fueltype ?? '';
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($brandID == null) {
            $err_array[] = 'brandID';
        }
        if ($modelID == null) {
            $err_array[] = 'modelID';
        }
        if ($year == null) {
            $err_array[] = 'year';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            // $Vehicle = CarJatoSpecification::where('modelID', $modelID)->where('status', 1)->get();
            $recordArray = array();
            $petrolArray = array();
            $dieselArray = array();
            // foreach ($Vehicle as $value) {
            //     $data['vehicleID'] = $value->id;
            //     $data['brandID'] = $value->brandID;
            //     $data['makeID'] = $value->makeID;
            //     $data['modelID'] = $value->modelID;
            //     if ($language == 2) {
            //         $data['name'] = $value->name_ar;
            //     } else {
            //         $data['name'] = $value->name;
            //     }
            //     //$data['name'] = $value->name;
            //     $data['price'] = "";
            //     //$engine_type = VehicleTrimEngine::where('vehicleID', $value->id)->first()->engine_type;
            //     $data['engine_type'] = $value->fuelTypeToDisplay;
            //     array_push($recordArray, $data);
            //     if ($value->fuelTypeToDisplay == "petrol") {
            //         array_push($petrolArray, $data);
            //     }
            //     if ($value->fuelTypeToDisplay== "diesel") {
            //         array_push($dieselArray, $data);
            //     }
            // }

            $brand = Brand::find($brandID);
            
            if (!$brand || empty($brand->excel)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Data not found.'
                ]);
            }

            $filePath = public_path('brand/excel/' . $brand->excel);
            
            // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Excel file does not exist in the specified path.'
                ]);
            }

            $filteredRows = [];
            $fullsheetRows = [];

            if (($handle = fopen($filePath, "r")) !== false) {
                $sample = fgets($handle);
                rewind($handle);
                $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                $rawHeaders = fgetcsv($handle, 0, $delimiter);
                $headers = array_map(function ($header) {
                    $header = trim($header);
                    $header = preg_replace('/\s+/', '_', $header); // spaces to _
                    $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                    return $header;
                }, $rawHeaders);

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if (count($row) !== count($headers)) {
                        continue;
                    }

                    $row = array_map('trim', $row);
                    $assocRow = array_combine($headers, $row);

                    $fullsheetRows[] = $assocRow;

                    if (
                        isset($assocRow['Model'], $assocRow['Model_Year']) &&
                        $assocRow['Model'] == $modelID &&
                        $assocRow['Model_Year'] == $year &&
                        (
                            empty($fueltype) || 
                            (isset($assocRow['Fuel_Type']) && $assocRow['Fuel_Type'] == $fueltype)
                        )
                    ) {
                        $key = $this->findHeaderKey($assocRow);
                        $basePrice = $key && isset($assocRow[$key]) ? $assocRow[$key] : '';
                        $formatted = [
                            'trim' => $assocRow['Trim'],
                            'fueltype' => $assocRow['Fuel_Type'],
                            'price' => $basePrice,
                        ];

                        $filteredRows[] = $formatted;
                    }

                }

                fclose($handle);
            }

            return response()->json(['success' => 'true', 'message' => "", 'data' => $filteredRows], 200);
        }
    }
    
    private function findHeaderKey(array $row, $targetPrefix = 'Base_Price')
    {
        foreach ($row as $key => $val) {
            $normalized = strtolower(preg_replace('/[^a-zA-Z_]/', '', $key));

            // Match headers that start with Base_Price and include currency in brackets, e.g., Base_Price_(SAR)
            if (preg_match('/^' . strtolower($targetPrefix) . '_?\([a-zA-Z]{3}\)$/', $key) || 
                strpos($normalized, strtolower($targetPrefix)) === 0) {
                return $key;
            }
        }

        return null;
    }

    //car compare
    public function carcompare(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $model = $request->model;
        $trim = $request->trim;
        $year = $request->year;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($brandID == null) {
            $err_array[] = 'brandID';
        }
        if ($model == null) {
            $err_array[] = 'model';
        }
        if ($trim == null) {
            $err_array[] = 'trim';
        }
        if ($year == null) {
            $err_array[] = 'year';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $brandIDArray = explode(",", $brandID);
            $modelArray = explode(",", $model);
            $trimArray = explode(",", $trim);
            $yearArray = explode(",", $year);
            $recordArray = array();
            //car compare
            if ($userID != 43) {
                $CarCompareByUser = new CarCompareByUser;
                $CarCompareByUser->userID = $userID;
                $CarCompareByUser->carID = $brandID.':'.$model.':'.$trim.':'.$year;
                $CarCompareByUser->save();
            }
            foreach ($brandIDArray as $index => $brand_id) {
                $brand = Brand::find($brand_id);
                
                if (!$brand || empty($brand->excel)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Data not found.'
                    ]);
                }
                
                $filePath = public_path('brand/excel/' . $brand->excel);
                
                // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');
                
                if (!file_exists($filePath)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Excel file does not exist in the specified path.'
                    ]);
                }
                
                $filteredRows = [];
                
                if (($handle = fopen($filePath, "r")) !== false) {
                    $sample = fgets($handle);
                    rewind($handle);
                    $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                    $rawHeaders = fgetcsv($handle, 0, $delimiter);
                    $headers = array_map(function ($header) {
                        $header = trim($header);
                        $header = preg_replace('/\s+/', '_', $header); // spaces to _
                        $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                        return $header;
                    }, $rawHeaders);

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        if (count($row) !== count($headers)) {
                            continue;
                        }

                        $row = array_map('trim', $row);
                        $assocRow = array_combine($headers, $row);
                        
                        if (
                            isset($assocRow['Model']) &&
                            $assocRow['Model'] == $modelArray[$index] &&
                            isset($assocRow['Model_Year']) &&
                            $assocRow['Model_Year'] == $yearArray[$index] &&
                            isset($assocRow['Trim']) &&
                            $assocRow['Trim'] == $trimArray[$index]
                        ) {
                            $key = $this->findHeaderKey($assocRow);
                            $basePrice = $key && isset($assocRow[$key]) ? $assocRow[$key] : '';
                            $formatted = [
                                'make' => $assocRow['Make'],
                                'model' => $assocRow['Model'],
                                'trim' => $assocRow['Trim'],
                                'year' => $assocRow['Model_Year'],
                                'price' => $basePrice,
                                'image' => url("public/images/".$brand->logo) ?? url("public/img/image-preview.png"),
                                'basic_information' => [
                                    'rating' => '4',
                                    'reviews' => '',
                                    'price' => $basePrice,
                                ],
                                'engine_transmission' => [
                                    'power' => $assocRow['Horsepower_(hp)'],
                                    'transmission' => $assocRow['Transmission_Type'],
                                ],
                                'fuel_performance' => [
                                    'engine_type' => $assocRow['Engine_Type'],
                                    'size' => $assocRow['Fuel_Tank_Capacity_(L)'],
                                ],
                                'dimension_capacity' => [
                                    'wheel_base' => $assocRow['Wheelbase_(mm)'],
                                    'cargo_capacity' => $assocRow['Cargo_Volume_(L)'],
                                ],
                                'comfort' => [
                                    'doors' => '4',
                                    'seats' => '6',
                                ],
                            ];
                            $filteredRows = $formatted;

                        }
                        
                    }
                    
                    fclose($handle);
                }
                // $Vehicle = CarJatoSpecification::where('id', $value)->first();
                // if ($language == 2) {
                //     $data['name'] = $Vehicle->name_ar;
                // } else {
                //     $data['name'] = $Vehicle->name;
                // }
                // $data['year'] = $Vehicle->year;
                // $Vehicle = CarImage::where('vehicleID',$value);
                // if($Vehicle->count()!=0){
                //     $data['image'] = $Vehicle->first()->image;
                // }else{
                //     $data['image'] = "";
                // }
                // $CarImage = CarImage::where('vehicleID', $value);
                // if ($CarImage->count() != 0) {
                //     if ($CarImage->first()->image_type == 1) {
                //         $data['image'] = url('public/images/car/' . $CarImage->first()->image);
                //     } else {
                //         $data['image'] = $CarImage->first()->image;
                //     }
                //     //$dataover['image'] =$CarImage->first()->image;
                // } else {
                //     $data['image'] = "";
                // }
                // $data['price'] = "";
                // $data['engine_type'] = "";

                //review
                // $avgStar = CarReview::where('vehicleID', $value)->avg('rate');
                // $dataover['rating'] = "" . $avgStar;
                // $CarReview = CarReview::where('vehicleID', $value);
                // $dataover['reviews'] = $CarReview->count();
                // $dataover['price'] = "";
                // $data['basic_information'] = $dataover;
                //engine
                //$engine = VehicleTrimEngine::where('vehicleID', $value)->first();
                // $eng['power'] =  $Vehicle->maximumPowerhpPs;
                // if ($language == 2) {
                //     $eng['transmission'] = $engine->transmission_ar;
                // } else {
                //     $eng['transmission'] = $engine->transmission;
                // }
                // $eng['transmission']=  $Vehicle->transmissionToDisplay;

                // $data["engine_transmission"] = $eng;
                //fule
                // if ($language == 2) {
                //     $fule['engine_type'] = $engine->engine_type_ar;
                // } else {
                //     $fule['engine_type'] = $engine->engine_type;
                // }
                // $fule['engine_type'] = $Vehicle->fuelTypeToDisplay;

                // $fule['size'] = $Vehicle->cylinderCapacity;
                // $data['fuel_performance'] = $fule;
                //dimension
                //$VehicleTrimBody = VehicleTrimBody::where('vehicleID', $value)->first();
                // $suspen['wheel_base'] = $Vehicle->wheelbaseTypeToDisplay;
                // $suspen['cargo_capacity'] = "";
                // $data['dimension_capacity'] = $suspen;
                //comfort
                // $conf['doors'] = $Vehicle->numberOfDoorsToDisplay;
                // $conf['seats'] = $Vehicle->seats;
                // $data['comfort'] = $conf;
                array_push($recordArray, $filteredRows);
            }
            return response()->json(['success' => 'true', 'message' => "", 'record' => $recordArray], 200);
        }
    }
}
