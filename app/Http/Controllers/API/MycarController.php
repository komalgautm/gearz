<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MyCar;
use App\Models\MycarDocument;
use App\Models\MycarSpecificationt;
use App\Models\MycarSpecificationtfirst;
use App\Models\MycarSpecificationtfive;
use App\Models\MycarSpecificationtfour;
use App\Models\MycarSpecificationtsecond;
use App\Models\MycarSpecificationtthree;
use App\Models\MycarSpecificationttrim;
use App\Models\MycarService;
use App\Models\MycarServiceTask;
use App\Modules\ExternalSystem;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\MyCarImage;
use App\Models\MycarspecificationEn;
use App\Models\MycarspecificationFirstEn;
use App\Models\MycarspecificationSeccondEn;
use App\Models\MycarspecificationThirdEn;
use App\Models\MycarspecificationFourEn;
use App\Models\MycarspecificationFiveEn;
use App\Models\MycarspecificationSixEn;
use App\Models\MycarspecificationSevenEn;
use App\Models\MycarspecificationExteriorcolorEn;
use App\Models\MycarspecificationInteriorcolorEn;
use App\Models\CarCheck;
use App\Models\CarCheckList;
use App\Models\MycarcheckImage;
use Illuminate\Support\Facades\DB;


class MycarController extends Controller
{
    // public function mycar(Request $request)
    // {
    //     try{
    //     $userID = $request->userID;
    //     $car_nick_name = $request->car_nick_name;
    //     $make = $request->make;
    //     $model = $request->model;
    //     $trim = $request->trim;
    //     $year = $request->year;
    //     $current_odo = $request->current_odo;
    //     // $type = $request->type;
    //     $language = $request->language;
    //     $err_array = array();
    //     if ($userID == null) {
    //         $err_array[] = 'userID';
    //     }
    //     if ($car_nick_name == null) {
    //         $err_array[] = 'car_nick_name';
    //     }
    //     if ($make == null) {
    //         $err_array[] = 'make';
    //     }
    //     if ($model == null) {
    //         $err_array[] = 'model';
    //     }
    //     if ($trim == null) {
    //         $err_array[] = 'trim';
    //     }
    //     if ($year == null) {
    //         $err_array[] = 'year';
    //     }
    //     if ($current_odo == null) {
    //         $err_array[] = 'current_odo';
    //     }
    //     // if ($type == null) {
    //     //     $err_array[] = 'type';
    //     // }
    //     if (count($err_array) > 0) {
    //         $er = implode(",", $err_array);
    //         return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
    //     } else {

    //         if ($language == 2) {
    //             $msg_success = "تم إضافة السيارة ";
    //             $msg_failed = "تم إضافة رقم الهيكل مسبقا ";
    //         } else {
    //             $msg_success = "Car successfully added.";
    //             $msg_failed = "VIN has already been added.";
    //         }
    //         // if ($request->vin != '') {
    //         //     $checkVIN = MyCar::where('vin', $request->vin)->count();
    //         //     if ($checkVIN == 0) {
    //         //         $add = 1;
    //         //     } else {
    //         //         $add = 0;
    //         //     }
    //         // } else {
    //         //     $add = 1;
    //         // }
    //         //check vin
    //         // $checkVIN = MyCar::where('vin', $request->vin)->count();
    //         // if ($add == 1) {
    //             //licence
    //             if ($request->licence != '') {
    //                 $licence = 'lic' . time() . '.' . $request->licence->extension();
    //                 $request->licence->move(public_path('images/mycar/'), $licence);
    //             } else {
    //                 $licence = '';
    //             }
    //             //licenceback
    //             if ($request->license_back != '') {
    //                 $license_back = 'licback' . time() . '.' . $request->license_back->extension();
    //                 $request->license_back->move(public_path('images/mycar/'), $license_back);
    //             } else {
    //                 $license_back = '';
    //             }
    //             //insurance
    //             if ($request->insurance != '') {
    //                 $insurance = 'insurance' . time() . '.' . $request->insurance->extension();
    //                 $request->insurance->move(public_path('images/mycar/'), $insurance);
    //             } else {
    //                 $insurance = '';
    //             }
    //             //car inspection
    //             if ($request->car_inspection != '') {
    //                 $car_inspection = 'car_inspection' . time() . '.' . $request->car_inspection->extension();
    //                 $request->car_inspection->move(public_path('images/mycar/'), $car_inspection);
    //             } else {
    //                 $car_inspection = '';
    //             }


    //             //brand
    //             $brand = Brand::where('name', $request->make);
    //             if ($brand->count() != 0) {
    //                 $brand_id = $brand->first()->id;
    //             } else {
    //                 $brand_id = "";
    //             }
    //             //model
    //             // $model = CarModel::where('id', $request->model);
    //             // if ($model->count() != 0) {
    //             //     $model_id = $model->first()->name;
    //             // } else {
    //             //     $model_id = "";
    //             // }

    //             //engine
    //             $mycar = new MyCar;
    //             $mycar->userID = $request->userID;
    //             $mycar->car_nick_name = $request->car_nick_name;
    //             $mycar->make = $make;
    //             $mycar->make_id = (int) $brand_id;
    //             $mycar->model = $model;
    //             $mycar->submodel = $trim;
    //             $mycar->year = $year;
    //             $mycar->color = $request->color;
    //             $mycar->owner = $request->owner;
    //             $mycar->insurance = $insurance;
    //             $mycar->licence = $licence;
    //             $mycar->license_back = $license_back;
    //             $mycar->licence_expire_date = $request->licence_expire_date;
    //             $mycar->notes = $request->notes;
    //             $mycar->millage = $request->reminder_date;
    //             $mycar->carminvalue = $request->carminvalue;
    //             $mycar->carmaxvalue = $request->carmaxvalue;
    //             $mycar->engine = $car_inspection;
    //             // $mycar->make = $brand_id;
    //             // $mycar->make_id = $request->make;
    //             // $mycar->model = $model_id;
    //             // $mycar->model_id = $request->model;
    //             // $mycar->submodel = "";
    //             // $mycar->submodel_id = $request->submodel;
    //             // $mycar->engine = $request->engine;
    //             // $mycar->vin = $request->vin;
    //             // $mycar->fuel_type = $request->fuel_type;
    //             // $mycar->millage = $request->millage;
    //             $mycar->status = 1;
    //             $mycar->save();
    //             //car image
    //             if ($request->hasFile('mycar_image')) {
    //                 $files = $request->file('mycar_image');
    //                 $i = 1;
    //                 foreach ($files as $file) {
    //                     $mycar_image = $i . time() . '.' . $file->getClientOriginalName();
    //                     $file->move(public_path('images/mycar'), $mycar_image);

    //                     $carimage = new MyCarImage;
    //                     $carimage->mycarID = $mycar->id;
    //                     $carimage->image = $mycar_image;
    //                     $carimage->image_type = 1;
    //                     $carimage->status = 1;
    //                     $carimage->save();
    //                     $i++;
    //                 }
    //             }
    //             //my car other files
    //             if ($request->hasFile('other_files')) {
    //                 $otherFiles = $request->file('other_files');
    //                 $i = 1;
    //                 foreach ($otherFiles as $other_file) {
    //                     $other_filename = $i . time() . '.' . $other_file->getClientOriginalName();
    //                     $file->move(public_path('images/mycar'), $other_filename);

    //                     $carimage = new MyCarImage;
    //                     $carimage->mycarID = $mycar->id;
    //                     $carimage->image = $other_filename;
    //                     $carimage->image_type = 3;
    //                     $carimage->status = 1;
    //                     $carimage->save();
    //                     $i++;
    //                 }
    //             }
    //             // if ($request->mycar_image_link != '') {
    //             //     $img_link = explode(',', $request->mycar_image_link);
    //             //     //$img_link = $request->mycar_image_link;
    //             //     foreach ($img_link as $link) {
    //             //         $carimage = new MyCarImage;
    //             //         $carimage->mycarID = $mycar->id;
    //             //         $carimage->image = $link;
    //             //         $carimage->image_type = 2;
    //             //         $carimage->status = 1;
    //             //         $carimage->save();
    //             //     }
    //             // }

    //             // Fetch latest odometer record for this car
    //             $maxOdometer = MycarService::where('mycarID', $mycar->id)
    //                 ->whereNotNull('odometer')
    //                 ->where('status', 1)
    //                 ->get()
    //                 ->sortByDesc(function ($item) {
    //                     return (int) preg_replace('/[^\d]/', '', $item->odometer);
    //                 })
    //                 ->first();

    //             if ($maxOdometer && $request->current_odo < $maxOdometer->odometer) {
    //                 return response()->json([
    //                     'success' => 'false',
    //                     'message' => 'Your ODO should be more than the previous ODO ' . $maxOdometer->odometer,
    //                 ], 200);
    //             }

    //             //odometer
    //             if ($request->current_odo != '') {
    //                 $MycarService = new MycarService;
    //                 $MycarService->userID = $userID;
    //                 $MycarService->mycarID = $mycar->id;
    //                 $MycarService->serviceDate = $request->current_odo_date;
    //                 $MycarService->odometer = $request->current_odo;
    //                 $MycarService->service_type = 2;
    //                 $MycarService->status = 1;
    //                 $MycarService->save();
    //             }

    //             return response()->json(['success' => 'true', 'message' => $msg_success], 200);
    //         // } else {
    //         //     return response()->json(['success' => 'false', 'message' => $msg_failed], 400);
    //         // }
    //     }
    // }catch(\Exception $e){
    //     return response()->json(['success' => 'false', 'message' => $e->getMessage()], 200);
    // }
    // }

    public function mycar(Request $request)
    {
        try {
            DB::beginTransaction(); // Begin transaction

            $userID = $request->userID;
            $car_nick_name = $request->car_nick_name;
            $make = $request->make;
            $model = $request->model;
            $trim = $request->trim;
            $year = $request->year;
            $current_odo = $request->current_odo;
            $language = $request->language;

            $err_array = [];
            foreach (['userID', 'car_nick_name', 'make', 'model', 'trim', 'year', 'current_odo'] as $field) {
                if (empty($request->$field)) $err_array[] = $field;
            }

            if (count($err_array)) {
                return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . implode(',', $err_array)], 400);
            }

            $msg_success = $language == 2 ? "تم إضافة السيارة " : "Car successfully added.";
            $msg_failed = $language == 2 ? "تم إضافة رقم الهيكل مسبقا " : "VIN has already been added.";

            // Handle file uploads
            $licence = $request->hasFile('licence') ? 'lic' . time() . '.' . $request->licence->extension() : '';
            if ($licence) $request->licence->move(public_path('images/mycar/'), $licence);

            $license_back = $request->hasFile('license_back') ? 'licback' . time() . '.' . $request->license_back->extension() : '';
            if ($license_back) $request->license_back->move(public_path('images/mycar/'), $license_back);

            $insurance = $request->hasFile('insurance') ? 'insurance' . time() . '.' . $request->insurance->extension() : '';
            if ($insurance) $request->insurance->move(public_path('images/mycar/'), $insurance);

            $car_inspection = $request->hasFile('car_inspection') ? 'car_inspection' . time() . '.' . $request->car_inspection->extension() : '';
            if ($car_inspection) $request->car_inspection->move(public_path('images/mycar/'), $car_inspection);

            // Brand ID lookup
            // $brand_id = Brand::where(function($query) use ($make) {
            //     $query->where('name', $make)
            //           ->orWhere('name_ar', $make);
            // })->value('id') ?? null;

            $brand_id = Brand::where('name', $make)->value('id') ?? null;

            // Save MyCar
            // $mycar = MyCar::create([
            //     'userID'               => $userID,
            //     'car_nick_name'        => $car_nick_name,
            //     'make'                 => $make,
            //     'make_id'              => (int) $brand_id,
            //     'model'                => $model,
            //     'submodel'             => $trim,
            //     'year'                 => $year,
            //     'color'                => $request->color,
            //     'owner'                => $request->owner,
            //     'insurance'            => $insurance,
            //     'licence'              => $licence,
            //     'license_back'         => $license_back,
            //     'licence_expire_date'  => $request->licence_expire_date,
            //     'notes'                => $request->notes,
            //     'millage'              => $request->reminder_date,
            //     'carminvalue'          => $request->carminvalue,
            //     'carmaxvalue'          => $request->carmaxvalue,
            //     'engine'               => $car_inspection,
            //     'status'               => 1,
            // ]);

            $mycar = new MyCar;
            $mycar->userID = $request->userID;
            $mycar->car_nick_name = $request->car_nick_name;
            $mycar->make = $make;
            $mycar->make_id = (int) $brand_id;
            $mycar->model = $model;
            $mycar->submodel = $trim;
            $mycar->year = $year;
            $mycar->color = $request->color;
            $mycar->owner = $request->owner;
            $mycar->insurance = $insurance;
            $mycar->licence = $licence;
            $mycar->license_back = $license_back;
            $mycar->licence_expire_date = $request->licence_expire_date;
            $mycar->notes = $request->notes;
            $mycar->millage = $request->reminder_date;
            $mycar->carminvalue = $request->carminvalue;
            $mycar->carmaxvalue = $request->carmaxvalue;
            $mycar->engine = $car_inspection;
            $mycar->status = 1;
            $mycar->save();

