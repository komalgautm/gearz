<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Agent;
use App\Models\AgentBrand;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\VehicleExteriorColors;
use App\Models\VehicleInteriorColors;
use App\Models\VehicleTrimBody;
use App\Models\VehicleTrimEngine;
use App\Models\VehicleTrimMileage;
use App\Models\City;
use App\Models\Country;
use App\Models\CarImage;
use App\Models\CarFavorite;
use App\Models\BrandReview;
use App\Models\BrandFavorite;
use App\Models\AgentFavorite;
use App\Models\AllBannerImage;
use App\Modules\ExternalSystem;
use App\Models\ApiTrimList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    //All brand list
    public function allBrand(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $categoryID = $request->categoryID;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($categoryID!=""){
                $brandpopcount = Brand::where('status',1)->where('popular',1)->where('categoryID',$categoryID)->count();
                if($brandpopcount!=0){
                    $popular = Brand::where('status',1)->where('categoryID',$categoryID)->orderby('popular','DESC')->orderby('position','ASC')->get();
                }else{
                    $popular = Brand::where('status',1)->where('categoryID',$categoryID)->orderby('name','ASC')->get();
                }
            }else{
                $brandpopcount = Brand::where('status',1)->where('popular',1)->where('categoryID',1)->count();
                if($brandpopcount!=0){
                    $popular = Brand::where('status',1)->where('categoryID',1)->orderby('popular','DESC')->orderby('position','ASC')->get();
                }else{
                    $popular = Brand::where('status',1)->where('categoryID',1)->orderby('name','ASC')->get();
                }
            }
            //$popular = Brand::where('status',1)->orderBy('name','ASC')->get();
            $popularBrandArray = array();
            foreach($popular as $popValue){
                $datap['id'] = $popValue->id;
                $datap['make_id'] = $popValue->make_id;
                $datap['logo'] = $popValue->logo;
                $datap['countryid'] = $popValue->countryid;
                if($popValue->countryid!=""){
                    $country = Country::where('id',$popValue->countryid);
                    if($language==2){
                        $datap['country_name'] = $country->first()->name_ar;
                    }else{
                        $datap['country_name'] = $country->first()->name;
                    }
                    $datap['country_flag'] ="https://flagcdn.com/w320/".strtolower($country->first()->shortname).".png";
                }else{
                    $datap['country_name'] = "";
                    $datap['country_flag'] = "";
                }
                $datap['tag_icon'] = $popValue->tag_icon;
                if($language==2){
                    $datap['name'] = $popValue->name_ar;
                    $datap['description'] = $popValue->description_ar;
                    $datap['tag_name'] = $popValue->tag_name_ar;
                }else{
                    $datap['name'] = $popValue->name;
                    $datap['description'] = $popValue->description;
                    $datap['tag_name'] = $popValue->tag_name;
                }
                $brandfav = BrandFavorite::where('brandID',$popValue->id)->where('userID',$userID)->count();
                if($brandfav == 0){
                    $datap['fav'] = "0";
                }else{
                    $datap['fav'] = "1";
                }
                array_push($popularBrandArray,$datap);
            }
            return response()->json(['success'=>'true','message'=>'','result'=>$popularBrandArray], 200);
        }
    }
    
    //Car Make List
    public function carMakeList(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            $carMakeList = Brand::where('status',1)->where('categoryID',1)->orderby('name','ASC')->get();
            
            $result = array();
            foreach($carMakeList as $carMake){
                $data['id'] = $carMake->id;
                // if($language==2){
                //     $data['name'] = $carMake->name_ar;
                // }else{
                    $data['name'] = $carMake->name;
                // }
                array_push($result,$data);
            }
            return response()->json(['success'=>'true','message'=>'','result'=>$result], 200);
        }
    }
    
    //Car Model List
    public function carModelList(Request $request){
        $userID = $request->userID;
        $brandID = $request->brandID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($brandID==null){
            $err_array[]='brandID';
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
                }

                fclose($handle);
            }

            $modelArray = [];
            $uniqueModels = [];

            foreach ($fullsheetRows as $row) {
                $model = $row['Model'];

                if (!in_array($model, $uniqueModels) && $model != "") {
                    $uniqueModels[] = $model;
                    $modelArray[] = ['model' => $model];
                }
            }

            // Sort by 'model' in ascending order
            usort($modelArray, function ($a, $b) {
                return $a['model'] <=> $b['model'];
            });

            return response()->json(['success'=>'true','message'=>'','data'=>$modelArray], 200);
        }
    }
    
    //Car Model Trim List
    public function carModelTrimList(Request $request){
        $userID = $request->userID;
        $brandID = $request->brandID;
        $model = $request->model;
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
                        $assocRow['Model'] == $model
                    ) {
                        $formatted = [
                            'trim' => $assocRow['Trim']
                        ];

                        $filteredRows[] = $formatted;
                    }
                }

                fclose($handle);
            }

            $trimArray = [];
            $uniqueTrims = [];

            foreach ($filteredRows as $row) {
                $trim = $row['trim'];

                if (!in_array($trim, $uniqueTrims) && $trim != "") {
                    $uniqueTrims[] = $trim;
                    $trimArray[] = ['trim' => $trim];
                }
            }

            // Sort by 'trim' in ascending order
            usort($trimArray, function ($a, $b) {
                return $a['trim'] <=> $b['trim'];
            });

            return response()->json(['success'=>'true','message'=>'','data'=>$trimArray], 200);
        }
    }

    //Brand Details
    // public function branddetails(Request $request){
    //     $userID = $request->userID;
    //     $brandID = $request->brandID;
    //     $year = $request->year ?? date('Y');
    //     $language = $request->language;
    //     $err_array =array();
    //     if($userID==null){
    //         $err_array[]='userID';
    //     }
    //     if($brandID==null){
    //         $err_array[]='brandID';
    //     }
    //     if(count($err_array)>0){
    //         $er = implode(",", $err_array);
    //         return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
    //     }else{
    //         $arr['year'] = $year;
    //         //about
    //         $brand = Brand::where('id',$brandID)->where('status',1)->first();
    //         $datap['id'] = $brand->id;
    //         $datap['categoryID'] = $brand->categoryID;
    //         $arr['categoryID'] = $brand->categoryID;
    //         $datap['logo'] = $brand->logo;
    //         $datap['website'] = $brand->website;
    //         $datap['youtube'] = $brand->youtube;
    //         $datap['twitter'] = $brand->twitter;
    //         $datap['linkedin'] = $brand->linkedin;
    //         $datap['facebook'] = $brand->facebook;
    //         $datap['instagram'] = $brand->instagram;
    //         $datap['tag_icon'] = $brand->tag_icon;
    //         $datap['sponser_icon'] = $brand->sponser_icon;
    //         $datap['countryid'] = $brand->countryid;
    //         if($brand->countryid!=""){
    //             $country = Country::where('id',$brand->countryid);
    //             if($language==2){
    //                 $datap['country_name'] = $country->first()->name_ar;
    //             }else{
    //                 $datap['country_name'] = $country->first()->name;
    //             }
    //             $datap['country_flag'] ="https://flagcdn.com/w320/".strtolower($country->first()->shortname).".png";
    //         }else{
    //             $datap['country_name'] = "";
    //             $datap['country_flag'] = "";
    //         }
    //         if($language==2){
    //             $datap['name'] = $brand->name_ar;
    //             $datap['tag_name'] = $brand->tag_name_ar;
    //             $datap['sponser_name'] = $brand->sponser_name_ar;
    //             $datap['description'] = $brand->description_ar;
    //         }else{
    //             $datap['name'] = $brand->name;
    //             $datap['tag_name'] = $brand->tag_name;
    //             $datap['sponser_name'] = $brand->sponser_name;
    //             $datap['description'] = $brand->description;
    //         }

    //         //agent
    //         $agentBrand = AgentBrand::where('brandID',$brandID)->get();
    //         $agenciesArray = array();
    //         foreach($agentBrand as $vagent){
    //             $agencies = Agent::where('id',$vagent->agentID)->where('status',1);
    //             if($agencies->count()!=0){
    //                 $aggvalue = $agencies->first();
    //                 $dataa['id'] = $aggvalue->id;
    //                 $dataa['logo'] = $aggvalue->logo;
    //                 $dataa['images'] = $aggvalue->images;
    //                 $dataa['type'] = $aggvalue->type;
    //                 $dataa['tag_icon'] = $aggvalue->tag_icon;
    //                 $dataa['website'] = $aggvalue->website;
    //                 if($language==2){
    //                     $dataa['name'] = $aggvalue->name_ar;
    //                     $dataa['description'] = $aggvalue->discription_ar;
    //                     $dataa['tag_name'] = $aggvalue->tag_name_ar;
    //                 }else{
    //                     $dataa['name'] = $aggvalue->name;
    //                     $dataa['description'] = $aggvalue->description;
    //                     $dataa['tag_name'] = $aggvalue->tag_name;
    //                 }
    //                 $checkagentfav = AgentFavorite::where('userID',$userID)->where('agentID',$aggvalue->id)->count();
    //                 if($checkagentfav!=0){
    //                     $dataa['favorite']=1;
    //                 }else{
    //                     $dataa['favorite']=0;
    //                 }
    //                 array_push($agenciesArray,$dataa);
    //             }
    //         }
    //         //car
    //         //new code 29-07-2024
    //         $vehicleArray = array();
    //         $vehiclebanner = array();
    //         $current_year = date('Y');

    //         // $modeldata = CarModel::where('brandID',$brandID)->get();

    //         $brand = Brand::find($brandID);

            
    //         if (!$brand || empty($brand->excel)) {
    //             return response()->json([
    //                 'success' => 'false',
    //                 'message' => 'Data not found.'
    //             ]);
    //         }

    //         $filePath = public_path('brand/excel/' . $brand->excel);
            
    //         // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

    //         if (!file_exists($filePath)) {
    //             return response()->json([
    //                 'success' => 'false',
    //                 'message' => 'Excel file does not exist in the specified path.'
    //             ]);
    //         }

    //         $filteredRows = [];
    //         $fullsheetRows = [];

    //         if (($handle = fopen($filePath, "r")) !== false) {
    //             $sample = fgets($handle);
    //             rewind($handle);
    //             $delimiter = strpos($sample, ';') !== false ? ';' : ',';

    //             $rawHeaders = fgetcsv($handle, 0, $delimiter);
    //             $headers = array_map(function ($header) {
    //                 $header = trim($header);
    //                 $header = preg_replace('/\s+/', '_', $header); // spaces to _
    //                 $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
    //                 return $header;
    //             }, $rawHeaders);

    //             while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
    //                 if (count($row) !== count($headers)) {
    //                     continue;
    //                 }

    //                 $row = array_map('trim', $row);
    //                 $assocRow = array_combine($headers, $row);

    //                 $fullsheetRows[] = $assocRow;

    //                 if (
    //                     isset($assocRow['Model_Year']) &&
    //                     $assocRow['Model_Year'] == $year
    //                 ) {
    //                     $make = $assocRow['Make'];
    //                     $model = $assocRow['Model'];
    //                     $trim = $assocRow['Trim'];
    //                     $existApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

    //                     if ($existApiTrimList->isEmpty()) {
    //                         $responseData = ExternalSystem::trim_list($year, $make, $model);
    //                         $trims = $responseData['data'] ?? [];

    //                         foreach ($trims as $apitrim) {
    //                             $saveTrim = new ApiTrimList;
    //                             $saveTrim->year = '';
    //                             $saveTrim->make = $make;
    //                             $saveTrim->model = $model;
    //                             $saveTrim->trim = $apitrim;
    //                             $saveTrim->save();
    //                         }
    //                     }

    //                     $ApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

    //                     $matchedTrim = null;
    //                     foreach ($ApiTrimList as $singleapitrim) {
    //                         if (stripos($singleapitrim->trim, $trim) !== false) {
    //                             $matchedTrim = $singleapitrim->trim;
    //                             break;
    //                         }
    //                     }

    //                     $photo = [];

    //                     if (!$matchedTrim) {
    //                         $dbImages = CarImage::where([
    //                             'brand_id'=>$brandID,
    //                             'make'=>$assocRow['Make'],
    //                             'model'=>$assocRow['Model'],
    //                             'trim'=>$assocRow['Trim'],
    //                             'year'=>$assocRow['Model_Year']
    //                             ])->whereIn('type',[1,2,3])->get();

    //                         foreach ($dbImages as $img) {
    //                             $photo[] = ['image' => url('public/images').'/'. $img->image];
    //                         }
    //                     }else{

    //                         $trimFolder = urlencode($matchedTrim);
    //                         $uploadPath = public_path("images/car/$make/$model/$trimFolder");

    //                         if (!file_exists($uploadPath)) {
    //                             mkdir($uploadPath, 0755, true);
    //                         }

    //                         $files = File::files($uploadPath);

    //                         if (count($files) == 0) {
    //                             $responseData = ExternalSystem::vehicle_image($make, $model, $matchedTrim, $year);
    //                             $allImages = [];

    //                             foreach (['exterior', 'interior', 'colors'] as $category) {
    //                                 if (!empty($responseData['data']['images'][$category])) {
    //                                     foreach ($responseData['data']['images'][$category] as $imageUrl) {
    //                                         $allImages[] = $imageUrl;
    //                                         if (count($allImages) == 10) break 2;
    //                                     }
    //                                 }
    //                             }

    //                             foreach ($allImages as $imageUrl) {
    //                                 try {
    //                                     $contents = file_get_contents($imageUrl);
    //                                     $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
    //                                     $filename = 'img_' . time() . '_' . Str::random(5) . '.' . $ext;
    //                                     file_put_contents($uploadPath . '/' . $filename, $contents);
    //                                 } catch (\Exception $e) {
    //                                     \Log::error("Failed to download image: $imageUrl - " . $e->getMessage());
    //                                 }
    //                             }
    //                         }

    //                         $files = File::files($uploadPath);

    //                         if (count($files) > 0) {
    //                             foreach ($files as $file) {
    //                                 $relativePath = str_replace(public_path(), '', $file->getPathname());
    //                                 $photo[] = ['image' => url('public' . $relativePath)];
    //                             }
    //                         } else {
                                
    //                             $dbImages = CarImage::where([
    //                                 'brand_id'=>$brandID,
    //                                 'make'=>$assocRow['Make'],
    //                                 'model'=>$assocRow['Model'],
    //                                 'trim'=>$assocRow['Trim'],
    //                                 'year'=>$assocRow['Model_Year']
    //                                 ])->whereIn('type',[1,2,3])->get();

    //                             foreach ($dbImages as $img) {
    //                                 $photo[] = ['image' => url('public/images').'/'. $img->image];
    //                             }
    //                         }
    //                     }
    //                     $formatted = [
    //                         'make' => $assocRow['Make'],
    //                         'model' => $assocRow['Model'],
    //                         'trim' => $assocRow['Trim'],
    //                         'body_style' => $assocRow['Body_Style'],
    //                         'model_year' => $assocRow['Model_Year'],
    //                         'photo' => count($photo) > 0 ? $photo[0]['image'] : ''
    //                     ];

    //                     $filteredRows[] = $formatted;
    //                 }

    //             }

    //             fclose($handle);
    //         }

    //         $yearArray = [];
    //         $uniqueYears = [];

    //         foreach ($fullsheetRows as $row) {
    //             $year = $row['Model_Year'];

    //             if (!in_array($year, $uniqueYears) && $year != "") {
    //                 $uniqueYears[] = $year;
    //                 $yearArray[] = ['year' => $year];
    //             }
    //         }

    //         // Sort by 'year' in ascending order
    //         usort($yearArray, function ($a, $b) {
    //             return $a['year'] <=> $b['year'];
    //         });

    //         foreach($filteredRows as $modelvalue){
    //             // $datavehicle['id'] = $modelvalue->id;
    //             $datavehicle['brand_id'] = $brandID;
    //             $datavehicle['make'] = $modelvalue['make'];
    //             $datavehicle['model'] = $modelvalue['model'];
    //             $datavehicle['trim'] = $modelvalue['trim'];
    //             $datavehicle['body_style'] = $modelvalue['body_style'];
    //             $datavehicle['year'] = $modelvalue['model_year'];
    //             // $vehicleimg = CarImage::where('modelID',$modelvalue->id);
    //             // if($vehicleimg->count()!=0){
    //             //     if($vehicleimg->first()->image_type==1){
    //             //         $datavehicle['image'] = url('public/images/car/'.$vehicleimg->first()->image);
    //             //         //$datavehicleimg['image'] = url('public/images/car/'.$vehicleimg->first()->image);

    //             //     }else{
    //             //         $datavehicle['image'] = $vehicleimg->first()->image;
    //             //         //$datavehicleimg['image'] = $vehicleimg->first()->image;
    //             //     }
    //             //     //array_push($vehiclebanner,$datavehicleimg);

    //             // }else{
    //                 // $datavehicle['image']= ExternalSystem::car_image($modelvalue['make'],$modelvalue['model'],$modelvalue['model_year'],'');
    //                 $datavehicle['image']= $modelvalue['photo'] ?: (url("public/images/".$brand->logo) ?? url("public/img/image-preview.png"));
    //             // }
    //             //$datavehicle['image']="";
    //             // $datavehicle['modelID']=$modelvalue->id;
    //             // if($language==2){
    //             //     $datavehicle['name'] = $modelvalue->name_ar;
    //             //     $datavehicle['description'] = "";
    //             // }else{
    //             //     $datavehicle['name'] = $modelvalue->name;
    //             //     $datavehicle['description'] = "";
    //             // }

    //             array_push($vehicleArray,$datavehicle);

    //             // $vehicleimgs = CarImage::where('modelID',$modelvalue->id)->skip(0)->take(1)->get();
    //             // foreach($vehicleimgs as $vimg){
    //             //     $datavehicleimg['id'] = $vimg->id;
    //             //     if($vimg->image_type==1){
    //             //         $datavehicleimg['image'] = url('public/images/car/'.$vimg->image);

    //             //     }else{
    //             //         $datavehicleimg['image'] = $vimg->image;
    //             //     }
    //             //     //array_push($vehiclebanner,$datavehicleimg);
    //             // }
    //         }
    //         //banner
    //         $brandbanner = AllBannerImage::where('bannertype',1)->where('bannerimageID',$brandID)->get();
    //         foreach($brandbanner as $bannervalue){
    //             $databrandbanner['image'] = url('public/images/'.$bannervalue->image);
    //             array_push($vehiclebanner,$databrandbanner);
    //         }
    //         //old code
    //         // $vehicle = Vehicle::where('status',1)->where('brandID',$brandID)->orderBy('year','DESC')->get();
    //         // $vehicleArray = array();
    //         // $vehiclebanner = array();
    //         // foreach($vehicle as $carvalue){
    //         //     $datavehicle['id'] = $carvalue->id;
    //         //     $CarImage = CarImage::where('vehicleID',$carvalue->id);
    //         //     if($CarImage->count()!=0){
    //         //         if($CarImage->first()->image_type==1){
    //         //             $datavehicle['image'] =url('public/images/car/'.$CarImage->first()->image);
    //         //             $datavehicleimg['image'] =url('public/images/car/'.$CarImage->first()->image);
    //         //         }else{
    //         //             $datavehicle['image'] =$CarImage->first()->image;
    //         //             $datavehicleimg['image'] =$CarImage->first()->image;
    //         //         }
    //         //         array_push($vehiclebanner,$datavehicleimg);

    //         //     }else{
    //         //         $datavehicle['image'] ="";
    //         //     }

    //         //     $datavehicle['modelID'] = $carvalue->modelID;
    //         //     if($language==2){
    //         //         $datavehicle['name'] = $carvalue->name;
    //         //         $datavehicle['description'] = $carvalue->description;
    //         //     }else{
    //         //         $datavehicle['name'] = $carvalue->name;
    //         //         $datavehicle['description'] = $carvalue->description;
    //         //     }
    //         //     array_push($vehicleArray,$datavehicle);
    //         // }
    //         $arr['about'] = $datap;
    //         $arr['agencies'] = $agenciesArray;
    //         $arr['car'] = $vehicleArray;
    //         $arr['yearlist'] = $yearArray;
    //         $arr['banner'] = $vehiclebanner;
    //         $brandfav = BrandFavorite::where('brandID',$brandID)->where('userID',$userID)->count();
    //         if($brandfav == 0){
    //             $arr['fav'] = "0";
    //         }else{
    //             $arr['fav'] = "1";
    //         }
    //         //review
    //         $reviews = BrandReview::where('brandID',$brandID)->orderBy('id','DESC')->get();
    //         $reviewArrays = array();
    //         foreach($reviews as $revValues){
    //             $datarevs['id'] = $revValues->id;
    //             $datarevs['userID'] = $revValues->userID;
    //             $datarevs['username'] = User::find($revValues->userID)->name;
    //             $datarevs['rate'] = $revValues->rate;
    //             $datarevs['title'] = $revValues->title;
    //             $datarevs['message'] = $revValues->message;
    //             $datarevs['create_date'] = date('M d,Y',strtotime($revValues->created_at));
    //             array_push($reviewArrays,$datarevs);
    //         }
    //         $arr['review'] = $reviewArrays;
    //         $avgStar = BrandReview::where('brandID',$brandID)->avg('rate');
    //         $arr['rating'] = "".number_format($avgStar, 1, '.', '');
    //         $CarReview = BrandReview::where('brandID',$brandID);
    //         $arr['reviews'] = $CarReview->count();

    //         return response()->json(['success'=>'true','message'=>'','result'=>$arr], 200);
    //     }
    // }

    // public function branddetails(Request $request)
    // {
    //     $userID = $request->userID;
    //     $brandID = $request->brandID;
    //     $year = $request->year ?? date('Y');
    //     $language = $request->language;
    //     $err_array = [];

    //     if (!$userID) $err_array[] = 'userID';
    //     if (!$brandID) $err_array[] = 'brandID';

    //     if (!empty($err_array)) {
    //         return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . implode(", ", $err_array)], 400);
    //     }

    //     $brand = Brand::where('id', $brandID)->where('status', 1)->first();
    //     if (!$brand) {
    //         return response()->json(['success' => 'false', 'message' => 'Brand not found'], 404);
    //     }

    //     $arr = ['year' => $year, 'categoryID' => $brand->categoryID];

    //     $datap = [
    //         'id' => $brand->id,
    //         'categoryID' => $brand->categoryID,
    //         'logo' => $brand->logo,
    //         'website' => $brand->website,
    //         'youtube' => $brand->youtube,
    //         'twitter' => $brand->twitter,
    //         'linkedin' => $brand->linkedin,
    //         'facebook' => $brand->facebook,
    //         'instagram' => $brand->instagram,
    //         'tag_icon' => $brand->tag_icon,
    //         'sponser_icon' => $brand->sponser_icon,
    //         'countryid' => $brand->countryid,
    //     ];

    //     if ($brand->countryid) {
    //         $country = Country::find($brand->countryid);
    //         $datap['country_name'] = $language == 2 ? $country->name_ar : $country->name;
    //         $datap['country_flag'] = "https://flagcdn.com/w320/" . strtolower($country->shortname) . ".png";
    //     } else {
    //         $datap['country_name'] = $datap['country_flag'] = "";
    //     }

    //     $datap['name'] = $language == 2 ? $brand->name_ar : $brand->name;
    //     $datap['tag_name'] = $language == 2 ? $brand->tag_name_ar : $brand->tag_name;
    //     $datap['sponser_name'] = $language == 2 ? $brand->sponser_name_ar : $brand->sponser_name;
    //     $datap['description'] = $language == 2 ? $brand->description_ar : $brand->description;

    //     $agentBrand = AgentBrand::where('brandID', $brandID)->get();
    //     $agenciesArray = [];
    //     foreach ($agentBrand as $vagent) {
    //         $aggvalue = Agent::where('id', $vagent->agentID)->where('status', 1)->first();
    //         if ($aggvalue) {
    //             $dataa = [
    //                 'id' => $aggvalue->id,
    //                 'logo' => $aggvalue->logo,
    //                 'images' => $aggvalue->images,
    //                 'type' => $aggvalue->type,
    //                 'tag_icon' => $aggvalue->tag_icon,
    //                 'website' => $aggvalue->website,
    //                 'name' => $language == 2 ? $aggvalue->name_ar : $aggvalue->name,
    //                 'description' => $language == 2 ? $aggvalue->discription_ar : $aggvalue->description,
    //                 'tag_name' => $language == 2 ? $aggvalue->tag_name_ar : $aggvalue->tag_name,
    //                 'favorite' => AgentFavorite::where('userID', $userID)->where('agentID', $aggvalue->id)->exists() ? 1 : 0
    //             ];
    //             $agenciesArray[] = $dataa;
    //         }
    //     }

    //     // Call a separate method to load car info from CSV
    //     [$vehicleArray, $yearArray] = $this->getVehicleDataFromCsv($brandID, $year);

    //     $vehiclebanner = AllBannerImage::where('bannertype', 1)->where('bannerimageID', $brandID)->get()->map(function ($item) {
    //         return ['image' => url('public/images/' . $item->image)];
    //     })->toArray();

    //     $arr['about'] = $datap;
    //     $arr['agencies'] = $agenciesArray;
    //     $arr['car'] = $vehicleArray;
    //     $arr['yearlist'] = $yearArray;
    //     $arr['banner'] = $vehiclebanner;
    //     $arr['fav'] = BrandFavorite::where('brandID', $brandID)->where('userID', $userID)->exists() ? "1" : "0";

    //     $reviews = BrandReview::where('brandID', $brandID)->latest()->get();
    //     $reviewArrays = $reviews->map(function ($rev) {
    //         return [
    //             'id' => $rev->id,
    //             'userID' => $rev->userID,
    //             'username' => optional(User::find($rev->userID))->name,
    //             'rate' => $rev->rate,
    //             'title' => $rev->title,
    //             'message' => $rev->message,
    //             'create_date' => $rev->created_at->format('M d,Y')
    //         ];
    //     })->toArray();

    //     $arr['review'] = $reviewArrays;
    //     $arr['rating'] = number_format($reviews->avg('rate'), 1);
    //     $arr['reviews'] = $reviews->count();

    //     return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
    // }

    // private function getVehicleDataFromCsv($brandID, $year)
    // {
    //     $brand = Brand::find($brandID);
    //     if (!$brand || empty($brand->excel)) return [[], []];

    //     $filePath = public_path('brand/excel/' . $brand->excel);
    //     if (!file_exists($filePath)) return [[], []];

    //     $filteredRows = [];
    //     $yearSet = [];

    //     if (($handle = fopen($filePath, "r")) !== false) {
    //         $sample = fgets($handle);
    //         rewind($handle);
    //         $delimiter = strpos($sample, ';') !== false ? ';' : ',';

    //         $rawHeaders = fgetcsv($handle, 0, $delimiter);
    //         $headers = array_map(function ($header) {
    //             $header = trim($header);
    //             $header = preg_replace('/\s+/', '_', $header);
    //             $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header);
    //             return $header;
    //         }, $rawHeaders);

    //         while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
    //             if (count($row) !== count($headers)) continue;

    //             $assocRow = array_combine($headers, array_map('trim', $row));
    //             if (!isset($assocRow['Model_Year'])) continue;

    //             $yearSet[$assocRow['Model_Year']] = true;

    //             if ($assocRow['Model_Year'] != $year) continue;

    //             $photo = url("public/images/noimage.png");
    //             $dbImages = CarImage::where([
    //                 'brand_id' => $brandID,
    //                 'make' => $assocRow['Make'] ?? '',
    //                 'model' => $assocRow['Model'] ?? '',
    //                 'trim' => $assocRow['Trim'] ?? '',
    //                 'year' => $assocRow['Model_Year'] ?? ''
    //             ])->whereIn('type', [1, 2, 3])->get();

    //             if ($dbImages->isNotEmpty()) {
    //                 $photo = url('public/images/' . $dbImages->first()->image);
    //             }

    //             $filteredRows[] = [
    //                 'make' => $assocRow['Make'] ?? '',
    //                 'model' => $assocRow['Model'] ?? '',
    //                 'trim' => $assocRow['Trim'] ?? '',
    //                 'body_style' => $assocRow['Body_Style'] ?? '',
    //                 'model_year' => $assocRow['Model_Year'] ?? '',
    //                 'photo' => $photo
    //             ];
    //         }
    //         fclose($handle);
    //     }

    //     $yearArray = collect(array_keys($yearSet))->filter()->sort()->map(fn($y) => ['year' => $y])->values()->toArray();

    //     return [$filteredRows, $yearArray];
    // }

    // private function getVehicleDataFromCsv($brandID, $year)
    // {
    //     $vehicleArray = [];
    //     $yearArray = [];
    //     $fullsheetRows = [];
    //     $brand = Brand::find($brandID);

    //     if (!$brand || empty($brand->excel)) return [[], []];

    //     $filePath = public_path('brand/excel/' . $brand->excel);
    //     if (!file_exists($filePath)) return [[], []];

    //     if (($handle = fopen($filePath, "r")) !== false) {
    //         $sample = fgets($handle);
    //         rewind($handle);
    //         $delimiter = strpos($sample, ';') !== false ? ';' : ',';

    //         $rawHeaders = fgetcsv($handle, 0, $delimiter);
    //         $headers = array_map(function ($header) {
    //             $header = trim($header);
    //             $header = preg_replace('/\s+/', '_', $header);
    //             $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header);
    //             return $header;
    //         }, $rawHeaders);

    //         while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
    //             if (count($row) !== count($headers)) continue;

    //             $row = array_map('trim', $row);
    //             $assocRow = array_combine($headers, $row);
    //             $fullsheetRows[] = $assocRow;

    //             if (isset($assocRow['Model_Year']) && $assocRow['Model_Year'] == $year) {
    //                 $make = $assocRow['Make'];
    //                 $model = $assocRow['Model'];
    //                 $trim = $assocRow['Trim'];
    //                 $photo = [];

    //                 $existApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();
    //                 if ($existApiTrimList->isEmpty()) {
    //                     $responseData = ExternalSystem::trim_list($year, $make, $model);
    //                     $trims = $responseData['data'] ?? [];

    //                     foreach ($trims as $apitrim) {
    //                         ApiTrimList::create([
    //                             'year' => '',
    //                             'make' => $make,
    //                             'model' => $model,
    //                             'trim' => $apitrim,
    //                         ]);
    //                     }
    //                 }

    //                 $ApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();
    //                 $matchedTrim = null;
    //                 foreach ($ApiTrimList as $singleapitrim) {
    //                     if (stripos($singleapitrim->trim, $trim) !== false) {
    //                         $matchedTrim = $singleapitrim->trim;
    //                         break;
    //                     }
    //                 }

    //                 if ($matchedTrim) {
    //                     $trimFolder = urlencode($matchedTrim);
    //                     $uploadPath = public_path("images/car/$make/$model/$trimFolder");

    //                     if (File::exists($uploadPath)) {
    //                         $files = File::files($uploadPath);
    //                         foreach ($files as $file) {
    //                             $relativePath = str_replace(public_path(), '', $file->getPathname());
    //                             $photo[] = ['image' => url('public' . $relativePath)];
    //                         }
    //                     }
    //                 }

    //                 if (empty($photo)) {
    //                     $dbImages = CarImage::where([
    //                         'brand_id' => $brandID,
    //                         'make' => $make,
    //                         'model' => $model,
    //                         'trim' => $trim,
    //                         'year' => $year
    //                     ])->whereIn('type', [1, 2, 3])->get();

    //                     foreach ($dbImages as $img) {
    //                         $photo[] = ['image' => url('public/images') . '/' . $img->image];
    //                     }
    //                 }

    //                 $image = !empty($photo) ? $photo[0]['image'] : (url("public/images/" . $brand->logo) ?: url("public/img/image-preview.png"));

    //                 $vehicleArray[] = [
    //                     'brand_id' => $brandID,
    //                     'make' => $make,
    //                     'model' => $model,
    //                     'trim' => $trim,
    //                     'body_style' => $assocRow['Body_Style'],
    //                     'year' => $assocRow['Model_Year'],
    //                     'image' => $image
    //                 ];
    //             }
    //         }
    //         fclose($handle);
    //     }

    //     $uniqueYears = [];
    //     foreach ($fullsheetRows as $row) {
    //         $y = $row['Model_Year'];
    //         if (!in_array($y, $uniqueYears) && $y != "") {
    //             $uniqueYears[] = $y;
    //             $yearArray[] = ['year' => $y];
    //         }
    //     }

    //     usort($yearArray, fn($a, $b) => $a['year'] <=> $b['year']);

    //     return [$vehicleArray, $yearArray];
    // }

    public function branddetails(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $year = $request->year ?? date('Y');
        $language = $request->language;
        $err_array = [];

        if (!$userID) $err_array[] = 'userID';
        if (!$brandID) $err_array[] = 'brandID';

        if (!empty($err_array)) {
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . implode(", ", $err_array)], 400);
        }

        $brand = Brand::where('id', $brandID)->where('status', 1)->first();
        if (!$brand) {
            return response()->json(['success' => 'false', 'message' => 'Brand not found'], 404);
        }

        $arr = ['year' => $year, 'categoryID' => $brand->categoryID];

        $datap = [
            'id' => $brand->id,
            'categoryID' => $brand->categoryID,
            'logo' => $brand->logo,
            'website' => $brand->website,
            'youtube' => $brand->youtube,
            'twitter' => $brand->twitter,
            'linkedin' => $brand->linkedin,
            'facebook' => $brand->facebook,
            'instagram' => $brand->instagram,
            'tag_icon' => $brand->tag_icon,
            'sponser_icon' => $brand->sponser_icon,
            'countryid' => $brand->countryid,
        ];

        if ($brand->countryid) {
            $country = Country::find($brand->countryid);
            $datap['country_name'] = $language == 2 ? $country->name_ar : $country->name;
            $datap['country_flag'] = "https://flagcdn.com/w320/" . strtolower($country->shortname) . ".png";
        } else {
            $datap['country_name'] = $datap['country_flag'] = "";
        }

        $datap['name'] = $language == 2 ? $brand->name_ar : $brand->name;
        $datap['tag_name'] = $language == 2 ? $brand->tag_name_ar : $brand->tag_name;
        $datap['sponser_name'] = $language == 2 ? $brand->sponser_name_ar : $brand->sponser_name;
        $datap['description'] = $language == 2 ? $brand->description_ar : $brand->description;

        $agentBrand = AgentBrand::where('brandID', $brandID)->get();
        $agenciesArray = [];
        foreach ($agentBrand as $vagent) {
            $aggvalue = Agent::where('id', $vagent->agentID)->where('status', 1)->first();
            if ($aggvalue) {
                $dataa = [
                    'id' => $aggvalue->id,
                    'logo' => $aggvalue->logo,
                    'images' => $aggvalue->images,
                    'type' => $aggvalue->type,
                    'tag_icon' => $aggvalue->tag_icon,
                    'website' => $aggvalue->website,
                    'name' => $language == 2 ? $aggvalue->name_ar : $aggvalue->name,
                    'description' => $language == 2 ? $aggvalue->discription_ar : $aggvalue->description,
                    'tag_name' => $language == 2 ? $aggvalue->tag_name_ar : $aggvalue->tag_name,
                    'favorite' => AgentFavorite::where('userID', $userID)->where('agentID', $aggvalue->id)->exists() ? 1 : 0
                ];
                $agenciesArray[] = $dataa;
            }
        }

        [$vehicleArray, $yearArray] = $this->getVehicleDataFromCsv($brandID, $year, $brand);

        $vehiclebanner = AllBannerImage::where('bannertype', 1)->where('bannerimageID', $brandID)->get()->map(function ($item) {
            return ['image' => url('public/images/' . $item->image)];
        })->toArray();

        $arr['about'] = $datap;
        $arr['agencies'] = $agenciesArray;
        $arr['car'] = $vehicleArray;
        $arr['yearlist'] = $yearArray;
        $arr['banner'] = $vehiclebanner;
        $arr['fav'] = BrandFavorite::where('brandID', $brandID)->where('userID', $userID)->exists() ? "1" : "0";

        $reviews = BrandReview::where('brandID', $brandID)->latest()->get();
        $reviewArrays = $reviews->map(function ($rev) {
            return [
                'id' => $rev->id,
                'userID' => $rev->userID,
                'username' => optional(User::find($rev->userID))->name,
                'rate' => $rev->rate,
                'title' => $rev->title,
                'message' => $rev->message,
                'create_date' => $rev->created_at->format('M d,Y')
            ];
        })->toArray();

        $arr['review'] = $reviewArrays;
        $arr['rating'] = number_format($reviews->avg('rate'), 1);
        $arr['reviews'] = $reviews->count();

        return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
    }

    private function getVehicleDataFromCsv($brandID, $year, $brand)
    {
        $csvPath = public_path('brand/excel/' . $brand->excel);
        if (!File::exists($csvPath)) return [[], []];

        $vehicleArray = [];
        $yearSet = [];
        $rows = array_map('str_getcsv', file($csvPath));
        $headers = array_map(fn($h) => str_replace(' ', '_', trim($h)), array_shift($rows));

        foreach ($rows as $row) {
            $row = array_combine($headers, $row);
            if (!isset($row['Model_Year']) || !$row['Model_Year']) continue;
            $yearSet[$row['Model_Year']] = true;

            if ($row['Model_Year'] != $year) continue;

            $make = $row['Make'];
            $model = $row['Model'];
            $trim = $row['Trim'];

            // $existApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();
            // if ($existApiTrimList->isEmpty()) {
            //     $response = ExternalSystem::trim_list($year, $make, $model);
            //     foreach ($response['data'] ?? [] as $t) {
            //         ApiTrimList::create(['year' => $year, 'make' => $make, 'model' => $model, 'trim' => $t]);
            //     }
            // }

            $ApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();
            $matchedTrim = null;
            foreach ($ApiTrimList as $t) {
                if (stripos($t->trim, $trim) !== false) {
                    $matchedTrim = $t->trim;
                    break;
                }
            }

            $image = '';
            if ($matchedTrim) {
                $trimFolder = urlencode($matchedTrim);
                $path = public_path("images/car/$make/$model/$trimFolder");
                if (File::exists($path)) {
                    $files = File::files($path);
                    if (!empty($files)) {
                        $image = url(str_replace(public_path(), 'public', $files[0]->getPathname()));
                    }
                }
            }

            if (!$image) {
                $dbImage = CarImage::where([
                    'brand_id' => $brandID,
                    'make' => $make,
                    'model' => $model,
                    'trim' => $trim,
                    'year' => $year
                ])->whereIn('type', [1, 2, 3])->first();

                if ($dbImage) {
                    $image = url('public/images/' . $dbImage->image);
                } elseif ($brand->logo) {
                    $image = url('public/images/' . $brand->logo);
                } else {
                    $image = url('public/img/image-preview.png');
                }
            }

            $vehicleArray[] = [
                'brand_id' => $brandID,
                'make' => $make,
                'model' => $model,
                'trim' => $trim,
                'body_style' => $row['Body_Style'],
                'year' => $row['Model_Year'],
                'image' => $image
            ];
        }

        $yearArray = array_map(fn($y) => ['year' => $y], array_keys($yearSet));
        usort($yearArray, fn($a, $b) => $a['year'] <=> $b['year']);

        return [$vehicleArray, $yearArray];
    }


    //Add brand review
    public function addBrandreview(Request $request){
        $userID = $request->userID;
        $brandID = $request->brandID;
        $rate = $request->rate;
        $title = $request->title;
        $message = $request->message;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($brandID==null){
            $err_array[]='brandID';
        }
        if($rate==null){
            $err_array[]='rate';
        }
        if($title==null){
            $err_array[]='title';
        }
        if($message==null){
            $err_array[]='message';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "شكرا لك ";
                $msg_failed = "تم تقييم البراند مسبقا";
            }else{
                $msg_success = "Thank you for your rating.";
                $msg_failed = "A rating has already been given for this brand.";
            }
            $check = BrandReview::where('userID',$userID)->where('brandID',$brandID)->count();
            if($check==0){
                $review = new BrandReview;
                $review->brandID = $brandID;
                $review->userID = $userID;
                $review->rate = $rate;
                $review->title = $title;
                $review->message = $message;
                $review->save();
                return response()->json(['success'=>'true','message'=>$msg_success], 200);
            }else{
                return response()->json(['success'=>'false','message'=>$msg_failed], 400);
            }

        }
    }
}
