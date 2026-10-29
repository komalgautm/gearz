<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
//use Session;
use App\Models\Magazine;
use App\Models\MagazineReview;
use App\Models\Admin;
use App\Models\NewsFavorite;
use App\Models\StaffLogEvent;
use App\Modules\ExternalSystem;
use App\Models\MagazineCategory;
use App\Models\MagazineSubcategory;
use App\Models\AllBannerImage;
use App\Models\MagazineBanner;
use App\Models\InternalAppPage;
use App\Models\Viewed;
use Illuminate\Support\Facades\Validator;

class MagazineController extends Controller
{
    //news/events
    public function news(Request $request){
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['title'] = "News/Event";
        $data['active'] = "news";
        $data['active1'] = "news";
        $data['data'] = Magazine::where('status','!=',2)->where('type',1)->orderBy('position','ASC')->get();
        $data['review'] = Magazine::where('status','!=',2)->where('type',2)->orderBy('position','ASC')->get();
        $data['video'] = Magazine::where('status','!=',2)->where('type',3)->orderBy('position','ASC')->get();
        $data['event'] = Magazine::where('status','!=',2)->where('type',4)->orderBy('position','ASC')->get();
        return view('admin.news', $data);
    }
    public function addnews(Request $request){
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['title'] = "News/Event";
        $data['active'] = "news";
        $data['active1'] = "news";
        $data['category'] = MagazineCategory::all();

        $id = base64_decode($request->key);
        $data['data'] = Magazine::find($id);
        if($id==""){
            $data['subcategory'] = MagazineSubcategory::where('categoryID',1)->get();
        }else{
            $categoryid = Magazine::where('id',$id)->first()->categoryID;
            if($categoryid==""){
                $data['subcategory'] = MagazineSubcategory::where('categoryID',1)->get();
            }else{
                $data['subcategory'] = MagazineSubcategory::where('categoryID',$categoryid)->get();
            }

        }
        $data['brandbanner'] = AllBannerImage::where('bannertype',4)->where('bannerimageID',$id)->get();
        return view('admin.addnews', $data);
    }
    public function insert_news(Request $request){
        $type = $request->type;
        if($type == 4){
            $contact_number = $request->contact_number;
            $website = $request->website;
            $address = $request->address;
            $facebook = $request->facebook;
            $instagram = $request->instagram;
            $linkedin = $request->linkedin;
            $twitter = $request->twitter;
        }else{
            $website = "";
            $address = "";
            $facebook = "";
            $instagram = "";
            $linkedin = "";
            $twitter = "";
            $contact_number = "";
        }

        if($request->id==""){
            //image upload
            $imageName1 = "";
            if($request->tag_icon!=''){
                $new_widtht = 100;
                $new_heightt = 100;
                $filet = $request->file('tag_icon');
                $fileNamet = $filet->getRealPath();
                $uploadPatht = public_path('images/');
                $fileExtt = $filet->getClientOriginalExtension();
                $imgnamet = "tagthump_";
                $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
            }else{
                $tag_icon='';
            }
            //sponser_icon
            $sponser_icon='';
            $magazine = new Magazine;
            $magazine->categoryID = 0;
            $magazine->subcategoryID = 0;
            $magazine->heading = $request->heading;
            $magazine->heading_ar = $request->headingar;
            $magazine->image = $imageName1;
            $magazine->sort_description = "";
            $magazine->sort_description_ar = "";
            $magazine->description = $request->info;
            $magazine->description_ar = $request->infoar;
            $magazine->videourl = $request->videourl;
            $magazine->postedBy = $request->postedBy;
            $magazine->postedBy_ar = $request->postedByar;
            $magazine->postedDate = $request->postedDate;
            $magazine->end_date = $request->end_date;
            $magazine->contact_number = $contact_number;
            $magazine->website = $website;
            $magazine->address = $address;
            $magazine->facebook = $facebook;
            $magazine->instagram = $instagram;
            $magazine->linkedin = $linkedin;
            $magazine->twitter = $twitter;
            $magazine->type = $request->type;
            $magazine->tag_icon = $tag_icon;
            $magazine->tag_name = $request->tag_name;
            $magazine->tag_name_ar = $request->tag_namear;
            $magazine->sponser_icon = $sponser_icon;
            $magazine->sponser_name = $request->sponser_name;
            $magazine->home = 0;
            $magazine->position = 0;
            $magazine->status = 1;
            $magazine->save();
            //image upload
            if(!empty($request->images)){
                $i=1;
                foreach($request->images as $imagevalue){
                    //upload images
                    $new_widthss = 720;
                    $new_heightss = 480;
                    $filess = $imagevalue;
                    $fileNamess = $filess->getRealPath();
                    $uploadPathss = public_path('images/');
                    $fileExtss = $filess->getClientOriginalExtension();
                    $imgnamess = $i."sponthump_";
                    $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    // $imageNames = $i.time().'.'.$imagevalue->extension();
                    // $imagevalue->move(public_path('images'), $imageNames);
                    $AllBannerImage = new AllBannerImage;
                    $AllBannerImage->bannerimageID = $magazine->id;
                    $AllBannerImage->image = $imageNames;
                    $AllBannerImage->bannertype = 4;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Add News",
                    'event_category'=>24,
                    'event_id'=>$magazine->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }

        }else{
            //image upload
            $imageName1 = "";
            //tag icon
            if($request->tag_icon!=''){
                $new_widtht = 100;
                $new_heightt = 100;
                $filet = $request->file('tag_icon');
                $fileNamet = $filet->getRealPath();
                $uploadPatht = public_path('images/');
                $fileExtt = $filet->getClientOriginalExtension();
                $imgnamet = "tagthump_";
                $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
            }else{
                $tag_icon='';
            }
            //sponser_icon
            $sponser_icon='';

            $magazine = new Magazine;
            $magazine = Magazine::find($request->id);
            $magazine->categoryID = 0;
            $magazine->subcategoryID = 0;
            $magazine->heading = $request->heading;
            $magazine->heading_ar = $request->headingar;
            $magazine->sort_description = "";
            $magazine->sort_description_ar = "";
            $magazine->description = $request->info;
            $magazine->description_ar = $request->infoar;
            $magazine->videourl = $request->videourl;
            $magazine->postedBy = $request->postedBy;
            $magazine->postedBy_ar = $request->postedByar;
            $magazine->postedDate = $request->postedDate;
            $magazine->end_date = $request->end_date;
            $magazine->contact_number = $contact_number;
            $magazine->website = $website;
            $magazine->address = $address;
            $magazine->facebook = $facebook;
            $magazine->instagram = $instagram;
            $magazine->linkedin = $linkedin;
            $magazine->twitter = $twitter;
            $magazine->type = $request->type;
            if($imageName1!=''){
                $magazine->image = $imageName1;
            }
            if($tag_icon!=''){
                $magazine->tag_icon = $tag_icon;
            }
            if($sponser_icon!=''){
                $magazine->sponser_icon = $sponser_icon;
            }
            $magazine->tag_name = $request->tag_name;
            $magazine->tag_name_ar = $request->tag_namear;
            $magazine->sponser_name = $request->sponser_name;
            $magazine->save();
            if(!empty($request->images)){
                $i=1;
                foreach($request->images as $imagevalue){
                    //upload images
                    $new_widthss = 720;
                    $new_heightss = 480;
                    $filess = $imagevalue;
                    $fileNamess = $filess->getRealPath();
                    $uploadPathss = public_path('images/');
                    $fileExtss = $filess->getClientOriginalExtension();
                    $imgnamess = $i."sponthump_";
                    $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                    $AllBannerImage = new AllBannerImage;
                    $AllBannerImage->bannerimageID = $request->id;
                    $AllBannerImage->image = $imageNames;
                    $AllBannerImage->bannertype = 4;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update News",
                    'event_category'=>24,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }
    public function newsdata(Request $request){

        $magazineID = base64_decode($request->key);
        try{
            $admin_id = session::get('id');
            $data['admin']=Admin::find($admin_id);
            $data['title'] = "News/Event";
            $data['active'] = "news";
            $data['active1'] = "news";
            $data['language'] = base64_decode($request->lang);
            $data['news'] = Magazine::where('id',$magazineID)->first();
            //review
            $data['reviews'] = MagazineReview::where('magazineID',$magazineID)->orderBy('id','DESC')->get();
            $avgStar = MagazineReview::where('magazineID',$magazineID)->avg('rate');
            $data['rating'] = "".number_format($avgStar, 1, '.', '');
            $CarReview = MagazineReview::where('magazineID',$magazineID);
            $data['reviews'] = $CarReview->count();
            $data['AllBannerImage'] = AllBannerImage::where('bannertype',4)->where('bannerimageID',$magazineID)->get();
            return view('admin.newsdata', $data);
        }catch(\Exception $e){
           echo "404";
        }

    }

    public function delete_news(Request $request){
        $id = $request->id;
        $category = Magazine::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete news",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_news_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change news Status",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function addhomenews(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->home = $status;
        $category->save();
    }
    public function addbannernews(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->banner = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Add news at banner",
                'event_category'=>24,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function updatenewsOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = Magazine::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update news Order",
                'event_category'=>24,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function changetotalviewnews(Request $request){
        $totalview = $request->totalview;
        $totalviewid = $request->totalviewid;
        $magazine = Magazine::find($totalviewid);
        $magazine->totalview = $totalview;
        $magazine->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update news view",
                'event_category'=>24,
                'event_id'=>$totalviewid,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }

    }
    //news/events

    public function magazine(){
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['title'] = "Magazines";
        $data['active'] = "magazine";
        $data['active1'] = "magazinelist";
        $data['data'] = Magazine::where('status','!=',2)->orderBy('position','ASC')->get();
        $data['category'] = MagazineCategory::where('status','!=',2)->orderBy('name','ASC')->get();
        return view('admin.magazine', $data);
    }

    public function addMagazine(Request $request){
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        if($request->key==""){
            $data['title'] = "Add Magazine";
        }else{
            $data['title'] = "Edit Magazine";
        }
        $data['active'] = "magazine";
        $data['active1'] = "magazinelist";
        $data['category'] = MagazineCategory::all();

        $id = base64_decode($request->key);
        $data['data'] = Magazine::find($id);
        if($id==""){
            $data['subcategory'] = MagazineSubcategory::where('categoryID',1)->get();
        }else{
            $categoryid = Magazine::where('id',$id)->first()->categoryID;
            if($categoryid==""){
                $data['subcategory'] = MagazineSubcategory::where('categoryID',1)->get();
            }else{
                $data['subcategory'] = MagazineSubcategory::where('categoryID',$categoryid)->get();
            }

        }
        $data['brandbanner'] = AllBannerImage::where('bannertype',4)->where('bannerimageID',$id)->get();
        return view('admin.addmagazine', $data);
    }

    public function get_subcategoryid(Request $request){
        $categoryID = $request->categoryID;
        $subcategory = MagazineSubcategory::where('categoryID',$categoryID)->get();
        foreach($subcategory as $value){
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }

    public function insert_magazine(Request $request){
        $validator = Validator::make($request->all(), [
            // 'images' => 'required|array',
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
        $type = $request->type;
        if($type == 4){
            $contact_number = $request->contact_number;
            $website = $request->website;
            $address = $request->address;
            $facebook = $request->facebook;
            $instagram = $request->instagram;
            $linkedin = $request->linkedin;
            $twitter = $request->twitter;
        }else{
            $website = "";
            $address = "";
            $facebook = "";
            $instagram = "";
            $linkedin = "";
            $twitter = "";
            $contact_number = "";
        }

        if($request->id==""){
            //image upload
            $imageName1 = "";
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
            //sponser_icon
            $sponser_icon='';
            $magazine = new Magazine;
            $magazine->categoryID = $request->categoryID;
            $magazine->subcategoryID = $request->subcategoryID;
            $magazine->heading = $request->heading;
            $magazine->heading_ar = $request->headingar;
            $magazine->image = $imageName1;
            $magazine->sort_description = "";
            $magazine->sort_description_ar = "";
            $magazine->description = $request->info;
            $magazine->description_ar = $request->infoar;
            $magazine->videourl = $request->videourl;
            $magazine->postedBy = $request->postedBy;
            $magazine->postedBy_ar = $request->postedByar;
            $magazine->postedDate = $request->postedDate;
            $magazine->end_date = $request->end_date;
            $magazine->contact_number = $contact_number;
            $magazine->website = $website;
            $magazine->address = $address;
            $magazine->facebook = $facebook;
            $magazine->instagram = $instagram;
            $magazine->linkedin = $linkedin;
            $magazine->twitter = $twitter;
            $magazine->type = 1;
            $magazine->tag_icon = $tag_icon;
            $magazine->tag_name = $request->tag_name;
            $magazine->tag_name_ar = $request->tag_namear;
            $magazine->sponser_icon = $sponser_icon;
            $magazine->sponser_name = $request->sponser_name;
            $magazine->home = 0;
            $magazine->position = 0;
            $magazine->status = 1;
            $magazine->save();
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
                    $AllBannerImage->bannerimageID = $magazine->id;
                    $AllBannerImage->image = $imageNames;
                    $AllBannerImage->bannertype = 4;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Add Magazine",
                    'event_category'=>16,
                    'event_id'=>$magazine->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }

        }else{
            //image upload
            $imageName1 = "";
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
            //sponser_icon
            $sponser_icon='';

            $magazine = new Magazine;
            $magazine = Magazine::find($request->id);
            $magazine->categoryID = $request->categoryID;
            $magazine->subcategoryID = $request->subcategoryID;
            $magazine->heading = $request->heading;
            $magazine->heading_ar = $request->headingar;
            $magazine->sort_description = "";
            $magazine->sort_description_ar = "";
            $magazine->description = $request->info;
            $magazine->description_ar = $request->infoar;
            $magazine->videourl = $request->videourl;
            $magazine->postedBy = $request->postedBy;
            $magazine->postedBy_ar = $request->postedByar;
            $magazine->postedDate = $request->postedDate;
            $magazine->end_date = $request->end_date;
            $magazine->contact_number = $contact_number;
            $magazine->website = $website;
            $magazine->address = $address;
            $magazine->facebook = $facebook;
            $magazine->instagram = $instagram;
            $magazine->linkedin = $linkedin;
            $magazine->twitter = $twitter;
            $magazine->type = 1;
            if($imageName1!=''){
                $magazine->image = $imageName1;
            }
            if($tag_icon!=''){
                $magazine->tag_icon = $tag_icon;
            }
            if($sponser_icon!=''){
                $magazine->sponser_icon = $sponser_icon;
            }
            $magazine->tag_name = $request->tag_name;
            $magazine->tag_name_ar = $request->tag_namear;
            $magazine->sponser_name = $request->sponser_name;
            $magazine->save();
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
                    $AllBannerImage->bannertype = 4;
                    $AllBannerImage->save();
                    $i++;
                }
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Magazine",
                    'event_category'=>16,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }

    public function delete_magazine(Request $request){
        $id = $request->id;
        $category = Magazine::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Magazine",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_magazine_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Magazine Status",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function addhomemagazine(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->home = $status;
        $category->save();
    }
    public function addbannermagazine(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Magazine::find($id);
        $category->banner = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Add Magazine at banner",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function updatemagzineOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = Magazine::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Magazine Order",
                'event_category'=>16,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function magazinedata(Request $request){

        $magazineID = base64_decode($request->key);
        try{
            $admin_id = session::get('id');
            $data['admin']=Admin::find($admin_id);
            $data['title'] = "Magazine";
            $data['active'] = "magazine";
            $data['active1'] = "magazinelist";
            $data['language'] = base64_decode($request->lang);
            $data['news'] = Magazine::where('id',$magazineID)->first();
            //review
            $data['reviews'] = MagazineReview::where('magazineID',$magazineID)->orderBy('id','DESC')->get();
            $avgStar = MagazineReview::where('magazineID',$magazineID)->avg('rate');
            $data['rating'] = "".number_format($avgStar, 1, '.', '');
            $CarReview = MagazineReview::where('magazineID',$magazineID);
            $data['reviews'] = $CarReview->count();
            $data['AllBannerImage'] = AllBannerImage::where('bannertype',4)->where('bannerimageID',$magazineID)->get();
            return view('admin.magazinedata', $data);
        }catch(\Exception $e){
           echo "404";
        }

    }

    public function changetotalview(Request $request){
        $totalview = $request->totalview;
        $totalviewid = $request->totalviewid;
        $magazine = Magazine::find($totalviewid);
        $magazine->totalview = $totalview;
        $magazine->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update magazine view",
                'event_category'=>18,
                'event_id'=>$totalviewid,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }

    }
// main category
public function magazinemaincategory(){
    $admin_id = session::get('id');
    $data['admin']=Admin::find($admin_id);
    $data['title'] = "Magazine Categories";
    $data['active'] = "magazine";
    $data['active1'] = "magazinemaincategory";
    $data['data'] = MagazineCategory::where('status','!=',2)->orderBy('position','ASC')->get();
    return view('admin.magazinemaincategory', $data);
}
public function addmagazinemaincategory(Request $request){
    $admin_id = Session::get('id');
    if($request->key==""){
        $data['title'] = "Add Magazine Category";
    }else{
        $data['title'] = "Edit Magazine Category";
    }
    $data['active'] = "magazine";
    $data['active1'] = "magazinemaincategory";
    $data['admin']=Admin::find($admin_id);
    $id = base64_decode($request->key);
    $data['category'] = MagazineCategory::all();
    $data['data'] = MagazineCategory::find($id);
    return view('admin.addmagazinemaincategory',$data);
}

public function insert_magazinemaincategory(Request $request){

        if($request->id==""){
            $position = MagazineCategory::orderBy('position','DESC')->take(1);
            if($position->count()==0){
                $pos = 1;
            }else{
                $pos = $position->first()->position+1;
            }
            $check_name = MagazineCategory::where('name',$request->name)->count();
            $check_namear = MagazineCategory::where('name_ar',$request->namear)->count();
            if($check_name==0 && $check_namear==0){
                $serviceType = new MagazineCategory;
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->position = $pos;
                $serviceType->status = 1;
                $serviceType->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Magazine Category",
                        'event_category'=>16,
                        'event_id'=>$serviceType->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = MagazineCategory::where('name',$request->name)->where('id',$request->id);
            $check_namear = MagazineCategory::where('name_ar',$request->namear)->where('id',$request->id);
            $st = 0;
            if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                $st = 2;
            }else{
                $st = 1;
            }
            if($check_namear->count() >= 1 && $request->id != $check_namear->first()->id){
                $st = 2;
            }else{
                $st = 1;
            }
            if($st == 1){
                $serviceType = new MagazineCategory;
                $serviceType = MagazineCategory::find($request->id);
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Magazine Category",
                        'event_category'=>16,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }

}
public function delete_magazinemaincategory(Request $request){
    $id = $request->id;
    $serviceType = MagazineCategory::find($id);
    $serviceType->status = 2;
    $serviceType->save();
    if(session::get('usertype')==2){
        $log = array(
            'staff_id'=>session::get('id'),
            'logDate'=>date('Y-m-d'),
            'event_name'=>"Delete Magazine Category",
            'event_category'=>16,
            'event_id'=>$request->id,
        );
        // print_r($log);
        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
    }
}

public function change_magazinemaincategory_status(Request $request){
    $id = $request->id;
    $status = $request->status;
    $serviceType = MagazineCategory::find($id);
    $serviceType->status = $status;
    $serviceType->save();
    if(session::get('usertype')==2){
        $log = array(
            'staff_id'=>session::get('id'),
            'logDate'=>date('Y-m-d'),
            'event_name'=>"Change Magazine Category Status",
            'event_category'=>16,
            'event_id'=>$request->id,
        );
        // print_r($log);
        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
    }
}
public function updatemagazinemaincategory(Request $request){
    $arr=$request->allData;
    $num=0;
    for($i=0;$i<count($arr);$i++)
    {
        $brand = MagazineCategory::find($arr[$i]);
        $brand->position = $num=$num+1;
        $brand->save();
    }
    if(session::get('usertype')==2){
        $log = array(
            'staff_id'=>session::get('id'),
            'logDate'=>date('Y-m-d'),
            'event_name'=>"Update Magazine Category Order",
            'event_category'=>16,
            'event_id'=>0,
        );
        // print_r($log);
        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
    }
}
//category
    public function magazinecategory(){
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['title'] = "Magazine Sub-Categories";
        $data['active'] = "magazine";
        $data['active1'] = "magazinecategory";
        $data['category'] = MagazineCategory::where('status',1)->get();
        $data['data'] = MagazineSubcategory::where('categoryID',1)->where('status','!=',2)->get();
        $data['data2'] = MagazineSubcategory::where('categoryID',2)->where('status','!=',2)->get();
        return view('admin.magazinecategory', $data);
    }
    public function addmagazinecategory(Request $request){
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Magazine Sub-Category";
        }else{
            $data['title'] = "Edit Magazine Sub-Category";
        }
        $data['active'] = "magazine";
        $data['active1'] = "magazinecategory";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['category'] = MagazineCategory::where('status',1)->get();
        $data['data'] = MagazineSubcategory::find($id);
        return view('admin.addmagazinecategory',$data);
    }

    public function insert_magazinecategory(Request $request){
            $validator = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=9/16',
            ], [
                'image.dimensions' => 'Image must have a 9:16 aspect ratio.',
                'image.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
                'image.max' => 'Image must not exceed 2MB.',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }
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
                $imageName = '';
            }
            if($request->id==""){
                $check_name = MagazineSubcategory::where('name',$request->name)->count();
                $check_namear = MagazineSubcategory::where('name_ar',$request->namear)->count();
                if($check_name==0 && $check_namear==0){
                    $serviceType = new MagazineSubcategory;
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    $serviceType->categoryID = $request->category;
                    $serviceType->image = $imageName;
                    $serviceType->status = 1;
                    $serviceType->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Magazine Sub-Category",
                            'event_category'=>16,
                            'event_id'=>$serviceType->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 2;
                }
            }else{
                $check_name = MagazineSubcategory::where('name',$request->name)->where('id',$request->id);
                $check_namear = MagazineSubcategory::where('name_ar',$request->namear)->where('id',$request->id);
                $st = 0;
                if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                    $st = 2;
                }else{
                    $st = 1;
                }
                if($check_namear->count() >= 1 && $request->id != $check_namear->first()->id){
                    $st = 2;
                }else{
                    $st = 1;
                }
                if($st == 1){
                    $serviceType = new MagazineSubcategory;
                    $serviceType = MagazineSubcategory::find($request->id);
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    $serviceType->categoryID = $request->category;
                    if($imageName!=""){
                        $serviceType->image = $imageName;
                    }
                    $serviceType->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Update Magazine Sub-Category",
                            'event_category'=>16,
                            'event_id'=>$request->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 2;
                }
            }

    }
    public function delete_magazinecategory(Request $request){
        $id = $request->id;
        $serviceType = MagazineSubcategory::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Magazine Sub-Category",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_magazinecategory_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $serviceType = MagazineSubcategory::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Magazine Sub-Category Status",
                'event_category'=>16,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function updatemagazinecategory(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = MagazineSubcategory::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Magazine Sub-Category Order",
                'event_category'=>16,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    //banner
    public function magazineBanners(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "magazine";
        $data['active1'] = "magazineBanners";
        $data['banners']=MagazineBanner::orderBy('position_id', 'asc')->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.magazinebanners', $data);
    }

    public function addmagazineBanner(Request $request){
        $data['active'] = "magazine";
        $data['active1'] = "magazineBanners";
        if($request->id){
            $data['banner']=MagazineBanner::find($request->id);
        }
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['InternalAppPage']=InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.addmagazineBanner', $data);
    }

    public function insertmagazineBanner(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
        ], [
            'image.dimensions' => 'Image must have a 16:9 aspect ratio.',
            'image.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
            'image.max' => 'Image must not exceed 2MB.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

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
        if($request->inlineRadioOptions==2){
            $page = $request->page;
        }else{
            $page = $request->url;
        }
        if($request->banner_id){
            $banner = MagazineBanner::find($request->banner_id);
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
                    'event_name'=>"Update Magazine Banner",
                    'event_category'=>16,
                    'event_id'=>$request->banner_id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
            // return redirect()->back()->with('message', 'Banner updated successfully.');
        }else{
            $position = MagazineBanner::orderBy('position_id','DESC')->take(1);
            if($position->count()==0){
                $pos = 1;
            }else{
                $pos = $position->first()->position_id+1;
            }
            $banner = new MagazineBanner;
            $banner->image = $imageName;
            $banner->name = $request->name;
            $banner->name_ar = $request->namear;
            $banner->url = $page;
            $banner->start_date = $request->start_date;
            $banner->end_date = $request->end_date;
            $banner->internal_external = $request->inlineRadioOptions;
            $banner->position_id = $pos;
            $banner->status = 1;
            $banner->log = 0;
            $banner->save();
            echo "add";
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Add Magazine Banner",
                    'event_category'=>16,
                    'event_id'=>$banner->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
            // return redirect()->back()->with('message', 'Banner added successfully.');
        }


    }

    public function update_magazinestatus($id, $table){
        $model = 'App\Models\\'.$table;
        $item = $model::find($id);
        if($item->status == 1){ $update=0; }else if($item->status == 0){ $update=1; }
        $model::where('id', $id)->update([ 'status' => $update ]);
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Status of Magazine Banner",
                'event_category'=>16,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
        return redirect()->back()->with('message', 'Status changed successfully.');
    }

    public function deletemagazinebanner($id, $table){
        $model = 'App\Models\\'.$table;
        $model::destroy($id);
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Magazine Banner",
                'event_category'=>16,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
        // $item = $model::find($id);
        // $item->delete();
        return redirect()->back()->with('message', 'Deleted successfully.');
    }

    public function updatemagazineBannerOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $banner = MagazineBanner::find($arr[$i]);
            $banner->position_id = $num=$num+1;
            $banner->save();
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Magazine Banner Order",
                    'event_category'=>16,
                    'event_id'=>0,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }

    public function change_magazinebanner_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = MagazineBanner::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Magazine Banner Status",
                'event_category'=>16,
                'event_id'=>$id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function filter_maincategorydata(Request $request) {
        // Category ID from the request
        $id = $request->id;
        $i = 1;

        // Fetch magazines based on the category ID and status
        $magazines = Magazine::where('status', '!=', 2)
            ->where('categoryID', $id)
            ->orderBy('position', 'ASC')
            ->get();

        $magazinedata = '';  // Initialize magazinedata to accumulate rows

        foreach ($magazines as $value) {
            // Get view count for each magazine
            $view = Viewed::where('menu_type', 2)
                ->where('menuID', $value->id)
                ->count();

            // Calculate total view count
            if ($view == 0) {
                $totalv = $value->totalview . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
            } else {
                $totalv = ($view + $value->totalview) . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
            }

            // Determine checkbox statuses
            $home = $value->home == 1 ? 'checked' : '';
            $banner = $value->banner == 1 ? 'checked' : '';
            $status = $value->status == 1 ? 'checked' : '';

            // Build the row HTML
            $magazinedata .= '<tr id="' . $value->id . '">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>' . $i . '</td>
                                    <td><a href="' . url("admin/magazinedata?key=" . base64_encode($value->id) . '&lang=' . base64_encode(1)) . '">' . $value->heading . '</a></td>
                                    <td><a href="' . url("admin/magazinedata?key=" . base64_encode($value->id) . '&lang=' . base64_encode(2)) . '">' . $value->heading_ar . '</a></td>
                                    <td>' . $totalv . '</td>
                                    <td>' . $value->log . '</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedhome" type="checkbox" role="switch" id="homeSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $home . '>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedbanner" type="checkbox" role="switch" id="bannerSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $banner . '>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox" role="switch" id="statusSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $status . '>
                                        </div>
                                    </td>
                                    <td><a href="' . url('admin/addMagazine?key=' . base64_encode($value->id)) . '"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="' . $value->id . '"><i class="fa fa-trash"></i></a></td>
                                </tr>';
            $i++;
        }

        // Return the magazine data
        $arr['magazine'] = $magazinedata;

        // Fetch subcategories
        $subcategories = MagazineSubcategory::where('categoryID', $id)->where('status', '!=', 2)->get();
        $subcategorydata = '<option value="">Choose</option>';  // Initialize subcategory data

        foreach ($subcategories as $subcategory) {
            $subcategorydata .= '<option value="' . $subcategory->id . '">' . $subcategory->name . '</option>';
        }

        // Return the subcategory data
        $arr['subcategory'] = $subcategorydata;

        return $arr;
    }
    public function filter_subcategorydata(Request $request) {
        // Category ID from the request
        $id = $request->id;
        $i = 1;

        // Fetch magazines based on the category ID and status
        $magazines = Magazine::where('status', '!=', 2)
            ->where('subcategoryID', $id)
            ->orderBy('position', 'ASC')
            ->get();

        $magazinedata = '';  // Initialize magazinedata to accumulate rows

        foreach ($magazines as $value) {
            // Get view count for each magazine
            $view = Viewed::where('menu_type', 2)
                ->where('menuID', $value->id)
                ->count();

            // Calculate total view count
            if ($view == 0) {
                $totalv = $value->totalview . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
            } else {
                $totalv = ($view + $value->totalview) . "<a href='#' onclick='changetotal_view($value->id,$value->totalview)'><i class='fa fa-edit' style='font-size:12px'></i></a>";
            }

            // Determine checkbox statuses
            $home = $value->home == 1 ? 'checked' : '';
            $banner = $value->banner == 1 ? 'checked' : '';
            $status = $value->status == 1 ? 'checked' : '';

            // Build the row HTML
            $magazinedata .= '<tr id="' . $value->id . '">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>' . $i . '</td>
                                    <td><a href="' . url("admin/magazinedata?key=" . base64_encode($value->id) . '&lang=' . base64_encode(1)) . '">' . $value->heading . '</a></td>
                                    <td><a href="' . url("admin/magazinedata?key=" . base64_encode($value->id) . '&lang=' . base64_encode(2)) . '">' . $value->heading_ar . '</a></td>
                                    <td>' . $totalv . '</td>
                                    <td>' . $value->log . '</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedhome" type="checkbox" role="switch" id="homeSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $home . '>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckCheckedbanner" type="checkbox" role="switch" id="bannerSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $banner . '>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox" role="switch" id="statusSwitch' . $value->id . '" data-id="' . $value->id . '" ' . $status . '>
                                        </div>
                                    </td>
                                    <td><a href="' . url('admin/addMagazine?key=' . base64_encode($value->id)) . '"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="' . $value->id . '"><i class="fa fa-trash"></i></a></td>
                                </tr>';
            $i++;
        }

        // Return the magazine data
        $arr['magazine'] = $magazinedata;
        return $arr;
    }




}
