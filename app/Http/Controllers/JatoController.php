<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Session;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AllBannerImage;
use App\Models\StaffLogEvent;
use App\Models\Viewed;
use App\Models\MyCar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use App\Modules\ExternalSystem;
use App\Modules\CarSystem;

class JatoController extends Controller
{
    public function market(Request $request){
        //echo 1;
        $market = CarSystem::jatomarket();
        //echo "<pre>";
        //print_r(json_decode($market['body']));
        if($market['status_code']==200){
            $res = json_decode($market['body']);
            // foreach($res as $value){

            // }
        }

    }

    public function carmakes(Request $request){
        $make = CarSystem::jatomake();
        //echo "<pre>";

        if($make['status_code']==200){
            // echo "<pre>";
            // print_r(json_decode($make['body']));
            $res = json_decode($make['body']);
            echo "<pre>";
            print_r($res[0]->selections);
            foreach($res[0]->selections as $value){
                $check_name = Brand::where('name',$value->makeNameToDisplay);
                if($check_name->count()==0){
                    $brand = new Brand;
                    $brand->name = $value->makeNameToDisplay;
                    $brand->makeKey = $value->makeKey;
                    $brand->makeGroup = $value->makeGroup;
                    $brand->categoryID = 1;
                    $brand->status = 1;
                    $brand->save();
                }else{
                    $brand = Brand::find($check_name->first()->id);
                    $brand->name = $value->makeNameToDisplay;
                    $brand->makeKey = $value->makeKey;
                    $brand->makeGroup = $value->makeGroup;
                    $brand->categoryID = 1;
                    $brand->status = 1;
                    $brand->save();
                }

            }
        }

    }

    public function carmodels(Request $request){
        $make = CarSystem::jatomodel('Ford');
        //echo "<pre>";

        if($make['status_code']==200){
            echo "<pre>";
            print_r(json_decode($make['body']));
            $res = json_decode($make['body']);
            foreach($res[0]->selections as $value){
                print_r($value);
            }
        }

    }
    public function carbodies(Request $request){
        $make = CarSystem::jatocarbodies('Ford','Puma','2021');
        //echo "<pre>";

        if($make['status_code']==200){
            echo "<pre>";
            //print_r(json_decode($make['body']));
            $res = json_decode($make['body']);
            print_r($res[0]->selections[0]);
            // foreach($res as $value){

            // }
        }

    }
    public function carvehicles(Request $request){
        $make = CarSystem::jatocarvehicles('Ford','Puma','2021');
        //echo "<pre>";

        if($make['status_code']==200){
            echo "<pre>";
            //print_r(json_decode($make['body']));
            $res = json_decode($make['body']);
            print_r($res[0]->selections[0]);
            // foreach($res as $value){

            // }
        }

    }

    public function carphoto(Request $request){
        $make = CarSystem::jatocarphoto('818317020210324');
        //echo "<pre>";

        if($make['status_code']==200){
            $res = json_decode($make['body']);
            echo "<pre>";
            print_r($res[0]->photoPath);
            foreach($res[0]->photoPath as $imageUrl){
                // echo $value;
                // die;
                $response = Http::get($imageUrl);

                // Check if the response is successful
                if ($response->successful()) {
                    // Get the image content (binary data)
                    $imageContent = $response->body();

                    // Generate a unique filename for the image
                    $imageName = time(). '.' . pathinfo($imageUrl, PATHINFO_EXTENSION);

                    // Save the image to the public storage
                    $path = Storage::disk('public')->put('carimage/images/' . $imageName, $imageContent);
                    echo "13-";
                    //$path = Storage::disk('public')->put($imageName, $imageContent);

                    // Return a success response with the image path
                    // return response()->json([
                    //     'message' => 'Image uploaded successfully!',
                    //     'path' => Storage::url($imageName),
                    // ], 200);
                } else {
                    echo 12;
                }

            }
        }

    }
}
