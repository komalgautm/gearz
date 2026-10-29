<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\ServiceProviderCategory;
use App\Models\ServiceProvideService;
use App\Models\StaffLogEvent;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderCategorylist;
use App\Models\ServiceProviderserviceslist;
use App\Models\AllBannerImage;
use App\Models\ServiceProviderBranch;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Modules\ExternalSystem;

class ServiceProviderController extends Controller
{
    public function serviceprovidercategory()
    {
        $admin_id = Session::get('id');
        $data['title'] = "Service Provider Categories";
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovidercategory";
        $data['admin'] = Admin::find($admin_id);
        $data['data'] = ServiceProviderCategory::where('status', '!=', 2)->get();
        return view('admin.serviceprovidercategory', $data);
    }
    public function addserviceprovidercategory(Request $request)
    {
        $admin_id = Session::get('id');
        if ($request->key == "") {
            $data['title'] = "Add Service Provider Category";
        } else {
            $data['title'] = "Edit Service Provider Category";
        }
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovidercategory";
        $data['admin'] = Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = ServiceProviderCategory::find($id);
        return view('admin.addserviceprovidercategory', $data);
    }

    public function insert_serviceprovidercategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                //'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=40,max_height=40'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            echo 3;
        } else {
            $imageName = $this->processImageUpload($request, 'image', 'thump_');
            if ($request->id == "") {
                $check_name = ServiceProviderCategory::where('name', $request->name)->count();
                $check_namear = ServiceProviderCategory::where('name_ar', $request->namear)->count();
                if ($check_name == 0 && $check_namear == 0) {
                    $serviceType = new ServiceProviderCategory;
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    $serviceType->image = $imageName;
                    $serviceType->status = 1;
                    $serviceType->save();
                    // Log the event if usertype is 2
                    $this->logEvent("Add Service Provider Category", $serviceType->id);
                    echo 1;

                } else {
                    echo 2;
                }
            } else {
                $check_name = ServiceProviderCategory::where('name', $request->name)->where('id', $request->id);
                $check_namear = ServiceProviderCategory::where('name_ar', $request->namear)->where('id', $request->id);
                $st = 0;
                if ($check_name->count() >= 1 && $request->id != $check_name->first()->id) {
                    $st = 2;
                } else {
                    $st = 1;
                }
                if ($check_namear->count() >= 1 && $request->id != $check_namear->first()->id) {
                    $st = 2;
                } else {
                    $st = 1;
                }
                if ($st == 1) {
                    $serviceType = new ServiceProviderCategory;
                    $serviceType = ServiceProviderCategory::find($request->id);
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    if ($imageName != "") {
                        $serviceType->image = $imageName;
                    }
                    $serviceType->save();
                    // Log the event if usertype is 2
                    $this->logEvent("Update Service Provider Category", $request->id);
                    echo 1;

                } else {
                    echo 2;
                }

            }
        }
    }
    public function delete_serviceprovidercategory(Request $request)
    {
        $id = $request->id;
        $serviceType = ServiceProviderCategory::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        // Log the event if usertype is 2
        $this->logEvent("Delete Service Provider Category", $id);
    }

    public function change_serviceprovidercategory_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $serviceType = ServiceProviderCategory::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        // Log the event if usertype is 2
        $this->logEvent("Change Service Provider Category status", $id);
    }
    //services
    public function serviceproviderservices()
    {
        $admin_id = Session::get('id');
        $data['title'] = "Service Provider services";
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceproviderservices";
        $data['admin'] = Admin::find($admin_id);
        $data['data'] = ServiceProvideService::where('status', '!=', 2)->get();
        return view('admin.serviceproviderservice', $data);
    }
    public function addserviceproviderservice(Request $request)
    {
        $admin_id = Session::get('id');
        if ($request->key == "") {
            $data['title'] = "Add Service Provider Service";
        } else {
            $data['title'] = "Edit Service Provider Service";
        }
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceproviderservices";
        $data['admin'] = Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = ServiceProvideService::find($id);
        return view('admin.addserviceproviderservice', $data);
    }

    public function insert_serviceproviderservice(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                //'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=40,max_height=40'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            echo 3;
        } else {
            $imageName = $this->processImageUpload($request, 'image', 'thump_');
            if ($request->id == "") {
                $check_name = ServiceProvideService::where('name', $request->name)->count();
                $check_namear = ServiceProvideService::where('name_ar', $request->namear)->count();
                if ($check_name == 0 && $check_namear == 0) {
                    $serviceType = new ServiceProvideService;
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    $serviceType->image = $imageName;
                    $serviceType->status = 1;
                    $serviceType->save();
                    // Log the event if usertype is 2
                    $this->logEvent("Add Service Provider service ", $serviceType->id);
                    echo 1;

                } else {
                    echo 2;
                }
            } else {
                $check_name = ServiceProvideService::where('name', $request->name)->where('id', $request->id);
                $check_namear = ServiceProvideService::where('name_ar', $request->namear)->where('id', $request->id);
                $st = 0;
                if ($check_name->count() >= 1 && $request->id != $check_name->first()->id) {
                    $st = 2;
                } else {
                    $st = 1;
                }
                if ($check_namear->count() >= 1 && $request->id != $check_namear->first()->id) {
                    $st = 2;
                } else {
                    $st = 1;
                }
                if ($st == 1) {
                    $serviceType = new ServiceProvideService;
                    $serviceType = ServiceProvideService::find($request->id);
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    if ($imageName != "") {
                        $serviceType->image = $imageName;
                    }
                    $serviceType->save();
                    // Log the event if usertype is 2
                    $this->logEvent("Update Service Provider service ", $request->id);
                    echo 1;

                } else {
                    echo 2;
                }

            }
        }
    }
    public function delete_serviceproviderservice(Request $request)
    {
        $id = $request->id;
        $serviceType = ServiceProvideService::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        // Log the event if usertype is 2
        $this->logEvent("Delete Service Provider service ", $request->id);
    }

    public function change_serviceproviderservice_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $serviceType = ServiceProvideService::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        // Log the event if usertype is 2
        $this->logEvent("Change Service Provider service status", $request->id);
    }

    //service provider
    public function serviceProvider()
    {
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovider";
        $admin_id = session::get('id');
        $data['admin'] = Admin::find($admin_id);
        $data['title'] = "Service Providers";
        $agentpopcount = ServiceProvider::where('status', '!=', 2)->where('popular', 1)->count();
        if ($agentpopcount != 0) {
            $data['data'] = ServiceProvider::where('status', '!=', 2)->orderBy('position', 'asc')->get();
        } else {
            $data['data'] = ServiceProvider::where('status', '!=', 2)->orderby('name', 'ASC')->get();
        }
        return view('admin.service_provider', $data);
    }
    public function addServiceProvider(Request $request)
    {
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovider";
        $admin_id = session::get('id');
        $data['admin'] = Admin::find($admin_id);
        $data['title'] = "Service Provider";
        $data['category'] = ServiceProviderCategory::where('status', 1)->get();
        $data['service'] = ServiceProvideService::where('status', 1)->get();
        $id = base64_decode($request->key);
        $data['data'] = ServiceProvider::find($id);
        $data['ServiceProviderCategorylist'] = ServiceProviderCategorylist::where('serviceproviderID', $id)->get();
        $data['ServiceProviderserviceslist'] = ServiceProviderserviceslist::where('serviceproviderID', $id)->where('type', 1)->get();
        $data['brandbanner'] = AllBannerImage::where('bannertype',6)->where('bannerimageID',$id)->get();
        return view('admin.addservice_provider', $data);
    }
    public function insert_service_provider(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            //'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=100,max_height=100',
            //'images' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=500,max_height=500',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        // Process images (logo and tag icon)
        $imageName = $this->processImageUpload($request, 'image', 'thump_');
        $tagIcon = $this->processImageUpload($request, 'tag_icon', 'tagthump_');

        if (empty($request->id)) {
            // Check for existing records (email, phone, name, Arabic name)
            if ($this->isDuplicate($request)) {
                return;
            }
            // Insert new service provider
            $serviceProvider = new ServiceProvider;
            $serviceProvider->fill($this->getServiceProviderData($request, $imageName, $tagIcon));
            $serviceProvider->save();

            // Insert associated categories and services
            $this->handleCategories($request, $serviceProvider->id);
            $this->handleServices($request, $serviceProvider->id);
            $this->handleBannerImages($request, $serviceProvider->id);

            // Log the event if usertype is 2
            $this->logEvent("Add Service Provider", $serviceProvider->id);

            echo 1;
        } else {
            // if($imageName=''){
            //     $imageName = ServiceProvider::where('id',$request->id)->first()->logo;
            // }
            // if($tagIcon=''){
            //     $tagIcon = ServiceProvider::where('id',$request->id)->first()->tag_icon;
            // }
            // Update existing service provider
            $serviceProvider = ServiceProvider::find($request->id);

            $serviceProvider->fill($this->getServiceProviderData($request, $imageName, $tagIcon));
            $serviceProvider->save();

            // Update categories and services
            $this->updateCategories($request, $serviceProvider->id);
            $this->updateServices($request, $serviceProvider->id);

            // Handle banner image upload
            $this->handleBannerImages($request, $serviceProvider->id);

            // Log the event if usertype is 2
            $this->logEvent("Update Service Provider", $serviceProvider->id);

            echo 1;
        }
    }

    private function isDuplicate($request)
    {
        // Check for duplicates (email, phone, name, name_ar)
        $checkEmail = ServiceProvider::where('email', $request->email)->exists();
        $checkPhone = ServiceProvider::where('contact', $request->mobile)->exists();
        $checkName = ServiceProvider::where('name', $request->name)->exists();
        $checkNameAr = ServiceProvider::where('name_ar', $request->namear)->exists();

        if ($checkEmail) {
            echo "2";
            return true;
        }

        if ($checkPhone) {
            echo "3";
            return true;
        }

        if ($checkName) {
            echo "4";
            return true;
        }

        if ($checkNameAr) {
            echo "4";
            return true;
        }

        return false;
    }

    private function processImageUpload($request, $inputName, $resizeName)
    {
        if ($request->hasFile($inputName)) {
            $new_width = 100;
            $new_height = 100;
            $file = $request->file($inputName);
            $fileName = $file->getRealPath();
            $uploadPath = public_path('images/');
            $fileExt = $file->getClientOriginalExtension();
            return ExternalSystem::saveresizeimage($new_width, $new_height, $fileName, $uploadPath, $fileExt, $resizeName);
        }
        return '';
    }

    private function getServiceProviderData($request, $imageName, $tagIcon)
    {
        return [
            'name' => $request->name,
            'name_ar' => $request->namear,
            'logo' => $imageName,
            'description' => $request->info,
            'description_ar' => $request->infoar,
            'email' => $request->email,
            'contact' => $request->mobile,
            'website' => $request->website,
            'youtube' => $request->youtube,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'snap_chat' => $request->snapchat,
            'tag_icon' => $tagIcon,
            'tag_name' => $request->tag_name,
            'tag_name_ar' => $request->tag_namear,
            'status' => 1,
            'position' => 1,
            'log' => 0,
        ];
    }

    private function handleCategories($request, $serviceProviderId)
    {
        foreach ($request->category as $categoryvalue) {
            $serviceProviderCategoryList = new ServiceProviderCategorylist;
            $serviceProviderCategoryList->serviceproviderID = $serviceProviderId;
            $serviceProviderCategoryList->categoryID = $categoryvalue;
            $serviceProviderCategoryList->save();
        }
    }

    private function handleServices($request, $serviceProviderId)
    {
        foreach ($request->service as $servicevalue) {
            $serviceProviderServiceList = new ServiceProviderserviceslist;
            $serviceProviderServiceList->serviceproviderID = $serviceProviderId;
            $serviceProviderServiceList->type = 1;
            $serviceProviderServiceList->serviceID = $servicevalue;
            $serviceProviderServiceList->save();
        }
    }

    private function handleBannerImages($request, $serviceProviderId)
    {
        if (!empty($request->images)) {
            $i = 1;
            foreach ($request->images as $imagevalue) {
                // Upload images
                $new_width = 720;
                $new_height = 480;
                $fileName = $imagevalue->getRealPath();
                $uploadPath = public_path('images/');
                $fileExt = $imagevalue->getClientOriginalExtension();
                $imageNames = ExternalSystem::saveresizeimage($new_width, $new_height, $fileName, $uploadPath, $fileExt, $i . "sponthump_");

                $bannerImage = new AllBannerImage;
                $bannerImage->bannerimageID = $serviceProviderId;
                $bannerImage->image = $imageNames;
                $bannerImage->bannertype = 6;
                $bannerImage->save();
                $i++;
            }
        }
    }

    private function updateCategories($request, $serviceProviderId)
    {
        $currentCategories = ServiceProviderCategorylist::where('serviceproviderID', $serviceProviderId)->pluck('categoryID')->toArray();
        $newCategories = $request->category;

        // Insert new categories
        foreach ($newCategories as $categoryvalue) {
            if (!in_array($categoryvalue, $currentCategories)) {
                $serviceProviderCategoryList = new ServiceProviderCategorylist;
                $serviceProviderCategoryList->serviceproviderID = $serviceProviderId;
                $serviceProviderCategoryList->categoryID = $categoryvalue;
                $serviceProviderCategoryList->save();
            }
        }

        // Remove deleted categories
        $result = array_diff($currentCategories, $newCategories);
        foreach ($result as $rval) {
            ServiceProviderCategorylist::where('categoryID', $rval)->where('serviceproviderID', $serviceProviderId)->delete();
        }
    }

    private function updateServices($request, $serviceProviderId)
    {
        $currentServices = ServiceProviderserviceslist::where('serviceproviderID', $serviceProviderId)->pluck('serviceID')->toArray();
        $newServices = $request->service;

        // Insert new services
        foreach ($newServices as $servicevalue) {
            if (!in_array($servicevalue, $currentServices)) {
                $serviceProviderServiceList = new ServiceProviderserviceslist;
                $serviceProviderServiceList->serviceproviderID = $serviceProviderId;
                $serviceProviderServiceList->type = 1;
                $serviceProviderServiceList->serviceID = $servicevalue;
                $serviceProviderServiceList->save();
            }
        }

        // Remove deleted services
        $result = array_diff($currentServices, $newServices);
        foreach ($result as $rval) {
            ServiceProviderserviceslist::where('serviceID', $rval)->where('serviceproviderID', $serviceProviderId)->delete();
        }
    }

    private function logEvent($eventName, $serviceProviderId)
    {
        if (session::get('usertype') == 2) {
            $log = [
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => $eventName,
                'event_category' => 15,
                'event_id' => $serviceProviderId,
            ];
            StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function delete_serviceprovider(Request $request){
        $id = $request->id;
        $category = ServiceProvider::find($id);
        $category->status = 2;
        $category->save();
        $this->logEvent("Delete Service Provider", $id);

    }
    public function change_service_provider_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = ServiceProvider::find($id);
        $category->status = $status;
        $category->save();
        $this->logEvent("Change Service Provider Status", $id);

    }
    public function updateserviceproviderOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = ServiceProvider::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->update();

        }
        $this->logEvent("Service Provider order updated",0);
    }

    //branch
    public function service_provider_branch(Request $request){
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovider";
        $data['id'] = $request->key;
        $admin_id = session::get('id');
        $data['title'] = "Service Provider Branch";
        $data['data']=ServiceProviderBranch::where('serviceproviderID', base64_decode($request->key))->get();
        $data['admin']=Admin::find($admin_id);

        return view('admin.service_provider_branch', $data);
    }

    public function add_service_provider_branch(Request $request){
        $data['active'] = "serviceprovider";
        $data['active1'] = "serviceprovider";
        $admin_id = session::get('id');
        $data['title'] = "Branch";
        $data['agentid'] = base64_decode($request->key);
        $branchid = base64_decode($request->id);
        $data['admin']=Admin::find($admin_id);
        //$data['service'] = ServiceProvideService::where('status', 1)->get();
        $data['country'] = Country::where('status',1)->get();
        $data['ServiceProviderserviceslist'] = ServiceProviderserviceslist::where('serviceproviderID', base64_decode($request->key))->where('type', 1)->get();
        if($branchid!='') {
        $data['data'] = ServiceProviderBranch::where('id',$branchid)->first();
        $data['state'] = State::where('country_id',$data['data']->country)->get();
        $data['city'] = City::where('state_id',$data['data']->state)->get();
        $data['servicebranch'] = ServiceProviderserviceslist::where('serviceproviderID',$branchid)->where('type', 2)->get();
        }
        return view('admin.addService_provider_branch', $data);
    }

    // public function getState(Request $request){
    //     $country_id = $request->country_id;
    //     $state = State::where('country_id',$country_id)->get();
    //     foreach($state as $value){
    //         echo "<option value='".$value->id."'>".$value->name."</option>";
    //     }
    // }

    // public function getCity(Request $request){
    //     $state_id = $request->state_id;
    //     $city = City::where('state_id',$state_id)->get();
    //     foreach($city as $value){
    //         echo "<option value='".$value->id."'>".$value->name."</option>";
    //     }
    // }

    public function insert_service_provider_branch(Request $request){
       // Google Maps API Key
    //     $GOOGLE_API_KEY = 'AIzaSyA_VxG35IaFz_h_F0G_786p77XvwRKG_WM';

    //     // Address from which the latitude and longitude will be retrieved
    //     $address = 'White House, Pennsylvania Avenue Northwest, Washington, DC, United States';

    //     // Formatted address
    //     $formatted_address = str_replace(' ', '+', $address);

    //     // Get geo data from Google Maps API by address
    //     $geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$formatted_address}&key={$GOOGLE_API_KEY}");

    //     // Decode JSON data returned by API
    //     $apiResponse = json_decode($geocodeFromAddr);
    //     print_r($apiResponse);

    //    // Retrieve latitude and longitude from API data
    //     $latitude  = $apiResponse->results[0]->geometry->location->lat;
    //     $longitude = $apiResponse->results[0]->geometry->location->lng;
    //     die;
        if($request->image!=''){
            $new_width = 720;
            $new_height = 480;
            $file = $request->file('image');
            $fileName = $file->getRealPath();
            $uploadPath = public_path('images/');
            $fileExt = $file->getClientOriginalExtension();
            $imgname = "thump_";
            $image = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
            // $image = 't'.time().'.'.$request->image->extension();
            // $request->image->move(public_path('images'), $image);
        }else{
            $image='';
        }
        if($request->id==""){

            $check_name = ServiceProviderBranch::where('name',$request->name)->count();
            if($check_name==0){
                $insert = new ServiceProviderBranch;
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                $insert->agentID = $request->agentid;
                $insert->servicetypeID = 0;
                $insert->contact = $request->mobile;
                $insert->start_day = $request->start_day;
                $insert->end_day = $request->end_day;
                $insert->start_time = $request->start_time;
                $insert->end_time = $request->end_time;
                $insert->country = $request->country;
                $insert->state = $request->state;
                $insert->city = $request->city;
                $insert->address = $request->address;
                $insert->image = $image;
                $insert->email = $request->email;
                $insert->latitude = 0;
                $insert->longitude = 0;
                $insert->status = 1;
                $insert->save();
                // //BranchBrand
                // foreach($request->brands as $val){
                //     $agentBrand = new BranchBrand;
                //     $agentBrand->branchId = $insert->id;
                //     $agentBrand->agentId = $request->agentid;
                //     $agentBrand->brandId = $val;
                //     $agentBrand->save();
                // }
                //Branch Services
                // foreach($request->servicetypeID as $serval){
                //     $ServiceBranch = new ServiceBranch;
                //     $ServiceBranch->branchId = $insert->id;
                //     $ServiceBranch->agentId = $request->agentid;
                //     $ServiceBranch->serviceID = $serval;
                //     $ServiceBranch->save();
                // }

                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Branch in an agent",
                        'event_category'=>16,
                        'event_id'=>$insert->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = ServiceProviderBranch::where('name',$request->name)->where('id',$request->id);
            if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                echo 2;
            }else{

                $insert = new ServiceProviderBranch;
                $insert = ServiceProviderBranch::find($request->id);
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                $insert->agentID = $request->agentid;
                $insert->servicetypeID = 0;
                $insert->contact = $request->mobile;
                $insert->email = $request->email;
                $insert->start_day = $request->start_day;
                $insert->end_day = $request->end_day;
                $insert->start_time = $request->start_time;
                $insert->end_time = $request->end_time;
                $insert->country = $request->country;
                $insert->state = $request->state;
                $insert->city = $request->city;
                $insert->address = $request->address;
                if($image!=""){
                    $insert->image = $image;
                }
                $insert->latitude = 0;
                $insert->longitude = 0;
                $insert->save();
                //branchbrand
                // $brandagent = BranchBrand::select('brandId')->where('branchId',$request->id)->get();
                // $brandarray = array();
                // foreach($brandagent as $val){
                //     array_push($brandarray,$val->brandId);
                // }
                // $result=array_diff($brandarray,$request->brands);
                // foreach($request->brands as $valb){
                //     $checkbrandid = BranchBrand::where('brandId',$valb)->where('branchId',$request->id)->count();
                //     if($checkbrandid==0){
                //         $agentBrand = new BranchBrand;
                //         $agentBrand->branchId = $request->id;
                //         $agentBrand->agentId = $request->agentid;
                //         $agentBrand->brandId = $valb;
                //         $agentBrand->save();
                //     }
                // }
                // foreach($result as $rval){
                //     BranchBrand::where('brandId',$rval)->where('branchId',$request->id)->delete();
                // }
                // //Branch Services
                // $branchservice = ServiceBranch::select('serviceID')->where('branchId',$request->id)->get();
                // $branchservicearray = array();
                // foreach($branchservice as $vals){
                //     array_push($branchservicearray,$vals->serviceID);
                // }
                // $results=array_diff($branchservicearray,$request->servicetypeID);
                // foreach($request->servicetypeID as $valbs){
                //     $checkserviceid = ServiceBranch::where('serviceID',$valbs)->where('branchId',$request->id)->count();
                //     if($checkserviceid==0){
                //         $ServiceBranch = new ServiceBranch;
                //         $ServiceBranch->branchId = $request->id;
                //         $ServiceBranch->agentId = $request->agentid;
                //         $ServiceBranch->serviceID = $valbs;
                //         $ServiceBranch->save();
                //     }
                // }
                // foreach($results as $rvals){
                //     ServiceBranch::where('serviceID',$rvals)->where('branchId',$request->id)->delete();
                // }
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Branch in an agent",
                        'event_category'=>16,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                echo 1;
            }

        }
    }

    public function change_service_provider_branch_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $data = ServiceProviderBranch::find($id);
        $data->status = $status;
        $data->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Branch Status",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function delete_service_provider_branch(Request $request){
        $id = $request->id;
        $category = ServiceProviderBranch::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Branch",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
}