            // Save car images
            if ($request->hasFile('mycar_image')) {
                foreach ($request->file('mycar_image') as $index => $file) {
                    $filename = ($index + 1) . time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path('images/mycar'), $filename);

                    $carimage = new MyCarImage;
                    $carimage->mycarID = $mycar->id;
                    $carimage->image = $filename;
                    $carimage->image_type = 1;
                    $carimage->status = 1;
                    $carimage->save();
                }
            }

            // Save other car files
            if ($request->hasFile('other_files')) {
                foreach ($request->file('other_files') as $index => $file) {
                    $filename = ($index + 1) . time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path('images/mycar'), $filename);

                    $carotherimage = new MyCarImage;
                    $carotherimage->mycarID = $mycar->id;
                    $carotherimage->image = $filename;
                    $carotherimage->image_type = 3;
                    $carotherimage->status = 1;
                    $carotherimage->save();
                }
            }

            // Check previous odometer
            $maxOdo = MycarService::where('mycarID', $mycar->id)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(fn($item) => (int) preg_replace('/[^\d]/', '', $item->odometer))
                ->first();

            if ($maxOdo && $current_odo < $maxOdo->odometer) {
                DB::rollBack();
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdo->odometer,
                ], 200);
            }

            // Save odometer
            if ($current_odo) {
                $MycarService = new MycarService;
                $MycarService->userID = $userID;
                $MycarService->mycarID = $mycar->id;
                $MycarService->serviceDate = $request->current_odo_date;
                $MycarService->odometer = $request->current_odo;
                $MycarService->service_type = 2;
                $MycarService->status = 1;
                $MycarService->save();
            }

            DB::commit(); // All good, commit transaction

            return response()->json(['success' => 'true', 'message' => $msg_success], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Something went wrong
            return response()->json([
                'success' => 'false',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function edit_mycar(Request $request)
    // {
    //     $myCarID = $request->myCarID;
    //     $userID = $request->userID;
    //     $car_nick_name = $request->car_nick_name;
    //     $make = $request->make;
    //     $model = $request->model;
    //     $trim = $request->trim;
    //     $year = $request->year;
    //     $language = $request->language;
    //     $err_array = array();
    //     if ($userID == null) {
    //         $err_array[] = 'userID';
    //     }
    //     if ($car_nick_name == null) {
    //         $err_array[] = 'car_nick_name';
    //     }
    //     if ($make == null) {
    //         $err_array[] = 'make';
    //     }
    //     if ($model == null) {
    //         $err_array[] = 'model';
    //     }
    //     if ($trim == null) {
    //         $err_array[] = 'trim';
    //     }
    //     if ($year == null) {
    //         $err_array[] = 'year';
    //     }
    //     if (count($err_array) > 0) {
    //         $er = implode(",", $err_array);
    //         return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
    //     } else {
    //         if ($language == 2) {
    //             $msg_success = "تم التحديث بنجاح.";
    //             $msg_failed = "تم إضافة رقم الهيكل مسبقا ";
    //         } else {
    //             $msg_success = "Car successfully updated.";
    //             $msg_failed = "VIN has already been added.";
    //         }
    //         // if ($request->vin != '') {
    //         //     $checkVIN = MyCar::where('vin', $request->vin);
    //         //     if ($checkVIN->count() >= 1 && $request->myCarID != $checkVIN->first()->id) {
    //         //         $add = 0;
    //         //     } else {
    //         //         $add = 1;
    //         //     }
    //         // } else {
    //         //     $add = 1;
    //         // }
    //         // if ($add = 1) {
    //             //licence
    //             if ($request->licence != '') {
    //                 $licence = 'lic' . time() . '.' . $request->licence->extension();
    //                 $request->licence->move(public_path('images/mycar/'), $licence);
    //             } else {
    //                 $licence = '';
    //             }
    //             //licenceback
    //             if ($request->license_back != '') {
    //                 $license_back = 'licback' . time() . '.' . $request->license_back->extension();
    //                 $request->license_back->move(public_path('images/mycar/'), $license_back);
    //             } else {
    //                 $license_back = '';
    //             }
    //             //insurance
    //             if ($request->insurance != '') {
    //                 $insurance = 'insurance' . time() . '.' . $request->insurance->extension();
    //                 $request->insurance->move(public_path('images/mycar/'), $insurance);
    //             } else {
    //                 $insurance = '';
    //             }
    //             //car inspection
    //             if ($request->car_inspection != '') {
    //                 $car_inspection = 'car_inspection' . time() . '.' . $request->car_inspection->extension();
    //                 $request->car_inspection->move(public_path('images/mycar/'), $car_inspection);
    //             } else {
    //                 $car_inspection = '';
    //             }
    //             //check vin
    //             //manually
    //             //brand
    //             $brand = Brand::where('name', $request->make);
    //             if ($brand->count() != 0) {
    //                 $brand_id = $brand->first()->id;
    //             } else {
    //                 $brand_id = "";
    //             }
    //             //model
    //             // $model = CarModel::where('id', $request->model);
    //             // if ($model->count() != 0) {
    //             //     $model_id = $model->first()->name;
    //             // } else {
    //             //     $model_id = "";
    //             // }
    //             //submodel
    //             //1= Sedan, 2=SUV, 3=Crossover, 4=hatchback
    //             // switch ($request->submodel) {
    //             //     case "1":
    //             //         $submodelname = "Sedan";
    //             //         break;
    //             //     case "2":
    //             //         $submodelname = "SUV";
    //             //         break;
    //             //     case "3":
    //             //         $submodelname = "Crossover";
    //             //         break;
    //             //     case "4":
    //             //         $submodelname = "hatchback";
    //             //         break;
    //             //     default:
    //             //         $submodelname = "";
    //             // }
    //             //engine
    //             $mycar = MyCar::find($myCarID);
    //             $mycar->car_nick_name = $request->car_nick_name;
    //             $mycar->make = $make;
    //             $mycar->make_id = (int) $brand_id;
    //             $mycar->model = $model;
    //             $mycar->submodel = $trim;
    //             $mycar->year = $year;
    //             $mycar->color = $request->color;
    //             $mycar->owner = $request->owner;
    //             if ($licence != '') {
    //                 $mycar->licence = $licence;
    //             }
    //             if ($license_back != '') {
    //                 $mycar->license_back = $license_back;
    //             }
    //             $mycar->licence_expire_date = $request->licence_expire_date;
    //             // $mycar->vin = $request->vin;
    //             if ($insurance != '') {
    //                 $mycar->insurance = $insurance;
    //             }
    //             if ($car_inspection != '') {
    //                 $mycar->engine = $car_inspection;
    //             }
    //             // $mycar->fuel_type = $request->fuel_type;
    //             $mycar->carminvalue = $request->carminvalue;
    //             $mycar->carmaxvalue = $request->carmaxvalue;
    //             $mycar->notes = $request->notes;
    //             $mycar->millage = $request->reminder_date;
    //             // $mycar->millage = $request->millage;
    //             $mycar->save();
    //             //car image
    //             if ($request->mycar_image != '') {
    //                 if (!empty($request->mycar_image)) {

    //                     if ($files = $request->file('mycar_image')) {
    //                         $i = 1;
    //                         foreach ($files as $file) {
    //                             $mycar_image = $i . time() . '.' . $file->getClientOriginalName();
    //                             $file->move(public_path('images/mycar/'), $mycar_image);
    //                             $carimage = new MyCarImage;
    //                             $carimage->mycarID = $myCarID;
    //                             $carimage->image = $mycar_image;
    //                             $carimage->status = 1;
    //                             $carimage->save();
    //                             $i++;
    //                         }
    //                     }
    //                 }
    //             }
    //             //my car other files
    //             if ($request->other_files != '') {
    //                 if (!empty($request->other_files)) {

    //                     if ($other_files = $request->file('other_files')) {
    //                         $i = 1;
    //                         foreach ($other_files as $other_file) {
    //                             $other_filename = $i . time() . '.' . $other_file->getClientOriginalName();
    //                             $file->move(public_path('images/mycar/'), $other_filename);
    //                             //$file->move('image',$name);
    //                             //$images[]=$data;
    //                             $carimage = new MyCarImage;
    //                             $carimage->mycarID = $mycar->id;
    //                             $carimage->image = $other_filename;
    //                             $carimage->image_type = 3;
    //                             $carimage->status = 1;
    //                             $carimage->save();
    //                             $i++;
    //                         }
    //                     }
    //                 }
    //             }
    //             return response()->json(['success' => 'true', 'message' => $msg_success], 200);
    //         // } else {
    //         //     return response()->json(['success' => 'false', 'message' => $msg_failed], 400);
    //         // }
    //     }
    // }

    public function edit_mycar(Request $request)
    {
        $err_array = [];
        $required_fields = ['myCarID', 'userID', 'car_nick_name', 'make', 'model', 'trim', 'year'];

        foreach ($required_fields as $field) {
            if (empty($request->$field)) {
                $err_array[] = $field;
            }
        }

        if (count($err_array)) {
            $missing = implode(', ', $err_array);
            return response()->json(['success' => false, 'message' => "400 Bad Request, missing $missing"], 400);
        }

        $language = $request->language;
        $msg_success = $language == 2 ? "تم التحديث بنجاح." : "Car successfully updated.";

        DB::beginTransaction();

        try {

            $saveFile = function($field, $prefix) use ($request) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $prefix . time() . '.' . $file->extension();
                    $file->move(public_path('images/mycar/'), $filename);
                    return $filename;
                }
                return '';
            };

            $licence = $saveFile('licence', 'lic');
            $license_back = $saveFile('license_back', 'licback');
            $insurance = $saveFile('insurance', 'insurance');
            $car_inspection = $saveFile('car_inspection', 'car_inspection');

            $brand_id = Brand::where('name', $request->make)->value('id') ?? null;

            $mycar = MyCar::find($request->myCarID);
            $mycar->car_nick_name = $request->car_nick_name;
            $mycar->make = $request->make;
            $mycar->make_id = (int) $brand_id;
            $mycar->model = $request->model;
            $mycar->submodel = $request->trim;
            $mycar->year = $request->year;
            $mycar->color = $request->color;
            $mycar->owner = $request->owner;
            if ($licence != '') {
                $mycar->licence = $licence;
            }
            if ($license_back != '') {
                $mycar->license_back = $license_back;
            }
            $mycar->licence_expire_date = $request->licence_expire_date;
            if ($insurance != '') {
                $mycar->insurance = $insurance;
            }
            if ($car_inspection != '') {
                $mycar->engine = $car_inspection;
            }
            $mycar->carminvalue = $request->carminvalue;
            $mycar->carmaxvalue = $request->carmaxvalue;
            $mycar->notes = $request->notes;
            $mycar->millage = $request->reminder_date;
            $mycar->save();

            // car images
            if ($request->hasFile('mycar_image')) {
                foreach ($request->file('mycar_image') as $index => $file) {
                    $filename = ($index + 1) . time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path('images/mycar/'), $filename);

                    $carimage = new MyCarImage;
                    $carimage->mycarID = $mycar->id;
                    $carimage->image = $filename;
                    $carimage->status = 1;
                    $carimage->save();
                }
            }

            // other files
            if ($request->hasFile('other_files')) {
                foreach ($request->file('other_files') as $index => $file) {
                    $filename = ($index + 1) . time() . '.' . $file->getClientOriginalName();
                    $file->move(public_path('images/mycar/'), $filename);

                    $carotherimage = new MyCarImage;
                    $carotherimage->mycarID = $mycar->id;
                    $carotherimage->image = $filename;
                    $carotherimage->image_type = 3;
                    $carotherimage->status = 1;
                    $carotherimage->save();
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => $msg_success], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the car.' . $e->getMessage()], 500);
        }
    }

    public function my_carlist(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $my_carlist = MyCar::where('userID', $userID)->where('status', 1)->get();
            $mycarArray = array();
            foreach ($my_carlist as $value) {
                $data['id'] = $value->id;
                // $data['car_nick_name'] = $value->car_nick_name;
                $data['make'] = $value->make;
                // $data['make_id'] = $value->make_id;
                $data['model'] = $value->model;
                // $data['model_id'] = $value->model_id;
                $data['year'] = $value->year;
                $data['trim'] = $value->submodel;
                // $data['submodel_id'] = $value->submodel_id;
                // $data['engine'] = $value->engine;
                // $data['color'] = $value->color;
                // $data['owner'] = $value->owner;
                // $data['licence'] = $value->licence;
                // $data['license_back'] = $value->license_back;
                // $data['vin'] = $value->vin;
                // $data['insurance'] = $value->insurance;
                // $data['fuel_type'] = $value->fuel_type;
                // $data['carminvalue'] = $value->carminvalue;
                // $data['carmaxvalue'] = $value->carmaxvalue;
                // $data['notes'] = $value->notes;
                // $data['reminder_date'] = $value->millage;
                // $data['licence_expire_date'] = $value->licence_expire_date;
                //image
                $carimage = MyCarImage::where('mycarID', $value->id)->whereIn('image_type', [1, 2]);
                if ($carimage->count() != 0) {
                    if ($carimage->first()->image_type == 1) {
                        $data['image'] = url('public/images/mycar/' . $carimage->first()->image);
                    } else {
                        $data['image'] = $carimage->first()->image;
                    }
                } else {
                    $data['image'] = "";
                }

                // $carimage = MyCarImage::where('mycarID',$value->id)->get();
                // $carImageArray = array();
                // foreach($carimage as $carimg){
                //     $arr['imageid'] = $carimg->id;
                //     if($carimg->image_type==1){
                //         $arr['image'] = url('public/images/mycar/'.$carimg->image);
                //     }else{
                //         $arr['image'] = $carimg->image;
                //     }
                //     array_push($carImageArray,$arr);
                //     break;
                // }
                // $data['mycar_image'] = $carImageArray;
                // //document
                // $cardocument = MycarDocument::where('mycarID',$value->id)->get();
                // $carDocumentArray = array();
                // foreach($cardocument as $document){
                //     $arr1['document'] = $document->document;
                //     array_push($carDocumentArray,$arr1);
                // }
                // $data['document'] = $carDocumentArray;

                $odometer = MycarService::where('mycarID', $value->id)->where('status', 1)->orderBy('serviceDate', 'DESC')->limit(1);
                if ($odometer->count() == 0) {
                    $data['odometer'] = "";
                } else {
                    $data['odometer'] = $odometer->first()->odometer;
                }
                array_push($mycarArray, $data);
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $mycarArray], 200);
        }
    }
    public function my_cardetail(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $value = MyCar::where('userID', $userID)->where('id', $myCarID)->first();
            $data['id'] = $value->id;
            $data['car_nick_name'] = $value->car_nick_name;
            $data['make'] = $value->make;
            $data['make_id'] = $value->make_id;
            $data['model'] = $value->model;
            // $data['model_id'] = $value->model_id;
            $data['trim'] = $value->submodel;
            $data['year'] = $value->year;
            // $data['submodel'] = $value->submodel_id;
            // $data['engine'] = $value->engine;
            $data['color'] = $value->color;
            $data['owner'] = $value->owner;
            $data['licence'] = $value->licence;
            $data['license_back'] = $value->license_back;
            $data['licence_expire_date'] = $value->licence_expire_date;
            // $data['vin'] = $value->vin;
            $data['insurance'] = $value->insurance;
            // $data['fuel_type'] = $value->fuel_type;
            $data['carminvalue'] = $value->carminvalue;
            $data['carmaxvalue'] = $value->carmaxvalue;
            $data['notes'] = $value->notes;
            $data['reminder_date'] = $value->millage;
            $data['car_inspection'] = $value->engine;
            //image
            $carimage = MyCarImage::where('mycarID', $value->id)->whereIn('image_type', [1, 2])->get();
            $carImageArray = array();
            foreach ($carimage as $carimg) {
                $arr['imageid'] = $carimg->id;
                if ($carimg->image_type == 1) {
                    $arr['image'] = url('public/images/mycar/' . $carimg->image);
                } else {
                    $arr['image'] = $carimg->image;
                }
                array_push($carImageArray, $arr);
            }
            $data['mycar_image'] = $carImageArray;
            //other files
            $carotherfiles = MyCarImage::where(['mycarID' => $value->id, 'image_type' => 3])->get();
            $carOtherFileArray = array();
            foreach ($carotherfiles as $carotherfile) {
                $arr['imageid'] = $carotherfile->id;
                $arr['image'] = url('public/images/mycar/' . $carotherfile->image);
                array_push($carOtherFileArray, $arr);
            }
            $data['mycar_other_files'] = $carOtherFileArray;
            $odometer = MycarService::where('mycarID', $value->id)->where('status', 1)->orderBy('serviceDate', 'DESC')->limit(1);
            if ($odometer->count() == 0) {
                $data['odometer'] = "";
                $data['odometerdate'] = "";
            } else {
                $data['odometer'] = $odometer->first()->odometer;
                $data['odometerdate'] = $odometer->first()->serviceDate;
            }
            //document
            // $cardocument = MycarDocument::where('mycarID',$value->id)->get();
            // $carDocumentArray = array();
            // foreach($cardocument as $document){
            //     $arr1['document'] = $document->document;
            //     array_push($carDocumentArray,$arr1);
            // }
            // $data['document'] = $carDocumentArray;
            return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
        }
    }

    public function addService(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceDate = $request->serviceDate;
        $odometer = $request->odometer;
        $service = $request->service;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($serviceDate == null) {
            $err_array[] = 'serviceDate';
        }
        if ($odometer == null) {
            $err_array[] = 'odometer';
        }
        if ($service == null) {
            $err_array[] = 'service';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم إضافة الخدمة ";
            } else {
                $msg_success = "Service successfully added.";
            }
            if ($request->attachment != '') {
                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('images/mycar/'), $imageName);
            } else {
                $imageName = '';
            }

            // Fetch latest odometer record for this car
            $maxOdometer = MycarService::where('mycarID', $myCarID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(function ($item) {
                    return (int) preg_replace('/[^\d]/', '', $item->odometer);
                })
                ->first();

            if ($maxOdometer && $odometer < $maxOdometer->odometer) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdometer->odometer,
                ], 200);
            }

            $MycarService = new MycarService;
            $MycarService->userID = $userID;
            $MycarService->mycarID = $request->myCarID;
            $MycarService->serviceDate = $request->serviceDate;
            $MycarService->odometer = $request->odometer;
            $MycarService->service = $request->service;
            $MycarService->serviceCenter = $request->serviceCenter;
            $MycarService->totalcost = $request->totalcost;
            $MycarService->notes = $request->notes;
            $MycarService->attachment = $imageName;
            $MycarService->service_type = 1;
            $MycarService->status = 1;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }
    public function editService(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceDate = $request->serviceDate;
        $odometer = $request->odometer;
        $service = $request->service;
        $serviceID = $request->serviceID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($serviceDate == null) {
            $err_array[] = 'serviceDate';
        }
        if ($odometer == null) {
            $err_array[] = 'odometer';
        }
        if ($service == null) {
            $err_array[] = 'service';
        }
        if ($serviceID == null) {
            $err_array[] = 'serviceID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث بنجاح.";
            } else {
                $msg_success = "Service updated successfully.";
            }
            if ($request->attachment != '') {
                $imageName = time() . '.' . $request->attachment->extension();
                $request->attachment->move(public_path('images/mycar/'), $imageName);
            } else {
                $imageName = '';
            }

            // Fetch latest odometer record for this car
            $maxOdometer = MycarService::where('mycarID', $myCarID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(function ($item) {
                    return (int) preg_replace('/[^\d]/', '', $item->odometer);
                })
                ->first();

            if ($maxOdometer && $odometer < $maxOdometer->odometer) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdometer->odometer,
                ], 200);
            }

            $MycarService = MycarService::find($serviceID);
            $MycarService->serviceDate = $request->serviceDate;
            $MycarService->odometer = $request->odometer;
            $MycarService->service = $request->service;
            $MycarService->serviceCenter = $request->serviceCenter;
            $MycarService->totalcost = $request->totalcost;
            $MycarService->notes = $request->notes;
            if ($imageName != '') {
                $MycarService->attachment = $imageName;
            }
            $MycarService->status = 1;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function servicelist(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $language = $request->language;
        $filter = $request->filter;
        $servicetype = $request->servicetype;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $page = $request->page;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($filter == 0 || $filter == "") {
                $perPage = 10;
                $offset = ($page - 1) * $perPage;
                //$MycarService = MycarService::where('userID', $userID)->where('mycarID', $myCarID)->where('status', 1)->orderBy('serviceDate', 'DESC')->get();
                $MycarService = MycarService::where('userID', $userID)
                    ->where('mycarID', $myCarID)
                    ->where('status', 1)
                    ->orderBy('serviceDate', 'DESC')
                    ->skip($offset)
                    ->take($perPage)
                    ->get();
                $MycarServiceArray = array();
                foreach ($MycarService as $value) {
                    $data['id'] = $value->id;
                    $data['serviceDate'] = $value->serviceDate;
                    $data['odometer'] = $value->odometer;
                    $data['service'] = $value->service;
                    if ($value->service_type == 3) {
                        if ($value->service != '') {
                            $serv = explode(",", $value->service)[0];

                            $carcheckid = CarCheckList::where('id', $serv)->first()->carcheckID;
                            if ($language == 2) {
                                $ser = CarCheck::find($carcheckid)->name_ar;
                            } else {
                                $ser = CarCheck::find($carcheckid)->name;
                            }
                            $data['service_name'] = $ser;

                            // $sername = "";

                            // foreach ($serv as $se) {
                            //     $carcheckid = CarCheckList::where('id', $se)->first()->carcheckID;
                            //     if ($language == 2) {
                            //         $ser = CarCheck::find($carcheckid)->name_ar;
                            //         $sername = $sername . ',' . $ser;
                            //     } else {
                            //         $ser = CarCheck::find($carcheckid)->name;
                            //         $sername = $sername . ',' . $ser;
                            //     }
                            // }
                            // $data['service_name'] = ltrim($sername, ',');

                            $data['notes'] = "";
                        } else {
                            $data['service_name'] = "";
                            $data['notes'] = "";
                        }
                    } else {
                        if ($value->service != '') {
                            $serv = explode(",", $value->service);
                            $sername = "";
                            foreach ($serv as $se) {
                                $ser = MycarServiceTask::find($se)->name;
                                $sername = $sername . ',' . $ser;
                            }
                            $data['service_name'] = ltrim($sername, ',');
                        } else {
                            $data['service_name'] = "";
                        }
                        $data['notes'] = $value->notes;
                    }

                    $data['serviceCenter'] = $value->serviceCenter;
                    $data['totalcost'] = $value->totalcost;
                    $data['notes'] = $value->notes;
                    $data['attachment'] = $value->attachment;
                    $data['service_type'] = $value->service_type;
                    array_push($MycarServiceArray, $data);
                }
                return response()->json(['success' => 'true', 'message' => "", 'result' => $MycarServiceArray], 200);
            } else {
                //$MycarService = MycarService::where('userID', $userID)->where('mycarID', $myCarID)->where('status', 1)->where('service_type',$servicetype)->orderBy('serviceDate', 'DESC')->get();
                $MycarService = MycarService::where('userID', $userID)
                    ->where('mycarID', $myCarID)
                    ->where('status', 1)
                    ->where('service_type', $servicetype)
                    ->when($fromdate && $todate, function ($query) use ($fromdate, $todate) {
                        // Filter by date range using whereBetween
                        return $query->whereBetween('serviceDate', [$fromdate, $todate]);
                    })
                    ->orderBy('serviceDate', 'DESC')
                    ->get();
                $MycarServiceArray = array();
                foreach ($MycarService as $value) {
                    $data['id'] = $value->id;
                    $data['serviceDate'] = $value->serviceDate;
                    $data['odometer'] = $value->odometer;
                    $data['service'] = $value->service;
                    if ($value->service_type == 3) {
                        if ($value->service != '') {
                            $serv = explode(",", $value->service);
                            $carcheckid = CarCheckList::where('id', $serv)->first()->carcheckID;
                            if ($language == 2) {
                                $ser = CarCheck::find($carcheckid)->name_ar;
                            } else {
                                $ser = CarCheck::find($carcheckid)->name;
                            }
                            $data['service_name'] = $ser;
                            // $sername = "";
                            // foreach ($serv as $se) {
                            //     $carcheckid = CarCheckList::where('id', $se)->first()->carcheckID;
                            //     if ($language == 2) {
                            //         $ser = CarCheck::find($carcheckid)->name_ar;
                            //         $sername = $sername . ',' . $ser;
                            //     } else {
                            //         $ser = CarCheck::find($carcheckid)->name;
                            //         $sername = $sername . ',' . $ser;
                            //     }
                            // }
                            // $data['service_name'] = ltrim($sername, ',');
                            $data['notes'] = "";
                        } else {
                            $data['service_name'] = "";
                            $data['notes'] = "";
                        }
                    } else {
                        if ($value->service != '') {
                            $serv = explode(",", $value->service);
                            $sername = "";
                            foreach ($serv as $se) {
                                $ser = MycarServiceTask::find($se)->name;
                                $sername = $sername . ',' . $ser;
                            }
                            $data['service_name'] = ltrim($sername, ',');
                        } else {
                            $data['service_name'] = "";
                        }
                        $data['notes'] = $value->notes;
                    }

                    $data['serviceCenter'] = $value->serviceCenter;
                    $data['totalcost'] = $value->totalcost;
                    $data['notes'] = $value->notes;
                    $data['attachment'] = $value->attachment;
                    $data['service_type'] = $value->service_type;
                    array_push($MycarServiceArray, $data);
                }
                return response()->json(['success' => 'true', 'message' => "", 'result' => $MycarServiceArray], 200);
            }
        }
    }

    public function servicedetail(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceID = $request->serviceID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($serviceID == null) {
            $err_array[] = 'serviceID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $MycarService = MycarService::where('id', $serviceID)->first();
            $data['id'] = $MycarService->id;
            $data['serviceDate'] = $MycarService->serviceDate;
            $data['odometer'] = $MycarService->odometer;
            $data['service'] = $MycarService->service;
            if ($MycarService->service_type == 3) {
                if ($language == 2) {
                    $data['service_name'] = CarCheck::where('id', $MycarService->service)->first()->name_ar;
                    $data['notes'] = CarCheck::where('id', $MycarService->service)->first()->content_ar;
                } else {
                    $data['service_name'] = CarCheck::where('id', $MycarService->service)->first()->name;
                    $data['notes'] = CarCheck::where('id', $MycarService->service)->first()->content;
                }
            } else {
                if ($MycarService->service != '') {
                    $serv = explode(",", $MycarService->service);
                    $sername = "";
                    foreach ($serv as $se) {
                        $ser = MycarServiceTask::find($se)->name;
                        $sername = $sername . ',' . $ser;
                    }
                    $data['service_name'] = ltrim($sername, ',');
                } else {
                    $data['service_name'] = "";
                }
                $data['notes'] = $MycarService->notes;
            }
            // if($MycarService->service != ''){
            //     $serv = explode(",",$MycarService->service);
            //     $sername = "";
            //     foreach($serv as $se){
            //         $ser = MycarServiceTask::find($se)->name;
            //         $sername = $sername.','.$ser;
            //         //$sername = $sername.','.$sername;
            //     }
            //     $data['service_name'] = ltrim($sername,',');
            // }else{
            //     $data['service_name'] = "";
            // }
            $data['serviceCenter'] = $MycarService->serviceCenter;
            $data['totalcost'] = $MycarService->totalcost;

            $data['attachment'] = $MycarService->attachment;
            return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
        }
    }

    public function addservicetask(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $language = $request->language;
        $name = $request->name;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($name == null) {
            $err_array[] = 'name';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الإضافة بنجاح ";
            } else {
                $msg_success = "Service task added successfully.";
            }
            $MycarServiceTask = new MycarServiceTask;
            $MycarServiceTask->userID = $request->userID;
            $MycarServiceTask->mycarID = $request->myCarID;
            $MycarServiceTask->name = $request->name;
            $MycarServiceTask->service_type = $request->service_type;
            $MycarServiceTask->status = 1;
            $MycarServiceTask->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function editservicetask(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceTaskID = $request->serviceTaskID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($serviceTaskID == null) {
            $err_array[] = 'serviceTaskID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث بنجاح";
            } else {
                $msg_success = "Service task updated successfully.";
            }
            $MycarServiceTask = MycarServiceTask::find($serviceTaskID);
            $MycarServiceTask->name = $request->name;
            $MycarServiceTask->service_type = $request->service_type;
            $MycarServiceTask->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function servicetasklist(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceID = $request->serviceId;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            // $MycarServiceTask = MycarServiceTask::where('userID', $userID)->orwhere('userID', 0)->where('status', 1)->get();
            $MycarServiceTask = MycarServiceTask::where(function ($query) use ($userID) {
                $query->where('userID', $userID)
                    ->orWhere('userID', 0);
            })->where('status', 1)->get();
            $MycarServiceTaskArray = array();
            foreach ($MycarServiceTask as $value) {
                $data['id'] = $value->id;
                if ($language == 2) {
                    //echo $value->name_ar;
                    if ($value->name_ar != "") {
                        $data['name'] = $value->name_ar;
                    } else {
                        $data['name'] = $value->name;
                    }
                } else {
                    $data['name'] = $value->name;
                }
                //$data['name'] = $value->name;
                //$lastservice =
                $myCarService = MycarService::find($serviceID);
                $serviceIds = explode(",", $myCarService->service ?? '');

                $data["checkValue"] = in_array($value->id, $serviceIds) ? "1" : "0";


                $latestService = MycarService::where('userID', $userID)
                ->whereRaw("FIND_IN_SET(?, service)", [$value->id])
                ->latest() 
                ->first();

                $data['lastservice'] = $latestService ? $latestService->serviceDate : "";

                $countservice =  MycarServiceTask::where('id', $value->id)->where('userID', $userID)->where('mycarID', $myCarID)->where('status', 1)->count();
                if ($countservice != 0) {
                    $MycarServiceTaskcheck = MycarServiceTask::where('id', $value->id)->where('userID', $userID)->where('mycarID', $myCarID)->where('status', 1)->first();
                    if ($MycarServiceTaskcheck->service_type == 2) {
                        array_push($MycarServiceTaskArray, $data);
                    } else if ($value->service_type == 1) {
                        array_push($MycarServiceTaskArray, $data);
                    }
                } else {
                    if ($value->service_type == 1) {
                        array_push($MycarServiceTaskArray, $data);
                    }
                }
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $MycarServiceTaskArray], 200);
        }
    }

    public function servicetaskdetail(Request $request)
    {
        $userID = $request->userID;
        $serviceTaskID = $request->serviceTaskID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($serviceTaskID == null) {
            $err_array[] = 'serviceTaskID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $MycarServiceTask = MycarServiceTask::where('id', $serviceTaskID)->first();
            $data['id'] = $MycarServiceTask->id;
            $data['name'] = $MycarServiceTask->name;
            $data['service_type'] = $MycarServiceTask->service_type;
            return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
        }
    }

    public function deleteService(Request $request)
    {
        $userID = $request->userID;
        $serviceID = $request->serviceID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($serviceID == null) {
            $err_array[] = 'serviceID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الحذف بنجاح";
            } else {
                $msg_success = "Service deleted successfully.";
            }
            $MycarService = MycarService::find($serviceID);
            $MycarService->status = 2;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function deleteServicetask(Request $request)
    {
        $userID = $request->userID;
        $servicetaskID = $request->servicetaskID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($servicetaskID == null) {
            $err_array[] = 'servicetaskID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الحذف بنجاح ";
            } else {
                $msg_success = "Service task deleted successfully.";
            }
            $MycarService = MycarServiceTask::find($servicetaskID);
            $MycarService->status = 2;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function addOdometer(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        // $serviceDate = $request->serviceDate;
        $odometer = $request->odometer;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        // if ($serviceDate == null) {
        //     $err_array[] = 'serviceDate';
        // }
        if ($odometer == null) {
            $err_array[] = 'odometer';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الإضافة ";
            } else {
                $msg_success = "ODO meter added successfully.";
            }
            
            // Fetch latest odometer record for this car
            $maxOdometer = MycarService::where('mycarID', $myCarID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(function ($item) {
                    return (int) preg_replace('/[^\d]/', '', $item->odometer);
                })
                ->first();

            if ($maxOdometer && $odometer < $maxOdometer->odometer) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdometer->odometer,
                ], 200);
            }

            $MycarService = new MycarService;
            $MycarService->userID = $userID;
            $MycarService->mycarID = $request->myCarID;
            $MycarService->serviceDate = date('Y-m-d');
            $MycarService->odometer = $request->odometer;
            $MycarService->notes = $request->notes;
            $MycarService->service_type = 2;
            $MycarService->status = 1;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }
    public function editOdometer(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        // $serviceDate = $request->serviceDate;
        $odometer = $request->odometer;
        $service = $request->service;
        $odometerID = $request->odometerID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        // if ($serviceDate == null) {
        //     $err_array[] = 'serviceDate';
        // }
        if ($odometer == null) {
            $err_array[] = 'odometer';
        }
        if ($odometerID == null) {
            $err_array[] = 'odometerID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث ";
            } else {
                $msg_success = "ODO meter updated successfully.";
            }

            // Fetch latest odometer record for this car
            $maxOdometer = MycarService::where('mycarID', $myCarID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(function ($item) {
                    return (int) preg_replace('/[^\d]/', '', $item->odometer);
                })
                ->first();

            if ($maxOdometer && $odometer < $maxOdometer->odometer) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdometer->odometer,
                ], 200);
            }

            $MycarService = MycarService::find($odometerID);
            // $MycarService->serviceDate = $request->serviceDate;
            $MycarService->odometer = $request->odometer;
            $MycarService->notes = $request->notes;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function deleteOdometer(Request $request)
    {
        $userID = $request->userID;
        $odometerID = $request->odometerID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($odometerID == null) {
            $err_array[] = 'odometerID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الحذف ";
            } else {
                $msg_success = "ODO meter deleted successfully.";
            }
            $MycarService = MycarService::find($odometerID);
            $MycarService->status = 2;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function last_odometer(Request $request)
    {
        $userID = $request->userID;
        $carID = $request->carID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($carID == null) {
            $err_array[] = 'carID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            // $odometer = MycarService::where('mycarID', $carID)->where('status', 1)->orderBy('serviceDate', 'DESC')->limit(1);
            $odometer = MycarService::where('mycarID', $carID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(function ($item) {
                    return (int) preg_replace('/[^\d]/', '', $item->odometer);
                });
                // ->first();
            if ($odometer->count() == 0) {
                $data['odometer'] = "";
            } else {
                $data['odometer'] = $odometer->first()->odometer;
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
        }
    }

    public function deletemycar(Request $request)
    {
        $userID = $request->userID;
        $mycarID = $request->mycarID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($mycarID == null) {
            $err_array[] = 'mycarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم حذف السيارة بنجاح";
            } else {
                $msg_success = "My car deleted successfully.";
            }
            $MycarService = MyCar::find($mycarID);
            $MycarService->status = 2;
            $MycarService->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function yearsList(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $model = $request->model;
        $trim = $request->trim;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($brandID==null){
            $err_array[]='brandID';
        }
        if($model==null){
            $err_array[]='model';
        }
        if($trim==null){
            $err_array[]='trim';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{

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
                        isset($assocRow['Make']) &&
                        $assocRow['Make'] == $brand->name &&
                        isset($assocRow['Model']) &&
                        $assocRow['Model'] == $model &&
                        isset($assocRow['Trim']) &&
                        $assocRow['Trim'] == $trim
                    ) {
                        $formatted = [
                            'year' => $assocRow['Model_Year']
                        ];

                        $filteredRows[] = $formatted;
                    }
                }

                fclose($handle);
            }

            $yearArray = [];
            $uniqueYears = [];

            foreach ($filteredRows as $row) {
                $year = $row['year'];

                if (!in_array($year, $uniqueYears) && $year != "") {
                    $uniqueYears[] = $year;
                    $yearArray[] = ['year' => $year];
                }
            }

            // Sort by 'year' in ascending order
            usort($yearArray, function ($a, $b) {
                return $a['year'] <=> $b['year'];
            });

            return response()->json(['success'=>'true','message'=>'','result'=>$yearArray], 200);
        }
        // $year = range(date('Y'), 2010);
        // $recordArray = array();
        // foreach ($year as $value) {
        //     $data['year'] = $value;
        //     array_push($recordArray, $data);
        // }
        // return response()->json(['success' => 'true', 'message' => "", 'result' => $recordArray], 200);
    }

    // get my car details by MMTY
    public function getMyCarDetailByMMTY(Request $request){

        $userID = $request->userID;
        $brand_id = $request->brandID;
        $model = $request->model;
        $trim = $request->trim;
        $year = $request->year;
        $err_array = [];

        if (!$userID) {
            $err_array[] = 'userID';
        }
        if (!$brand_id) {
            $err_array[] = 'brandID';
        }
        if (!$model) {
            $err_array[] = 'model';
        }
        if (!$trim) {
            $err_array[] = 'trim';
        }
        if (!$year) {
            $err_array[] = 'year';
        }

        if (!empty($err_array)) {
            $missing = implode(",", $err_array);
            return response()->json([
                'success' => 'false',
                'message' => "400 Bad Request, missing " . $missing
            ], 400);
        }

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
                    $assocRow['Model'] == $model &&
                    isset($assocRow['Trim']) &&
                    $assocRow['Trim'] == $trim &&
                    isset($assocRow['Model_Year']) &&
                    $assocRow['Model_Year'] == $year
                ) {
                    $formatted = [
                        'make' => $assocRow['Make'],
                        'model' => $assocRow['Model'],
                        'trim' => $assocRow['Trim'],
                        'body_style' => $assocRow['Body_Style'],
                        'model_year' => $assocRow['Model_Year'],
                        'performance' => [
                            [
                                'label1' => 'Engine Type',
                                'value1' => $assocRow['Engine_Type'] ?? '',
                                'label2' => 'Engine Displacement (cc)',
                                'value2' => $assocRow['Engine_Displacement_(cc)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Transmission Type',
                                'value1' => $assocRow['Transmission_Type'] ?? '',
                                'label2' => 'Cylinders',
                                'value2' => $assocRow['Cylinders'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Top Speed (km/h)',
                                'value1' => $assocRow['Top_Speed_(kmh)'] ?? '',
                                'label2' => 'Number of Gears',
                                'value2' => $assocRow['Number_of_Gears'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Horsepower (hp)',
                                'value1' => $assocRow['Horsepower_(hp)'] ?? '',
                                'label2' => 'Torque (Nm)',
                                'value2' => $assocRow['Torque_(Nm)'] ?? '',
                                'label3' => 'Towing Capacity (kg)',
                                'value3' => $assocRow['Towing_Capacity_(kg)'] ?? ''
                            ],
                            [
                                'label1' => 'Drivetrain',
                                'value1' => $assocRow['Drivetrain'] ?? '',
                                'label2' => '0-100 km/h (seconds)',
                                'value2' => $assocRow['0-100_kmh_(seconds)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fuel Type',
                                'value1' => $assocRow['Fuel_Type'] ?? '',
                                'label2' => 'Charging Time (hours)',
                                'value2' => $assocRow['Charging_Time_(hours)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'CO2 Emissions (g/km)',
                                'value1' => $assocRow['CO2_Emissions_(gkm)'] ?? '',
                                'label2' => 'Emission Standard',
                                'value2' => $assocRow['Emission_Standard'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Electric Range (km)',
                                'value1' => $assocRow['Electric_Range_(km)'] ?? '',
                                'label2' => 'Battery & Group Size',
                                'value2' => $assocRow['Battery_&_Group_Size'] ?? '',
                                'label3' => 'Battery Health Indicator',
                                'value3' => $assocRow['Battery_Health_Indicator'] ?? ''
                            ]
                        ],
                        'size_and_efficiency' => [
                            [
                                'label1' => 'Wheelbase (mm)',
                                'value1' => $assocRow['Wheelbase_(mm)'] ?? '',
                                'label2' => 'Height (mm)',
                                'value2' => $assocRow['Height_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Length (mm)',
                                'value1' => $assocRow['Length_(mm)'] ?? '',
                                'label2' => 'Width (mm)',
                                'value2' => $assocRow['Width_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fuel Tank Capacity (L)',
                                'value1' => $assocRow['Fuel_Tank_Capacity_(L)'] ?? '',
                                'label2' => 'Fuel Economy Avg (km/L)',
                                'value2' => $assocRow['Fuel_Economy_Combined_(kmL)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Gross Vehicle Weight (kg)',
                                'value1' => $assocRow['Gross_Vehicle_Weight_(kg)'] ?? '',
                                'label2' => 'Cargo Volume (L)',
                                'value2' => $assocRow['Cargo_Volume_(L)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Turning Circle (m)',
                                'value1' => $assocRow['Turning_Circle_(m)'] ?? '',
                                'label2' => 'Ground Clearance (mm)',
                                'value2' => $assocRow['Ground_Clearance_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'safety' => [
                            [
                                'label1' => 'Safety Rating',
                                'value1' => $assocRow['Safety_Rating'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Automatic Emergency Braking',
                                'value1' => $assocRow['Automatic_Emergency_Braking'] ?? '',
                                'label2' => 'Number of Airbags',
                                'value2' => $assocRow['Number_of_Airbags'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Blind Spot Monitoring',
                                'value1' => $assocRow['Blind_Spot_Monitoring'] ?? '',
                                'label2' => 'Parking Assist / Rear Camera',
                                'value2' => $assocRow['Parking_Assist__Rear_Camera'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Adaptive Cruise Control',
                                'value1' => $assocRow['Adaptive_Cruise_Control'] ?? '',
                                'label2' => 'Lane Keeping Assist',
                                'value2' => $assocRow['Lane_Keeping_Assist'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Rain-Sensing Wipers',
                                'value1' => $assocRow['Rain-Sensing_Wipers'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'comfort_and_tech' => [
                            [
                                'label1' => 'Apple CarPlay / Android Auto',
                                'value1' => $assocRow['Apple_CarPlay__Android_Auto'] ?? '',
                                'label2' => 'Digital Instrument Cluster / Display Size',
                                'value2' => $assocRow['Digital_Instrument_Cluster__Display_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Dual-Zone / Tri-Zone Climate Control',
                                'value1' => $assocRow['Dual-Zone__Tri-Zone_Climate_Control'] ?? '',
                                'label2' => 'Information system Size',
                                'value2' => $assocRow['Information_system_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Number of USB Ports',
                                'value1' => $assocRow['Number_of_USB_Ports'] ?? '',
                                'label2' => 'Remote Start / Keyless Entry',
                                'value2' => $assocRow['Remote_Start__Keyless_Entry'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Number of Speakers',
                                'value1' => $assocRow['Number_of_Speakers'] ?? '',
                                'label2' => 'Power Tailgate',
                                'value2' => $assocRow['Power_Tailgate'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Sound System Brand & Specs',
                                'value1' => $assocRow['Sound_System_Brand_&_Specs'] ?? '',
                                'label2' => 'Wireless Charging',
                                'value2' => $assocRow['Wireless_Charging'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Seat Material',
                                'value1' => $assocRow['Seat_Material'] ?? '',
                                'label2' => 'Sunroof / Panoramic Roof',
                                'value2' => $assocRow['Sunroof__Panoramic_Roof'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Seating Capacity',
                                'value1' => $assocRow['Seating_Capacity'] ?? '',
                                'label2' => 'Interior Color Combinations',
                                'value2' => $assocRow['Interior_Color_Combinations'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fold-Flat Rear Seats / Split Ratio',
                                'value1' => $assocRow['Fold-Flat_Rear_Seats__Split_Ratio'] ?? '',
                                'label2' => 'Heated/Ventilated Seats',
                                'value2' => $assocRow['HeatedVentilated_Seats'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Ambient Lighting',
                                'value1' => $assocRow['Ambient_Lighting'] ?? '',
                                'label2' => 'Fog Lights',
                                'value2' => $assocRow['Fog_Lights'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'design' => [
                            [
                                'label1' => 'Wheel Size (inches)',
                                'value1' => $assocRow['Wheel_Size_(inches)'] ?? '',
                                'label2' => 'Tire Size',
                                'value2' => $assocRow['Tire_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Wheel Type',
                                'value1' => $assocRow['Wheel_Type'] ?? '',
                                'label2' => 'Taillights Type',
                                'value2' => $assocRow['Taillights_Type'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Headlights Type',
                                'value1' => $assocRow['Headlights_Type'] ?? '',
                                'label2' => 'Side Mirror Functions',
                                'value2' => $assocRow['Side_Mirror_Functions'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Available Packages',
                                'value1' => $assocRow['Available_Packages'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'ownership' => [
                            [
                                'label1' => 'Owner Ratings',
                                'value1' => $assocRow['Owner_Ratings'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Average Resale Value (5 years)',
                                'value1' => $assocRow['Average_Resale_Value_(5_years)'] ?? '',
                                'label2' => 'Parts Availability Index',
                                'value2' => $assocRow['Parts_Availability_Index'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Common Problems/Recalls',
                                'value1' => $assocRow['Common_ProblemsRecalls'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ]
                    ];

                    $filteredRows = $formatted;
                }

            }

            fclose($handle);
        }

        return response()->json([
            'success' => 'true',
            'message' => '',
            'data' => $filteredRows ?? json_decode('{}')
        ]);
    }

    public function deletemycarimage(Request $request)
    {
        $userID = $request->userID;
        $mycarID = $request->mycarID;
        $imageID = $request->imageID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($mycarID == null) {
            $err_array[] = 'mycarID';
        }
        if ($imageID == null) {
            $err_array[] = 'imageID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم حذف صورة السيارة ";
            } else {
                $msg_success = "My car image deleted successfully.";
            }
            $MyCarImage = MyCarImage::find($imageID);
            $MyCarImage->delete();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function car_detail_by_vin(Request $request)
    {
        $userID = $request->userID;
        $vin = $request->vin;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($vin == null) {
            $err_array[] = 'vin';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_failed = "أدخل رقم VIN صالحًا";
            } else {
                $msg_failed = "Enter a valid VIN.";
            }
            $check_vin = MycarspecificationEn::where('vin', $request->vin);
            if ($check_vin->count() == 0) {
                $carspec =  ExternalSystem::getvin_decoder($request->vin);
                if ($carspec['status_code'] == 200) {
                    $record = json_decode($carspec['body']);
                    //brand
                    $make = Brand::where('name', $record->attributes->make);
                    if ($make->count() != 0) {
                        $make_id = $make->first()->id;
                    } else {
                        $make_id = "";
                    }
                    //model
                    $model = CarModel::where('name', $record->attributes->model);
                    if ($model->count() != 0) {
                        $model_id = $model->first()->id;
                    } else {
                        $model_id = "";
                    }
                    //insert data MycarspecificationEn
                    $MycarspecificationEn = new MycarspecificationEn;
                    $MycarspecificationEn->language_id = 1;
                    $MycarspecificationEn->vin = $request->vin;
                    $MycarspecificationEn->year = $record->attributes->year;
                    $MycarspecificationEn->make = $record->attributes->make;
                    $MycarspecificationEn->model = $record->attributes->model;
                    $MycarspecificationEn->make_id = $make_id;
                    $MycarspecificationEn->model_id = $model_id;
                    $MycarspecificationEn->trim = $record->attributes->trim;
                    $MycarspecificationEn->style = $record->attributes->style;
                    $MycarspecificationEn->type = $record->attributes->type;
                    $MycarspecificationEn->size = $record->attributes->size;
                    $MycarspecificationEn->category = $record->attributes->category;
                    $MycarspecificationEn->made_in = $record->attributes->made_in;
                    $MycarspecificationEn->made_in_city = $record->attributes->made_in_city;
                    $MycarspecificationEn->doors = $record->attributes->doors;
                    $MycarspecificationEn->fuel_type = $record->attributes->fuel_type;
                    $MycarspecificationEn->fuel_capacity = $record->attributes->fuel_capacity;
                    $MycarspecificationEn->city_mileage = $record->attributes->city_mileage;
                    $MycarspecificationEn->highway_mileage = $record->attributes->highway_mileage;
                    $MycarspecificationEn->engine = $record->attributes->engine;
                    $MycarspecificationEn->engine_size = $record->attributes->engine_size;
                    $MycarspecificationEn->engine_cylinders = $record->attributes->engine_cylinders;
                    $MycarspecificationEn->transmission = $record->attributes->transmission;
                    $MycarspecificationEn->transmission_short = $record->attributes->transmission_short;
                    $MycarspecificationEn->transmission_type = $record->attributes->transmission_type;
                    $MycarspecificationEn->transmission_speeds = $record->attributes->transmission_speeds;
                    $MycarspecificationEn->drivetrain = $record->attributes->drivetrain;
                    $MycarspecificationEn->anti_brake_system = $record->attributes->anti_brake_system;
                    $MycarspecificationEn->steering_type = $record->attributes->steering_type;
                    $MycarspecificationEn->curb_weight = $record->attributes->curb_weight;
                    $MycarspecificationEn->gross_vehicle_weight_rating = $record->attributes->gross_vehicle_weight_rating;
                    $MycarspecificationEn->overall_height = $record->attributes->overall_height;
                    $MycarspecificationEn->overall_length = $record->attributes->overall_length;
                    $MycarspecificationEn->overall_width = $record->attributes->overall_width;
                    $MycarspecificationEn->save();

                    //insert data MycarspecificationFirstEn
                    $MycarspecificationFirstEn = new MycarspecificationFirstEn;
                    $MycarspecificationFirstEn->language_id = 1;
                    $MycarspecificationFirstEn->vin = $request->vin;
                    $MycarspecificationFirstEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationFirstEn->wheelbase_length = $record->attributes->wheelbase_length;
                    $MycarspecificationFirstEn->standard_seating = $record->attributes->standard_seating;
                    $MycarspecificationFirstEn->invoice_price = $record->attributes->invoice_price;
                    $MycarspecificationFirstEn->delivery_charges = $record->attributes->delivery_charges;
                    $MycarspecificationFirstEn->manufacturer_suggested_retail_price = $record->attributes->manufacturer_suggested_retail_price;
                    $MycarspecificationFirstEn->production_seq_number = $record->attributes->production_seq_number;
                    $MycarspecificationFirstEn->front_brake_type = $record->attributes->front_brake_type;
                    $MycarspecificationFirstEn->rear_brake_type = $record->attributes->rear_brake_type;
                    $MycarspecificationFirstEn->turning_diameter = $record->attributes->turning_diameter;
                    $MycarspecificationFirstEn->front_suspension = $record->attributes->front_suspension;
                    $MycarspecificationFirstEn->rear_suspension = $record->attributes->rear_suspension;
                    $MycarspecificationFirstEn->front_spring_type = $record->attributes->front_spring_type;
                    $MycarspecificationFirstEn->rear_spring_type = $record->attributes->rear_spring_type;
                    $MycarspecificationFirstEn->tires = $record->attributes->tires;
                    $MycarspecificationFirstEn->front_headroom = $record->attributes->front_headroom;
                    $MycarspecificationFirstEn->rear_headroom = $record->attributes->rear_headroom;
                    $MycarspecificationFirstEn->front_legroom = $record->attributes->front_legroom;
                    $MycarspecificationFirstEn->rear_legroom = $record->attributes->rear_legroom;
                    $MycarspecificationFirstEn->front_shoulder_room = $record->attributes->front_shoulder_room;
                    $MycarspecificationFirstEn->rear_shoulder_room = $record->attributes->rear_shoulder_room;
                    $MycarspecificationFirstEn->front_hip_room = $record->attributes->front_hip_room;
                    $MycarspecificationFirstEn->rear_hip_room = $record->attributes->rear_hip_room;
                    $MycarspecificationFirstEn->save();

                    //insert data MycarspecificationSeccondEn
                    $MycarspecificationSeccondEn = new MycarspecificationSeccondEn;
                    $MycarspecificationSeccondEn->language_id = 1;
                    $MycarspecificationSeccondEn->vin = $request->vin;
                    $MycarspecificationSeccondEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationSeccondEn->curb_weight_manual = $record->attributes->curb_weight_manual;
                    $MycarspecificationSeccondEn->ground_clearance = $record->attributes->ground_clearance;
                    $MycarspecificationSeccondEn->track_front = $record->attributes->track_front;
                    $MycarspecificationSeccondEn->track_rear = $record->attributes->track_rear;
                    $MycarspecificationSeccondEn->cargo_length = $record->attributes->cargo_length;
                    $MycarspecificationSeccondEn->width_at_wheelwell = $record->attributes->width_at_wheelwell;
                    $MycarspecificationSeccondEn->width_at_wall = $record->attributes->width_at_wall;
                    $MycarspecificationSeccondEn->depth = $record->attributes->depth;
                    $MycarspecificationSeccondEn->optional_seating = $record->attributes->optional_seating;
                    $MycarspecificationSeccondEn->passenger_volume = $record->attributes->passenger_volume;
                    $MycarspecificationSeccondEn->cargo_volume = $record->attributes->cargo_volume;
                    $MycarspecificationSeccondEn->standard_towing = $record->attributes->standard_towing;
                    $MycarspecificationSeccondEn->maximum_towing = $record->attributes->maximum_towing;
                    $MycarspecificationSeccondEn->standard_payload = $record->attributes->standard_payload;
                    $MycarspecificationSeccondEn->maximum_payload = $record->attributes->maximum_payload;
                    $MycarspecificationSeccondEn->maximum_gvwr = $record->attributes->maximum_gvwr;
                    $MycarspecificationSeccondEn->save();

                    //insert data MycarspecificationThirdEn
                    $MycarspecificationThirdEn = new MycarspecificationThirdEn;
                    $MycarspecificationThirdEn->language_id = 1;
                    $MycarspecificationThirdEn->vin = $request->vin;
                    $MycarspecificationThirdEn->myspec_id = $MycarspecificationEn->id;
                    //$MycarspecificationThirdEn->4wd_awd = $record->equipment->4wd_awd;
                    $MycarspecificationThirdEn->abs_brakes = $record->equipment->abs_brakes;
                    $MycarspecificationThirdEn->adjustable_foot_pedals = $record->equipment->adjustable_foot_pedals;
                    $MycarspecificationThirdEn->air_conditioning = $record->equipment->air_conditioning;
                    $MycarspecificationThirdEn->alloy_wheels = $record->equipment->alloy_wheels;
                    $MycarspecificationThirdEn->am_fm_radio = $record->equipment->am_fm_radio;
                    $MycarspecificationThirdEn->automatic_headlights = $record->equipment->automatic_headlights;
                    $MycarspecificationThirdEn->automatic_load_leveling = $record->equipment->automatic_load_leveling;
                    $MycarspecificationThirdEn->cargo_area_cover = $record->equipment->cargo_area_cover;
                    $MycarspecificationThirdEn->cargo_area_tiedowns = $record->equipment->cargo_area_tiedowns;
                    $MycarspecificationThirdEn->cargo_net = $record->equipment->cargo_net;
                    $MycarspecificationThirdEn->cassette_player = $record->equipment->cassette_player;
                    $MycarspecificationThirdEn->cd_changer = $record->equipment->cd_changer;
                    $MycarspecificationThirdEn->cd_player = $record->equipment->cd_player;
                    $MycarspecificationThirdEn->child_safety_door_locks = $record->equipment->child_safety_door_locks;
                    $MycarspecificationThirdEn->chrome_wheels = $record->equipment->chrome_wheels;
                    $MycarspecificationThirdEn->cruise_control = $record->equipment->cruise_control;
                    $MycarspecificationThirdEn->daytime_running_lights = $record->equipment->daytime_running_lights;
                    $MycarspecificationThirdEn->save();

                    //insert data MycarspecificationFourEn
                    $MycarspecificationFourEn = new MycarspecificationFourEn;
                    $MycarspecificationFourEn->language_id = 1;
                    $MycarspecificationFourEn->vin = $request->vin;
                    $MycarspecificationFourEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationFourEn->deep_tinted_glass = $record->equipment->deep_tinted_glass;
                    $MycarspecificationFourEn->driver_airbag = $record->equipment->driver_airbag;
                    $MycarspecificationFourEn->driver_multi_adjustable_power_seat = $record->equipment->driver_multi_adjustable_power_seat;
                    $MycarspecificationFourEn->dvd_player = $record->equipment->dvd_player;
                    $MycarspecificationFourEn->electrochromic_exterior_rearview_mirror = $record->equipment->electrochromic_exterior_rearview_mirror;
                    $MycarspecificationFourEn->electrochromic_interior_rearview_mirror = $record->equipment->electrochromic_interior_rearview_mirror;
                    $MycarspecificationFourEn->electronic_brake_assistance = $record->equipment->electronic_brake_assistance;
                    $MycarspecificationFourEn->electronic_parking_aid = $record->equipment->electronic_parking_aid;
                    $MycarspecificationFourEn->first_aid_kit = $record->equipment->first_aid_kit;
                    $MycarspecificationFourEn->fog_lights = $record->equipment->fog_lights;
                    $MycarspecificationFourEn->front_air_dam = $record->equipment->front_air_dam;
                    $MycarspecificationFourEn->front_cooled_seat = $record->equipment->front_cooled_seat;
                    $MycarspecificationFourEn->front_heated_seat = $record->equipment->front_heated_seat;
                    $MycarspecificationFourEn->front_power_lumbar_support = $record->equipment->front_power_lumbar_support;
                    $MycarspecificationFourEn->front_power_memory_seat = $record->equipment->front_power_memory_seat;
                    $MycarspecificationFourEn->front_side_airbag = $record->equipment->front_side_airbag;
                    $MycarspecificationFourEn->front_side_airbag_with_head_protection = $record->equipment->front_side_airbag_with_head_protection;
                    $MycarspecificationFourEn->front_split_bench_seat = $record->equipment->front_split_bench_seat;
                    $MycarspecificationFourEn->save();

                    //insert data MycarspecificationFiveEn
                    $MycarspecificationFiveEn = new MycarspecificationFiveEn;
                    $MycarspecificationFiveEn->language_id = 1;
                    $MycarspecificationFiveEn->vin = $request->vin;
                    $MycarspecificationFiveEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationFiveEn->full_size_spare_tire = $record->equipment->full_size_spare_tire;
                    $MycarspecificationFiveEn->genuine_wood_trim = $record->equipment->genuine_wood_trim;
                    $MycarspecificationFiveEn->glass_rear_window_on_convertible = $record->equipment->glass_rear_window_on_convertible;
                    $MycarspecificationFiveEn->heated_exterior_mirror = $record->equipment->heated_exterior_mirror;
                    $MycarspecificationFiveEn->heated_steering_wheel = $record->equipment->heated_steering_wheel;
                    $MycarspecificationFiveEn->high_intensity_discharge_headlights = $record->equipment->high_intensity_discharge_headlights;
                    $MycarspecificationFiveEn->interval_wipers = $record->equipment->interval_wipers;
                    $MycarspecificationFiveEn->keyless_entry = $record->equipment->keyless_entry;
                    $MycarspecificationFiveEn->leather_seat = $record->equipment->leather_seat;
                    $MycarspecificationFiveEn->leather_steering_wheel = $record->equipment->leather_steering_wheel;
                    $MycarspecificationFiveEn->limited_slip_differential = $record->equipment->limited_slip_differential;
                    $MycarspecificationFiveEn->load_bearing_exterior_rack = $record->equipment->load_bearing_exterior_rack;
                    $MycarspecificationFiveEn->locking_differential = $record->equipment->locking_differential;
                    $MycarspecificationFiveEn->locking_pickup_truck_tailgate = $record->equipment->locking_pickup_truck_tailgate;
                    $MycarspecificationFiveEn->manual_sunroof = $record->equipment->manual_sunroof;
                    $MycarspecificationFiveEn->navigation_aid = $record->equipment->navigation_aid;
                    $MycarspecificationFiveEn->passenger_airbag = $record->equipment->passenger_airbag;
                    $MycarspecificationFiveEn->passenger_multi_adjustable_power_seat = $record->equipment->passenger_multi_adjustable_power_seat;
                    $MycarspecificationFiveEn->pickup_truck_bed_liner = $record->equipment->pickup_truck_bed_liner;
                    $MycarspecificationFiveEn->pickup_truck_cargo_box_light = $record->equipment->power_adjustable_exterior_mirror;
                    $MycarspecificationFiveEn->power_door_locks = $record->equipment->power_door_locks;
                    $MycarspecificationFiveEn->power_sliding_side_van_door = $record->equipment->power_sliding_side_van_door;
                    $MycarspecificationFiveEn->save();

                    //insert data MycarspecificationSixEn
                    $MycarspecificationSixEn = new MycarspecificationSixEn;
                    $MycarspecificationSixEn->language_id = 1;
                    $MycarspecificationSixEn->vin = $request->vin;
                    $MycarspecificationSixEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationSixEn->power_sunroof = $record->equipment->power_sunroof;
                    $MycarspecificationSixEn->power_trunk_lid = $record->equipment->power_trunk_lid;
                    $MycarspecificationSixEn->power_windows = $record->equipment->power_windows;
                    $MycarspecificationSixEn->rain_sensing_wipers = $record->equipment->rain_sensing_wipers;
                    $MycarspecificationSixEn->rear_spoiler = $record->equipment->rear_spoiler;
                    $MycarspecificationSixEn->rear_window_defogger = $record->equipment->rear_window_defogger;
                    $MycarspecificationSixEn->rear_wiper = $record->equipment->rear_wiper;
                    $MycarspecificationSixEn->remote_ignition = $record->equipment->remote_ignition;
                    $MycarspecificationSixEn->removable_top = $record->equipment->removable_top;
                    $MycarspecificationSixEn->run_flat_tires = $record->equipment->run_flat_tires;
                    $MycarspecificationSixEn->running_boards = $record->equipment->running_boards;
                    $MycarspecificationSixEn->second_row_folding_seat = $record->equipment->second_row_folding_seat;
                    $MycarspecificationSixEn->second_row_heated_seat = $record->equipment->second_row_heated_seat;
                    $MycarspecificationSixEn->second_row_multi_adjustable_power_seat = $record->equipment->second_row_multi_adjustable_power_seat;
                    $MycarspecificationSixEn->second_row_removable_seat = $record->equipment->second_row_removable_seat;
                    $MycarspecificationSixEn->second_row_side_airbag = $record->equipment->second_row_side_airbag;
                    $MycarspecificationSixEn->second_row_side_airbag_with_head_protection = $record->equipment->second_row_side_airbag_with_head_protection;
                    $MycarspecificationSixEn->second_row_sound_controls = $record->equipment->second_row_sound_controls;
                    $MycarspecificationSixEn->separate_driver_front_passenger_climate_controls = $record->equipment->separate_driver_front_passenger_climate_controls;
                    $MycarspecificationSixEn->save();

                    //insert data MycarspecificationSevenEn
                    $MycarspecificationSevenEn = new MycarspecificationSevenEn;
                    $MycarspecificationSevenEn->language_id = 1;
                    $MycarspecificationSevenEn->vin = $request->vin;
                    $MycarspecificationSevenEn->myspec_id = $MycarspecificationEn->id;
                    $MycarspecificationSevenEn->side_head_curtain_airbag = $record->equipment->side_head_curtain_airbag;
                    $MycarspecificationSevenEn->skid_plate = $record->equipment->skid_plate;
                    $MycarspecificationSevenEn->sliding_rear_pickup_truck_window = $record->equipment->sliding_rear_pickup_truck_window;
                    $MycarspecificationSevenEn->splash_guards = $record->equipment->splash_guards;
                    $MycarspecificationSevenEn->steel_wheels = $record->equipment->steel_wheels;
                    $MycarspecificationSevenEn->steering_wheel_mounted_controls = $record->equipment->steering_wheel_mounted_controls;
                    $MycarspecificationSevenEn->subwoofer = $record->equipment->subwoofer;
                    $MycarspecificationSevenEn->tachometer = $record->equipment->tachometer;
                    $MycarspecificationSevenEn->telematics_system = $record->equipment->telematics_system;
                    $MycarspecificationSevenEn->telescopic_steering_column = $record->equipment->telescopic_steering_column;
                    $MycarspecificationSevenEn->third_row_removable_seat = $record->equipment->third_row_removable_seat;
                    $MycarspecificationSevenEn->tilt_steering = $record->equipment->tilt_steering;
                    $MycarspecificationSevenEn->tilt_steering_column = $record->equipment->tilt_steering_column;
                    $MycarspecificationSevenEn->tire_pressure_monitor = $record->equipment->tire_pressure_monitor;
                    $MycarspecificationSevenEn->tow_hitch_receiver = $record->equipment->tow_hitch_receiver;
                    $MycarspecificationSevenEn->traction_control = $record->equipment->traction_control;
                    $MycarspecificationSevenEn->trip_computer = $record->equipment->trip_computer;
                    $MycarspecificationSevenEn->trunk_anti_trap_device = $record->equipment->trunk_anti_trap_device;
                    $MycarspecificationSevenEn->vehicle_anti_theft = $record->equipment->vehicle_anti_theft;
                    $MycarspecificationSevenEn->vehicle_stability_control_system = $record->equipment->vehicle_stability_control_system;
                    $MycarspecificationSevenEn->voice_activated_telephone = $record->equipment->voice_activated_telephone;
                    $MycarspecificationSevenEn->wind_deflector_for_convertibles = $record->equipment->wind_deflector_for_convertibles;
                    $MycarspecificationSevenEn->save();

                    //insert data MycarspecificationInteriorcolorEn
                    foreach ($record->attributes->interior_trim as $valinter) {
                        $MycarspecificationInteriorcolorEn = new MycarspecificationInteriorcolorEn;
                        $MycarspecificationInteriorcolorEn->vin = $request->vin;
                        $MycarspecificationInteriorcolorEn->myspec_id = $MycarspecificationEn->id;
                        $MycarspecificationInteriorcolorEn->name = $valinter;
                        $MycarspecificationInteriorcolorEn->name_ar = $this->stringtranslation($valinter);
                        $MycarspecificationInteriorcolorEn->save();
                    }
                    //insert data MycarspecificationExteriorcolorEn
                    foreach ($record->attributes->exterior_color as $valex) {
                        $MycarspecificationExteriorcolorEn = new MycarspecificationExteriorcolorEn;
                        $MycarspecificationExteriorcolorEn->vin = $request->vin;
                        $MycarspecificationExteriorcolorEn->myspec_id = $MycarspecificationEn->id;
                        $MycarspecificationExteriorcolorEn->name = $valex;
                        $MycarspecificationExteriorcolorEn->name_ar = $this->stringtranslation($valex);
                        $MycarspecificationExteriorcolorEn->save();
                    }

                    //language ar
                    $MycarspecificationAr = new MycarspecificationEn;
                    $MycarspecificationAr->language_id = 2;
                    $MycarspecificationAr->vin = $request->vin;
                    $MycarspecificationAr->year = $record->attributes->year;
                    $MycarspecificationAr->make = $this->stringtranslation($record->attributes->make);
                    $MycarspecificationAr->model = $this->stringtranslation($record->attributes->model);
                    $MycarspecificationAr->make_id = $make_id;
                    $MycarspecificationAr->model_id = $model_id;
                    $MycarspecificationAr->trim = $this->stringtranslation($record->attributes->trim);
                    $MycarspecificationAr->style = $this->stringtranslation($record->attributes->style);
                    $MycarspecificationAr->type = $this->stringtranslation($record->attributes->type);
                    $MycarspecificationAr->size = $this->stringtranslation($record->attributes->size);
                    $MycarspecificationAr->category = $this->stringtranslation($record->attributes->category);
                    $MycarspecificationAr->made_in = $this->stringtranslation($record->attributes->made_in);
                    $MycarspecificationAr->made_in_city = $this->stringtranslation($record->attributes->made_in_city);
                    $MycarspecificationAr->doors = $this->stringtranslation($record->attributes->doors);
                    $MycarspecificationAr->fuel_type = $this->stringtranslation($record->attributes->fuel_type);
                    $MycarspecificationAr->fuel_capacity = $this->stringtranslation($record->attributes->fuel_capacity);
                    $MycarspecificationAr->city_mileage = $this->stringtranslation($record->attributes->city_mileage);
                    $MycarspecificationAr->highway_mileage = $this->stringtranslation($record->attributes->highway_mileage);
                    $MycarspecificationAr->engine = $this->stringtranslation($record->attributes->engine);
                    $MycarspecificationAr->engine_size = $this->stringtranslation($record->attributes->engine_size);
                    $MycarspecificationAr->engine_cylinders = $this->stringtranslation($record->attributes->engine_cylinders);
                    $MycarspecificationAr->transmission = $this->stringtranslation($record->attributes->transmission);
                    $MycarspecificationAr->transmission_short = $this->stringtranslation($record->attributes->transmission_short);
                    $MycarspecificationAr->transmission_type = $this->stringtranslation($record->attributes->transmission_type);
                    $MycarspecificationAr->transmission_speeds = $this->stringtranslation($record->attributes->transmission_speeds);
                    $MycarspecificationAr->drivetrain = $this->stringtranslation($record->attributes->drivetrain);
                    $MycarspecificationAr->anti_brake_system = $this->stringtranslation($record->attributes->anti_brake_system);
                    $MycarspecificationAr->steering_type = $this->stringtranslation($record->attributes->steering_type);
                    $MycarspecificationAr->curb_weight = $this->stringtranslation($record->attributes->curb_weight);
                    $MycarspecificationAr->gross_vehicle_weight_rating = $this->stringtranslation($record->attributes->gross_vehicle_weight_rating);
                    $MycarspecificationAr->overall_height = $this->stringtranslation($record->attributes->overall_height);
                    $MycarspecificationAr->overall_length = $this->stringtranslation($record->attributes->overall_length);
                    $MycarspecificationAr->overall_width = $this->stringtranslation($record->attributes->overall_width);
                    $MycarspecificationAr->save();

                    //insert data MycarspecificationFirstEn
                    $MycarspecificationFirstAr = new MycarspecificationFirstEn;
                    $MycarspecificationFirstAr->language_id = 2;
                    $MycarspecificationFirstAr->vin = $request->vin;
                    $MycarspecificationFirstAr->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationFirstAr->wheelbase_length = $this->stringtranslation($record->attributes->wheelbase_length);
                    $MycarspecificationFirstAr->standard_seating = $this->stringtranslation($record->attributes->standard_seating);
                    $MycarspecificationFirstAr->invoice_price = $this->stringtranslation($record->attributes->invoice_price);
                    $MycarspecificationFirstAr->delivery_charges = $this->stringtranslation($record->attributes->delivery_charges);
                    $MycarspecificationFirstAr->manufacturer_suggested_retail_price = $this->stringtranslation($record->attributes->manufacturer_suggested_retail_price);
                    $MycarspecificationFirstAr->production_seq_number = $this->stringtranslation($record->attributes->production_seq_number);
                    $MycarspecificationFirstAr->front_brake_type = $this->stringtranslation($record->attributes->front_brake_type);
                    $MycarspecificationFirstAr->rear_brake_type = $this->stringtranslation($record->attributes->rear_brake_type);
                    $MycarspecificationFirstAr->turning_diameter = $this->stringtranslation($record->attributes->turning_diameter);
                    $MycarspecificationFirstAr->front_suspension = $this->stringtranslation($record->attributes->front_suspension);
                    $MycarspecificationFirstAr->rear_suspension = $this->stringtranslation($record->attributes->rear_suspension);
                    $MycarspecificationFirstAr->front_spring_type = $this->stringtranslation($record->attributes->front_spring_type);
                    $MycarspecificationFirstAr->rear_spring_type = $this->stringtranslation($record->attributes->rear_spring_type);
                    $MycarspecificationFirstAr->tires = $this->stringtranslation($record->attributes->tires);
                    $MycarspecificationFirstAr->front_headroom = $this->stringtranslation($record->attributes->front_headroom);
                    $MycarspecificationFirstAr->rear_headroom = $this->stringtranslation($record->attributes->rear_headroom);
                    $MycarspecificationFirstAr->front_legroom = $this->stringtranslation($record->attributes->front_legroom);
                    $MycarspecificationFirstAr->rear_legroom = $this->stringtranslation($record->attributes->rear_legroom);
                    $MycarspecificationFirstAr->front_shoulder_room = $this->stringtranslation($record->attributes->front_shoulder_room);
                    $MycarspecificationFirstAr->rear_shoulder_room = $this->stringtranslation($record->attributes->rear_shoulder_room);
                    $MycarspecificationFirstAr->front_hip_room = $this->stringtranslation($record->attributes->front_hip_room);
                    $MycarspecificationFirstAr->rear_hip_room = $this->stringtranslation($record->attributes->rear_hip_room);
                    $MycarspecificationFirstAr->save();

                    //insert data MycarspecificationSeccondEn
                    $MycarspecificationSeccondAR = new MycarspecificationSeccondEn;
                    $MycarspecificationSeccondAR->language_id = 2;
                    $MycarspecificationSeccondAR->vin = $request->vin;
                    $MycarspecificationSeccondAR->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationSeccondAR->curb_weight_manual = $this->stringtranslation($record->attributes->curb_weight_manual);
                    $MycarspecificationSeccondAR->ground_clearance = $this->stringtranslation($record->attributes->ground_clearance);
                    $MycarspecificationSeccondAR->track_front = $this->stringtranslation($record->attributes->track_front);
                    $MycarspecificationSeccondAR->track_rear = $this->stringtranslation($record->attributes->track_rear);
                    $MycarspecificationSeccondAR->cargo_length = $this->stringtranslation($record->attributes->cargo_length);
                    $MycarspecificationSeccondAR->width_at_wheelwell = $this->stringtranslation($record->attributes->width_at_wheelwell);
                    $MycarspecificationSeccondAR->width_at_wall = $this->stringtranslation($record->attributes->width_at_wall);
                    $MycarspecificationSeccondAR->depth = $this->stringtranslation($record->attributes->depth);
                    $MycarspecificationSeccondAR->optional_seating = $this->stringtranslation($record->attributes->optional_seating);
                    $MycarspecificationSeccondAR->passenger_volume = $this->stringtranslation($record->attributes->passenger_volume);
                    $MycarspecificationSeccondAR->cargo_volume = $this->stringtranslation($record->attributes->cargo_volume);
                    $MycarspecificationSeccondAR->standard_towing = $this->stringtranslation($record->attributes->standard_towing);
                    $MycarspecificationSeccondAR->maximum_towing = $this->stringtranslation($record->attributes->maximum_towing);
                    $MycarspecificationSeccondAR->standard_payload = $this->stringtranslation($record->attributes->standard_payload);
                    $MycarspecificationSeccondAR->maximum_payload = $this->stringtranslation($record->attributes->maximum_payload);
                    $MycarspecificationSeccondAR->maximum_gvwr = $this->stringtranslation($record->attributes->maximum_gvwr);
                    $MycarspecificationSeccondAR->save();

                    //insert data MycarspecificationThirdEn
                    $MycarspecificationThirdAr = new MycarspecificationThirdEn;
                    $MycarspecificationThirdAr->language_id = 2;
                    $MycarspecificationThirdAr->vin = $request->vin;
                    $MycarspecificationThirdAr->myspec_id = $MycarspecificationAr->id;
                    //$MycarspecificationThirdAr->4wd_awd = $this->stringtranslation($record->equipment->4wd_awd);
                    $MycarspecificationThirdAr->abs_brakes = $this->stringtranslation($record->equipment->abs_brakes);
                    $MycarspecificationThirdAr->adjustable_foot_pedals = $this->stringtranslation($record->equipment->adjustable_foot_pedals);
                    $MycarspecificationThirdAr->air_conditioning = $this->stringtranslation($record->equipment->air_conditioning);
                    $MycarspecificationThirdAr->alloy_wheels = $this->stringtranslation($record->equipment->alloy_wheels);
                    $MycarspecificationThirdAr->am_fm_radio = $this->stringtranslation($record->equipment->am_fm_radio);
                    $MycarspecificationThirdAr->automatic_headlights = $this->stringtranslation($record->equipment->automatic_headlights);
                    $MycarspecificationThirdAr->automatic_load_leveling = $this->stringtranslation($record->equipment->automatic_load_leveling);
                    $MycarspecificationThirdAr->cargo_area_cover = $this->stringtranslation($record->equipment->cargo_area_cover);
                    $MycarspecificationThirdAr->cargo_area_tiedowns = $this->stringtranslation($record->equipment->cargo_area_tiedowns);
                    $MycarspecificationThirdAr->cargo_net = $this->stringtranslation($record->equipment->cargo_net);
                    $MycarspecificationThirdAr->cassette_player = $this->stringtranslation($record->equipment->cassette_player);
                    $MycarspecificationThirdAr->cd_changer = $this->stringtranslation($record->equipment->cd_changer);
                    $MycarspecificationThirdAr->cd_player = $this->stringtranslation($record->equipment->cd_player);
                    $MycarspecificationThirdAr->child_safety_door_locks = $this->stringtranslation($record->equipment->child_safety_door_locks);
                    $MycarspecificationThirdAr->chrome_wheels = $this->stringtranslation($record->equipment->chrome_wheels);
                    $MycarspecificationThirdAr->cruise_control = $this->stringtranslation($record->equipment->cruise_control);
                    $MycarspecificationThirdAr->daytime_running_lights = $this->stringtranslation($record->equipment->daytime_running_lights);
                    $MycarspecificationThirdAr->save();

                    //insert data MycarspecificationFourEn
                    $MycarspecificationFourAr = new MycarspecificationFourEn;
                    $MycarspecificationFourAr->language_id = 2;
                    $MycarspecificationFourAr->vin = $request->vin;
                    $MycarspecificationFourAr->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationFourAr->deep_tinted_glass = $this->stringtranslation($record->equipment->deep_tinted_glass);
                    $MycarspecificationFourAr->driver_airbag = $this->stringtranslation($record->equipment->driver_airbag);
                    $MycarspecificationFourAr->driver_multi_adjustable_power_seat = $this->stringtranslation($record->equipment->driver_multi_adjustable_power_seat);
                    $MycarspecificationFourAr->dvd_player = $this->stringtranslation($record->equipment->dvd_player);
                    $MycarspecificationFourAr->electrochromic_exterior_rearview_mirror = $this->stringtranslation($record->equipment->electrochromic_exterior_rearview_mirror);
                    $MycarspecificationFourAr->electrochromic_interior_rearview_mirror = $this->stringtranslation($record->equipment->electrochromic_interior_rearview_mirror);
                    $MycarspecificationFourAr->electronic_brake_assistance = $this->stringtranslation($record->equipment->electronic_brake_assistance);
                    $MycarspecificationFourAr->electronic_parking_aid = $this->stringtranslation($record->equipment->electronic_parking_aid);
                    $MycarspecificationFourAr->first_aid_kit = $this->stringtranslation($record->equipment->first_aid_kit);
                    $MycarspecificationFourAr->fog_lights = $this->stringtranslation($record->equipment->fog_lights);
                    $MycarspecificationFourAr->front_air_dam = $this->stringtranslation($record->equipment->front_air_dam);
                    $MycarspecificationFourAr->front_cooled_seat = $this->stringtranslation($record->equipment->front_cooled_seat);
                    $MycarspecificationFourAr->front_heated_seat = $this->stringtranslation($record->equipment->front_heated_seat);
                    $MycarspecificationFourAr->front_power_lumbar_support = $this->stringtranslation($record->equipment->front_power_lumbar_support);
                    $MycarspecificationFourAr->front_power_memory_seat = $this->stringtranslation($record->equipment->front_power_memory_seat);
                    $MycarspecificationFourAr->front_side_airbag = $this->stringtranslation($record->equipment->front_side_airbag);
                    $MycarspecificationFourAr->front_side_airbag_with_head_protection = $this->stringtranslation($record->equipment->front_side_airbag_with_head_protection);
                    $MycarspecificationFourAr->front_split_bench_seat = $this->stringtranslation($record->equipment->front_split_bench_seat);
                    $MycarspecificationFourAr->save();

                    //insert data MycarspecificationFiveEn
                    $MycarspecificationFiveAr = new MycarspecificationFiveEn;
                    $MycarspecificationFiveAr->language_id = 2;
                    $MycarspecificationFiveAr->vin = $request->vin;
                    $MycarspecificationFiveAr->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationFiveAr->full_size_spare_tire = $this->stringtranslation($record->equipment->full_size_spare_tire);
                    $MycarspecificationFiveAr->genuine_wood_trim = $this->stringtranslation($record->equipment->genuine_wood_trim);
                    $MycarspecificationFiveAr->glass_rear_window_on_convertible = $this->stringtranslation($record->equipment->glass_rear_window_on_convertible);
                    $MycarspecificationFiveAr->heated_exterior_mirror = $this->stringtranslation($record->equipment->heated_exterior_mirror);
                    $MycarspecificationFiveAr->heated_steering_wheel = $this->stringtranslation($record->equipment->heated_steering_wheel);
                    $MycarspecificationFiveAr->high_intensity_discharge_headlights = $this->stringtranslation($record->equipment->high_intensity_discharge_headlights);
                    $MycarspecificationFiveAr->interval_wipers = $this->stringtranslation($record->equipment->interval_wipers);
                    $MycarspecificationFiveAr->keyless_entry = $this->stringtranslation($record->equipment->keyless_entry);
                    $MycarspecificationFiveAr->leather_seat = $this->stringtranslation($record->equipment->leather_seat);
                    $MycarspecificationFiveAr->leather_steering_wheel = $this->stringtranslation($record->equipment->leather_steering_wheel);
                    $MycarspecificationFiveAr->limited_slip_differential = $this->stringtranslation($record->equipment->limited_slip_differential);
                    $MycarspecificationFiveAr->load_bearing_exterior_rack = $this->stringtranslation($record->equipment->load_bearing_exterior_rack);
                    $MycarspecificationFiveAr->locking_differential = $this->stringtranslation($record->equipment->locking_differential);
                    $MycarspecificationFiveAr->locking_pickup_truck_tailgate = $this->stringtranslation($record->equipment->locking_pickup_truck_tailgate);
                    $MycarspecificationFiveAr->manual_sunroof = $this->stringtranslation($record->equipment->manual_sunroof);
                    $MycarspecificationFiveAr->navigation_aid = $this->stringtranslation($record->equipment->navigation_aid);
                    $MycarspecificationFiveAr->passenger_airbag = $this->stringtranslation($record->equipment->passenger_airbag);
                    $MycarspecificationFiveAr->passenger_multi_adjustable_power_seat = $this->stringtranslation($record->equipment->passenger_multi_adjustable_power_seat);
                    $MycarspecificationFiveAr->pickup_truck_bed_liner = $this->stringtranslation($record->equipment->pickup_truck_bed_liner);
                    $MycarspecificationFiveAr->pickup_truck_cargo_box_light = $this->stringtranslation($record->equipment->power_adjustable_exterior_mirror);
                    $MycarspecificationFiveAr->power_door_locks = $this->stringtranslation($record->equipment->power_door_locks);
                    $MycarspecificationFiveAr->power_sliding_side_van_door = $this->stringtranslation($record->equipment->power_sliding_side_van_door);
                    $MycarspecificationFiveAr->save();

                    //insert data MycarspecificationSixEn
                    $MycarspecificationSixAr = new MycarspecificationSixEn;
                    $MycarspecificationSixAr->language_id = 2;
                    $MycarspecificationSixAr->vin = $request->vin;
                    $MycarspecificationSixAr->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationSixAr->power_sunroof = $this->stringtranslation($record->equipment->power_sunroof);
                    $MycarspecificationSixAr->power_trunk_lid = $this->stringtranslation($record->equipment->power_trunk_lid);
                    $MycarspecificationSixAr->power_windows = $this->stringtranslation($record->equipment->power_windows);
                    $MycarspecificationSixAr->rain_sensing_wipers = $this->stringtranslation($record->equipment->rain_sensing_wipers);
                    $MycarspecificationSixAr->rear_spoiler = $this->stringtranslation($record->equipment->rear_spoiler);
                    $MycarspecificationSixAr->rear_window_defogger = $this->stringtranslation($record->equipment->rear_window_defogger);
                    $MycarspecificationSixAr->rear_wiper = $this->stringtranslation($record->equipment->rear_wiper);
                    $MycarspecificationSixAr->remote_ignition = $this->stringtranslation($record->equipment->remote_ignition);
                    $MycarspecificationSixAr->removable_top = $this->stringtranslation($record->equipment->removable_top);
                    $MycarspecificationSixAr->run_flat_tires = $this->stringtranslation($record->equipment->run_flat_tires);
                    $MycarspecificationSixAr->running_boards = $this->stringtranslation($record->equipment->running_boards);
                    $MycarspecificationSixAr->second_row_folding_seat = $this->stringtranslation($record->equipment->second_row_folding_seat);
                    $MycarspecificationSixAr->second_row_heated_seat = $this->stringtranslation($record->equipment->second_row_heated_seat);
                    $MycarspecificationSixAr->second_row_multi_adjustable_power_seat = $this->stringtranslation($record->equipment->second_row_multi_adjustable_power_seat);
                    $MycarspecificationSixAr->second_row_removable_seat = $this->stringtranslation($record->equipment->second_row_removable_seat);
                    $MycarspecificationSixAr->second_row_side_airbag = $this->stringtranslation($record->equipment->second_row_side_airbag);
                    $MycarspecificationSixAr->second_row_side_airbag_with_head_protection = $this->stringtranslation($record->equipment->second_row_side_airbag_with_head_protection);
                    $MycarspecificationSixAr->second_row_sound_controls = $this->stringtranslation($record->equipment->second_row_sound_controls);
                    $MycarspecificationSixAr->separate_driver_front_passenger_climate_controls = $this->stringtranslation($record->equipment->separate_driver_front_passenger_climate_controls);
                    $MycarspecificationSixAr->save();

                    //insert data MycarspecificationSevenEn
                    $MycarspecificationSevenAr = new MycarspecificationSevenEn;
                    $MycarspecificationSevenAr->language_id = 2;
                    $MycarspecificationSevenAr->vin = $request->vin;
                    $MycarspecificationSevenAr->myspec_id = $MycarspecificationAr->id;
                    $MycarspecificationSevenAr->side_head_curtain_airbag = $this->stringtranslation($record->equipment->side_head_curtain_airbag);
                    $MycarspecificationSevenAr->skid_plate = $this->stringtranslation($record->equipment->skid_plate);
                    $MycarspecificationSevenAr->sliding_rear_pickup_truck_window = $this->stringtranslation($record->equipment->sliding_rear_pickup_truck_window);
                    $MycarspecificationSevenAr->splash_guards = $this->stringtranslation($record->equipment->splash_guards);
                    $MycarspecificationSevenAr->steel_wheels = $this->stringtranslation($record->equipment->steel_wheels);
                    $MycarspecificationSevenAr->steering_wheel_mounted_controls = $this->stringtranslation($record->equipment->steering_wheel_mounted_controls);
                    $MycarspecificationSevenAr->subwoofer = $this->stringtranslation($record->equipment->subwoofer);
                    $MycarspecificationSevenAr->tachometer = $this->stringtranslation($record->equipment->tachometer);
                    $MycarspecificationSevenAr->telematics_system = $this->stringtranslation($record->equipment->telematics_system);
                    $MycarspecificationSevenAr->telescopic_steering_column = $this->stringtranslation($record->equipment->telescopic_steering_column);
                    $MycarspecificationSevenAr->third_row_removable_seat = $this->stringtranslation($record->equipment->third_row_removable_seat);
                    $MycarspecificationSevenAr->tilt_steering = $this->stringtranslation($record->equipment->tilt_steering);
                    $MycarspecificationSevenAr->tilt_steering_column = $this->stringtranslation($record->equipment->tilt_steering_column);
                    $MycarspecificationSevenAr->tire_pressure_monitor = $this->stringtranslation($record->equipment->tire_pressure_monitor);
                    $MycarspecificationSevenAr->tow_hitch_receiver = $this->stringtranslation($record->equipment->tow_hitch_receiver);
                    $MycarspecificationSevenAr->traction_control = $this->stringtranslation($record->equipment->traction_control);
                    $MycarspecificationSevenAr->trip_computer = $this->stringtranslation($record->equipment->trip_computer);
                    $MycarspecificationSevenAr->trunk_anti_trap_device = $this->stringtranslation($record->equipment->trunk_anti_trap_device);
                    $MycarspecificationSevenAr->vehicle_anti_theft = $this->stringtranslation($record->equipment->vehicle_anti_theft);
                    $MycarspecificationSevenAr->vehicle_stability_control_system = $this->stringtranslation($record->equipment->vehicle_stability_control_system);
                    $MycarspecificationSevenAr->voice_activated_telephone = $this->stringtranslation($record->equipment->voice_activated_telephone);
                    $MycarspecificationSevenAr->wind_deflector_for_convertibles = $this->stringtranslation($record->equipment->wind_deflector_for_convertibles);
                    $MycarspecificationSevenAr->save();

                    //year
                    //make
                    //model
                    //submodel
                    //engine
                    //milage
                    //fuletype
                    //color
                    $mycarsp1 = MycarspecificationEn::where('vin', $request->vin)->where('language_id', $language)->first();
                    $mycarsp2 = MycarspecificationFirstEn::where('vin', $request->vin)->where('language_id', $language)->first();
                    $data['year'] = $mycarsp1->year;
                    $data['make'] = $mycarsp1->make;
                    $data['make_id'] = $mycarsp1->make_id;
                    $data['model'] = $mycarsp1->model;
                    $data['model_id'] = $mycarsp1->model_id;
                    $data['style'] = $mycarsp1->style;
                    $data['fuel_type'] = $mycarsp1->fuel_type;
                    $data['engine'] = $mycarsp1->engine;
                    $data['highway_mileage'] = $mycarsp1->highway_mileage;
                    if ($language == 1) {
                        $data['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id', 'name')->where('vin', $request->vin)->get();
                    } else {
                        $data['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id', 'name_ar as name')->where('vin', $request->vin)->get();
                    }



                    // $arr['mycarsp1'] = MycarspecificationEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp2'] = MycarspecificationFirstEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp3'] = MycarspecificationSeccondEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp4'] = MycarspecificationThirdEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp5'] = MycarspecificationFourEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp6'] = MycarspecificationFiveEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp7'] = MycarspecificationSixEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // $arr['mycarsp8'] = MycarspecificationSevenEn::where('vin',$request->vin)->where('language_id',$language)->first();
                    // if($language==1){
                    //     $arr['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id','name')->where('vin',$request->vin)->get();
                    //     $arr['Interiorcolor'] = MycarspecificationInteriorcolorEn::select('id','name')->where('vin',$request->vin)->get();
                    // }else{
                    //     $arr['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id','name_ar')->where('vin',$request->vin)->get();
                    //     $arr['Interiorcolor'] = MycarspecificationInteriorcolorEn::select('id','name_ar')->where('vin',$request->vin)->get();
                    // }
                    return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
                } else {
                    return response()->json(['success' => 'false', 'message' => $msg_failed], 200);
                }
            } else {
                // $arr['mycarsp1'] = MycarspecificationEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp2'] = MycarspecificationFirstEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp3'] = MycarspecificationSeccondEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp4'] = MycarspecificationThirdEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp5'] = MycarspecificationFourEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp6'] = MycarspecificationFiveEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp7'] = MycarspecificationSixEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // $arr['mycarsp8'] = MycarspecificationSevenEn::where('vin',$request->vin)->where('language_id',$language)->first();
                // if($language==1){
                //     $arr['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id','name')->where('vin',$request->vin)->get();
                //     $arr['Interiorcolor'] = MycarspecificationInteriorcolorEn::select('id','name')->where('vin',$request->vin)->get();
                // }else{
                //     $arr['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id','name_ar')->where('vin',$request->vin)->get();
                //     $arr['Interiorcolor'] = MycarspecificationInteriorcolorEn::select('id','name_ar')->where('vin',$request->vin)->get();
                // }
                $mycarsp1 = MycarspecificationEn::where('vin', $request->vin)->where('language_id', $language)->first();
                $mycarsp2 = MycarspecificationFirstEn::where('vin', $request->vin)->where('language_id', $language)->first();
                $data['year'] = $mycarsp1->year;
                $data['make'] = $mycarsp1->make;
                $data['make_id'] = $mycarsp1->make_id;
                $data['model'] = $mycarsp1->model;
                $data['model_id'] = $mycarsp1->model_id;
                $data['style'] = $mycarsp1->style;
                $data['fuel_type'] = $mycarsp1->fuel_type;
                $data['engine'] = $mycarsp1->engine;
                $data['highway_mileage'] = $mycarsp1->highway_mileage;
                if ($language == 1) {
                    $data['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id', 'name')->where('vin', $request->vin)->get();
                } else {
                    $data['Exteriorcolor'] = MycarspecificationExteriorcolorEn::select('id', 'name_ar')->where('vin', $request->vin)->get();
                }
                return response()->json(['success' => 'true', 'message' => "", 'result' => $data], 200);
            }
        }
    }

    public function getcarimage(Request $request)
    {
        $userID = $request->userID;
        $make = $request->make;
        $model = $request->model;
        $year = $request->year;
        $color = $request->color;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $carimage = $this->carimage($make, $model, $year, $color);
            if ($carimage != "") {
                $record_array = array();
                foreach ($carimage as $img) {
                    $data['image'] = $img->link;
                    array_push($record_array, $data);
                }

                return response()->json(['success' => 'true', 'message' => "", 'record' => $record_array], 200);
            } else {
                return response()->json(['success' => 'false', 'message' => "No Data Found"], 200);
            }
        }
    }

    public function stringtranslation($string)
    {
        if ($string != '') {
            $translate = ExternalSystem::translate($string);
            //print_r($translate);
            if ($translate['status_code'] == 200) {
                $record = json_decode($translate['body']);
                return $record->translations[0]->text;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public function carimage($make, $model, $year, $color)
    {
        $image = ExternalSystem::car_image($make, $model, $year, $color);
        if ($image['status_code'] == 200) {
            $record = json_decode($image['body']);
            return $record->images;
        } else {
            return "";
        }
    }

    public function car_draft(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $myCarID = $request->myCarID;
        $myserviceID = $request->myserviceID;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //Date
            if ($myserviceID != "") {
                $carservices = MycarService::where('service_type', 3)->where('id', $myserviceID);
                if ($carservices->count() != 0) {
                    $datadate = $carservices->first()->serviceDate;
                    $odometer = $carservices->first()->odometer;
                    $notes = $carservices->first()->notes;
                } else {
                    $datadate = "";
                    $odometer = "";
                    $notes = "";
                }
            } else {
                $datadate = "";
                $odometer = "";
                $notes = "";
            }
            $recordArray = array();
            if ($myserviceID == "" || $myserviceID == "0") {
                //services
                $checked_service = "";
                $carservices = MycarService::where('service_type', 3)->where('userID', $userID)->where('mycarID', $myCarID)->get();
                foreach ($carservices as $cvalue) {
                    $checked_service .=  $cvalue->service . ",";
                }
                // if($checked_service!=""){
                //     $chvalue = trim($checked_service,",");
                //     $serv = explode(",", $chvalue);
                //     $uniqueArray = collect($serv)->unique()->values()->all();
                // }

                //services
                $carcheck = CarCheck::where('status', 1)->get();
                foreach ($carcheck as $value) {
                    $data['id'] = $value->id;
                    if ($language == 2) {
                        $data['name'] = $value->name_ar;
                        $data['content'] = $value->content_ar;
                    } else {
                        $data['name'] = $value->name;
                        $data['content'] = $value->content;
                    }
                    $carchecklistArray = array();
                    $checklist = CarCheckList::where('carcheckID', $value->id)->where('status', 1)->get();
                    foreach ($checklist as $checkvalue) {
                        $arr['id'] = $checkvalue->id;
                        //check value checked
                        // if ($checked_service != "") {
                        //     $chvalue = trim($checked_service, ",");
                        //     $serv = explode(",", $chvalue);
                        //     $uniqueArray = collect($serv)->unique()->values()->all();
                        //     if (in_array($checkvalue->id, $uniqueArray)) {
                        //         $arr['checked'] = 1;
                        //     } else {
                        //         $arr['checked'] = 0;
                        //     }
                        // } else {
                            $arr['checked'] = 0;
                        // }

                        if ($language == 2) {
                            $arr['name'] = $checkvalue->name_ar;
                        } else {
                            $arr['name'] = $checkvalue->name;
                        }
                        array_push($carchecklistArray, $arr);
                    }
                    $data['checklist'] = $carchecklistArray;

                    array_push($recordArray, $data);
                }
                return response()->json(['success' => 'true', 'message' => "", 'record' => $recordArray, 'date' => $datadate], 200);
            } else {
                $carservicess = MycarService::where('service_type', 3)->where('id', $myserviceID);
                if ($carservicess->count() != 0) {
                    $carcheckID = CarCheckList::whereIN('id', [$carservicess->first()->service])->first()->carcheckID;
                    $carcheck = CarCheck::where('id', $carcheckID)->get();
                    foreach ($carcheck as $value) {
                        $data['id'] = $value->id;
                        if ($language == 2) {
                            $data['name'] = $value->name_ar;
                            $data['content'] = $value->content_ar;
                        } else {
                            $data['name'] = $value->name;
                            $data['content'] = $value->content;
                        }
                        $carchecklistArray = array();
                        $checklist = CarCheckList::where('carcheckID', $value->id)->where('status', 1)->get();
                        foreach ($checklist as $checkvalue) {
                            $arr['id'] = $checkvalue->id;
                            //check value checked
                            if ($myserviceID != "") {
                                $carservices = MycarService::where('service_type', 3)->where('id', $myserviceID);
                                if ($carservices->count() != 0) {
                                    $serv = explode(",", $carservices->first()->service);
                                    if (in_array($checkvalue->id, $serv)) {
                                        $arr['checked'] = 1;
                                    } else {
                                        $arr['checked'] = 0;
                                    }
                                } else {
                                    $arr['checked'] = 0;
                                }
                            } else {
                                $arr['checked'] = 0;
                            }

                            if ($language == 2) {
                                $arr['name'] = $checkvalue->name_ar;
                            } else {
                                $arr['name'] = $checkvalue->name;
                            }
                            array_push($carchecklistArray, $arr);
                        }
                        $data['checklist'] = $carchecklistArray;
                        

                        array_push($recordArray, $data);
                    }
                    //images
                    //$checklist_images = array();
                    $images = MycarcheckImage::where('service_id',$myserviceID)->get();
                    
                    return response()->json(['success' => 'true', 'message' => "", 'record' => $recordArray, 'date' => $datadate, 'odometer' => $odometer, 'notes' => $notes, 'checklist_images'=>$images], 200);
                }
            }
            ////////////////
            // $carcheck = CarCheck::where('status', 1)->get();
            // foreach ($carcheck as $value) {
            //     $data['id'] = $value->id;
            //     if ($language == 2) {
            //         $data['name'] = $value->name_ar;
            //         $data['content'] = $value->content_ar;
            //     } else {
            //         $data['name'] = $value->name;
            //         $data['content'] = $value->content;
            //     }
            //     $carchecklistArray = array();
            //     $checklist = CarCheckList::where('carcheckID', $value->id)->where('status', 1)->get();
            //     foreach ($checklist as $checkvalue) {
            //         $arr['id'] = $checkvalue->id;
            //         //check value checked
            //         if ($myserviceID != "") {
            //             $carservices = MycarService::where('service_type', 3)->where('id', $myserviceID);
            //             if ($carservices->count() != 0) {
            //                 $serv = explode(",", $carservices->first()->service);
            //                 if (in_array($checkvalue->id, $serv)) {
            //                     $arr['checked'] = 1;
            //                 } else {
            //                     $arr['checked'] = 0;
            //                 }
            //             } else {
            //                 $arr['checked'] = 0;
            //             }
            //         } else {
            //             $arr['checked'] = 0;
            //         }

            //         if ($language == 2) {
            //             $arr['name'] = $checkvalue->name_ar;
            //         } else {
            //             $arr['name'] = $checkvalue->name;
            //         }
            //         array_push($carchecklistArray, $arr);
            //     }
            //     $data['checklist'] = $carchecklistArray;

            //     array_push($recordArray, $data);
            // }
            // return response()->json(['success' => 'true', 'message' => "", 'record' => $recordArray, 'date' => $datadate], 200);
        }
    }

    public function addcarcheck(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $serviceId = $request->serviceId;
        $serviceDate = $request->serviceDate;
        $carcheckid = $request->carcheckid;
        $current_odo = $request->odometer;
        $notes = $request->notes;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if ($serviceDate == null) {
            $err_array[] = 'serviceDate';
        }
        if ($carcheckid == null) {
            $err_array[] = 'carcheckid';
        }
        if ($current_odo == null) {
            $err_array[] = 'odometer';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {

            // Check previous odometer
            $maxOdo = MycarService::where('mycarID', $myCarID)
                ->whereNotNull('odometer')
                ->where('status', 1)
                ->get()
                ->sortByDesc(fn($item) => (int) preg_replace('/[^\d]/', '', $item->odometer))
                ->first();

            if ($maxOdo && $current_odo < $maxOdo->odometer) {
                DB::rollBack();
                return response()->json([
                    'success' => 'false',
                    'message' => 'Your ODO should be more than the previous ODO ' . $maxOdo->odometer,
                ], 200);
            }

            if ($language == 2) {
                $msg_success = "تم الحفظ ";
            } else {
                $msg_success = "Successfully saved.";
            }
            if($serviceId != ""){
                $MycarService = MycarService::find($serviceId);
            }else{
                $MycarService = new MycarService;
            }
            $MycarService->userID = $userID;
            $MycarService->mycarID = $request->myCarID;
            $MycarService->serviceDate = $request->serviceDate;
            $MycarService->service = $request->carcheckid;
            $MycarService->odometer = $request->odometer;
            $MycarService->notes = $request->notes;
            $MycarService->service_type = 3;
            $MycarService->status = 1;
            $MycarService->save();
            //images
            if(!empty($request->images)){
                $i=1;
                foreach($request->images as $imagevalue){
                    $imageNames = $i.time().'.'.$imagevalue->extension();
                    $imagevalue->move(public_path('images/mycar/'), $imageNames);
                    $mycarcheckimage = new MycarcheckImage;
                    $mycarcheckimage->service_id = $MycarService->id;
                    $mycarcheckimage->images = $imageNames;
                    $mycarcheckimage->save();
                    $i++;
                }
            }
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    public function cartransfer(Request $request)
    {
        $userID = $request->userID;
        $myCarID = $request->myCarID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($myCarID == null) {
            $err_array[] = 'myCarID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم النقل بنجاح ";
            } else {
                $msg_success = "Transferred successfully.";
            }
            $mycar = MyCar::where('id', $myCarID);
            if ($mycar->count() != 0) {
                $transferedid = $mycar->first()->userID;
                $mycar = MyCar::find($myCarID);
                $mycar->userID = $userID;
                $mycar->transfer_by = $transferedid;
                $mycar->save();
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            } else {
                return response()->json(['success' => 'false', 'message' => ""], 200);
            }
        }
    }
    
    //sourabh 14-8-2025
    public function deletemycarcheckimage(Request $request){
        $userID = $request->userID;
        $imageId = $request->imageId;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($imageId == null) {
            $err_array[] = 'imageId';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم النقل بنجاح ";
            } else {
                $msg_success = "Image deleted successfully";
            }
            $image = MycarcheckImage::where('id', $imageId)->first();
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404);
            }
            $image->delete();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            
            
        }
    }
    
}
