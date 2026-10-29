<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\VehicleExteriorColors;
use App\Models\VehicleInteriorColors;
use App\Models\VehicleTrimBody;
use App\Models\VehicleTrimEngine;
use App\Models\VehicleTrimMileage;
use App\Models\CarVideo;
use App\Models\CarImage;
use App\Models\CarPrice;
use App\Models\City;
use App\Models\CarYears;
use App\Models\CarClasses;
use App\Models\StaffLogEvent;
use App\Models\CarJatoSpecification;
use App\Models\CarEngine;
use App\Models\CarMileage;
use App\Models\CarColor;
use App\Modules\CarSystem;
use App\Modules\ExternalSystem;
use App\Models\ApiTrimList;
use Illuminate\Support\Facades\DB;
use App\Models\PopularCar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function carmodel(Request $request){
        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['brand_name'] = Brand::where('id',base64_decode($request->key))->first()->name;
        $data['data'] = CarModel::where('status','!=',2)->where('brandID',base64_decode($request->key))->get();
        $data['years'] = range(date('Y'), 2010);
        $data['brand_id'] = $request->key;
        $makekey = Brand::where('id', base64_decode($request->key))->first()->makeKey;

        if ($makekey != "") {
            $model = CarSystem::jatomodel($makekey);

            if ($model['status_code'] == 200) {
                $res = json_decode($model['body']);

                foreach ($res[0]->selections as $value) {
                    $check_name = CarModel::where('brandID', base64_decode($request->key))
                                        ->where('name', $value->modelNameToDisplay);

                    if ($check_name->count() == 0) {
                        $carmodel = new CarModel;
                        $carmodel->brandID = base64_decode($request->key);
                        $carmodel->year = $value->modelYear;
                        $carmodel->name = $value->modelNameToDisplay;
                        $carmodel->modelKey = $value->modelKey;
                        $carmodel->status = 1;
                        $carmodel->save();
                    } else {
                        $carmodel = CarModel::find($check_name->first()->id);
                        $carmodel->year = $value->modelYear;
                        $carmodel->name = $value->modelNameToDisplay;
                        $carmodel->modelKey = $value->modelKey;
                        $carmodel->status = 1;
                        $carmodel->save();
                    }
                }

                // Flash message to notify the user about successful insertion/update
                session()->flash('success', 'Car models updated successfully!');
            }
        }

    // Return the view with the data
    return view('admin.carmodels', $data);
        // $makekey = Brand::where('id',base64_decode($request->key))->first()->makeKey;
        // if($makekey!=""){
        //     $model = CarSystem::jatomodel($makekey);
        //     if($model['status_code']==200){
        //         $res = json_decode($model['body']);
        //         foreach($res[0]->selections as $value){
        //             $check_name = CarModel::where('brandID',base64_decode($request->key))->where('name',$value->modelNameToDisplay);
        //             if($check_name->count()==0){
        //                 $carmodel = new CarModel;
        //                 $carmodel->brandID = base64_decode($request->key);
        //                 $carmodel->year = $value->modelYear;
        //                 $carmodel->name = $value->modelNameToDisplay;
        //                 $carmodel->modelKey = $value->modelKey;
        //                 $carmodel->status = 1;
        //                 $carmodel->save();
        //             }else{
        //                 $carmodel = CarModel::find($check_name->first()->id);
        //                 $carmodel->year = $value->modelYear;
        //                 $carmodel->name = $value->modelNameToDisplay;
        //                 $carmodel->modelKey = $value->modelKey;
        //                 $carmodel->status = 1;
        //                 $carmodel->save();
        //             }

        //         }
        //     }

        // }

        // return view('admin.carmodels', $data);
    }

    public function addModel(Request $request){
        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['brand_name'] = Brand::where('id',base64_decode($request->key))->first()->name;
        $data['brand_id'] = $request->key;
        $data['data'] = CarModel::find(base64_decode($request->id));
        return view('admin.addmodels', $data);
    }

    public function insert_carmodel(Request $request){
        if($request->id==''){
            $check_name = CarModel::where('brandID',base64_decode($request->brandID))->where('name',$request->name)->count();
            if($check_name==0){
                $check_namear = CarModel::where('brandID',base64_decode($request->brandID))->where('name_ar',$request->namear)->count();
                if($check_namear==0){
                    $carmodel = new CarModel;
                    $carmodel->brandID = base64_decode($request->brandID);
                    $carmodel->makeID = "";
                    $carmodel->modelID = "";
                    $carmodel->year = "";
                    $carmodel->name = $request->name;
                    $carmodel->name_ar = $request->namear;
                    $carmodel->status = 1;
                    $carmodel->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Model",
                            'event_category'=>12,
                            'event_id'=>$carmodel->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 2;
                }
            }else{
                echo 2;
            }
        }else{
            $carmodel = CarModel::find($request->id);
            $carmodel->name = $request->name;
            $carmodel->name_ar = $request->namear;
            $carmodel->save();
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Model",
                    'event_category'=>12,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }
    public function change_carmodel_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = CarModel::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change car Model status",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function insertcar(Request $request){
        $brandID = $request->brandID;
        $modelid = $request->modelid;
        $yearID = $request->yearID;
        $classesID = $request->CarClasses;
        $brandname = Brand::where('id',$brandID)->first()->makeKey;
        $modelname = CarModel::where('id',$modelid)->first()->modelKey;
        //$CarClasses = CarClasses::where('id',$classesID)->first()->name;
        $getcar_details = CarSystem::jatocarbodies($brandname,$modelname,$yearID);
        $getcar_specification = CarSystem::jatocarvehicles($brandname,$modelname,$yearID);

        if($getcar_details['status_code']==200 && $getcar_specification['status_code']==200){
            $res = json_decode($getcar_details['body']);
            $res1 = json_decode($getcar_specification['body']);
            $resvehicle = $res[0]->selections[0];
            $resvehicle1 = $res1[0]->selections[0];
            // print_r($resvehicle);
            // die;
            $check = CarJatoSpecification::where('brandID',$brandID)->where('modelID',$modelid)->where('year',$yearID)->count();
            if($check==0){
                //insert
                $vehicle = new CarJatoSpecification;
                $vehicle->brandID = $brandID;
                $vehicle->modelID = $modelid;
                $vehicle->vehicleID =$resvehicle->vehicleId;
                $vehicle->name = $resvehicle->shortBodyName;
                $vehicle->name_ar = $this->stringtranslation($resvehicle->shortBodyName);
                $vehicle->year = $resvehicle->modelYear;
                $vehicle->numberOfDoors = $resvehicle1->numberOfDoors;
                $vehicle->bodyCode = $resvehicle1->bodyCode;
                $vehicle->drivenWheels = $resvehicle1->drivenWheels;
                $vehicle->price = $resvehicle1->price;
                $vehicle->basePrice = $resvehicle1->basePrice;
                $vehicle->fuelType = $resvehicle1->fuelType;
                $vehicle->secondaryFuelType = $resvehicle1->secondaryFuelType;
                $vehicle->derivativeGlobal = $resvehicle1->derivativeGlobal;
                $vehicle->derivativeLocal = $resvehicle1->derivativeLocal;
                $vehicle->engine = $resvehicle1->engine;
                $vehicle->transmission = $resvehicle1->transmission;
                $vehicle->trimLevel = $resvehicle1->trimLevel;
                $vehicle->seats = $resvehicle1->seats;
                $vehicle->liters = $resvehicle1->liters;
                $vehicle->manufacturerCode = $resvehicle1->manufacturerCode;
                $vehicle->maximumPowerKw = $resvehicle1->maximumPowerKw;
                $vehicle->maximumPowerhpPs = $resvehicle1->maximumPowerhpPs;
                $vehicle->vehicleType = $resvehicle1->vehicleType;
                $vehicle->concludedDate = $resvehicle1->concludedDate;
                $vehicle->availability = $resvehicle1->availability;
                $vehicle->cylinderCapacity = $resvehicle1->cylinderCapacity;
                $vehicle->wheelbaseType = $resvehicle1->wheelbaseType;
                $vehicle->vehicleState = $resvehicle1->vehicleState;
                $vehicle->roofType = $resvehicle1->roofType;
                $vehicle->makeNameToDisplay = $resvehicle1->makeNameToDisplay;
                $vehicle->modelNameToDisplay = $resvehicle1->modelNameToDisplay;
                $vehicle->modelYearToDisplay = $resvehicle1->modelYearToDisplay;
                $vehicle->numberOfDoorsToDisplay = $resvehicle1->numberOfDoorsToDisplay;
                $vehicle->shortBodyName = $resvehicle1->shortBodyName;
                $vehicle->bodyCodeToDisplay = $resvehicle1->bodyCodeToDisplay;
                $vehicle->drivenWheelsToDisplay = $resvehicle1->drivenWheelsToDisplay;
                $vehicle->fuelTypeToDisplay = $resvehicle1->fuelTypeToDisplay;
                $vehicle->secondaryFuelTypeToDisplay = $resvehicle1->secondaryFuelTypeToDisplay;
                $vehicle->transmissionToDisplay = $resvehicle1->transmissionToDisplay;
                $vehicle->derivativeToDisplay = $resvehicle1->derivativeToDisplay;
                $vehicle->trimLevelToDisplay = $resvehicle1->trimLevelToDisplay;
                $vehicle->wheelbaseTypeToDisplay = $resvehicle1->wheelbaseTypeToDisplay;
                $vehicle->priceToDisplay = $resvehicle1->priceToDisplay;
                $vehicle->basePriceToDisplay = $resvehicle1->basePriceToDisplay;
                $vehicle->cO2 = $resvehicle1->cO2;
                $vehicle->photoUrl = $resvehicle1->photoUrl;
                $vehicle->save();

                //image upload
                $photo = CarSystem::jatocarphoto($resvehicle->vehicleId);
                if($photo['status_code']==200){
                    $res = json_decode($photo['body']);
                    foreach($res[0]->photoPath as $imageUrl){
                        $in_image_table = new CarImage;
                        $in_image_table->vehicleID = $vehicle->id;
                        $in_image_table->modelID = $modelid;
                        $in_image_table->year = $yearID;
                        $in_image_table->image = $imageUrl;
                        $in_image_table->type = 1;
                        $in_image_table->image_type = 2;
                        $in_image_table->save();
                    }
                }

                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add car detail",
                        'event_category'=>13,
                        'event_id'=>$vehicle->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                echo 1;
            }
        }else{
            echo 2;
        }
        //$getcar_details = ExternalSystem::get_car_detail($brandname,$modelname,$CarClasses,$yearID);
        // if($getcar_details['status_code']==200){
        //     $res = json_decode($getcar_details['body']);
        //     $resvehicle = $res->response;
        //     // print_r($resvehicle);
        //     // die;
        //     $check = Vehicle::where('brandID',$brandID)->where('modelID',$modelid)->where('classesID',$classesID)->where('year',$yearID)->count();
        //     if($check==0){
        //         //insert
        //         $vehicle = new Vehicle;
        //         $vehicle->brandID = $brandID;
        //         $vehicle->classesID = $classesID;
        //         $vehicle->modelID = $modelid;
        //         $vehicle->vehicleID = 0;
        //         $vehicle->name = $resvehicle->name;
        //         $vehicle->name_ar = $this->stringtranslation($resvehicle->name);
        //         $vehicle->year = $resvehicle->year;
        //         $vehicle->description = $resvehicle->description;
        //         $vehicle->description_ar = $this->stringtranslation($resvehicle->description);
        //         $vehicle->msrp = "0";
        //         $vehicle->invoice = "0";
        //         $vehicle->status = 1;
        //         $vehicle->save();
        //         //VehicleExteriorColors
        //         foreach($resvehicle->make_model_trim_exterior_colors as $exvalue){
        //             $exterior = new VehicleExteriorColors;
        //             $exterior->brandID = $brandID;
        //             $exterior->classesID = $classesID;
        //             $exterior->modelID = $modelid;
        //             $exterior->year = $yearID;
        //             $exterior->vehicleID = $vehicle->id;
        //             $exterior->make_model_trim_id = 0;
        //             $exterior->exterior_colorsID = 0;
        //             $exterior->name = $exvalue->name;
        //             $exterior->name_ar = $this->stringtranslation($exvalue->name);
        //             $exterior->rgb = $exvalue->rgb;
        //             $exterior->status = 1;
        //             $exterior->save();
        //             //image
        //             $carimage = $this->carimage($brandname,$modelname,$yearID,$exvalue->name);
        //             if($carimage!=""){
        //                 foreach($carimage as $val_image){
        //                     $in_image_table = new CarImage;
        //                     $in_image_table->vehicleID = $vehicle->id;
        //                     $in_image_table->modelID = $modelid;
        //                     $in_image_table->year = $yearID;
        //                     $in_image_table->image = $val_image->link;
        //                     $in_image_table->type = 1;
        //                     $in_image_table->image_type = 2;
        //                     $in_image_table->save();
        //                 }
        //             }
        //         }
        //         //VehicleInteriorColors
        //         foreach($resvehicle->make_model_trim_interior_colors as $invalue){
        //             $interior = new VehicleInteriorColors;
        //             $interior->brandID = $brandID;
        //             $interior->classesID = $classesID;
        //             $interior->modelID = $modelid;
        //             $interior->year = $yearID;
        //             $interior->vehicleID = $vehicle->id;
        //             $interior->make_model_trim_id = 0;
        //             $interior->interior_colorsID = 0;
        //             $interior->name = $invalue->name;
        //             $interior->name_ar = $this->stringtranslation($invalue->name);
        //             $interior->rgb = $invalue->rgb;
        //             $interior->status = 1;
        //             $interior->save();
        //         }
        //         //VehicleTrimBody
        //         $body = new VehicleTrimBody;
        //         $body->brandID = $brandID;
        //         $body->classesID = $classesID;
        //         $body->modelID = $modelid;
        //         $body->year = $yearID;
        //         $body->vehicleID = $vehicle->id;
        //         $body->make_model_trim_id = 0;
        //         $body->trim_bodyID = 0;
        //         $body->type = $resvehicle->make_model_trim_body->type;
        //         $body->type_ar = $this->stringtranslation($resvehicle->make_model_trim_body->type);
        //         $body->doors = $resvehicle->make_model_trim_body->doors;
        //         $body->length = $resvehicle->make_model_trim_body->length;
        //         $body->width = $resvehicle->make_model_trim_body->width;
        //         $body->seats = $resvehicle->make_model_trim_body->seats;
        //         $body->height = $resvehicle->make_model_trim_body->height;
        //         $body->wheel_base = $resvehicle->make_model_trim_body->wheel_base;
        //         $body->front_track = $resvehicle->make_model_trim_body->front_track;
        //         $body->rear_track = $resvehicle->make_model_trim_body->rear_track;
        //         $body->ground_clearance = $resvehicle->make_model_trim_body->ground_clearance;
        //         $body->cargo_capacity = $resvehicle->make_model_trim_body->cargo_capacity;
        //         $body->max_cargo_capacity = $resvehicle->make_model_trim_body->max_cargo_capacity;
        //         $body->curb_weight = $resvehicle->make_model_trim_body->curb_weight;
        //         $body->gross_weight = $resvehicle->make_model_trim_body->gross_weight;
        //         $body->max_payload = $resvehicle->make_model_trim_body->max_payload;
        //         $body->max_towing_capacity = $resvehicle->make_model_trim_body->max_towing_capacity;
        //         $body->save();
        //         //VehicleTrimEngine
        //         $engine = new VehicleTrimEngine;
        //         $engine->brandID = $brandID;
        //         $engine->classesID = $classesID;
        //         $engine->modelID = $modelid;
        //         $engine->year = $yearID;
        //         $engine->vehicleID = $vehicle->id;
        //         $engine->make_model_trim_id = 0;
        //         $engine->trim_engineID = 0;
        //         $engine->engine_type = $resvehicle->make_model_trim_engine->engine_type;
        //         $engine->engine_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->engine_type);
        //         $engine->fuel_type = $resvehicle->make_model_trim_engine->fuel_type;
        //         $engine->fuel_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->fuel_type);
        //         $engine->cylinders = $resvehicle->make_model_trim_engine->cylinders;
        //         $engine->size = $resvehicle->make_model_trim_engine->size;
        //         $engine->horsepower_hp = $resvehicle->make_model_trim_engine->horsepower_hp;
        //         $engine->horsepower_rpm = $resvehicle->make_model_trim_engine->horsepower_rpm;
        //         $engine->torque_ft_lbs = $resvehicle->make_model_trim_engine->torque_ft_lbs;
        //         $engine->torque_rpm = $resvehicle->make_model_trim_engine->torque_rpm;
        //         $engine->valves = $resvehicle->make_model_trim_engine->valves;
        //         $engine->valve_timing = $resvehicle->make_model_trim_engine->valve_timing;
        //         $engine->valve_timing_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->valve_timing);
        //         $engine->cam_type = $resvehicle->make_model_trim_engine->cam_type;
        //         $engine->cam_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->cam_type);
        //         $engine->drive_type = $resvehicle->make_model_trim_engine->drive_type;
        //         $engine->drive_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->drive_type);
        //         $engine->transmission = $resvehicle->make_model_trim_engine->transmission;
        //         $engine->transmission_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->transmission);
        //         $engine->save();
        //         //VehicleTrimMileage
        //         $mileage = new VehicleTrimMileage;
        //         $mileage->brandID = $brandID;
        //         $mileage->classesID = $classesID;
        //         $mileage->modelID = $modelid;
        //         $mileage->year = $yearID;
        //         $mileage->vehicleID = $vehicle->id;
        //         $mileage->make_model_trim_id = 0;
        //         $mileage->trim_mileagesID = 0;
        //         $mileage->fuel_tank_capacity = $resvehicle->make_model_trim_mileage->fuel_tank_capacity;
        //         $mileage->combined_mpg = $resvehicle->make_model_trim_mileage->combined_mpg;
        //         $mileage->epa_city_mpg = $resvehicle->make_model_trim_mileage->epa_city_mpg;
        //         $mileage->epa_highway_mpg = $resvehicle->make_model_trim_mileage->epa_highway_mpg;
        //         $mileage->range_city = $resvehicle->make_model_trim_mileage->range_city;
        //         $mileage->range_highway = $resvehicle->make_model_trim_mileage->range_highway;
        //         $mileage->battery_capacity_electric = $resvehicle->make_model_trim_mileage->battery_capacity_electric;
        //         $mileage->epa_time_to_charge_hr_240v_electric = $resvehicle->make_model_trim_mileage->epa_time_to_charge_hr_240v_electric;
        //         $mileage->epa_kwh_100_mi_electric = $resvehicle->make_model_trim_mileage->epa_kwh_100_mi_electric;
        //         $mileage->range_electric = $resvehicle->make_model_trim_mileage->range_electric;
        //         $mileage->epa_highway_mpg_electric = $resvehicle->make_model_trim_mileage->epa_highway_mpg_electric;
        //         $mileage->epa_city_mpg_electric = $resvehicle->make_model_trim_mileage->epa_city_mpg_electric;
        //         $mileage->epa_combined_mpg_electric = $resvehicle->make_model_trim_mileage->epa_combined_mpg_electric;
        //         $mileage->status = 1;
        //         $mileage->save();
        //         if(session::get('usertype')==2){
        //             $log = array(
        //                 'staff_id'=>session::get('id'),
        //                 'logDate'=>date('Y-m-d'),
        //                 'event_name'=>"Add car detail",
        //                 'event_category'=>13,
        //                 'event_id'=>$vehicle->id,
        //             );
        //             // print_r($log);
        //             $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        //         }
        //         echo 1;

        //     }else{
        //         //update
        //         $vehicle = Vehicle::where('brandID',$brandID)->where('modelID',$modelid)->where('classesID',$classesID)->where('year',$yearID)->first();
        //         $vehicle->brandID = $brandID;
        //         $vehicle->classesID = $classesID;
        //         $vehicle->modelID = $modelid;
        //         $vehicle->name = $resvehicle->name;
        //         $vehicle->name_ar = $this->stringtranslation($resvehicle->name);
        //         $vehicle->year = $resvehicle->year;
        //         $vehicle->description = $resvehicle->description;
        //         $vehicle->description_ar = $this->stringtranslation($resvehicle->description);
        //         $vehicle->save();
        //         //VehicleExteriorColors
        //         foreach($resvehicle->make_model_trim_exterior_colors as $exvalue){
        //             $checkexcolor = VehicleExteriorColors::where('name',$exvalue->name)->count();
        //             if($checkexcolor == 0){
        //                 $exterior = new VehicleExteriorColors;
        //                 $exterior->brandID = $brandID;
        //                 $exterior->classesID = $classesID;
        //                 $exterior->modelID = $modelid;
        //                 $exterior->vehicleID = $vehicle->id;
        //                 $exterior->name = $exvalue->name;
        //                 $exterior->name_ar = $this->stringtranslation($exvalue->name);
        //                 $exterior->rgb = $exvalue->rgb;
        //                 $exterior->status = 1;
        //                 $exterior->save();
        //             }
        //             //image
        //             $carimage = $this->carimage($brandname,$modelname,$yearID,$exvalue->name);
        //             if($carimage!=""){
        //                 foreach($carimage as $val_image){
        //                     $check_image = CarImage::where('vehicleID',$vehicle->id)->where('image',$val_image->link)->count();
        //                     if($check_image==0){
        //                         $in_image_table = new CarImage;
        //                         $in_image_table->vehicleID = $vehicle->id;
        //                         $in_image_table->modelID = $modelid;
        //                         $in_image_table->year = $yearID;
        //                         $in_image_table->image = $val_image->link;
        //                         $in_image_table->type = 1;
        //                         $in_image_table->image_type = 2;
        //                         $in_image_table->save();
        //                     }

        //                 }
        //             }
        //         }
        //         //VehicleInteriorColors
        //         foreach($resvehicle->make_model_trim_interior_colors as $invalue){
        //             $checkincolor = VehicleInteriorColors::where('name',$invalue->name)->count();
        //             if($checkincolor==0){
        //                 $interior = new VehicleInteriorColors;
        //                 $interior->brandID = $brandID;
        //                 $interior->classesID = $classesID;
        //                 $interior->modelID = $modelid;
        //                 $interior->vehicleID = $vehicle->id;
        //                 $interior->name = $invalue->name;
        //                 $interior->name_ar = $this->stringtranslation($invalue->name);
        //                 $interior->rgb = $invalue->rgb;
        //                 $interior->status = 1;
        //                 $interior->save();
        //             }
        //         }
        //         //VehicleTrimBody
        //         $body = VehicleTrimBody::where('vehicleID',$vehicle->id)->first();
        //         $body->brandID = $brandID;
        //         $body->classesID = $classesID;
        //         $body->modelID = $modelid;
        //         $body->type = $resvehicle->make_model_trim_body->type;
        //         $body->type_ar = $this->stringtranslation($resvehicle->make_model_trim_body->type);
        //         $body->doors = $resvehicle->make_model_trim_body->doors;
        //         $body->length = $resvehicle->make_model_trim_body->length;
        //         $body->width = $resvehicle->make_model_trim_body->width;
        //         $body->seats = $resvehicle->make_model_trim_body->seats;
        //         $body->height = $resvehicle->make_model_trim_body->height;
        //         $body->wheel_base = $resvehicle->make_model_trim_body->wheel_base;
        //         $body->front_track = $resvehicle->make_model_trim_body->front_track;
        //         $body->rear_track = $resvehicle->make_model_trim_body->rear_track;
        //         $body->ground_clearance = $resvehicle->make_model_trim_body->ground_clearance;
        //         $body->cargo_capacity = $resvehicle->make_model_trim_body->cargo_capacity;
        //         $body->max_cargo_capacity = $resvehicle->make_model_trim_body->max_cargo_capacity;
        //         $body->curb_weight = $resvehicle->make_model_trim_body->curb_weight;
        //         $body->gross_weight = $resvehicle->make_model_trim_body->gross_weight;
        //         $body->max_payload = $resvehicle->make_model_trim_body->max_payload;
        //         $body->max_towing_capacity = $resvehicle->make_model_trim_body->max_towing_capacity;
        //         $body->save();
        //         //VehicleTrimEngine
        //         $engine = VehicleTrimEngine::where('vehicleID',$vehicle->id)->first();
        //         $engine->brandID = $brandID;
        //         $engine->classesID = $classesID;
        //         $engine->modelID = $modelid;
        //         $engine->engine_type = $resvehicle->make_model_trim_engine->engine_type;
        //         $engine->engine_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->engine_type);
        //         $engine->fuel_type = $resvehicle->make_model_trim_engine->fuel_type;
        //         $engine->fuel_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->fuel_type);
        //         $engine->cylinders = $resvehicle->make_model_trim_engine->cylinders;
        //         $engine->size = $resvehicle->make_model_trim_engine->size;
        //         $engine->horsepower_hp = $resvehicle->make_model_trim_engine->horsepower_hp;
        //         $engine->horsepower_rpm = $resvehicle->make_model_trim_engine->horsepower_rpm;
        //         $engine->torque_ft_lbs = $resvehicle->make_model_trim_engine->torque_ft_lbs;
        //         $engine->torque_rpm = $resvehicle->make_model_trim_engine->torque_rpm;
        //         $engine->valves = $resvehicle->make_model_trim_engine->valves;
        //         $engine->valve_timing = $resvehicle->make_model_trim_engine->valve_timing;
        //         $engine->valve_timing_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->valve_timing);
        //         $engine->cam_type = $resvehicle->make_model_trim_engine->cam_type;
        //         $engine->cam_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->cam_type);
        //         $engine->drive_type = $resvehicle->make_model_trim_engine->drive_type;
        //         $engine->drive_type_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->drive_type);
        //         $engine->transmission = $resvehicle->make_model_trim_engine->transmission;
        //         $engine->transmission_ar = $this->stringtranslation($resvehicle->make_model_trim_engine->transmission);
        //         $engine->save();
        //         //VehicleTrimMileage
        //         $mileage = VehicleTrimMileage::where('vehicleID',$vehicle->id)->first();
        //         $mileage->brandID = $brandID;
        //         $mileage->classesID = $classesID;
        //         $mileage->modelID = $modelid;
        //         $mileage->fuel_tank_capacity = $resvehicle->make_model_trim_mileage->fuel_tank_capacity;
        //         $mileage->combined_mpg = $resvehicle->make_model_trim_mileage->combined_mpg;
        //         $mileage->epa_city_mpg = $resvehicle->make_model_trim_mileage->epa_city_mpg;
        //         $mileage->epa_highway_mpg = $resvehicle->make_model_trim_mileage->epa_highway_mpg;
        //         $mileage->range_city = $resvehicle->make_model_trim_mileage->range_city;
        //         $mileage->range_highway = $resvehicle->make_model_trim_mileage->range_highway;
        //         $mileage->battery_capacity_electric = $resvehicle->make_model_trim_mileage->battery_capacity_electric;
        //         $mileage->epa_time_to_charge_hr_240v_electric = $resvehicle->make_model_trim_mileage->epa_time_to_charge_hr_240v_electric;
        //         $mileage->epa_kwh_100_mi_electric = $resvehicle->make_model_trim_mileage->epa_kwh_100_mi_electric;
        //         $mileage->range_electric = $resvehicle->make_model_trim_mileage->range_electric;
        //         $mileage->epa_highway_mpg_electric = $resvehicle->make_model_trim_mileage->epa_highway_mpg_electric;
        //         $mileage->epa_city_mpg_electric = $resvehicle->make_model_trim_mileage->epa_city_mpg_electric;
        //         $mileage->epa_combined_mpg_electric = $resvehicle->make_model_trim_mileage->epa_combined_mpg_electric;
        //         $mileage->save();
        //         echo 1;
        //         if(session::get('usertype')==2){
        //             $log = array(
        //                 'staff_id'=>session::get('id'),
        //                 'logDate'=>date('Y-m-d'),
        //                 'event_name'=>"Update car detail",
        //                 'event_category'=>13,
        //                 'event_id'=>$vehicle->id,
        //             );
        //             // print_r($log);
        //             $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        //         }
        //     }
        //     //echo $result->year;

        // }else{
        //     echo 2;
        // }
        //print_r($getcar_details);
        // print_r($CarClasses);
        // foreach($CarClasses as $value){
        //     $getcar_details = ExternalSystem::get_car_detail($brandname,$brandname,$value->name,$yearID);
        //     //$response_car_data = json_decode($getcar_details);
        //     print_r($getcar_details);
        // }
    }

    public function carlist(Request $request){

        // create a table in current database using raw sql query
        // try {
            // Step 1: Create Table
            // DB::unprepared("
            //     CREATE TABLE IF NOT EXISTS `popular_cars` (
            //     `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            //     `brand_id` bigint(20) UNSIGNED NOT NULL,
            //     `make` varchar(255) NOT NULL,
            //     `model` varchar(255) NOT NULL,
            //     `trim` varchar(255) NOT NULL,
            //     `year` varchar(255) NOT NULL,
            //     `popular` tinyint(1) NOT NULL,
            //     `status` tinyint(1) NOT NULL,
            //     `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            //     `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            //     PRIMARY KEY (`id`),
            //     KEY `popular_cars_brand_id_foreign` (`brand_id`),
            //     CONSTRAINT `popular_cars_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            // ");

            // return "popular_cars table created successfully.";
            // DB::statement("
            //     CREATE TABLE IF NOT EXISTS `intro_videos` (
            //         `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            //         `video` VARCHAR(255) NOT NULL,
            //         `created_at` TIMESTAMP NULL DEFAULT NULL,
            //         `updated_at` TIMESTAMP NULL DEFAULT NULL
            //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            // ");

            // Step 2: Insert Initial Data (only if not already exists)
            // $exists = DB::table('intro_videos')->where('id', 1)->exists();

            // if (!$exists) {
            //     DB::statement("
            //         INSERT INTO `intro_videos` (`id`, `video`, `created_at`, `updated_at`) VALUES
            //         (1, 'intro_video_1749448316.mp4', '2025-06-09 00:21:56', '2025-06-09 00:21:56');
            //     ");
            // }

            // return "Table and initial data setup done.";
        // } catch (\Exception $e) {
        //     return "Error: " . $e->getMessage();
        // }

        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        // $carmodel = CarModel::where('id',base64_decode($request->key))->first();
        // $data['brand_name'] = Brand::where('id',$carmodel->brandID)->first()->name;
        // $data['model_name'] = $carmodel->name;
        // $data['model_year'] = $carmodel->year;
        // $data['yearlist'] = CarYears::where('status',1)->get();
        // $data['CarClasses'] = CarClasses::where('modelID',base64_decode($request->key))->where('brandID',$carmodel->brandID)->get();
       // $data['year'] = $request->year;
        // $data['data'] = CarJatoSpecification::where('status','!=',2)->where('modelID',base64_decode($request->key))->get();
        // $data['make_id'] = $request->key;
        // $data['carmodel'] = $carmodel;

        $filteredRows = array();
        $fullsheetRows = array();
        $vehicleArray = array();

        $brandID = base64_decode($request->key);

        $brand = Brand::find($brandID);
            
        if (!$brand || empty($brand->excel)) {
            // return response()->json([
            //     'success' => 'false',
            //     'message' => 'Brand or Excel file name not found in database.'
            // ], 404);

            $data['data'] = $vehicleArray;
            return view('admin.carlist', $data);
        }

        $filePath = public_path('brand/excel/' . $brand->excel);
            
        // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

        if (!file_exists($filePath)) {
            // return response()->json([
            //     'success' => 'false',
            //     'message' => 'Excel file does not exist in the specified path.'
            // ], 404);

            $data['data'] = $vehicleArray;
            return view('admin.carlist', $data);
        }

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

                // $fullsheetRows[] = $assocRow;

                if (
                    isset($assocRow['Make']) &&
                    $assocRow['Make'] != ""
                ) {
                    $formatted = [
                        'make' => $assocRow['Make'],
                        'model' => $assocRow['Model'],
                        'trim' => $assocRow['Trim'],
                        'body_style' => $assocRow['Body_Style'],
                        'model_year' => $assocRow['Model_Year']
                    ];

                    $filteredRows[] = $formatted;
                }

            }

            fclose($handle);
        }

        // $yearArray = [];
        // $uniqueYears = [];

        // foreach ($fullsheetRows as $row) {
        //     $year = $row['Model_Year'];

        //     if (!in_array($year, $uniqueYears) && $year != "") {
        //         $uniqueYears[] = $year;
        //         $yearArray[] = ['year' => $year];
        //     }
        // }

        // Sort by 'year' in ascending order
        // usort($yearArray, function ($a, $b) {
        //     return $a['year'] <=> $b['year'];
        // });

        foreach($filteredRows as $modelvalue){
            // $datavehicle['id'] = $modelvalue->id;
            $datavehicle['brand_id'] = $brandID;
            $datavehicle['make'] = $modelvalue['make'];
            $datavehicle['model'] = $modelvalue['model'];
            $datavehicle['trim'] = $modelvalue['trim'];
            $datavehicle['body_style'] = $modelvalue['body_style'];
            $datavehicle['year'] = $modelvalue['model_year'];
            // $vehicleimg = CarImage::where('modelID',$modelvalue->id);
            // if($vehicleimg->count()!=0){
            //     if($vehicleimg->first()->image_type==1){
            //         $datavehicle['image'] = url('public/images/car/'.$vehicleimg->first()->image);
            //         //$datavehicleimg['image'] = url('public/images/car/'.$vehicleimg->first()->image);

            //     }else{
            //         $datavehicle['image'] = $vehicleimg->first()->image;
            //         //$datavehicleimg['image'] = $vehicleimg->first()->image;
            //     }
            //     //array_push($vehiclebanner,$datavehicleimg);

            // }else{
                // $datavehicle['image']= ExternalSystem::car_image($modelvalue['make'],$modelvalue['model'],$modelvalue['model_year'],'');
                $datavehicle['image']= "";
            // }
            //$datavehicle['image']="";
            // $datavehicle['modelID']=$modelvalue->id;
            // if($language==2){
            //     $datavehicle['name'] = $modelvalue->name_ar;
            //     $datavehicle['description'] = "";
            // }else{
            //     $datavehicle['name'] = $modelvalue->name;
            //     $datavehicle['description'] = "";
            // }

            array_push($vehicleArray,$datavehicle);

            // $vehicleimgs = CarImage::where('modelID',$modelvalue->id)->skip(0)->take(1)->get();
            // foreach($vehicleimgs as $vimg){
            //     $datavehicleimg['id'] = $vimg->id;
            //     if($vimg->image_type==1){
            //         $datavehicleimg['image'] = url('public/images/car/'.$vimg->image);

            //     }else{
            //         $datavehicleimg['image'] = $vimg->image;
            //     }
            //     //array_push($vehiclebanner,$datavehicleimg);
            // }
        }
        // return $vehicleArray;

        $data['data'] = $vehicleArray;
        $data['brand_name'] = $brand->name;

        return view('admin.carlist', $data);
    }
    public function delete_car(Request $request){
        $id = $request->id;
        $category = CarJatoSpecification::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete car detail",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_car_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = CarJatoSpecification::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change car status",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function add_popular_car(Request $request){
        $brand_id = $request->brand_id;
        $make = $request->make;
        $model = $request->model;
        $trim = $request->trim;
        $year = $request->year;
        $popularCar = PopularCar::where(['brand_id' => $brand_id, 'make' => $make, 'model' => $model, 'trim' => $trim, 'year' => $year])->first();
        if($popularCar){
            $popularCar->delete();
        }else{
            $dataCount = PopularCar::count();
            $insert = new PopularCar;
            $insert->brand_id = $brand_id;
            $insert->make = $make;
            $insert->model = $model;
            $insert->trim = $trim;
            $insert->year = $year;
            $insert->position = $dataCount+1;
            $insert->status = 1;
            $insert->save();
        }
        // $id = $request->id;
        // $status = $request->status;
        // $category = CarJatoSpecification::find($id);
        // $category->popular = $status;
        // $category->save();
        // if(session::get('usertype')==2){
        //     $log = array(
        //         'staff_id'=>session::get('id'),
        //         'logDate'=>date('Y-m-d'),
        //         'event_name'=>"Add car in popular list",
        //         'event_category'=>13,
        //         'event_id'=>$request->id,
        //     );
        //     // print_r($log);
        //     $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        // }
    }

    public function fetch_api_car_images(Request $request)
    {
        $make  = $request->make;
        $model = $request->model;
        $trim  = $request->trim;
        $year  = $request->year;

        $trimFolder = urlencode($trim);
        $uploadPath = public_path("images/car/$make/$model/$trimFolder");

        // Create directory if not exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $files = File::files($uploadPath);

        if (count($files) == 0) {
            $responseData = ExternalSystem::vehicle_image($make, $model, $trim, $year);
            $allImages = [];

            $categories = ['exterior', 'interior', 'colors'];

            foreach ($categories as $category) {
                if (!empty($responseData['data']['images'][$category])) {
                    foreach ($responseData['data']['images'][$category] as $imageUrl) {
                        $allImages[] = $imageUrl;
                        if (count($allImages) == 10) break 2; // limit to 10 total
                    }
                }
            }

            foreach ($allImages as $imageUrl) {
                try {
                    $contents = file_get_contents($imageUrl);
                    $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = 'img_' . time() . '_' . Str::random(5) . '.' . $ext;
                    file_put_contents($uploadPath . '/' . $filename, $contents);
                } catch (\Exception $e) {
                    \Log::error("Failed to download image: $imageUrl - " . $e->getMessage());
                }
            }
        }

        // Return image URLs
        $imageUrls = [];
        $files = File::files($uploadPath);
        foreach ($files as $file) {
            $relativePath = str_replace(public_path(), '', $file->getPathname());
            $imageUrls[] = url('public' . $relativePath); 
        }
        return response()->json(['images' => $imageUrls]);

    }


    public function cardetail(Request $request){
        // $brandId = base64_decode($request->key);

        // $carImagesCountByApi = CarImage::where([
        //     'brand_id' => $brandId,
        //     'make'     => $request->make,
        //     'model'    => $request->model,
        //     'trim'     => $request->trim,
        //     'year'     => $request->year
        // ])->whereIn('type', [1, 2, 3])
        // ->where('image_type', 2)
        // ->count();

        // if ($carImagesCountByApi == 0) {
        //     $responseData = ExternalSystem::vehicle_image($request->make, $request->model, $request->trim, $request->year);
        //     $images = $responseData['data']['images'] ?? [];
        //     // if ($responseData['statusCode'] != 403){
        //         foreach (['exterior' => 1, 'interior' => 2, 'colors' => 3] as $imageType => $typeValue) {
        //             if (!empty($images[$imageType])) {
        //                 foreach ($images[$imageType] as $imageUrl) {
        //                     CarImage::create([
        //                         'brand_id'   => $brandId,
        //                         'make'       => $request->make,
        //                         'model'      => $request->model,
        //                         'trim'       => $request->trim,
        //                         'year'       => $request->year,
        //                         'image'      => $imageUrl,
        //                         'type'       => $typeValue,
        //                         'image_type' => 2
        //                     ]);
        //                 }
        //             }
        //         }
        //     // }else{
        //     //     return $responseData['message'];
        //     // }
        // }

        $apiTrimList = ApiTrimList::where(['make'=>$request->make,'model'=>$request->model])->get();
        // $apiTrimList = ApiTrimList::where(['make'=>'BMW','model'=>'X5'])->get();
        
        if(count($apiTrimList) == 0){
            $responseData = ExternalSystem::trim_list($request->year,$request->make,$request->model);
            // $responseData = ExternalSystem::trim_list('2005','BMW','X5');
            $trims = $responseData['data'] ?? [];
            if(count($trims) > 0){
                foreach ($trims as $trim) {
                    $saveTrim = new ApiTrimList;
                    $saveTrim->year = '';
                    $saveTrim->make = $request->make;
                    $saveTrim->model = $request->model;
                    $saveTrim->trim = $trim;
                    $saveTrim->save();
                }
            }
            $data['apiTrimList'] = ApiTrimList::where(['make'=>$request->make,'model'=>$request->model])->get();
            // $data['apiTrimList'] = ApiTrimList::where(['make'=>'BMW','model'=>'X5'])->get();
        }else{
            $data['apiTrimList'] = $apiTrimList;
        }
        // return $data;

        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);

        // $Vehicle = CarJatoSpecification::where('id',base64_decode($request->key))->first();
        $brand = Brand::find(base64_decode($request->key));

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
            ], 404);
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
                    isset($assocRow['Make']) &&
                    $assocRow['Make'] == $request->make &&
                    isset($assocRow['Model']) &&
                    $assocRow['Model'] == $request->model &&
                    isset($assocRow['Trim']) &&
                    $assocRow['Trim'] == $request->trim &&
                    isset($assocRow['Model_Year']) &&
                    $assocRow['Model_Year'] == $request->year
                ) {
                    $filteredRows = $assocRow;
                }

            }

            fclose($handle);
        }

        $data['data'] = $filteredRows;

        // $data['brand_name'] = Brand::where('id',$Vehicle->brandID)->first()->name;
        // $data['model_name'] = CarModel::where('id',$Vehicle->modelID)->first()->name;

        // $data['data'] = CarJatoSpecification::where('id',base64_decode($request->key))->first();
        // $data['engine'] = CarEngine::where('vehicleID',base64_decode($request->key))->get();
        // $data['mileage'] = CarMileage::where('vehicleID',base64_decode($request->key))->get();
        // $data['color'] = CarColor::where('vehicleID',base64_decode($request->key))->get();
        // // $data['body'] = VehicleTrimBody::where('vehicleID',base64_decode($request->key))->first();
        // // $data['engine'] = VehicleTrimEngine::where('vehicleID',base64_decode($request->key))->first();
        // // $data['mileage'] = VehicleTrimMileage::where('vehicleID',base64_decode($request->key))->first();
        // // $data['interiorcolor'] = VehicleInteriorColors::where('vehicleID',base64_decode($request->key))->get();
        // // $data['exteriorcolor'] = VehicleExteriorColors::where('vehicleID',base64_decode($request->key))->get();
        // $data['carimage'] = CarImage::where('vehicleID',base64_decode($request->key))->whereIn('type',[1,2,3])->get();
        // $data['carvideo'] = CarVideo::where('vehicleID',base64_decode($request->key))->get();
        // $data['car360'] = CarImage::where('vehicleID',base64_decode($request->key))->whereIn('type',[4]);
        // echo"<pre>";print_r($data['car360']->first()->image);die;
        $data['carimages'] = CarImage::where([
            'brand_id'=>base64_decode($request->key),
            'make'=>$request->make,
            'model'=>$request->model,
            'trim'=>$request->trim,
            'year'=>$request->year
            ])->whereIn('type',[1,2,3])->where('image_type', 1)->get();
        $data['carapiimages'] = CarImage::where([
            'brand_id'=>base64_decode($request->key),
            'make'=>$request->make,
            'model'=>$request->model,
            'trim'=>$request->trim,
            'year'=>$request->year
            ])->whereIn('type',[1,2,3])->where('image_type', 2)->get();
        $data['carvideos'] = CarVideo::where([
            'brand_id'=>base64_decode($request->key),
            'make'=>$request->make,
            'model'=>$request->model,
            'trim'=>$request->trim,
            'year'=>$request->year
            ])->get();
        $data['car360Images'] = CarImage::where([
            'brand_id'=>base64_decode($request->key),
            'make'=>$request->make,
            'model'=>$request->model,
            'trim'=>$request->trim,
            'year'=>$request->year,
            'type'=>4
            ])->orderBy('sequence', 'asc')->get();

            $stockNumber = "stock_".$request->make.$request->model.$request->trim.$request->year;
        
        $data['car360ViewData'] = ExternalSystem::car_360View_by_Spyne($data['car360Images'],$stockNumber);
        
        $viewData = $data['car360ViewData'];

        // $url       = $viewData->url ?? 'https://assets.spyne.ai/360?sku_id=35c31a0f812644cb8e4e7cc0522a6af1&showHotspot=true';
        // $thumbnail = $viewData->thumbnail ?? 'https://spyne-media.s3.amazonaws.com/2025-07-23/9c8bbcff-5dca-41d6-b77f-8c5c4fec6c13.jpg';

        $url       = $viewData->url ?? '';
        $thumbnail = $viewData->thumbnail ?? '';

        $data['url360'] = $url;
        $data['thumbnail360'] = $thumbnail;

        $data['brand_id'] = $request->key;
        $data['make'] = $request->make;
        $data['model'] = $request->model;
        $data['trim'] = $request->trim;
        $data['year'] = $request->year;
        return view('admin.cardetail', $data);
    }

    public function addcar(Request $request){
        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['data'] = CarJatoSpecification::where('id',base64_decode($request->key))->first();
        // use App\Models\CarEngine;
        // use App\Models\CarMileage;
        // use App\Models\CarColor;
        $data['engine'] = CarEngine::where('vehicleID',base64_decode($request->key))->get();
        $data['mileage'] = CarMileage::where('vehicleID',base64_decode($request->key))->get();
        $data['color'] = CarColor::where('vehicleID',base64_decode($request->key))->get();
        // $data['body'] = VehicleTrimBody::where('vehicleID',base64_decode($request->key))->first();
        // $data['engine'] = VehicleTrimEngine::where('vehicleID',base64_decode($request->key))->first();
        // $data['mileage'] = VehicleTrimMileage::where('vehicleID',base64_decode($request->key))->first();
        // $data['interiorcolor'] = VehicleInteriorColors::where('vehicleID',base64_decode($request->key))->get();
        // $data['exteriorcolor'] = VehicleExteriorColors::where('vehicleID',base64_decode($request->key))->get();
        $data['carimage'] = CarImage::where('vehicleID',base64_decode($request->key))->get();
        $data['carvideo'] = CarVideo::where('vehicleID',base64_decode($request->key))->get();
        return view('admin.addcar', $data);

    }

    public function upload_car_images(Request $request){
        // echo"<pre>";print_r($request->all());die;
        $validator = Validator::make($request->all(), [
            'carimages' => 'required',
            'carimages.*' => 'image|mimes:jpeg,jpg,png,gif|max:2048|dimensions:ratio=16/9',
        ], [
            'carimages.required' => 'Please upload at least one image.',
            'carimages.*.image' => 'Each file must be a valid image.',
            'carimages.*.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'carimages.*.max' => 'Each image must not exceed 2MB.',
            'carimages.*.dimensions' => 'Each image must have a 16:9 aspect ratio.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($request->carimages!=''){
            //image upload
            if(!empty($request->carimages)){
                $i=1;
                foreach($request->carimages as $imagevalue){
                    //upload images
                    // $new_widthss = 720;
                    // $new_heightss = 480;
                    // $filess = $imagevalue;
                    // $fileNamess = $filess->getRealPath();
                    // $uploadPathss = public_path('images/');
                    // $fileExtss = $filess->getClientOriginalExtension();
                    // $imgnamess = $i."carimage_";
                    // $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    $imageNames = $i.time().'.'.$imagevalue->extension();
                    $imagevalue->move(public_path('images'), $imageNames);
                    $in_image_table=new CarImage;
                    $in_image_table->brand_id=base64_decode($request->brand_id);
                    $in_image_table->make=$request->make;
                    $in_image_table->model=$request->model;
                    $in_image_table->trim=$request->trim;
                    $in_image_table->year=$request->year;
                    $in_image_table->image=$imageNames;
                    $in_image_table->type=2;
                    $in_image_table->save();
                    $i++;
                }
            }
        }

        return back()->with('message', 'Car Image uploaded successfully');
    }

    public function upload_car_videos(Request $request){
        // echo"<pre>";print_r($request->all());die;
        if ($request->hasFile('carvideo')) {
                
            $videoFile = $request->file('carvideo');
            $uploadPath = public_path('videos/');

            // Ensure the directory exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileExt = $videoFile->getClientOriginalExtension();
            $videoName = 'carvideo_' . time() . '.' . $fileExt;
            $videoFile->move($uploadPath, $videoName);

            $carVideo = new CarVideo;
            $carVideo->brand_id = base64_decode($request->brand_id);
            $carVideo->make = $request->make;
            $carVideo->model = $request->model;
            $carVideo->trim = $request->trim;
            $carVideo->year = $request->year;
            $carVideo->video = $videoName;
            $carVideo->description = $request->description ?? '';
            $carVideo->save();
            
        }

        return back()->with('message', 'Car Video uploaded successfully');
    }

    public function upload_car_360_image(Request $request)
    {
        $request->validate([
            'car360_images' => 'required|array',
            'car360_images.*.sequence' => 'required|integer',
            'car360_images.*.image' => 'nullable|image',
        ]);

        try {
            foreach ($request->car360_images as $imageData) {
                $sequence = $imageData['sequence'];

                // Skip if no image was uploaded for this index
                if (!isset($imageData['image']) || !$imageData['image']) {
                    continue;
                }

                $imageFile = $imageData['image'];

                // Check for existing image
                $existing = CarImage::where([
                    'brand_id' => base64_decode($request->brand_id),
                    'make'     => $request->make,
                    'model'    => $request->model,
                    'trim'     => $request->trim,
                    'year'     => $request->year,
                    'type'     => 4,
                    'sequence' => $sequence,
                ])->first();

                // Delete old file from disk if exists
                if ($existing && file_exists(public_path('images/' . $existing->image))) {
                    unlink(public_path('images/' . $existing->image));
                }

                // Save new file
                $filename = 'car360_' . time() . "_{$sequence}." . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('images/'), $filename);

                // Update or create
                $carImage = $existing ?? new CarImage();
                $carImage->brand_id = base64_decode($request->brand_id);
                $carImage->make     = $request->make;
                $carImage->model    = $request->model;
                $carImage->trim     = $request->trim;
                $carImage->year     = $request->year;
                $carImage->image    = $filename;
                $carImage->type     = 4; // 360 image
                $carImage->sequence = $sequence;
                $carImage->save();
            }

            return back()->with('message', 'Car 360 images uploaded successfully');

        } catch (\Exception $e) {
            return back()->with('message', 'Failed to upload images: ' . $e->getMessage());
        }
    }

    public function update_car_360_image_sequence(Request $request){
        // echo"<pre>";print_r($request->all());die;

        $carImage = CarImage::find($request->id);
        $carImage->sequence = $request->sequence;
        $carImage->save();

        echo "1";
    }

    public function deletevideo(Request $request)
    {
        $id = $request->id;
        $carVideo = CarVideo::find($id);
        
        if ($carVideo) {
            // Define the video path
            $videoPath = public_path('videos/' . $carVideo->video);

            // Delete the video file if it exists
            if (File::exists($videoPath)) {
                File::delete($videoPath);
            }

            // Delete the DB record
            $carVideo->delete();

            // Log the action if usertype is 2
            if (session::get('usertype') == 2) {
                $log = [
                    'staff_id'       => session::get('id'),
                    'logDate'        => date('Y-m-d'),
                    'event_name'     => "Delete car video",
                    'event_category' => 13,
                    'event_id'       => $id,
                ];
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }

            echo "1";
        } else {
            echo "0"; // If video not found
        }
    }

    public function insert_car(Request $request){

        // print_r($request->key);
        // print_r($request->valueengine);
        // print_r($request->keyar);
        // print_r($request->valueenginear);
        $array1 = $request->key; // Dynamic size
        $array2 = $request->valueengine; // Dynamic size
        $array3 = $request->keyar; // Dynamic size
        $array4 = $request->valueenginear; // Dynamic size
        $array9 = $request->engineid; // Dynamic size
        $result = [];
        $maxLength = max(count($array1), count($array2), count($array3), count($array4), count($array9)); // Get the maximum length of the two arrays
        for ($i = 0; $i < $maxLength; $i++) {
            $result[] = [
                'array1_value' => $array1[$i] ?? null, // Use null if index doesn't exist
                'array2_value' => $array2[$i] ?? null, // Use null if index doesn't exist
                'array3_value' => $array3[$i] ?? null, // Use null if index doesn't exist
                'array4_value' => $array4[$i] ?? null, // Use null if index doesn't exist
                'array9_value' => $array9[$i] ?? null, // Use null if index doesn't exist
            ];
        }
       // print_r($result);
        //mileage
        $array5 = $request->key_mileage; // Dynamic size
        $array6 = $request->value_mileage; // Dynamic size
        $array7 = $request->key_mileage_ar; // Dynamic size
        $array8 = $request->value_mileage_ar; // Dynamic size
        $array10 = $request->mileage_id; // Dynamic size
        $result_mileage = [];
        $maxLength_mileage = max(count($array5), count($array6), count($array7), count($array8), count($array10)); // Get the maximum length of the two arrays
        for ($i = 0; $i < $maxLength_mileage; $i++) {
            $result_mileage[] = [
                'array5_value' => $array5[$i] ?? null, // Use null if index doesn't exist
                'array6_value' => $array6[$i] ?? null, // Use null if index doesn't exist
                'array7_value' => $array7[$i] ?? null, // Use null if index doesn't exist
                'array8_value' => $array8[$i] ?? null, // Use null if index doesn't exist
                'array10_value' => $array10[$i] ?? null, // Use null if index doesn't exist
            ];
        }
       // print_r($result_mileage);
        //color

        $array11 = $request->key_color; // Dynamic size
        $array12 = $request->colorID; // Dynamic size
        $result_color = [];
        $maxLength_color = max(count($array11), count($array12)); // Get the maximum length of the two arrays
        for ($i = 0; $i < $maxLength_color; $i++) {
            $result_color[] = [
                'array11_value' => $array11[$i] ?? null, // Use null if index doesn't exist
                'array12_value' => $array12[$i] ?? null, // Use null if index doesn't exist
            ];
        }
        //print_r($result_color);
        //die;
        //insert
        $vehicle = CarJatoSpecification::find($request->id);
        $vehicle->name = $request->name;
        $vehicle->name_ar = $request->name_ar;
        $vehicle->year = $request->year;
        $vehicle->numberOfDoors = $request->numberOfDoors;
        $vehicle->fuelTypeToDisplay = $request->fuelTypeToDisplay;
        $vehicle->secondaryFuelTypeToDisplay = $request->secondaryFuelTypeToDisplay;
        $vehicle->derivativeGlobal = $request->derivativeGlobal;
        $vehicle->derivativeLocal = $request->derivativeLocal;
        $vehicle->drivenWheelsToDisplay = $request->drivenWheelsToDisplay;
        $vehicle->engine = $request->engine;
        $vehicle->transmissionToDisplay = $request->transmissionToDisplay;
        $vehicle->trimLevelToDisplay = $request->trimLevelToDisplay;
        $vehicle->liters = $request->liters;
        $vehicle->maximumPowerKw = $request->maximumPowerKw;
        $vehicle->maximumPowerhpPs = $request->maximumPowerhpPs;
        $vehicle->vehicleType = $request->vehicleType;
        $vehicle->cylinderCapacity = $request->cylinderCapacity;
        $vehicle->bodyCodeToDisplay = $request->bodyCodeToDisplay;
        $vehicle->derivativeToDisplay = $request->derivativeToDisplay;
        $vehicle->wheelbaseTypeToDisplay = $request->wheelbaseTypeToDisplay;
        $vehicle->save();
        // use App\Models\CarEngine;
        // use App\Models\CarMileage;
        // use App\Models\CarColor;
        //engine
        foreach($result as $value){
            if($value['array9_value']==""){
                $list = new CarEngine;
                $list->vehicleID = $request->id;
                $list->name = $value['array1_value'];
                $list->name_ar = $value['array3_value'];
                $list->value = $value['array2_value'];
                $list->value_ar = $value['array4_value'];
                $list->save();
            }else{
                $list = CarEngine::find($value['array9_value']);
                $list->vehicleID = $request->id;
                $list->name = $value['array1_value'];
                $list->name_ar = $value['array3_value'];
                $list->value = $value['array2_value'];
                $list->value_ar = $value['array4_value'];
                $list->save();
            }
        }

        //mileage
        foreach($result_mileage as $value_mileage){
            if($value_mileage['array10_value']==""){
                $list = new CarMileage;
                $list->vehicleID = $request->id;
                $list->name = $value_mileage['array5_value'];
                $list->name_ar = $value_mileage['array7_value'];
                $list->value = $value_mileage['array6_value'];
                $list->value_ar = $value_mileage['array8_value'];
                $list->save();
            }else{
                $list = CarMileage::find($value_mileage['array10_value']);
                $list->vehicleID = $request->id;
                $list->name = $value_mileage['array5_value'];
                $list->name_ar = $value_mileage['array7_value'];
                $list->value = $value_mileage['array6_value'];
                $list->value_ar = $value_mileage['array8_value'];
                $list->save();
            }
        }
        //color
        foreach($result_color as $value_color){
            if($value_color['array12_value']==""){
                $list = new CarColor;
                $list->vehicleID = $request->id;
                $list->value = $value_color['array11_value'];
                $list->save();
            }else{
                $list = CarColor::find($value_color['array12_value']);
                $list->vehicleID = $request->id;
                $list->value = $value_color['array11_value'];
                $list->save();
            }
        }
        if($request->interiorimg!=''){
            //image upload
            if(!empty($request->interiorimg)){
                $i=1;
                foreach($request->interiorimg as $imagevalue){
                    //upload images
                    $new_widthss = 720;
                    $new_heightss = 480;
                    $filess = $imagevalue;
                    $fileNamess = $filess->getRealPath();
                    $uploadPathss = public_path('images/');
                    $fileExtss = $filess->getClientOriginalExtension();
                    $imgnamess = $i."sponthump_";
                    $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    $in_image_table=new CarImage;
                    $in_image_table->vehicleID=$request->id;
                    $in_image_table->modelID=0;
                    $in_image_table->image=$imageNames;
                    $in_image_table->type=2;
                    $in_image_table->save();
                    $i++;
                }
            }
            // $new_width = 720;
            // $new_height = 480;
            // $file = $request->file('interiorimg');
            // $fileName = $file->getRealPath();
            // $uploadPath = public_path('images/car/');
            // $fileExt = $file->getClientOriginalExtension();
            // $imgname = "inthump_";
            // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);

            // $in_image_table=new CarImage;
            // $in_image_table->vehicleID=$request->id;
            // $in_image_table->modelID=0;
            // $in_image_table->image=$imageName;
            // $in_image_table->type=2;
            // $in_image_table->save();
        }
        if($request->exteriorimg!=''){
            //image upload
            if(!empty($request->exteriorimg)){
                $e=1;
                foreach($request->exteriorimg as $imagevalueex){
                    //upload images
                    $new_widthss_ex = 720;
                    $new_heightss_ex = 480;
                    $filess_ex = $imagevalueex;
                    $fileNamess_ex = $filess_ex->getRealPath();
                    $uploadPathss_ex = public_path('images/');
                    $fileExtss_ex = $filess_ex->getClientOriginalExtension();
                    $imgnamess_ex = $e."sponthumpex_";
                    $imageNames_ex = ExternalSystem::saveresizeimage($new_widthss_ex,$new_heightss_ex,$fileNamess_ex,$uploadPathss_ex,$fileExtss_ex,$imgnamess_ex);
                    $ex_image_table=new CarImage;
                    $ex_image_table->vehicleID=$request->id;
                    $ex_image_table->modelID=0;
                    $ex_image_table->image=$imageNames_ex;
                    $ex_image_table->type=1;
                    $ex_image_table->save();
                    $e++;
                }
            }
            // $new_widthe = 720;
            // $new_heighte = 480;
            // $filee = $request->file('exteriorimg');
            // $fileNamee = $filee->getRealPath();
            // $uploadPathe = public_path('images/car/');
            // $fileExte = $filee->getClientOriginalExtension();
            // $imgnamee = "exthump_";
            // $imageNames = ExternalSystem::saveresizeimage($new_widthe,$new_heighte,$fileNamee,$uploadPathe,$fileExte,$imgnamee);
            // $ex_image_table=new CarImage;
            // $ex_image_table->vehicleID=$request->id;
            // $ex_image_table->modelID=0;
            // $ex_image_table->image=$imageNames;
            // $ex_image_table->type=1;
            // $ex_image_table->save();
        }
        if($request->testimg!=''){
             //image upload
            if(!empty($request->testimg)){
                $t=1;
                foreach($request->testimg as $imagevaluets){
                    //upload images
                    $new_widthss_ts = 720;
                    $new_heightss_ts = 480;
                    $filess_ts = $imagevaluets;
                    $fileNamess_ts = $filess_ts->getRealPath();
                    $uploadPathss_ts = public_path('images/');
                    $fileExtss_ts = $filess_ts->getClientOriginalExtension();
                    $imgnamess_ts = $t."sponthumpt_";
                    $imageNames_ts = ExternalSystem::saveresizeimage($new_widthss_ts,$new_heightss_ts,$fileNamess_ts,$uploadPathss_ts,$fileExtss_ts,$imgnamess_ts);
                    $te_image_table=new CarImage;
                    $te_image_table->vehicleID=$request->id;
                    $te_image_table->modelID=0;
                    $te_image_table->image=$imageNames_ts;
                    $te_image_table->type=3;
                    $te_image_table->save();
                    $t++;
                }
            }
            // $new_widtht = 720;
            // $new_heightt = 480;
            // $filet = $request->file('testimg');
            // $fileNamet = $filet->getRealPath();
            // $uploadPatht = public_path('images/car/');
            // $fileExtt = $filet->getClientOriginalExtension();
            // $imgnamet = "tethump_";
            // $imageNamet = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
            // $te_image_table=new CarImage;
            // $te_image_table->vehicleID=$request->id;
            // $te_image_table->modelID=0;
            // $te_image_table->image=$imageNamet;
            // $te_image_table->type=3;
            // $te_image_table->save();
        }
        //video
        $videourl = $request->videourl;
        if(!empty($videourl)){
            $carvideo = CarVideo::select('video')->where('vehicleID',$request->id)->get();
            $carvideoarray = array();
            foreach($carvideo as $val){
                array_push($carvideoarray,$val->video);
            }
            $result=array_diff($carvideoarray,$request->videourl);
            foreach($request->videourl as $valb){
                if($valb!=''){
                    $check = CarVideo::where('video',$valb)->where('vehicleID',$request->id)->count();
                    if($check==0){
                        $agentBrand = new CarVideo;
                        $agentBrand->vehicleID = $request->id;
                        $agentBrand->modelID = 0;
                        $agentBrand->video = $valb;
                        $agentBrand->save();
                    }
                }
            }
            foreach($result as $rval){
                CarVideo::where('video',$rval)->where('vehicleID',$request->id)->delete();
            }

        }
        //360 video
        if($request->video360!=''){
           $new_widtht = 720;
           $new_heightt = 480;
           $filet = $request->file('video360');
           $fileNamet = $filet->getRealPath();
           $uploadPatht = public_path('images/car/');
           $fileExtt = $filet->getClientOriginalExtension();
           $imgnamet = "tethump_";
           $imageNamet = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
           $te_image_table=new CarImage;
           $te_image_table->vehicleID=$request->id;
           $te_image_table->modelID=0;
           $te_image_table->image=$imageNamet;
           $te_image_table->type=4;
           $te_image_table->save();
       }

        echo 1;
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update car Details",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function deleteimg(Request $request)
    {
        $id = $request->id;
        $carImage = CarImage::find($id);
        
        if ($carImage) {
            // Define the image path
            $imagePath = public_path('images/' . $carImage->image);

            // Delete the image file if it exists
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Delete the DB record
            $carImage->delete();

            // Log the action if usertype is 2
            if (session::get('usertype') == 2) {
                $log = [
                    'staff_id'       => session::get('id'),
                    'logDate'        => date('Y-m-d'),
                    'event_name'     => "Delete car image",
                    'event_category' => 13,
                    'event_id'       => $id,
                ];
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }

            echo "1";
        } else {
            echo "0"; // If image not found
        }
    }

    public function delete_carcolor(Request $request){
        $id = $request->id;
        $category = CarColor::find($id);
        $category->delete();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete car color",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    // use App\Models\CarMileage;
    public function delete_carmileage(Request $request){
        $id = $request->id;
        $category = CarMileage::find($id);
        $category->delete();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete car mileage",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    // use App\Models\CarEngine;
    public function delete_carengine(Request $request){
        $id = $request->id;
        $category = CarEngine::find($id);
        $category->delete();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete car engine",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function updateimagetype(Request $request){
        $id = $request->id;
        $status = $request->status;
        $carimg = CarImage::find($id);
        $carimg->type = $status;
        $carimg->save();
    }

    public function pricelist(Request $request){
        $data['active'] = "brand";
        $Vehicle = CarJatoSpecification::where('id',base64_decode($request->key))->first();
        $data['brand_name'] = Brand::where('id',$Vehicle->brandID)->first()->name;
        $data['model_name'] = CarModel::where('id',$Vehicle->modelID)->first()->name;
        $data['vehicle_name'] = CarJatoSpecification::where('id',base64_decode($request->key))->first()->name;
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['vehicleID'] = base64_decode($request->key);
        $data['data'] = CarPrice::where('vehicleID',base64_decode($request->key))->get();
        return view('admin.carprice', $data);
    }

    public function addprice(Request $request){
        $data['active'] = "brand";
        $Vehicle = CarJatoSpecification::where('id',base64_decode($request->id))->first();
        $data['brand_name'] = Brand::where('id',$Vehicle->brandID)->first()->name;
        $data['model_name'] = CarModel::where('id',$Vehicle->modelID)->first()->name;
        $data['vehicle_name'] = CarJatoSpecification::where('id',base64_decode($request->id))->first()->name;
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['data'] = CarPrice::where('id',base64_decode($request->key))->first();
        $data['city'] = City::where('status',1)->get();
        $data['vehicleID'] = base64_decode($request->id);
        $data['modelID'] = CarJatoSpecification::find(base64_decode($request->id))->modelID;
        return view('admin.addcarprice', $data);
    }

    public function insert_carprice(Request $request){
        if($request->id==""){
            $check_name = CarPrice::where('vehicleID',$request->vehicleID)->where('CityID',$request->category)->count();
            if($check_name==0){
                $CarPrice = new CarPrice;
                $CarPrice->modelID = $request->modelID;
                $CarPrice->vehicleID = $request->vehicleID;
                $CarPrice->CityID = $request->category;
                $CarPrice->price = $request->name;
                $CarPrice->rto = 0;
                $CarPrice->insurance = 0;
                $CarPrice->other = 0;
                $CarPrice->onroadprice = 0;
                $CarPrice->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Car Price",
                        'event_category'=>13,
                        'event_id'=>$CarPrice->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = CarPrice::where('vehicleID',$request->vehicleID)->where('CityID',$request->category)->where('id',$request->id);
            if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                echo 2;
            }else{
                $CarPrice = new CarPrice;
                $CarPrice = CarPrice::find($request->id);
                $CarPrice->CityID = $request->category;
                $CarPrice->price = $request->name;
                $CarPrice->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Car Price",
                        'event_category'=>13,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }
        }
    }

    public function delete_carprice(Request $request){
        $id = $request->id;
        $category = CarPrice::find($id);
        $category->delete();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Car Price",
                'event_category'=>13,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function popularCar(Request $request){
        // try {
        //     DB::statement("ALTER TABLE popular_cars CHANGE popular position BIGINT(20) UNSIGNED NOT NULL");

        //     return "Column renamed successfully.";
        // } catch (\Exception $e) {
        //     return "Error: " . $e->getMessage();
        // }
        $data['active'] = "pcar";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        // $data['data'] = CarJatoSpecification::where('status','!=',2)->where('popular',1)->orderBy('position','ASC')->get();
        $data['data'] = PopularCar::where('status','!=',2)->orderBy('position','ASC')->get();
        return view('admin.popularCar', $data);
    }

    public function updateCarOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            // $brand = CarJatoSpecification::find($arr[$i]);
            $brand = PopularCar::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Car Order",
                'event_category'=>13,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function stringtranslation($string){
        if($string!=''){
            $translate = ExternalSystem::translate($string);
            //print_r($translate);
            if($translate['status_code']==200){
                $record = json_decode($translate['body']);
                return $record->translations[0]->text;
            }else{
                return "";
            }
        }else{
            return "";
        }
    }

    public function carimage($make,$model,$year,$color){
        $image = ExternalSystem::car_image($make,$model,$year,$color);
        if($image['status_code']==200){
            $record = json_decode($image['body']);
            return $record->images;
        }else{
            return "";
        }
    }

    public function insertbrochure(Request $request){
        if($request->brochure!=''){
            $vehicleid = $request->broid;
            $imageName = time().'.'.$request->brochure->extension();
            $request->brochure->move(public_path('images/car/'), $imageName);
            $vehicle = CarJatoSpecification::find($vehicleid);
            $vehicle->brochure = $imageName;
            $vehicle->save();
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update brochure",
                    'event_category'=>13,
                    'event_id'=>$vehicleid,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
        echo 1;

    }
}
