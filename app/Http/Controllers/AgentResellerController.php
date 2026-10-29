<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\WelcomeImage;
use App\Models\AgentBanner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Agent;
use App\Models\AgentBrand;
use App\Models\ServiceType;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\AgentBranch;
use App\Models\BranchBrand;
use App\Models\ServiceBranch;
use App\Models\AllBannerImage;
use App\Models\InternalAppPage;
use App\Models\StaffLogEvent;
use App\Models\BranchWorkingDays;
use Illuminate\Support\Facades\Session as session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use App\Modules\ExternalSystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgentResellerController extends Controller
{
    public function agentBanners(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "agent";
        $data['active1'] = "agentBanners";
        $data['banners']=AgentBanner::orderBy('position_id', 'asc')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.agentbanners', $data);
    }

    public function addAgentBanner(Request $request){
        $data['active'] = "agent";
        $data['active1'] = "agentBanners";
        if($request->id){
            $data['banner']=AgentBanner::find($request->id);
        }
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['InternalAppPage']=InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.addAgentBanner', $data);
    }

    public function insertAgentBanner(Request $request){

        $validator = Validator::make($request->all(),
            [
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=3/1',
                'name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ], [
                'image.dimensions' => 'Image must have a 3:1 aspect ratio.',
                'image.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }else{
            if($request->hasFile('image')){
                $new_width = 720;
                $new_height = 480;
                $file = $request->file('image');
                $fileName = $file->getRealPath();
                $uploadPath = public_path('img/banners/');
                $fileExt = $file->getClientOriginalExtension();
                $imgname = "thump_";
                $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                // $image = $request->file('image');
                // $filename = time().'.'.$image->getClientOriginalExtension();
                // $image->move('public/img/banners', $filename);
                // $imageName = $filename;
            }else{
                if($request->banner_id){
                    $imageName = $request->pre_image;
                }
            }
            if($request->inlineRadioOptions==2){
                $page = $request->page;
            }else{
                $page = $request->url;
            }
            if($request->banner_id){
                $banner = AgentBanner::find($request->banner_id);
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
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Agent Banner",
                        'event_category'=>14,
                        'event_id'=>$request->banner_id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner updated successfully.');
            }else{
                $banner = new AgentBanner;
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
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Agent Banner",
                        'event_category'=>14,
                        'event_id'=>$banner->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
                // return redirect()->back()->with('message', 'Banner added successfully.');
            }
        }

    }

    public function update_Agentstatus($id, $table){
        $model = 'App\Models\\'.$table;
        $item = $model::find($id);
        if($item->status == 1){ $update=0; }else if($item->status == 0){ $update=1; }
        $model::where('id', $id)->update([ 'status' => $update ]);
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Status of Agent Banner",
                'event_category'=>14,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
        return redirect()->back()->with('message', 'Status changed successfully.');
    }

    public function deleteAgent($id, $table){
        $model = 'App\Models\\'.$table;
        $model::destroy($id);
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Agent Banner",
                'event_category'=>14,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
        // $item = $model::find($id);
        // $item->delete();
        return redirect()->back()->with('message', 'Deleted successfully.');
    }

    public function updateAgentBannerOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $banner = AgentBanner::find($arr[$i]);
            $banner->position_id = $num=$num+1;
            $banner->save();
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Agent Banner Order",
                    'event_category'=>14,
                    'event_id'=>0,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }

    public function change_agentbanner_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = AgentBanner::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Agent Banner Status",
                'event_category'=>14,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    //////////////////////////
    public function agent(){
        $data['active'] = "agent";
        $data['active1'] = "agent";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['title'] = "Agent/Reseller";
        //$data['brand'] = Brand::where('status','!=',2)->get();
        //$data['data'] = Agent::where('status','!=',2)->get();
        $agentpopcount = Agent::where('status','!=',2)->where('popular',1)->count();
        if($agentpopcount!=0){
            //$data['data'] = Agent::where('status','!=',2)->orderby('popular','DESC')->get();
            $data['data'] = Agent::where('status','!=',2)->orderBy('position', 'asc')->get();
        }else{
            $data['data'] = Agent::where('status','!=',2)->orderby('name','ASC')->get();
        }
        return view('admin.agent', $data);
    }
    public function addAgent(Request $request){
        $data['active'] = "agent";
        $data['active1'] = "agent";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['category'] = Category::where('status','!=',2)->get();
        $data['title'] = "Agent/Reseller";
        $id = base64_decode($request->key);
        $data['data'] = Agent::find($id);
        $data['brand'] = Brand::where('status','!=',2)->get();
        $data['agentbrand'] = AgentBrand::where('agentID',$id)->get();
        $data['brandbanner'] = AllBannerImage::where('bannertype',2)->where('bannerimageID',$id)->get();
        return view('admin.addagent', $data);
    }

    public function insert_agent(Request $request){
        $validator = Validator::make($request->all(),
            [
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=1/1',
                'imagess' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
                'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
            ], [
                'image.dimensions' => 'Logo must have a 1:1 aspect ratio.',
                'image.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'image.max' => 'Logo size must not exceed 2MB.',
                'imagess.dimensions' => 'Image must have a 16:9 aspect ratio.',
                'imagess.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'imagess.max' => 'Image size must not exceed 2MB.',
                'images.*.dimensions' => 'Banner image must have a 16:9 aspect ratio.',
                'images.*.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
                'images.*.max' => 'Each banner image must not exceed 2MB.',
                'images.*.image' => 'Each banner file must be a valid image.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }else{
            if($request->id==""){
                $check_name = Agent::where('name',$request->name)->count();
                if($check_name==0){
                    //image upload
                    if($request->image!=''){
                        // $new_width = 100;
                        // $new_height = 100;
                        // $file = $request->file('image');
                        // $fileName = $file->getRealPath();
                        // $uploadPath = public_path('images/');
                        // $fileExt = $file->getClientOriginalExtension();
                        // $imgname = "thump_";
                        // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                        $imageName = time().'.'.$request->image->extension();
                        $request->image->move(public_path('images'), $imageName);
                    }else{
                        $imageName = "";
                    }

                    if($request->imagess!=''){
                        // $new_widths = 720;
                        // $new_heights = 480;
                        // $files = $request->file('imagess');
                        // $fileNames = $files->getRealPath();
                        // $uploadPaths = public_path('images/');
                        // $fileExts = $files->getClientOriginalExtension();
                        // $imgnames = "imthump_";
                        // $imageName1 = ExternalSystem::saveresizeimage($new_widths,$new_heights,$fileNames,$uploadPaths,$fileExts,$imgnames);
                        $imageName1 = 'i'.time().'.'.$request->imagess->extension();
                        $request->imagess->move(public_path('images'), $imageName1);
                    }else{
                        $imageName1='';
                    }


                    //tag icon
                    if($request->tag_icon!=''){
                        // $new_widtht = 100;
                        // $new_heightt = 100;
                        // $filet = $request->file('tag_icon');
                        // $fileNamet = $filet->getRealPath();
                        // $uploadPatht = public_path('images/');
                        // $fileExtt = $filet->getClientOriginalExtension();
                        // $imgnamet = "tagthump_";
                        // $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
                        $tag_icon = 't'.time().'.'.$request->tag_icon->extension();
                        $request->tag_icon->move(public_path('images'), $tag_icon);

                    }else{
                        $tag_icon='';
                    }

                    $agent = new Agent;
                    $agent->name = $request->name;
                    $agent->name_ar = $request->namear;
                    $agent->logo = $imageName;
                    $agent->images = $imageName1;
                    $agent->description = $request->info;
                    $agent->discription_ar = $request->infoar;
                    $agent->email = $request->email;
                    $agent->contact = $request->mobile;
                    $agent->whatsapp = $request->whatsapp;
                    $agent->website = $request->website;
                    $agent->youtube = $request->youtube;
                    $agent->twitter = $request->twitter;
                    $agent->linkedin = $request->linkedin;
                    $agent->facebook = $request->facebook;
                    $agent->instagram = $request->instagram;
                    $agent->snap_chat = $request->snapchat;
                    $agent->type = $request->type;
                    $agent->tag_icon = $tag_icon;
                    $agent->tag_name = $request->tag_name;
                    $agent->tag_name_ar = $request->tag_namear;
                    $agent->status = 1;
                    $agent->save();
                    foreach($request->brands as $val){
                        $agentBrand = new AgentBrand;
                        $agentBrand->agentID = $agent->id;
                        $agentBrand->brandID = $val;
                        $agentBrand->save();
                    }
                    //image upload
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
                            $AllBannerImage->bannerimageID = $agent->id;
                            $AllBannerImage->image = $imageNames;
                            $AllBannerImage->bannertype = 2;
                            $AllBannerImage->save();
                            $i++;
                        }
                    }

                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Agent",
                            'event_category'=>15,
                            'event_id'=>$agent->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                $check_name = Agent::where('name',$request->name)->where('id',$request->id);
                if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                    echo 2;
                }else{
                    //image upload
                    if($request->image!=''){
                        // $new_width = 100;
                        // $new_height = 100;
                        // $file = $request->file('image');
                        // $fileName = $file->getRealPath();
                        // $uploadPath = public_path('images/');
                        // $fileExt = $file->getClientOriginalExtension();
                        // $imgname = "thump_";
                        // $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                        $imageName = time().'.'.$request->image->extension();
                        $request->image->move(public_path('images'), $imageName);
                    }else{
                        $imageName = "";
                    }

                    if($request->imagess!=''){
                        // $new_widths = 720;
                        // $new_heights = 480;
                        // $files = $request->file('imagess');
                        // $fileNames = $files->getRealPath();
                        // $uploadPaths = public_path('images/');
                        // $fileExts = $files->getClientOriginalExtension();
                        // $imgnames = "imthump_";
                        // $imageName1 = ExternalSystem::saveresizeimage($new_widths,$new_heights,$fileNames,$uploadPaths,$fileExts,$imgnames);
                        $imageName1 = 'i'.time().'.'.$request->imagess->extension();
                        $request->imagess->move(public_path('images'), $imageName1);
                    }else{
                        $imageName1='';
                    }

                    //tag icon
                    if($request->tag_icon!=''){
                        // $new_widtht = 100;
                        // $new_heightt = 100;
                        // $filet = $request->file('tag_icon');
                        // $fileNamet = $filet->getRealPath();
                        // $uploadPatht = public_path('images/');
                        // $fileExtt = $filet->getClientOriginalExtension();
                        // $imgnamet = "tagthump_";
                        // $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
                        $tag_icon = 't'.time().'.'.$request->tag_icon->extension();
                        $request->tag_icon->move(public_path('images'), $tag_icon);
                    }else{
                        $tag_icon='';
                    }

                    $agent = new Agent;
                    $agent = Agent::find($request->id);
                    $agent->name = $request->name;
                    $agent->name_ar = $request->namear;
                    $agent->description = $request->info;
                    $agent->discription_ar = $request->infoar;
                    $agent->email = $request->email;
                    $agent->contact = $request->mobile;
                    $agent->whatsapp = $request->whatsapp;
                    $agent->website = $request->website;
                    $agent->youtube = $request->youtube;
                    $agent->twitter = $request->twitter;
                    $agent->linkedin = $request->linkedin;
                    $agent->facebook = $request->facebook;
                    $agent->instagram = $request->instagram;
                    $agent->snap_chat = $request->snapchat;
                    $agent->type = $request->type;
                    $agent->tag_name = $request->tag_name;
                    $agent->tag_name_ar = $request->tag_namear;
                    if($imageName!=''){
                        $agent->logo = $imageName;
                    }
                    if($imageName1!=''){
                        $agent->images = $imageName1;
                    }
                    if($tag_icon!=''){
                        $agent->tag_icon = $tag_icon;
                    }
                    $agent->save();
                    $brandagent = AgentBrand::select('brandID')->where('agentID',$request->id)->get();
                    $brandarray = array();
                    foreach($brandagent as $val){
                        array_push($brandarray,$val->brandID);
                    }
                    $result=array_diff($brandarray,$request->brands);
                    foreach($request->brands as $valb){
                        $checkbrandid = AgentBrand::where('brandID',$valb)->where('agentID',$request->id)->count();
                        if($checkbrandid==0){
                            $agentBrand = new AgentBrand;
                            $agentBrand->agentID = $request->id;
                            $agentBrand->brandID = $valb;
                            $agentBrand->save();
                        }
                    }
                    foreach($result as $rval){
                        AgentBrand::where('brandID',$rval)->where('agentID',$request->id)->delete();
                    }

                    //image upload
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
                            $AllBannerImage->bannertype = 2;
                            $AllBannerImage->save();
                            $i++;
                        }
                    }
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Update Agent",
                            'event_category'=>15,
                            'event_id'=>$request->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }

            }
        }
    }
    public function delete_agent(Request $request){
        $id = $request->id;
        $category = Agent::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Agent",
                'event_category'=>15,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_agent_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Agent::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Agent Status",
                'event_category'=>15,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function add_popular_agent(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Agent::find($id);
        $category->popular = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Add Agent in popular list",
                'event_category'=>15,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function popularAgent(Request $request){
        $data['active'] = "agent";
        $data['active1'] = "popularAgent";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['data'] = Agent::where('status','!=',2)->where('popular',1)->orderBy('position','ASC')->get();
        return view('admin.popularAgents', $data);
    }

    public function updateAgentOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = Agent::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->update();

        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Agent Order",
                'event_category'=>15,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function branch(Request $request){
        $data['active'] = "agent";
        $data['active1'] = "agent";
        $data['id'] = $request->key;
        $admin_id = session::get('id');
        $data['title'] = "Branch";
        $data['data']=AgentBranch::where('agentID', base64_decode($request->key))->get();
        $data['admin']=Admin::find($admin_id);

        return view('admin.branch', $data);
    }

    public function addBranch(Request $request){
        $data['active'] = "agent";
        $data['active1'] = "agent";
        $admin_id = session::get('id');
        $data['title'] = "Branch";
        $data['agentid'] = base64_decode($request->key);
        $branchid = base64_decode($request->id);
        $data['eye'] = base64_decode($request->view);
        $data['admin']=Admin::find($admin_id);
        $data['servicetype'] = ServiceType::where('status',1)->get();
        $data['country'] = Country::where('status',1)->get();
        $data['brand'] = AgentBrand::where('agentID',base64_decode($request->key))->get();
        if($branchid!='') {
        $data['data'] = AgentBranch::where('id',$branchid)->first();
        $data['state'] = State::where('country_id',$data['data']->country)->get();
        $data['city'] = City::where('state_id',$data['data']->state)->get();
        $data['agentbrand'] = BranchBrand::where('branchId',$branchid)->get();
        $data['service'] = ServiceBranch::where('branchId',$branchid)->get();
        $data['branchWorkingDays'] = BranchWorkingDays::where('branchId',$branchid)->get();
        }
        return view('admin.addBranch', $data);
    }

    public function getState(Request $request){
        $country_id = $request->country_id;
        $states = State::where('country_id',$country_id)->get();
        $options = '';
        foreach ($states as $state) {
            $options .= "<option value='{$state->id}'>{$state->name}</option>";
        }
        return $options;
    }

    public function getCity(Request $request){
        $state_id = $request->state_id;
        $city = City::where('state_id',$state_id)->get();
        $options = '';
        foreach($city as $value){
           $options .= "<option value='".$value->id."'>".$value->name."</option>";
        }
        return $options;
    }

    // public function insert_branch(Request $request){
    //     echo"<pre>";print_r($request->first_shift['start_time']);die;
        
    //             //Working Days First
    //     foreach($request->first_shift['start_time'] as $i => $time){
    //         $ServiceBranch = new BranchWorkingDays;
    //         $ServiceBranch->branchId = $insert->id;
    //         $ServiceBranch->day = $i+1;
    //         $ServiceBranch->first_shift_start_time = $request->time;
    //         $ServiceBranch->first_shift_end_time = $request->first_shift['end_time'][$i];
    //         $ServiceBranch->second_shift_start_time = $request->second_shift['start_time'][$i];
    //         $ServiceBranch->second_shift_end_time = $request->second_shift['end_time'][$i];
    //         $ServiceBranch->save();
    //     }
    //    // Google Maps API Key
    //     //     $GOOGLE_API_KEY = 'AIzaSyA_VxG35IaFz_h_F0G_786p77XvwRKG_WM';

    //     //     // Address from which the latitude and longitude will be retrieved
    //     //     $address = 'White House, Pennsylvania Avenue Northwest, Washington, DC, United States';

    //     //     // Formatted address
    //     //     $formatted_address = str_replace(' ', '+', $address);

    //     //     // Get geo data from Google Maps API by address
    //     //     $geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$formatted_address}&key={$GOOGLE_API_KEY}");

    //     //     // Decode JSON data returned by API
    //     //     $apiResponse = json_decode($geocodeFromAddr);
    //     //     print_r($apiResponse);

    //     //    // Retrieve latitude and longitude from API data
    //     //     $latitude  = $apiResponse->results[0]->geometry->location->lat;
    //     //     $longitude = $apiResponse->results[0]->geometry->location->lng;
    //     //     die;
    //     if($request->image!=''){
    //         $new_width = 720;
    //         $new_height = 480;
    //         $file = $request->file('image');
    //         $fileName = $file->getRealPath();
    //         $uploadPath = public_path('images/');
    //         $fileExt = $file->getClientOriginalExtension();
    //         $imgname = "thump_";
    //         $image = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
    //         // $image = 't'.time().'.'.$request->image->extension();
    //         // $request->image->move(public_path('images'), $image);
    //     }else{
    //         $image='';
    //     }
    //     if($request->id==""){

    //         $check_name = AgentBranch::where('name',$request->name)->count();
    //         if($check_name==0){
    //             $insert = new AgentBranch;
    //             $insert->name = $request->name;
    //             $insert->name_ar = $request->namear;
    //             $insert->agentID = $request->agentid;
    //             $insert->servicetypeID = 0;
    //             $insert->contact = $request->mobile;
    //             $insert->start_day = $request->start_day;
    //             $insert->end_day = $request->end_day;
    //             $insert->start_time = $request->start_time;
    //             $insert->end_time = $request->end_time;
    //             $insert->country = $request->country;
    //             $insert->state = $request->state;
    //             $insert->city = $request->city;
    //             $insert->address = $request->address ?? '';
    //             $insert->image = $image;
    //             $insert->email = $request->email;
    //             $insert->latitude = $request->latitude;
    //             $insert->longitude = $request->longitude;
    //             $insert->status = 1;
    //             $insert->save();
    //             //BranchBrand
    //             foreach($request->brands as $val){
    //                 $agentBrand = new BranchBrand;
    //                 $agentBrand->branchId = $insert->id;
    //                 $agentBrand->agentId = $request->agentid;
    //                 $agentBrand->brandId = $val;
    //                 $agentBrand->save();
    //             }
    //             //Branch Services
    //             foreach($request->servicetypeID as $serval){
    //                 $ServiceBranch = new ServiceBranch;
    //                 $ServiceBranch->branchId = $insert->id;
    //                 $ServiceBranch->agentId = $request->agentid;
    //                 $ServiceBranch->serviceID = $serval;
    //                 $ServiceBranch->save();
    //             }
    //             //Working Days First
    //             foreach($request->first_shift as $i => $day){
    //                 $ServiceBranch = new BranchWorkingDays;
    //                 $ServiceBranch->day = $i+1;
    //                 $ServiceBranch->agentId = $request->agentid;
    //                 $ServiceBranch->serviceID = $serval;
    //                 $ServiceBranch->save();
    //             }

    //             echo 1;
    //             if(session::get('usertype')==2){
    //                 $log = array(
    //                     'staff_id'=>session::get('id'),
    //                     'logDate'=>date('Y-m-d'),
    //                     'event_name'=>"Add Branch in an agent",
    //                     'event_category'=>16,
    //                     'event_id'=>$insert->id,
    //                 );
    //                 // print_r($log);
    //                 $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
    //             }
    //         }else{
    //             echo 2;
    //         }
    //     }else{
    //         $check_name = AgentBranch::where('name',$request->name)->where('id',$request->id);
    //         if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
    //             echo 2;
    //         }else{

    //             $insert = new AgentBranch;
    //             $insert = AgentBranch::find($request->id);
    //             $insert->name = $request->name;
    //             $insert->name_ar = $request->namear;
    //             $insert->agentID = $request->agentid;
    //             $insert->servicetypeID = 0;
    //             $insert->contact = $request->mobile;
    //             $insert->email = $request->email;
    //             $insert->start_day = $request->start_day;
    //             $insert->end_day = $request->end_day;
    //             $insert->start_time = $request->start_time;
    //             $insert->end_time = $request->end_time;
    //             $insert->country = $request->country;
    //             $insert->state = $request->state;
    //             $insert->city = $request->city;
    //             $insert->address = $request->address ?? '';
    //             if($image!=""){
    //                 $insert->image = $image;
    //             }
    //             $insert->latitude = $request->latitude;
    //             $insert->longitude = $request->longitude;
    //             $insert->save();
    //             //branchbrand
    //             $brandagent = BranchBrand::select('brandId')->where('branchId',$request->id)->get();
    //             $brandarray = array();
    //             foreach($brandagent as $val){
    //                 array_push($brandarray,$val->brandId);
    //             }
    //             $result=array_diff($brandarray,$request->brands);
    //             foreach($request->brands as $valb){
    //                 $checkbrandid = BranchBrand::where('brandId',$valb)->where('branchId',$request->id)->count();
    //                 if($checkbrandid==0){
    //                     $agentBrand = new BranchBrand;
    //                     $agentBrand->branchId = $request->id;
    //                     $agentBrand->agentId = $request->agentid;
    //                     $agentBrand->brandId = $valb;
    //                     $agentBrand->save();
    //                 }
    //             }
    //             foreach($result as $rval){
    //                 BranchBrand::where('brandId',$rval)->where('branchId',$request->id)->delete();
    //             }
    //             //Branch Services
    //             $branchservice = ServiceBranch::select('serviceID')->where('branchId',$request->id)->get();
    //             $branchservicearray = array();
    //             foreach($branchservice as $vals){
    //                 array_push($branchservicearray,$vals->serviceID);
    //             }
    //             $results=array_diff($branchservicearray,$request->servicetypeID);
    //             foreach($request->servicetypeID as $valbs){
    //                 $checkserviceid = ServiceBranch::where('serviceID',$valbs)->where('branchId',$request->id)->count();
    //                 if($checkserviceid==0){
    //                     $ServiceBranch = new ServiceBranch;
    //                     $ServiceBranch->branchId = $request->id;
    //                     $ServiceBranch->agentId = $request->agentid;
    //                     $ServiceBranch->serviceID = $valbs;
    //                     $ServiceBranch->save();
    //                 }
    //             }
    //             foreach($results as $rvals){
    //                 ServiceBranch::where('serviceID',$rvals)->where('branchId',$request->id)->delete();
    //             }
    //             if(session::get('usertype')==2){
    //                 $log = array(
    //                     'staff_id'=>session::get('id'),
    //                     'logDate'=>date('Y-m-d'),
    //                     'event_name'=>"Update Branch in an agent",
    //                     'event_category'=>16,
    //                     'event_id'=>$request->id,
    //                 );
    //                 // print_r($log);
    //                 $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
    //             }
    //             echo 1;
    //         }

    //     }
    // }

    public function insert_branch(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->image) {
                $new_width = 720;
                $new_height = 480;
                $file = $request->file('image');
                $fileName = $file->getRealPath();
                $uploadPath = public_path('images/');
                $fileExt = $file->getClientOriginalExtension();
                $imgname = "thump_";
                $image = ExternalSystem::saveresizeimage($new_width, $new_height, $fileName, $uploadPath, $fileExt, $imgname);
            } else {
                $image = '';
            }

            if ($request->id == "") {
                $check_name = AgentBranch::where('name', $request->name)->count();
                if ($check_name == 0) {
                    $insert = new AgentBranch;
                    $insert->name = $request->name;
                    $insert->name_ar = $request->namear;
                    $insert->agentID = $request->agentid;
                    $insert->servicetypeID = 0;
                    $insert->contact = $request->mobile;
                    $insert->start_day = $request->start_day ?? 1;
                    $insert->end_day = $request->end_day ?? 1;
                    $insert->start_time = $request->start_time ?? '';
                    $insert->end_time = $request->end_time ?? '';
                    $insert->country = $request->country;
                    $insert->state = $request->state;
                    $insert->city = $request->city;
                    $insert->address = $request->address ?? '';
                    $insert->image = $image;
                    $insert->email = $request->email;
                    $insert->latitude = $request->latitude;
                    $insert->longitude = $request->longitude;
                    $insert->status = 1;
                    $insert->save();

                    foreach($request->brands as $val){
                        $branchBrand = new BranchBrand;
                        $branchBrand->branchId = $insert->id;
                        $branchBrand->agentId = $request->agentid;
                        $branchBrand->brandId = $val;
                        $branchBrand->save();
                    }

                    foreach ($request->servicetypeID as $serval) {
                        $serviceBranch = new ServiceBranch;
                        $serviceBranch->branchId = $insert->id;
                        $serviceBranch->agentId = $request->agentid;
                        $serviceBranch->serviceID = $serval;
                        $serviceBranch->save();
                    }

                    foreach ($request->first_shift['start_time'] as $i => $startTime) {
                        $branchWorkingDays = new BranchWorkingDays;
                        $branchWorkingDays->branchId = $insert->id;
                        $branchWorkingDays->day = $i + 1;
                        $branchWorkingDays->first_shift_start_time = $startTime;
                        $branchWorkingDays->first_shift_end_time = $request->first_shift['end_time'][$i];
                        $branchWorkingDays->second_shift_start_time = $request->second_shift['start_time'][$i];
                        $branchWorkingDays->second_shift_end_time = $request->second_shift['end_time'][$i];
                        $branchWorkingDays->save();
                    }

                    if (Session::get('usertype') == 2) {
                        StaffLogEvent::createStaffLogEvent([
                            'staff_id' => Session::get('id'),
                            'logDate' => date('Y-m-d'),
                            'event_name' => "Add Branch in an agent",
                            'event_category' => 16,
                            'event_id' => $insert->id
                        ]);
                    }
                    DB::commit();
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                $check_name = AgentBranch::where('name', $request->name)->where('id', '<>', $request->id)->count();
                if ($check_name > 0) {
                    echo 2;
                } else {
                    $insert = AgentBranch::find($request->id);
                    $insert->name = $request->name;
                    $insert->name_ar = $request->namear;
                    $insert->agentID = $request->agentid;
                    $insert->servicetypeID = 0;
                    $insert->contact = $request->mobile;
                    $insert->email = $request->email;
                    $insert->start_day = $request->start_day ?? 1;
                    $insert->end_day = $request->end_day ?? 1;
                    $insert->start_time = $request->start_time ?? '';
                    $insert->end_time = $request->end_time ?? '';
                    $insert->country = $request->country;
                    $insert->state = $request->state;
                    $insert->city = $request->city;
                    $insert->address = $request->address ?? '';
                    if ($image != "") {
                        $insert->image = $image;
                    }
                    $insert->latitude = $request->latitude;
                    $insert->longitude = $request->longitude;
                    $insert->save();

                    $existingBrands = BranchBrand::where('branchId', $request->id)->pluck('brandId')->toArray();
                    $newBrands = $request->brands;
                    $brandsToAdd = array_diff($newBrands, $existingBrands);
                    $brandsToDelete = array_diff($existingBrands, $newBrands);

                    foreach ($brandsToAdd as $brandId) {
                        $branchBrand = new BranchBrand;
                        $branchBrand->branchId = $request->id;
                        $branchBrand->agentId = $request->agentid;
                        $branchBrand->brandId = $brandId;
                        $branchBrand->save();
                    }

                    BranchBrand::where('branchId', $request->id)->whereIn('brandId', $brandsToDelete)->delete();

                    $existingServices = ServiceBranch::where('branchId', $request->id)->pluck('serviceID')->toArray();
                    $newServices = $request->servicetypeID;
                    $servicesToAdd = array_diff($newServices, $existingServices);
                    $servicesToDelete = array_diff($existingServices, $newServices);

                    foreach ($servicesToAdd as $serviceId) {
                        $serviceBranch = new ServiceBranch;
                        $serviceBranch->branchId = $insert->id;
                        $serviceBranch->agentId = $request->agentid;
                        $serviceBranch->serviceID = $serviceId;
                        $serviceBranch->save();
                    }

                    ServiceBranch::where('branchId', $request->id)->whereIn('serviceID', $servicesToDelete)->delete();

                    // Update Branch Working Days
                    BranchWorkingDays::where('branchId', $request->id)->delete();
                    foreach ($request->first_shift['start_time'] as $i => $startTime) {
                        $branchWorkingDays = new BranchWorkingDays;
                        $branchWorkingDays->branchId = $request->id;
                        $branchWorkingDays->day = $i + 1;
                        $branchWorkingDays->first_shift_start_time = $startTime;
                        $branchWorkingDays->first_shift_end_time = $request->first_shift['end_time'][$i];
                        $branchWorkingDays->second_shift_start_time = $request->second_shift['start_time'][$i];
                        $branchWorkingDays->second_shift_end_time = $request->second_shift['end_time'][$i];
                        $branchWorkingDays->save();
                    }

                    if (Session::get('usertype') == 2) {
                        StaffLogEvent::createStaffLogEvent([
                            'staff_id' => Session::get('id'),
                            'logDate' => date('Y-m-d'),
                            'event_name' => "Update Branch in an agent",
                            'event_category' => 16,
                            'event_id' => $request->id
                        ]);
                    }
                    DB::commit();
                    echo 1;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('Branch Insert/Update Error: ' . $e->getMessage());
            echo 0; 
        }
    }

    public function importBranches(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ], [
            'csv_file.required' => 'Please upload a CSV file.',
            'csv_file.file' => 'Uploaded file must be a valid file.',
            'csv_file.mimes' => 'Only CSV or TXT files are allowed.',
            'csv_file.max' => 'File size must not exceed 2MB.'
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');

        $header = fgetcsv($file); // Skip header

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            
            if (empty($row[1]) || empty($row[3])) {
                $skipped++;
                continue; // skip incomplete rows
            }

            $existAgentBranch = AgentBranch::where('name', $row[1]);
            if ($existAgentBranch->count() != 0) {
                $skipped++;
                continue; // skip incomplete rows
            }

            $country = Country::where("name", ucfirst($row[5]));
            if ($country->count() == 0) {
                $countryinst = new Country;
                $countryinst->shortname = '';
                $countryinst->name = $row[5];
                $countryinst->phonecode = '';
                $countryinst->status = '1';
                $countryinst->save();
                $country_id = $countryinst->id;
            } else {
                $country_id = $country->first()->id;
            }

            $state = State::where("name", ucfirst($row[6]));
            if ($state->count() == 0) {
                $stateinsert = new State;
                $stateinsert->name = $row[6];
                $stateinsert->country_id = $country_id;
                $stateinsert->status = 1;
                $stateinsert->save();
                $state_id = $stateinsert->id;
            } else {
                $state_id = $state->first()->id;
            }

            $city_id = City::where('name', ucfirst($row[7]));
            if ($city_id->count() == 0) {
                $city_insert = new City;
                $city_insert->name = $row[7];
                $city_insert->state_id = $state_id;
                $city_insert->popular = 0;
                $city_insert->status = 1;
                $city_insert->save();
                $cityid = $city_insert->id;
            } else {
                $cityid = $city_id->first()->id;
            }

            $insert = new AgentBranch;
            $insert->agentID = base64_decode($request->agentid);
            $insert->name = $row[1];
            $insert->name_ar = $row[2];
            $insert->email = $row[3];
            $insert->contact = $row[4];
            $insert->servicetypeID = 0;
            $insert->start_day = $request->start_day ?? 1;
            $insert->end_day = $request->end_day ?? 1;
            $insert->start_time = $request->start_time ?? '';
            $insert->end_time = $request->end_time ?? '';
            $insert->country = $country_id;
            $insert->state = $state_id;
            $insert->city = $cityid;
            $insert->address = $request->address ?? '';
            $insert->image = '';
            $insert->latitude = $row[8];
            $insert->longitude = $row[9];
            $insert->status = 1;
            $insert->save();

            $brandsName = explode(", ",$row[10]);

            foreach ($brandsName as $brandName) {
                $brandDetails = Brand::where('name', $brandName);
                if ($brandDetails->count() == 0) {
                    $brand = new Brand;
                    $brand->name = $brandName;
                    $brand->save();
                    $brandid = $brand->id;
                } else {
                    $brandid = $brandDetails->first()->id;
                }

                $agentBrandDetails = AgentBrand::where('agentID', base64_decode($request->agentid))->where('brandID', $brandid);
                if ($agentBrandDetails->count() == 0) {
                    $brand = new AgentBrand;
                    $brand->agentID = base64_decode($request->agentid);
                    $brand->brandID = $brandid;
                    $brand->save();
                }
                $branchBrand = new BranchBrand;
                $branchBrand->branchId = $insert->id;
                $branchBrand->agentId = base64_decode($request->agentid);
                $branchBrand->brandId = $brandid;
                $branchBrand->save();
            }

            $serviceNames = explode(", ",$row[11]);

            foreach ($serviceNames as $serviceName) {
                $serviceDetails = ServiceType::where('name', $serviceName);
                if ($serviceDetails->count() == 0) {
                    $serviceType = new ServiceType;
                    $serviceType->name = $serviceName;
                    $serviceType->status = 1;
                    $serviceType->save();
                    $serviceid = $serviceType->id;
                } else {
                    $serviceid = $serviceDetails->first()->id;
                }

                $serviceBranch = new ServiceBranch;
                $serviceBranch->branchId = $insert->id;
                $serviceBranch->agentId = base64_decode($request->agentid);
                $serviceBranch->serviceID = $serviceid;
                $serviceBranch->save();
            }

            $dayMap = [
                'Sunday' => 1,
                'Monday' => 2,
                'Tuesday' => 3,
                'Wednesday' => 4,
                'Thursday' => 5,
                'Friday' => 6,
                'Saturday' => 7
            ];

            // Step 1: Parse Excel working days into associative array
            $workingDaysInput = explode(", ", $row[12]);
            $workingData = [];

            foreach ($workingDaysInput as $workingDay) {
                $parts = explode(" - ", $workingDay);
                if (count($parts) === 5) {
                    $dayName = trim($parts[0]);
                    if (isset($dayMap[$dayName])) {
                        $workingData[$dayMap[$dayName]] = [
                            'first_shift_start_time' => trim($parts[1]),
                            'first_shift_end_time' => trim($parts[2]),
                            'second_shift_start_time' => trim($parts[3]),
                            'second_shift_end_time' => trim($parts[4]),
                        ];
                    }
                }
            }

            // Step 2: Loop through all 7 days
            foreach ($dayMap as $dayName => $dayNumber) {
                $shift = $workingData[$dayNumber] ?? [
                    'first_shift_start_time' => '',
                    'first_shift_end_time' => '',
                    'second_shift_start_time' => '',
                    'second_shift_end_time' => '',
                ];

                $branchWorkingDays = new BranchWorkingDays;
                $branchWorkingDays->branchId = $insert->id;
                $branchWorkingDays->day = $dayNumber;
                $branchWorkingDays->first_shift_start_time = $shift['first_shift_start_time'];
                $branchWorkingDays->first_shift_end_time = $shift['first_shift_end_time'];
                $branchWorkingDays->second_shift_start_time = $shift['second_shift_start_time'];
                $branchWorkingDays->second_shift_end_time = $shift['second_shift_end_time'];
                $branchWorkingDays->save();
            }

            $imported++;
        }

        fclose($file);

        // return back()->with('message', "Imported: $imported branches, Skipped: $skipped rows");
        return back()->with('message', "Branch file imported successfully");
    }

    public function change_branch_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $data = AgentBranch::find($id);
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

    public function delete_branch(Request $request){
        $id = $request->id;
        $branch = AgentBranch::find($id);
        $branch->delete();
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
