<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\WelcomeImage;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\ServiceType;
use App\Models\Deal;
use App\Models\Agent;
use App\Models\User;
use App\Models\ImageSetting;
use App\Models\InternalAppPage;
use App\Models\AccessRight;
use App\Models\StaffLog;
use App\Models\StaffLogEvent;
use App\Models\CarCheck;
use App\Models\CarCheckList;
use App\Models\IntroVideo;
use Illuminate\Support\Facades\Session as session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use App\Modules\ExternalSystem;
use Google\Service\CloudSourceRepositories\Repo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function login(){
        return view('admin.login');
    }

    public function admin_login(Request $request){
        $username = $request->username;
        $password = md5($request->password);
        $check = Admin::where('username',$username)->where('password',$password);
        if($check->count()!=0)
        {
            $row= $check->first();
            $adminUser =  $row->username;
            $adminPassword =  $row->password;
            $adminName = $row->name;
            $request->session()->put('adminUser', $adminUser);
            $request->session()->put('adminPassword', $adminPassword);
            $request->session()->put('gearzapp_admin_user_id',$row->id);
            $request->session()->put('gearzapp_admin_user_type',$row->userType);
            session::put('id',$row->id);
            session::put('name',$row->name);
            session::put('email',$row->email);
            session::put('profile_img',$row->profile_img);
            session::put('password', $row->password);
            session::put('usertype', $row->userType);
            Session::flash('message', 'You are logged in successfully.');
            $user_rights = Admin::where('id',$row->id)->first()->access_right;
            $user_rights = explode(',',$user_rights);
            $rights = AccessRight::select('id','route')->whereIn('id',$user_rights)->where('management_section_id','!=',0)->get()->toArray();
            if($row->userType==2){
                //add log
                $StaffLog = new StaffLog;
                $StaffLog->staff_id = $row->id;
                $StaffLog->logindatetime = date('Y-m-d H:i:s');
                $StaffLog->save();

                return redirect($rights[0]['route']);
            }else{
                return redirect('admin/dashboard');
            }

        }else{
            Session::flash('message','Wrong username or password.');
            return redirect('/admin');
        }
    }

    public function dashboard()
    {
        try {
            $admin_id = session::get('id');

            if ($admin_id == "" || $admin_id == null) {
                session::flush();
                return redirect('/admin');
            }

            $data['admin'] = Admin::find($admin_id);
            $data['active'] = "dashboard";

            // Totals
            $data['brand']    = Brand::where('status', 1)->count();
            $data['car']      = Vehicle::where('status', 1)->count();
            $data['category'] = Category::where('status', 1)->count();
            $data['service']  = ServiceType::where('status', 1)->count();
            $data['agent']    = Agent::where('status', 1)->count();
            $data['deals']    = Deal::where('status', 1)->count();
            $data['user']     = User::where('status', 1)->count();

            return view('admin.dashboard', $data);

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());

            return redirect('/admin')->with('error', 'Something went wrong while loading the dashboard.');
        }
    }

    public function logout(){
        //add log
        $admin_id = session::get('id');
        $check_login = StaffLog::where('staff_id',$admin_id)->skip(0)->take(1);
        if($check_login->count()!=0){
            $StaffLog = StaffLog::find($check_login->first()->id);
            $StaffLog->logoutdatetime = date('Y-m-d H:i:s');
            $StaffLog->save();
        }

        session::flush();
        return redirect('/admin');
    }

    public function profile($admin_id){
        $adminId = base64_decode($admin_id);
        $data['admin']=Admin::find($adminId);
        return view('admin.profile', $data);
    }
    public function update_profile(Request $request){
        // print_r($request->all());die;
        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');
            $profile_image = time().'.'.$image->getClientOriginalExtension();
            $image->move("public/img/", $profile_image);
            $profile_image = $profile_image;
        }else{
            $profile_image = $request->pre_profile_img;
        }
        $admin = Admin::find($request->id);
        $admin->image = $profile_image;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->save();
        Session::flash('message', 'Profile updated successfully.');
        return redirect('/admin/profile/'.base64_encode($request->id));
    }

    public function banners(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "banner";
        $data['active1'] = "banners";
        $data['banners']=Banner::orderBy('position_id', 'asc')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.banners', $data);
    }

    public function addBanner(Request $request){
        $data['active'] = "banner";
        $data['active1'] = "banners";
        if($request->id){
            $data['banner']=Banner::find($request->id);
        }
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['InternalAppPage']=InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.addBanner', $data);
    }

    public function insertBanner(Request $request){

        $validator = Validator::make($request->all(),
            [
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=4/3',
                'name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ], [
                'image.dimensions' => 'Image must have a 4:3 aspect ratio.',
                'image.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }else{
        
            if($request->hasFile('image')){
                // $new_width = 720;
                // $new_height = 480;
                // $file = $request->file('image');
                // $fileName = $file->getRealPath();
                // $uploadPath = public_path('img/banners/');
                // $fileExt = $file->getClientOriginalExtension();
                // $imgname = "thump_";
                // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                $image = $request->file('image');
                $filename = time().'.'.$image->getClientOriginalExtension();
                $image->move('public/img/banners', $filename);
                $imageName = $filename;
            }else if($request->banner_id){

                    $imageName = $request->pre_image;

            }else{
                $imageName = "";
            }
            if($request->inlineRadioOptions==2){
                $page = $request->page;
            }else{
                $page = $request->url;
            }
            if($request->banner_id){
                $banner = Banner::find($request->banner_id);
                $banner->image = $imageName;
                $banner->name = $request->name;
                $banner->name_ar = $request->namear;
                $banner->url = $page;
                $banner->start_date = $request->start_date;
                $banner->end_date = $request->end_date;
                $banner->internal_external = $request->inlineRadioOptions;
                $banner->update();
                echo "update";
                if(session::get('usertype')==2){
                    //log
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Updated Banner",
                        'event_category'=>8,
                        'event_id'=>$request->banner_id,
                    );
                // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner updated successfully.');
            }else{
                $banner = new Banner;
                $banner->image = $imageName;
                $banner->name = $request->name;
                $banner->name_ar = $request->namear;
                $banner->url = $page;
                $banner->start_date = $request->start_date;
                $banner->end_date = $request->end_date;
                $banner->internal_external = $request->inlineRadioOptions;
                $banner->save();
                echo "add";
                if(session::get('usertype')==2){
                    //log
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Added Banner",
                        'event_category'=>8,
                        'event_id'=>$banner->id,
                    );
                // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner added successfully.');
            }
        }

    }

    public function intro_video(Request $request){
        
        $data['active'] = "banner";
        $data['active1'] = "intro_video";
        $data['intro_video']=IntroVideo::find(1);
        $admin_id = session::get('id');
        $data['admin']=Admin::where('id',$admin_id)->first();
        return view('admin.intro_video', $data);
    }

    
    public function updateIntroVideo(Request $request)
    {   
        
        $validator = Validator::make($request->all(), [
            'video' => 'nullable|mimes:mp4,avi,mov,mpeg,webm|max:2048', // 2MB
        ], [
            'video.max' => 'The video must not be greater than 2MB.',
            'video.mimes' => 'Only video formats like mp4, avi, mov, mpeg, webm are allowed.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first('video')], 422);
        }

        try {
            $isRepeat = $request->has('is_repeat') ? 1 : 0;
            $isSkipable = $request->has('is_skipable') ? 1 : 0;
            $uploadPath = public_path('video/intro_video'); 

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $videoName = $request->pre_video; 

            if ($request->hasFile('video')) {
                if (!empty($request->pre_video)) {
                    $oldVideoPath = $uploadPath . '/' . $request->pre_video;
                    if (File::exists($oldVideoPath)) {
                        File::delete($oldVideoPath);
                    }
                }

                $file = $request->file('video');
                $filename = 'intro_video_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $videoName = $filename;
            }
            
            $intro_video = IntroVideo::find($request->intro_video_id);
            $intro_video->video = $videoName;
            $intro_video->is_repeat = $isRepeat;
            $intro_video->is_skipable = $isSkipable;
            $intro_video->skip_time = $request->skip_time;
            $intro_video->save();

            echo "update";
        } catch (\Exception $e) {
            \Log::error('Intro video update error: ' . $e->getMessage());
            echo "error";
        }
    }

    public function welcome_images(){
        $data['active'] = "banner";
        $data['active1'] = "welcome_images";
        $admin_id = session::get('id');
        $data['welcome_images']=WelcomeImage::orderBy('position_id', 'asc')->get();
        $data['admin']=Admin::where('id',$admin_id)->first();
        return view('admin.welcome_images', $data);
    }

    public function add_welcome_image(Request $request){
        $data['active'] = "banner";
        $data['active1'] = "welcome_images";
        if($request->id){
            $data['welcome_image']=WelcomeImage::find($request->id);
        }
        $admin_id = session::get('id');
        $data['admin']=Admin::where('id',$admin_id)->first();
        $data['InternalAppPage']=InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.add_welcome_image', $data);
    }

    public function insertWelcomeImage(Request $request){
        // $validator = Validator::make($request->all(),
        //     [
        //         //'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=500,max_height=500',
        //         'name' => 'required',
        //         'start_date' => 'required',
        //         'end_date' => 'required',
        //     ]
        // );
        // if ($validator->fails()){
        //     $errors = $validator->errors();
        //     echo $errors;
        // }
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=9/18',
        ], [
            'image.dimensions' => 'Image must have a 9:18 aspect ratio.',
            'image.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'image.max' => 'Image must not exceed 2MB.',
            'image.image' => 'File must be a valid image.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }else{
            if($request->hasFile('image')){
                // $new_width = 1179;
                // $new_height = 900;
                // $file = $request->file('image');
                // $fileName = $file->getRealPath();
                // $uploadPath = public_path('img/welcome_images/');
                // $fileExt = $file->getClientOriginalExtension();
                // $imgname = "thump_";
                // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                $image = $request->file('image');
                $filename = time().'.'.$image->getClientOriginalExtension();
                $image->move('public/img/welcome_images', $filename);
                $imageName = $filename;
            }else{
                if($request->welcome_image_id){
                    $imageName = $request->pre_image;
                }
            }
            if($request->inlineRadioOptions==2){
                $page = $request->page;
            }else{
                $page = $request->url;
            }
            if($request->welcome_image_id){
                $welcome_image = WelcomeImage::find($request->welcome_image_id);
                $welcome_image->image = $imageName;
                $welcome_image->name = $request->name;
                $welcome_image->name_ar = $request->namear;
                $welcome_image->url = $page;
                $welcome_image->discription = $request->info;
                $welcome_image->discription_ar = $request->infoar;
                $welcome_image->start_date = $request->start_date;
                $welcome_image->end_date = $request->end_date;
                $welcome_image->image_time = $request->image_time;
                $welcome_image->skipable = $request->skip;
                $welcome_image->internal_external = $request->inlineRadioOptions;
                $welcome_image->sliding = 0;
                $welcome_image->update();
                echo "update";
                if(session::get('usertype')==2){
                    //log
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Updated Welcome Image",
                        'event_category'=>9,
                        'event_id'=>$request->welcome_image_id,
                    );
                // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                $welcome_image = new WelcomeImage;
                $welcome_image->image = $imageName;
                $welcome_image->name = $request->name;
                $welcome_image->name_ar = $request->namear;
                $welcome_image->url = $page;
                $welcome_image->discription = $request->info;
                $welcome_image->discription_ar = $request->infoar;
                $welcome_image->start_date = $request->start_date;
                $welcome_image->end_date = $request->end_date;
                $welcome_image->image_time = $request->image_time;
                $welcome_image->skipable = $request->skip;
                $welcome_image->internal_external = $request->inlineRadioOptions;
                $welcome_image->save();
                echo "add";
                if(session::get('usertype')==2){
                    //log
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Added Welcome Image",
                        'event_category'=>9,
                        'event_id'=>$welcome_image->id,
                    );
                // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }
        }

    }
    public function update_status($id, $table){
        $model = 'App\Models\\'.$table;
        $item = $model::find($id);
        if($item->status == 1){ $update=0; }else if($item->status == 0){ $update=1; }
        $model::where('id', $id)->update([ 'status' => $update ]);
        return redirect()->back()->with('message', 'Status changed successfully.');
    }

    public function delete($id, $table){
        $model = 'App\Models\\'.$table;
        $model::destroy($id);
        // $item = $model::find($id);
        // $item->delete();
        return redirect()->back()->with('message', 'Deleted successfully.');
    }

    public function updateBannerOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $banner = Banner::find($arr[$i]);
            $banner->position_id = $num=$num+1;
            $banner->save();
        }
        if(session::get('usertype')==2){
            //log
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Updated Banner Order",
                'event_category'=>8,
                'event_id'=>0,
            );
        // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function updateBannerOrders(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $banner = WelcomeImage::find($arr[$i]);
            $banner->position_id = $num=$num+1;
            $banner->save();
        }
        if(session::get('usertype')==2){
            //log
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Updated Welcome Image Order",
                'event_category'=>9,
                'event_id'=>0,
            );
        // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function countryList(Request $request){

        // try {
        //     // Drop the table if exists
        //     DB::statement('DROP TABLE IF EXISTS car_favorites');

        //     // Read the SQL file contents
        //     $sql = Storage::disk('local')->get('sql/car_favorites.sql');

        //     // Split and run each SQL statement
        //     $statements = array_filter(array_map('trim', explode(';', $sql)));

        //     foreach ($statements as $statement) {
        //         if (!empty($statement)) {
        //             DB::statement($statement);
        //         }
        //     }

        //     return response()->json(['success' => true, 'message' => 'States table imported successfully.']);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'message' => 'Error importing states: ' . $e->getMessage()]);
        // }
        $admin_id = session::get('id');
        $data['title'] = "Country";
        $data['active'] = "setting";
        $data['active1'] = "countryList";
        $data['data'] = Country::where('status','!=',2)->orderby('name','ASC')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.countrylist', $data);
    }
    public function change_country_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Country::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            //log
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Country Status",
                'event_category'=>23,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function stateList(Request $request){
        $admin_id = session::get('id');
        $data['title'] = "State";
        $data['active'] = "setting";
        $data['active1'] = "countryList";
        $countryid = base64_decode($request->key);
        $data['data'] = State::where('country_id',$countryid)->where('status',1)->orderby('name','ASC')->get();

        //$data['data']=City::where('status',1)->orderBy('name','ASC')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.statelist', $data);
    }

    public function cityList(Request $request){
        $admin_id = session::get('id');
        $data['title'] = "City";
        $data['active'] = "setting";
        $data['active1'] = "countryList";
        $stateid = base64_decode($request->key);
        $countryid = base64_decode($request->id);
        $data['stateid'] = base64_decode($request->key);
        $data['countryid'] = base64_decode($request->id);
        if($stateid==""){
            $data['data'] = City::where('countryid',$countryid)->where('status',1)->orderby('name','ASC')->orderby('popular','DESC')->get();
        }else{
            $data['data'] = City::where('state_id',$stateid)->where('status',1)->orderby('name','ASC')->orderby('popular','DESC')->get();
        }


        //$data['data']=City::where('status',1)->orderBy('name','ASC')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.citylist', $data);
    }

    public function delete_city(Request $request){
        $id = $request->id;
        $category = City::find($id);
        $category->status = 0;
        $category->save();
        if(session::get('usertype')==2){
            //log
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete City",
                'event_category'=>23,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_city_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = City::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            //log
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change City",
                'event_category'=>23,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_banner_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Banner::find($id);
        $category->status = $status;
        $category->save();
        //log
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Banner Status",
                'event_category'=>8,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_welcomeimage_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = WelcomeImage::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Welcome Image Status",
                'event_category'=>9,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function image_setting(Request $request){
        $admin_id = session::get('id');
        $data['title'] = "Image Setting";
        $data['active'] = "banner";
        $data['active1'] = "image_setting";
        $data['data']=ImageSetting::where('id',1)->first();
        $data['admin']=Admin::find($admin_id);
        return view('admin.image_setting', $data);
    }

    public function image_setting_insert(Request $request){
        if($request->hasFile('image')){
            $new_width = 1242;
            $new_height = 2689;
            $file = $request->file('image');
            $fileName = $file->getRealPath();
            $uploadPath = public_path('img/welcome_images/');
            $fileExt = $file->getClientOriginalExtension();
            $imgname = "thump_";
            $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
            // $image = $request->file('image');
            // $filename = time().'.'.$image->getClientOriginalExtension();
            // $image->move('public/img/welcome_images/', $filename);
            // $imageName = $filename;
        }else{
            $imageName = "";
        }
        $image_setting = ImageSetting::find(1);
        $image_setting->backgroundimage = $imageName;
        $image_setting->save();
        echo "update";
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Welcome Image BackGround",
                'event_category'=>9,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }


    public function add_popular_city(Request $request){
        // $checkpop = City::where('popular',1)->count();
        // if($checkpop==0){
        //     $position = 1;
        // }else{
        //     $pop = City::where('popular',1)->limit(1)->orderBy('name','ASC')->first();
        //     $position = $pop->position+1;
        // }
        $id = $request->id;
        $status = $request->status;
        $category = City::find($id);
        $category->popular = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Added popular city in a list",
                'event_category'=>23,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function addCity(Request $request){
        $admin_id = session::get('id');
        $data['title'] = "City";
        $data['active'] = "setting";

        if($request->country==""){
            $country = State::where('id',base64_decode($request->id))->first()->country_id;
            $data['country_id'] = $country;
        }else{
            $country = base64_decode($request->country);
            $data['country_id'] = base64_decode($request->country);
        }

        $data['state'] = State::where('country_id',$country)->get();
        $data['country'] = Country::where('status',1)->get();
        $data['state_id'] = base64_decode($request->id);

        if($request->key){
            $data['data']=City::find(base64_decode($request->key));
            $countryid = State::where('id',$data['data']->state_id)->first()->country_id;

            $data['state'] = State::where('country_id',$countryid)->get();
            $data['countryid'] = $countryid;
        }
        $data['admin']=Admin::find($admin_id);
        return view('admin.addCity', $data);
    }

    public function insertCity(Request $request){
        if($request->id==""){
            $check_name = City::where('name',$request->name)->count();
            if($check_name==0){
                $insert = new City;
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                $insert->state_id = $request->state;
                $insert->countryid = $request->country;
                $insert->popular = 0;
                $insert->status = 1;
                $insert->updated_at = date('Y-m-d H:i:s');
                $insert->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add city",
                        'event_category'=>23,
                        'event_id'=>$insert->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = City::where('name',$request->name)->where('id',$request->id);
            if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                echo 2;
            }else{
                $insert = new City;
                $insert = City::find($request->id);
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                $insert->state_id = $request->state;
                $insert->countryid = $request->country;
                $insert->updated_at = date('Y-m-d H:i:s');
                $insert->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update city",
                        'event_category'=>23,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }

        }
    }

    // public function welcomeimagebackground(Request $request){
    //     $admin_id = session::get('id');
    //     $data['title'] = "Welcome Image Background";
    //     $data['active'] = "banner";
    //     $data['banners']=ImageSetting::where('id',1)->get();
    //     $data['admin']=Admin::find($admin_id);
    //     return view('admin.background', $data);
    // }
    public function permissiondenied(Request $request){
        $admin_id = session::get('id');;
        $data['admin']=Admin::find($admin_id);
        return view('admin.permissiondenied', $data);
    }

    public function CarCheck(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "master";
        $data['active1'] = "CarCheck";
        $data['data'] = CarCheck::where('status','!=', 2)->get();
        $data['admin'] = Admin::find($admin_id);
        return view('admin.carcheck', $data);
    }
    //carcheckdetail
    public function carcheckdetail(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "master";
        $data['active1'] = "CarCheck";
        $id = base64_decode($request->key);
        $data['language'] = base64_decode($request->lang);
        $data['data'] = CarCheck::where('id',$id)->first();
        $data['list'] = CarCheckList::where('carcheckID',$id)->where('status', 1)->get();
        $data['admin'] = Admin::find($admin_id);
        return view('admin.carcheckdetail', $data);
    }
    public function addCarCheck(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "master";
        $data['active1'] = "CarCheck";
        $data['data'] = CarCheck::where('id', base64_decode($request->key))->first();
        $data['list'] = CarCheckList::where('carcheckID',base64_decode($request->key))->where('status', 1)->get();
        $data['admin'] = Admin::find($admin_id);
        return view('admin.addCarCheck', $data);
    }
    public function insert_carcheck(Request $request){
        if($request->id == ""){
            $array1 = $request->checkname; // Dynamic size
            $array2 = $request->checknamear; // Dynamic size
            $result = [];
            $maxLength = max(count($array1), count($array2)); // Get the maximum length of the two arrays

            for ($i = 0; $i < $maxLength; $i++) {
                $result[] = [
                    'array1_value' => $array1[$i] ?? null, // Use null if index doesn't exist
                    'array2_value' => $array2[$i] ?? null, // Use null if index doesn't exist
                ];
            }
            $carcheck = new CarCheck;
            $carcheck->name = $request->name;
            $carcheck->name_ar = $request->namear;
            $carcheck->content = "";
            $carcheck->content_ar = "";
            $carcheck->status = 1;
            $carcheck->save();
            foreach($result as $value){
                $list = new CarCheckList;
                $list->carcheckID = $carcheck->id;
                $list->name = $value['array1_value'];
                $list->name_ar = $value['array2_value'];
                $list->status = 1;
                $list->save();
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Add Car Check",
                    'event_category'=>24,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }else{
            //print_r($request->checklistid);
            //print_r($request->checkname);
            //print_r($request->checknamear);
            $array1 = $request->checkname; // Dynamic size
            $array2 = $request->checknamear; // Dynamic size
            $array3 = $request->checklistid; // Dynamic size
            $result = [];
            $maxLength = max(count($array1), count($array2), count($array3)); // Get the maximum length of the two arrays

            for ($i = 0; $i < $maxLength; $i++) {
                $result[] = [
                    'array1_value' => $array1[$i] ?? null, // Use null if index doesn't exist
                    'array2_value' => $array2[$i] ?? null, // Use null if index doesn't exist
                    'array3_value' => $array3[$i] ?? null, // Use null if index doesn't exist
                ];
            }
            //print_r($result);
            $carcheck = CarCheck::find($request->id);
            $carcheck->name = $request->name;
            $carcheck->name_ar = $request->namear;
            //$carcheck->content = $request->info;
            //$carcheck->content_ar = $request->infoar;
            $carcheck->save();
            foreach($result as $value){
                if($value['array3_value']==""){
                    $list = new CarCheckList;
                    $list->carcheckID = $request->id;
                    $list->name = $value['array1_value'];
                    $list->name_ar = $value['array2_value'];
                    $list->status = 1;
                    $list->save();
                }else{
                    $list = CarCheckList::find($value['array3_value']);
                    $list->carcheckID = $request->id;
                    $list->name = $value['array1_value'];
                    $list->name_ar = $value['array2_value'];
                    $list->status = 1;
                    $list->save();
                }

            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Car Check",
                    'event_category'=>24,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }

    }

    public function delete_carChecklist(Request $request){
        $id = $request->id;
        $category = CarCheckList::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Car Checklist",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function delete_carCheck(Request $request){
        $id = $request->id;
        $category = CarCheck::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Car Check",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_carcheck_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = CarCheck::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Car Check Status",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function privacyPolicy(Request $request){
        return view('app.privacy');
    }

    public function countrystatecityupdate(){
        //$this->stringtranslation($resvehicle->shortBodyName);
        $countries = City::where('status',1)->orderby('name','ASC')->get();
        foreach($countries as $country_value){
            $country = City::find($country_value->id);
            $country->name_ar =  $this->stringtranslation($country_value->name);
            $country->save();
            // $allstate = State::where('country_id', $country_value->id)->get();
            // foreach($allstate as $state_value){
            //     $state = State::find($state_value->id);
            //     $state->name_ar = $this->stringtranslation($state_value->name);
            //     $state->save();

            // }
            // $stateIds = State::where('country_id', $country_value->id)->pluck('id');
            // City::whereIn('state_id', $stateIds)->update(['countryid' => $countryId]);
            // City::whereIn('state_id', $stateIds)->update(['countryid' => $countryId]);
        }
        echo 1;
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

    public function fetchMerchandise()
    {
        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbnRlcnByaXNlSWQiOiJjZTlmZGU1MmUiLCJ0ZWFtSWQiOiI1ZTE5MDkwYjNjIiwidXNlcklkIjoiMTYxMGE3MmUiLCJzZWNyZXRLZXkiOiJhNmQ1YTAyMmEyNDQ0Zjc2OTgzMTJiNGY5Yjc4M2ZkOCIsImlhdCI6MTc1MzQyOTQwOCwiZXhwIjoxODQ4MDM3NDA4fQ.y9apGcx6CoMeOOsQPaZMI4dMv0pzQHpN52ScbNc_BPE';

        $payload = [
            'stockNumber' => 'STOCK_ABARTH500ESSEESSE2008',
            "dealerVinId" => "dfda6806-f73b-4398-bfea-4b9a15c10da0",  // Your custom reference ID
            "webhookUrl" => "http://localhost/gearz/Webhook.php"
        ];

        $query = http_build_query($payload);
        $retrieveEndpoint = "https://api.spyne.ai/api/pv1/merchandise?$query";
			
    try {

        $response = Http::timeout(180)
					->withToken($apiKey)
					->get($retrieveEndpoint);

        // $response = Http::withHeaders([
        //         'accept' => 'application/json',

        //     ])
        //                 ->timeout(30)
        //                 ->get('https://api.spyne.ai/api/pv1/merchandise');

        if ($response->successful()) {
            return response()->json($response->json()); // returns parsed JSON
        } else {
            return response()->json([
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body()
            ], $response->status());
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Request exception',
            'message' => $e->getMessage()
        ], 500);
    }
}

}
