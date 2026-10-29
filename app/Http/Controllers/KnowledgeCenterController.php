<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\User;
use App\Models\Advice;
use App\Models\SponserBanner;
use App\Models\MyCar;
use App\Models\Country;
use App\Models\AdviceReview;
use App\Models\AdviceLike;
use App\Models\Translation;
use App\Models\KnowledgeImageSection;
use App\Models\StaffLogEvent;
use App\Models\AdviceNotificationUser;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use Google\Client as GoogleClient;
use App\Modules\ExternalSystem;
use App\Models\AllBannerImage;

class KnowledgeCenterController extends Controller
{
    //addSponserBanner.blade.php
    //sponserbanners.blade.php
    private $serviceAccountFile;
    function __construct()
    {
       $this->serviceAccountFile = storage_path('gearz.json'); 
    }
    
    public function sponserBanners(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "advice";
        $data['active1'] = "sponserBanners";
        $data['banners']=SponserBanner::orderBy('position_id', 'asc')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.sponserbanners', $data);
    }

    public function addSponserBanner(Request $request){
        $data['active'] = "advice";
        $data['active1'] = "sponserBanners";
        if($request->id){
            $data['banner']=SponserBanner::find($request->id);
        }
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        return view('admin.addSponserBanner', $data);
    }

    public function insertSponserBanner(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=3/1',
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'image.dimensions' => 'Image must have a 3:1 aspect ratio.',
            'image.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'image.max' => 'Image must not exceed 2MB.',
            'image.image' => 'File must be a valid image.',
        ]);
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
            }else{
                if($request->banner_id){
                    $imageName = $request->pre_image;
                }
            }
            if($request->banner_id){
                $banner = SponserBanner::find($request->banner_id);
                $banner->image = $imageName;
                $banner->name = $request->name;
                $banner->name_ar = $request->namear;
                $banner->url = $request->url;
                $banner->start_date = $request->start_date;
                $banner->end_date = $request->end_date;
                $banner->update();
                echo "update";
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Sponser",
                        'event_category'=>20,
                        'event_id'=>$request->banner_id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner updated successfully.');
            }else{
                $banner = new SponserBanner;
                $banner->image = $imageName;
                $banner->name = $request->name;
                $banner->name_ar = $request->namear;
                $banner->url = $request->url;
                $banner->start_date = $request->start_date;
                $banner->end_date = $request->end_date;
                $banner->save();
                echo "add";
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Sponser",
                        'event_category'=>20,
                        'event_id'=>$banner->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner added successfully.');
            }
        }
        
    }

    public function deleteSponser($id, $table){
        $model = 'App\Models\\'.$table;
        $model::where('id',$id)->update(['status'=>0]);
        // $item = $model::find($id);
        // $item->delete();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Sponser",
                'event_category'=>20,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
        return redirect()->back()->with('message', 'Deleted successfully.');
    }

    public function updatesponserBannerOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $banner = SponserBanner::find($arr[$i]);
            $banner->position_id = $num=$num+1;
            $banner->save();
        } 
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Sponser Order",
                'event_category'=>20,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_sponserbanner_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = SponserBanner::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Sponser status",
                'event_category'=>20,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    
    //Advice
    //sendadvice.blade.php
    //advice.blade.php
    public function advice(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Gearz Advice";
        $data['active'] = "advice";
        $data['active1'] = "advice";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = Advice::whereIn('status',[1,0])->get();
        return view('admin.advice', $data);
    }

    public function sendadvice(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Gearz Advice";
        $data['active'] = "advice";
        $data['active1'] = "advice";
        $data['admin']=Admin::find($admin_id);
        $data['user'] = User::where('status',1)->get();
        $data['brand'] = Brand::where('status',1)->get();
        $data['country'] = Country::where('status',1)->get();
        $adviceID = base64_decode($request->key);
        if(isset($request->key)){
            $data['data'] = Advice::where('id',$adviceID)->first();
            $data['brandbanner'] = AllBannerImage::where('bannertype',3)->where('bannerimageID',$adviceID)->get();
            $alluser = AdviceNotificationUser::where('notifytype',1)->where('notifyID',$adviceID);
            if($alluser->count()!=0){
                $data['brands'] = $alluser->value('brandID');
                $data['gender'] = $alluser->value('gendervalue');
                $data['age'] = $alluser->value('agevalue');
                $data['countrys'] = $alluser->value('countryID');
                $data['state'] = $alluser->value('stateID');
                $data['city'] = $alluser->value('cityID');

            }else{
                $data['brands'] = "";
                $data['gender'] = "";
                $data['age'] = "";
                $data['countrys'] = "";
                $data['state'] = "";
                $data['city'] = "";
            }
            $data['allusers'] = $alluser->get();
        }
              
        return view('admin.sendadvice', $data);
    }

    public function getuserlist(Request $request){
        $type = $request->type;
        $value = $request->value;
        if($type==1){
            $user = User::where('status',1)->get();            
            echo "<option value='0'>All</option>";
            foreach($user as $value){
                echo "<option value='".$value->id."'>".$value->name."</option>";
            }
        }else if($type==2){
            $brand = $request->brand;
            $mycar = MyCar::where('make_id',$brand)->get();
            $userarray = array();
            foreach($mycar as $carval){
                if(!in_array($carval->userID,$userarray)){
                    array_push($userarray,$carval->userID);
                } 
            }
            echo "<option value='0'>All</option>";
            foreach($userarray as $userval){
                $uval = User::where('id',$userval)->first();
                echo "<option value='".$uval->id."'>".$uval->name."</option>";
            }
        }else if($type==3){
            $age = $request->age;
            $user = User::where('status',1)->get();
            echo "<option value='0'>All</option>";
            foreach($user as $value){
                $userage = (date('Y') - date('Y',strtotime($value->dob)));
                if($userage==$age){
                    echo "<option value='".$value->id."'>".$value->name."</option>";
                }                
            }
        }else if($type==5){
            $city = $request->city; 
            $user = User::where('location',$city)->where('status',1)->get();           
            echo "<option value='0'>All</option>";
            foreach($user as $value){                
                echo "<option value='".$value->id."'>".$value->name."</option>";                               
            }
        }else if($type==4){
            $gender = $request->gender; 
            $user = User::where('gender',$gender)->where('status',1)->get();           
            echo "<option value='0'>All</option>";
            foreach($user as $value){                
                echo "<option value='".$value->id."'>".$value->name."</option>";                               
            }
        }        
    }
    public function insertadvice(Request $request){
        $validator = Validator::make($request->all(), [
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
        ], [
            'images.*.dimensions' => 'Each image must have a 16:9 aspect ratio.',
            'images.*.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'images.*.max' => 'Each image must not exceed 2MB.',
            'images.*.image' => 'Each file must be a valid image.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user = $request->user;
        $title = $request->name;
        $titlear = $request->namear;
        $message = $request->info;
        $messagear = $request->infoar;
        $type = $request->usertype;
        $url = $request->url;
        $image = $request->image;        
        $brand = $request->brand;
        $age = $request->age;
        $gender = $request->gender;
        $city = $request->city;
        $sponser_name = $request->sponser_name;
        $sponser_namear = $request->sponser_namear;
        $sponser_icon = $request->sponser_icon;
        $expire_date = $request->expire_date;
        $advicetype = $request->advicetype;
        $sendpush = $request->sendpush;             
        $imageName = "";
        if($request->sponser_icon!=''){
            $new_widths = 100;
            $new_heights = 100;
            $files = $request->file('sponser_icon');
            $fileNames = $files->getRealPath();
            $uploadPaths = public_path('images/');
            $fileExts = $files->getClientOriginalExtension();
            $imgnames = "sponthump_";
            $sponser_icon = ExternalSystem::saveresizeimage($new_widths,$new_heights,$fileNames,$uploadPaths,$fileExts,$imgnames);
        }else{
            $sponser_icon='';
        }
        $checkposition =  Advice::orderBy('position','DESC')->limit(1);
        if($checkposition->count()==0){
            $position = 1;
        }else{
            $position = $checkposition->value('position')+1;
        }
        if($request->id==""){

            $notification = new Advice;
            $notification->UserID = $advicetype; //advice type 1=Genral 2=Sponser 
            $notification->title = $title;
            $notification->message = $message;
            $notification->type = $type;
            $notification->url = $url;
            $notification->image = $imageName;
            $notification->sponser_name = $sponser_name;                    
            $notification->sponser_icon = $sponser_icon;
            $notification->status = 1;                    
            $notification->title_ar = $titlear;                    
            $notification->message_ar = $messagear;
            $notification->sponser_name_ar = $sponser_namear;
            $notification->expire_date = $expire_date;
            $notification->sendpush = $sendpush;
            $notification->position = $position;
            $notification->save();
            // banner
            if(!empty($request->images)){
                $i=1;
                foreach($request->images as $imagevalue){
                    //upload images
                    // $new_widthss = 720;
                    // $new_heightss = 480;
                    // $filess = $imagevalue;
                    // $fileNamess = $filess->getRealPath();
                    // $uploadPathss = public_path('images/');
                    // $fileExtss = $filess->getClientOriginalExtension();
                    // $imgnamess = $i."sponthump_";
                    // $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    $imageNames = $i.time().'.'.$imagevalue->extension();
                    $imagevalue->move(public_path('images'), $imageNames);
                    $AllBannerImage = new AllBannerImage;
                    $AllBannerImage->bannerimageID = $notification->id;
                    $AllBannerImage->image = $imageNames;
                    $AllBannerImage->bannertype = 3;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            
            if($advicetype==2){
                foreach($user as $value){
                    if($value!=0){
                        $userlist = User::find($value);
                        $devicetoken = $userlist->deviceToken;
                        $userlanguages = $userlist->language;                
                        $AdviceNotificationUser = new AdviceNotificationUser;
                        $AdviceNotificationUser->notifytype = 1;
                        $AdviceNotificationUser->userID = $value;
                        $AdviceNotificationUser->notifyID = $notification->id;
                        $AdviceNotificationUser->brandID = $request->brand;
                        $AdviceNotificationUser->agevalue = $request->age;
                        $AdviceNotificationUser->gendervalue = $request->gender;
                        $AdviceNotificationUser->countryID = $request->country;
                        $AdviceNotificationUser->stateID = $request->state;
                        $AdviceNotificationUser->cityID = $request->city;
                        $AdviceNotificationUser->status = 1;
                        $AdviceNotificationUser->save();
                        
                        if($userlanguages==2){
                            $notimessages = $messagear;
                            $notititles = $titlear;
                        }else{
                            $notimessages = $message;
                            $notititles = $title;
                        }
                        if($sendpush==1){
                            $this->send_notification($devicetoken, $notimessages, $notititles);
                        }
                    }
                }
                echo 1;

            }else{
                $user = User::where('status',1)->get();
                foreach($user as $value){
                    $devicetoken = $value->deviceToken;
                    $userlanguage = $value->language;
                    if($userlanguage==2){
                        $notimessages = $messagear;
                        $notititles = $titlear;
                    }else{
                        $notimessages = $message;
                        $notititles = $title;
                    }
                    if($sendpush==1){
                        $this->send_notification($devicetoken, $notimessages, $notititles);
                    }
                    
                }
                echo 1;
            }
        }else{
            $notification = Advice::find($request->id);
            $notification->UserID = $advicetype; //advice type 1=Genral 2=Sponser 
            $notification->title = $title;
            $notification->message = $message;
            $notification->type = $type;
            $notification->url = $url;
            $notification->image = $imageName;
            $notification->sponser_name = $sponser_name;                    
            $notification->sponser_icon = $sponser_icon;
            $notification->status = 1;                    
            $notification->title_ar = $titlear;                    
            $notification->message_ar = $messagear;
            $notification->sponser_name_ar = $sponser_namear;
            $notification->expire_date = $expire_date;
            $notification->sendpush = $sendpush;
            $notification->position = $position;
            $notification->save();
            // banner
            if(!empty($request->images)){
                $i=1;
                foreach($request->images as $imagevalue){
                    //upload images
                    // $new_widthss = 720;
                    // $new_heightss = 480;
                    // $filess = $imagevalue;
                    // $fileNamess = $filess->getRealPath();
                    // $uploadPathss = public_path('images/');
                    // $fileExtss = $filess->getClientOriginalExtension();
                    // $imgnamess = $i."sponthump_";
                    // $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    $imageNames = $i.time().'.'.$imagevalue->extension();
                    $imagevalue->move(public_path('images'), $imageNames);
                    $AllBannerImage = new AllBannerImage;
                    $AllBannerImage->bannerimageID = $request->id;
                    $AllBannerImage->image = $imageNames;
                    $AllBannerImage->bannertype = 3;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            
            if($advicetype==2){
                foreach($user as $value){
                    if($value!=0){
                        $userlist = User::find($value);
                        $devicetoken = $userlist->deviceToken;
                        $userlanguages = $userlist->language;
                        $check_user = AdviceNotificationUser::where('notifytype',1)->where('notifyID',$request->id)->where('userID',$value)->count();
                        if($check_user==0){
                            $AdviceNotificationUser = new AdviceNotificationUser;
                            $AdviceNotificationUser->notifytype = 1;
                            $AdviceNotificationUser->userID = $value;
                            $AdviceNotificationUser->notifyID = $request->id;
                            $AdviceNotificationUser->brandID = $request->brand;
                            $AdviceNotificationUser->agevalue = $request->age;
                            $AdviceNotificationUser->gendervalue = $request->gender;
                            $AdviceNotificationUser->countryID = $request->country;
                            $AdviceNotificationUser->stateID = $request->state;
                            $AdviceNotificationUser->cityID = $request->city;
                            $AdviceNotificationUser->status = 1;
                            $AdviceNotificationUser->save();
                        }
                        
                        if($userlanguages==2){
                            $notimessages = $messagear;
                            $notititles = $titlear;
                        }else{
                            $notimessages = $message;
                            $notititles = $title;
                        }
                        if($sendpush==1){
                            $this->send_notification($devicetoken, $notimessages, $notititles);
                        }
                    }
                }
                echo 1;

            }else{
                $user = User::where('status',1)->get();
                foreach($user as $value){
                    $devicetoken = $value->deviceToken;
                    $userlanguage = $value->language;
                    if($userlanguage==2){
                        $notimessages = $messagear;
                        $notititles = $titlear;
                    }else{
                        $notimessages = $message;
                        $notititles = $title;
                    }
                    if($sendpush==1){
                        $this->send_notification($devicetoken, $notimessages, $notititles);
                    }
                    
                }
                echo 1;
            }
        }

    }

    public function delete_advice(Request $request){
        $id = $request->id;
        $adviceID = explode(",",$request->id);
        $category = Advice::whereIn('id', $adviceID)->update(['status' => 2]);;
        //$category = Advice::find($id);
        //$category->status = 2;
        //$category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Advice",
                'event_category'=>21,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function advicedetail(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Advice Detail";
        $data['active'] = "advice";
        $data['active1'] = "advice";
        $data['admin']=Admin::find($admin_id);
        $adviceID = base64_decode($request->key);
        $data['language'] = base64_decode($request->lang);
        $data['data'] = Advice::where('id',$adviceID)->first();
        $data['AllBannerImage'] = AllBannerImage::where('bannerimageID',$adviceID)->where('bannertype',3)->get();
        $data['userlist'] = AdviceNotificationUser::where('notifyID',$adviceID)->where('notifytype',1)->get();
        //rating
        $avgStar = AdviceReview::where('adviceID',$adviceID)->avg('Rate');
        $data['rating'] = "".$avgStar;
        $adviceReview = AdviceReview::where('adviceID',$adviceID);
        $data['reviews'] = $adviceReview->count();
        //like
        $data['totallike'] = AdviceLike::where('likedislike',1)->where('adviceID',$adviceID)->count();
        $data['like'] = AdviceLike::where('likedislike',1)->where('adviceID',$adviceID)->count();
        //review
        $review_array = array();            
        foreach($adviceReview->get() as $reviewvalue){
            $ar['id'] = $reviewvalue->id;
            $ar['userID'] = $reviewvalue->userID;
            $ar['username'] = User::find($reviewvalue->userID)->name;
            $ar['Rate'] = $reviewvalue->Rate;
            $ar['title'] = $reviewvalue->title;
            $ar['message'] = $reviewvalue->message;
            array_push($review_array,$ar);
        }
        $data['review'] = $review_array;
        return view('admin.advicedetail', $data);
    }
    public function change_advice_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Advice::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"change Advice status",
                'event_category'=>21,
                'event_id'=>id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function updateadviceOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = Advice::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Advice Order",
                'event_category'=>21,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        } 
    }

    // public function filter_advicetypedata(Request $request){


    // }

    //translation
    public function translation(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "GearZ Dictionary";
        $data['active'] = "advice";
        $data['active1'] = "translation";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = Translation::where('status',1)->get();        
        return view('admin.translation', $data);
    }
    public function translationdetail(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Advice Detail";
        $data['active'] = "advice";
        $data['active1'] = "translation";
        $data['admin']=Admin::find($admin_id);
        $translationID = base64_decode($request->key);
        $data['language'] = base64_decode($request->lang);
        $data['data'] = Translation::where('id',$translationID)->first();        
        return view('admin.translationdetail', $data);
    }

    public function addtranslation(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "GearZ Dictionary";
        $data['active'] = "advice";
        $data['active1'] = "translation";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = Translation::where('id',$id)->first();        
        return view('admin.addtranslation', $data);
    }

    public function inserttranslation(Request $request){
        $validator = Validator::make($request->all(), [
            'sponser_icon' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
        ], [
            'sponser_icon.dimensions' => 'Image must have a 16:9 aspect ratio.',
            'sponser_icon.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'sponser_icon.max' => 'Image must not exceed 2MB.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if($request->id==""){
            $check_title = Translation::where('title',$request->name)->count();
            $check_titlear = Translation::where('title',$request->namear)->count();
            if($check_title==0){
                if($check_titlear==0){
                    //image upload
                    if($request->sponser_icon!=''){
                        // $new_width = 720;
                        // $new_height = 480;
                        // $file = $request->file('sponser_icon');
                        // $fileName = $file->getRealPath();
                        // $uploadPath = public_path('images/');
                        // $fileExt = $file->getClientOriginalExtension();
                        // $imgname = "thump_";
                        // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                        $imageName = time().'.'.$request->sponser_icon->extension();      
                        $request->sponser_icon->move(public_path('images'), $imageName);
                    }else{
                        $imageName='';
                    }
                    $insert = new Translation;
                    $insert->title = $request->name;
                    $insert->titlear = $request->namear;
                    $insert->message = $request->info;
                    $insert->messagear = $request->infoar;
                    $insert->searchkeyword = $request->searchkeyword;
                    $insert->searchkeyword_ar = $request->searchkeywordar;
                    $insert->image = $imageName;
                    $insert->status = 1;
                    $insert->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Dictionary",
                            'event_category'=>22,
                            'event_id'=>$insert->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 3;
                }

            }else{
                echo 2;
            }                 
        }else{
            //image upload
            if($request->sponser_icon!=''){
                // $new_width = 720;
                // $new_height = 480;
                // $file = $request->file('sponser_icon');
                // $fileName = $file->getRealPath();
                // $uploadPath = public_path('images/');
                // $fileExt = $file->getClientOriginalExtension();
                // $imgname = "thump_";
                // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                $imageName = time().'.'.$request->sponser_icon->extension();      
                $request->sponser_icon->move(public_path('images'), $imageName);
            }else{
                $imageName='';
            }
            $check_title = Translation::where('title',$request->name)->where('id',$request->id);
            $check_titlear = Translation::where('title',$request->namear)->where('id',$request->id);
            if($check_title->count() >= 1 && $request->id != $check_title->first()->id){
                echo 2;
            }else if($check_titlear->count() >= 1 && $request->id != $check_titlear->first()->id){
                echo 3;
            }else{
                $insert = Translation::find($request->id);
                $insert->title = $request->name;
                $insert->titlear = $request->namear;
                $insert->message = $request->info;
                $insert->messagear = $request->infoar;
                $insert->searchkeyword = $request->searchkeyword;
                $insert->searchkeyword_ar = $request->searchkeywordar;
                if($imageName!=''){
                    $insert->image = $imageName;
                }                
                $insert->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Dictionary",
                        'event_category'=>22,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }
        }
    }

    public function delete_translation(Request $request){
        $insert = Translation::find($request->id);
        $insert->status = 2;
        $insert->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Dictionary",
                'event_category'=>22,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function knowledgeimagesection(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Image Section";
        $data['active'] = "advice";
        $data['active1'] = "knowledgeimagesection";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = KnowledgeImageSection::all();        
        return view('admin.knowledgeimagesection', $data);
    }

    public function addknowledgeimagesection(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Image Section";
        $data['active'] = "advice";
        $data['active1'] = "knowledgeimagesection";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = KnowledgeImageSection::where('id',$id)->first();        
        return view('admin.addknowledgeimagesection', $data);
    }

    public function addknowledgeimagesectionupdate(Request $request){
        $validator = Validator::make($request->all(), [
            'sponser_icon' => 'image|mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
        ], [
            'sponser_icon.dimensions' => 'Image must have a 16:9 aspect ratio.',
            'sponser_icon.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'sponser_icon.max' => 'Image must not exceed 2MB.',
            'sponser_icon.image' => 'File must be a valid image.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if($request->id==""){          
            //image upload
            if($request->sponser_icon!=''){
                // $new_width = 720;
                // $new_height = 480;
                // $file = $request->file('sponser_icon');
                // $fileName = $file->getRealPath();
                // $uploadPath = public_path('images/');
                // $fileExt = $file->getClientOriginalExtension();
                // $imgname = "thump_";
                // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                $imageName = time().'.'.$request->sponser_icon->extension();      
                $request->sponser_icon->move(public_path('images'), $imageName);
            }else{
                $imageName='';
            }
            $insert = new KnowledgeImageSection;
            $insert->name = $request->name;
            $insert->name_ar = $request->namear;
            $insert->image = $imageName;
            $insert->save();
            echo 1; 
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Add Knowledge Image Section",
                    'event_category'=>19,
                    'event_id'=>$insert->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }         
        }else{
            //image upload
            if($request->sponser_icon!=''){
                // $new_width = 720;
                // $new_height = 480;
                // $file = $request->file('sponser_icon');
                // $fileName = $file->getRealPath();
                // $uploadPath = public_path('images/');
                // $fileExt = $file->getClientOriginalExtension();
                // $imgname = "thump_";
                // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                // $imageName = time().'.'.$request->sponser_icon->extension();      
                // $request->sponser_icon->move(public_path('images'), $imageName);
                $file = $request->sponser_icon;
                $imageName = time().'.'.$file->extension();      
                $file->move(public_path('images'), $imageName);
            }else{
                $imageName='';
            }            
            $insert = KnowledgeImageSection::find($request->id);
            $insert->name = $request->name;
            $insert->name_ar = $request->namear;
            if($imageName!=''){
                $insert->image = $imageName;
            }                
            $insert->save();
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Knowledge Image Section",
                    'event_category'=>19,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }            
        }
    }

    private function getAccessToken()
    {
        try {
            $client = new GoogleClient();
            $client->setAuthConfig($this->serviceAccountFile);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $token = $client->fetchAccessTokenWithAssertion();        
            return $token['access_token'];
        } catch (\Throwable $th) {
           //echo 5;
        }
       
    }
    function send_notification($deviceToken, $msg, $title)
    {
        //echo $deviceToken;
        //echo $msg;
        //echo $title;
        $json = json_encode(['key' => '1', 'key2' => '10','internal_external' => "",'page' => "",'image' => ""]);
        $accessToken = $this->getAccessToken();
        $url = 'https://fcm.googleapis.com/v1/projects/gearz-bc901/messages:send'; // Replace YOUR_PROJECT_ID with your actual project ID
        $notification = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $msg,
                    'image' => "",                    
                ],
                'data' => [
                    'internal_external' => "",
                    'page' => "",
                    'image' => "",                    
                    'android' => $json, // Custom data for Android
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                    
                ],
            ],
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // if ($httpcode === 200) {
        //     echo "Notification sent successfully\n";
        // } else {
        //     echo "Failed to send notification\n";
        //     echo "HTTP Status Code: $httpcode\n";
        //     echo "Response: $result\n";
        // }
        curl_close($ch);
        return $result;
    }

}
