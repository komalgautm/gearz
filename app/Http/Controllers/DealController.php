<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Agent;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Deal;
use App\Models\DealDetail;
use App\Models\DealType;
use App\Models\Magazine;
use App\Models\InternalAppPage;
use App\Models\Vehicle;
use App\Models\StaffLogEvent;
use App\Models\Viewed;
use App\Modules\ExternalSystem;
use Illuminate\Support\Facades\Session as session;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    public function dealList(Request $request)
    {
        $admin_id = session::get('id');
        $data['title'] = "Deals";
        $data['active'] = "deals";
        $data['data'] = Deal::where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $data['admin'] = Admin::find($admin_id);
        $data['dealtype'] = DealType::where('status', 1)->get();
        return view('admin.deallist', $data);
    }

    public function addDeal(Request $request)
    {
        $admin_id = session::get('id');
        $data['admin'] = Admin::find($admin_id);
        $data['title'] = "Deal";
        $data['active'] = "deals";
        $id = base64_decode($request->key);
        $data['data'] = Deal::find($id);
        $data['brand'] = Brand::where('status', '!=', 2)->get();
        $data['country'] = Country::where('status', 1)->orderBy('name', 'ASC')->get();
        //$data['city'] = City::where('status',1)->get();
        $data['agent'] = Agent::where('status', '!=', 2)->get();
        $data['dealtype'] = DealType::where('status', 1)->get();
        if ($request->key != '') {
            $data['dealdetail'] = DealDetail::where('dealID', $id)->get();
            $data['state'] = State::where('country_id', $data['data']->country)->orderBy('name', 'ASC')->get();
            $state = explode(",", $data['data']->state);
            $data['statedata'] = $state;
            $data['city'] = City::whereIN('state_id', $state)->orderBy('name', 'ASC')->get();
            // $state = explode(",",$data['data']->state);
            // $data['statedata'] = $state;
            // if(in_array("All",$state)){
            //     $stateArray = array();
            //     $states = State::select('id')->where('country_id',$request->country)->get();
            //     foreach($states as $statevalue){
            //         array_push($stateArray,$statevalue->id);
            //     }

            //     $data['city'] = City::whereIN('state_id',$stateArray)->get();

            // }else{

            //     $data['city'] = City::whereIN('state_id',$state)->get();

            // }

            //$data['city'] = City::where('state_id',$data['data']->state)->get();
        }
        $data['InternalAppPage'] = InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.adddeal', $data);
    }

    public function getdealtype(Request $request)
    {
        if ($request->id == 1) {
            $brands = Brand::where('status', '!=', 2)->get();
            foreach ($brands as $bvalue) {
                echo '<option value="' . $bvalue->id . '">' . $bvalue->name . '</option>';
            }
        } else {
            $agent = Agent::where('status', '!=', 2)->get();
            foreach ($agent as $avalue) {
                echo '<option value="' . $avalue->id . '">' . $avalue->name . '</option>';
            }
        }
    }

    function insertdealtype(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'images' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
            ], [
                'images.dimensions' => 'Image must have a 16:9 aspect ratio.',
                'images.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'images.max' => 'Image size must not exceed 2MB.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        //echo $request->addasanews;
        // die;
        if ($request->addasanews == 1) {
            $addasanews = 1;
        } else {
            $addasanews = 0;
        }
        if ($request->id == "") {
            $check_name = Deal::where('name', $request->name)->count();
            if ($check_name == 0) {
                if (!empty($request->state)) {
                    $stateid = implode(",", $request->state);
                } else {
                    $stateid = 0;
                }

                //image upload
                if ($request->images != '') {
                    $new_width = 720;
                    $new_height = 480;
                    $file = $request->file('images');
                    $fileName = $file->getRealPath();
                    $uploadPath = public_path('images/');
                    $fileExt = $file->getClientOriginalExtension();
                    $imgname = "thump_";
                    $imageName = ExternalSystem::saveresizeimage($new_width, $new_height, $fileName, $uploadPath, $fileExt, $imgname);
                } else {
                    $imageName = "";
                }

                //tag icon
                if ($request->tag_icon != '') {
                    $new_widtht = 100;
                    $new_heightt = 100;
                    $filet = $request->file('tag_icon');
                    $fileNamet = $filet->getRealPath();
                    $uploadPatht = public_path('images/');
                    $fileExtt = $filet->getClientOriginalExtension();
                    $imgnamet = "tagthump_";
                    $tag_icon = ExternalSystem::saveresizeimage($new_widtht, $new_heightt, $fileNamet, $uploadPatht, $fileExtt, $imgnamet);
                } else {
                    $tag_icon = '';
                }

                if ($request->page != "") {
                    $page = $request->page;
                } else {
                    $page = 0;
                }
                if ($request->pageid != "") {
                    if ($request->page == 1) {
                        $pageid = 0;
                    } else {
                        $pageid = $request->pageid;
                    }
                } else {
                    $pageid = 0;
                }
                $insert = new Deal;
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                $insert->image = $imageName;
                $insert->discription = $request->discription;
                $insert->discription_ar = $request->discriptionar;
                $insert->startDate = $request->startDate;
                $insert->endDate = $request->endDate;
                $insert->dealType = $request->dealtype;
                $insert->typesofdeal = $request->dealtypesid;
                $insert->popular = 0;
                $insert->position = 0;
                $insert->tag_icon = $tag_icon;
                $insert->tag_name = $request->tag_name;
                $insert->tag_name_ar = $request->tag_namear;
                $insert->state = $stateid;
                $insert->country = $request->country;
                $insert->internalpage = $page;
                $insert->pageid = $pageid;
                $insert->addasanews = $addasanews;
                $insert->whereshow = $request->whereshow;
                $insert->status = 1;
                $insert->save();
                $citydata = $request->city;
                foreach ($citydata as $val) {
                    $deal_details = new DealDetail;
                    $deal_details->dealID = $insert->id;
                    $deal_details->cityID = $val;
                    $deal_details->dealTypeID = $request->dealtypeid;
                    $deal_details->dealType = $request->dealtype;
                    $deal_details->save();
                }

                if ($addasanews == 1) {
                    $magazine = new Magazine;
                    $magazine->heading = $request->name;
                    $magazine->heading_ar = $request->namear;
                    $magazine->image = $imageName;
                    $magazine->sort_description = $request->discription;
                    $magazine->sort_description_ar = $request->discriptionar;
                    $magazine->description = $request->discription;
                    $magazine->description_ar = $request->discriptionar;
                    $magazine->videourl = "";
                    $magazine->postedBy = "";
                    $magazine->postedBy_ar = "";
                    $magazine->postedDate = $request->startDate;
                    $magazine->end_date = $request->endDate;
                    $magazine->contact_number = "";
                    $magazine->website = "";
                    $magazine->address = "";
                    $magazine->facebook = "";
                    $magazine->instagram = "";
                    $magazine->linkedin = "";
                    $magazine->twitter = "";
                    $magazine->type = 1;
                    $magazine->tag_icon = $tag_icon;
                    $magazine->tag_name = $request->tag_name;
                    $magazine->tag_name_ar = $request->tag_namear;
                    $magazine->sponser_icon = "";
                    $magazine->sponser_name = "";
                    $magazine->home = 0;
                    $magazine->position = 0;
                    $magazine->status = 1;
                    $magazine->dealid = $insert->id;
                    $magazine->save();
                }

                // if(!empty($request->city)){
                //     foreach($citydata as $val){
                //         $deal_details = new DealDetail;
                //         $deal_details->dealID = $insert->id;
                //         $deal_details->cityID = $val;
                //         $deal_details->dealTypeID = $request->dealtypeid;
                //         $deal_details->dealType = $request->dealtype;
                //         $deal_details->save();
                //     }
                // }else{
                //     $deal_details = new DealDetail;
                //     $deal_details->dealID = $insert->id;
                //     $deal_details->cityID = '';
                //     $deal_details->dealTypeID = $request->dealtypeid;
                //     $deal_details->dealType = $request->dealtype;
                //     $deal_details->save();
                // }

                echo 1;
                if (session::get('usertype') == 2) {
                    $log = array(
                        'staff_id' => session::get('id'),
                        'logDate' => date('Y-m-d'),
                        'event_name' => "Add Deal",
                        'event_category' => 17,
                        'event_id' => $insert->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            } else {
                echo 2;
            }
        } else {
            $check_name = Deal::where('name', $request->name)->where('id', $request->id);
            if ($check_name->count() >= 1 && $request->id != $check_name->first()->id) {
                echo 2;
            } else {
                // $stateid = implode(",",$request->state);
                if (!empty($request->state)) {
                    $stateid = implode(",", $request->state);
                } else {
                    $stateid = 0;
                }
                //image upload
                if ($request->images != '') {
                    $new_width = 720;
                    $new_height = 480;
                    $file = $request->file('images');
                    $fileName = $file->getRealPath();
                    $uploadPath = public_path('images/');
                    $fileExt = $file->getClientOriginalExtension();
                    $imgname = "thump_";
                    $imageName = ExternalSystem::saveresizeimage($new_width, $new_height, $fileName, $uploadPath, $fileExt, $imgname);
                } else {
                    $imageName = "";
                }

                //tag icon
                if ($request->tag_icon != '') {
                    $new_widtht = 100;
                    $new_heightt = 100;
                    $filet = $request->file('tag_icon');
                    $fileNamet = $filet->getRealPath();
                    $uploadPatht = public_path('images/');
                    $fileExtt = $filet->getClientOriginalExtension();
                    $imgnamet = "tagthump_";
                    $tag_icon = ExternalSystem::saveresizeimage($new_widtht, $new_heightt, $fileNamet, $uploadPatht, $fileExtt, $imgnamet);
                } else {
                    $tag_icon = '';
                }


                if ($request->page != "") {
                    $page = $request->page;
                } else {
                    $page = 0;
                }
                if ($request->pageid != "") {
                    if ($request->page == 1) {
                        $pageid = 0;
                    } else {
                        $pageid = $request->pageid;
                    }
                } else {
                    $pageid = 0;
                }
                $insert = new Deal;
                $insert = Deal::find($request->id);
                $insert->name = $request->name;
                $insert->name_ar = $request->namear;
                if ($imageName != '') {
                    $insert->image = $imageName;
                }

                $insert->discription = $request->discription;
                $insert->discription_ar = $request->discriptionar;
                $insert->startDate = $request->startDate;
                $insert->endDate = $request->endDate;
                $insert->dealType = $request->dealtype;
                $insert->typesofdeal = $request->dealtypesid;
                $insert->tag_name = $request->tag_name;
                $insert->tag_name_ar = $request->tag_namear;
                $insert->state = $stateid;
                $insert->country = $request->country;
                $insert->internalpage = $page;
                $insert->pageid = $pageid;
                if ($tag_icon != '') {
                    $insert->tag_icon = $tag_icon;
                }
                $insert->addasanews = $addasanews;
                $insert->whereshow = $request->whereshow;
                $insert->save();
                //detail
                DealDetail::where('dealID', $request->id)->delete();
                // if (in_array("All", $request->city)){
                //     if(in_array("All",$request->state)){
                //         $stateArray = array();
                //         $states = State::select('id')->where('country_id',$request->country)->get();
                //         foreach($states as $statevalue){
                //             array_push($stateArray,$statevalue->id);
                //         }
                //         $cityArray = array();
                //         $city = City::whereIN('state_id',$stateArray)->get();
                //         foreach($city as $value){
                //             array_push($cityArray,$value->id);
                //         }
                //         $citydata = $cityArray;
                //     }else{
                //         $cityArray = array();
                //         $city = City::whereIN('state_id',$request->state)->get();
                //         foreach($city as $value){
                //             array_push($cityArray,$value->id);
                //         }
                //         $citydata = $cityArray;
                //     }

                // }else{
                //     $citydata = $request->city;
                // }
                $citydata = $request->city;
                foreach ($citydata as $val) {
                    $deal_details = new DealDetail;
                    $deal_details->dealID = $insert->id;
                    $deal_details->cityID = $val;
                    $deal_details->dealTypeID = $request->dealtypeid;
                    $deal_details->dealType = $request->dealtype;
                    $deal_details->save();
                }

                if ($addasanews == 1) {
                    $checkmag = Magazine::where('dealid', $request->id);
                    if ($checkmag->count() == 0) {
                        $magazine = new Magazine;
                        $magazine->heading = $request->name;
                        $magazine->heading_ar = $request->namear;
                        $magazine->image = $imageName;
                        $magazine->sort_description = $request->discription;
                        $magazine->sort_description_ar = $request->discriptionar;
                        $magazine->description = $request->discription;
                        $magazine->description_ar = $request->discriptionar;
                        $magazine->videourl = "";
                        $magazine->postedBy = "";
                        $magazine->postedBy_ar = "";
                        $magazine->postedDate = $request->startDate;
                        $magazine->end_date = $request->endDate;
                        $magazine->contact_number = "";
                        $magazine->website = "";
                        $magazine->address = "";
                        $magazine->facebook = "";
                        $magazine->instagram = "";
                        $magazine->linkedin = "";
                        $magazine->twitter = "";
                        $magazine->type = 1;
                        $magazine->tag_icon = $tag_icon;
                        $magazine->tag_name = $request->tag_name;
                        $magazine->tag_name_ar = $request->tag_namear;
                        $magazine->sponser_icon = "";
                        $magazine->sponser_name = "";
                        $magazine->home = 0;
                        $magazine->position = 0;
                        $magazine->status = 1;
                        $magazine->dealid = $request->id;
                        $magazine->save();
                    } else {
                        $magazine = Magazine::find($checkmag->first()->id);
                        $magazine->heading = $request->name;
                        $magazine->heading_ar = $request->namear;
                        if ($imageName != '') {
                            $magazine->image = $imageName;
                        }
                        $magazine->sort_description = $request->discription;
                        $magazine->sort_description_ar = $request->discriptionar;
                        $magazine->description = $request->discription;
                        $magazine->description_ar = $request->discriptionar;
                        $magazine->videourl = "";
                        $magazine->postedBy = "";
                        $magazine->postedBy_ar = "";
                        $magazine->postedDate = $request->startDate;
                        $magazine->end_date = $request->endDate;
                        $magazine->contact_number = "";
                        $magazine->website = "";
                        $magazine->address = "";
                        $magazine->facebook = "";
                        $magazine->instagram = "";
                        $magazine->linkedin = "";
                        $magazine->twitter = "";
                        $magazine->type = 1;
                        if ($tag_icon != "") {
                            $magazine->tag_icon = $tag_icon;
                        }
                        $magazine->tag_name = $request->tag_name;
                        $magazine->tag_name_ar = $request->tag_namear;
                        $magazine->sponser_icon = "";
                        $magazine->sponser_name = "";
                        $magazine->home = 0;
                        $magazine->position = 0;
                        $magazine->status = 1;
                        $magazine->dealid = $request->id;
                        $magazine->save();
                    }
                }
                /////////////////////

                // if(!empty($request->city)){
                //     $brandagent = DealDetail::select('cityID')->where('dealID',$request->id)->get();
                //     $brandarray = array();
                //     foreach($brandagent as $val){
                //         array_push($brandarray,$val->cityID);
                //     }
                //     $result=array_diff($brandarray,$request->city);
                //     foreach($request->city as $valb){
                //         $checkbrandid = DealDetail::where('cityID',$valb)->where('dealID',$request->id);
                //         if($checkbrandid->count()==0){
                //             $deal_details = new DealDetail;
                //             $deal_details->dealID = $insert->id;
                //             $deal_details->cityID = $valb;
                //             $deal_details->dealTypeID = $request->dealtypeid;
                //             $deal_details->dealType = $request->dealtype;
                //             $deal_details->save();
                //         }else{
                //             $deal_details = DealDetail::find($checkbrandid->first()->id);
                //             $deal_details->dealTypeID = $request->dealtypeid;
                //             $deal_details->dealType = $request->dealtype;
                //             $deal_details->save();
                //         }
                //     }
                //     foreach($result as $rval){
                //         DealDetail::where('cityID',$rval)->where('dealID',$request->id)->delete();
                //     }
                // }else{
                //     $checkbrandid = DealDetail::where('dealID',$request->id)->first();
                //     $deal_details = DealDetail::find($checkbrandid->id);
                //     $deal_details->dealTypeID = $request->dealtypeid;
                //     $deal_details->dealType = $request->dealtype;
                //     $deal_details->save();
                // }

                echo 1;
                if (session::get('usertype') == 2) {
                    $log = array(
                        'staff_id' => session::get('id'),
                        'logDate' => date('Y-m-d'),
                        'event_name' => "Update Deal",
                        'event_category' => 17,
                        'event_id' => $request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }
        }
    }

    public function delete_deal(Request $request)
    {
        $id = $request->id;
        $category = Deal::find($id);
        $category->status = 2;
        $category->save();
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Delete Deal",
                'event_category' => 17,
                'event_id' => $request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_deal_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $category = Deal::find($id);
        $category->status = $status;
        $category->save();
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Change Deal Status",
                'event_category' => 17,
                'event_id' => $request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function add_popular_deal(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $category = Deal::find($id);
        $category->popular = $status;
        $category->save();
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Add Deal in popular list",
                'event_category' => 17,
                'event_id' => $request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function updateDealOrder(Request $request)
    {
        $arr = $request->allData;
        $num = 0;
        for ($i = 0; $i < count($arr); $i++) {
            $brand = Deal::find($arr[$i]);
            $brand->position = $num = $num + 1;
            $brand->save();
        }
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Update Deal Order",
                'event_category' => 17,
                'event_id' => 0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    //deal controller
    public function dealtype(Request $request)
    {
        $admin_id = Session::get('id');
        $data['title'] = "Deal Types";
        $data['active'] = "master";
        $data['active1'] = "DealType";
        $data['admin'] = Admin::find($admin_id);
        $data['data'] = DealType::where('status', '!=', 2)->get();
        return view('admin.dealtype', $data);
    }

    public function adddealtype(Request $request)
    {
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Deal Type";
        }else{
            $data['title'] = "Edit Deal Type";
        }
        $data['active'] = "master";
        $data['active1'] = "DealType";
        $data['admin'] = Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = DealType::find($id);
        return view('admin.adddealtype', $data);
    }

    public function insert_dealtype(Request $request)
    {
        if ($request->id == "") {
            $check_name = DealType::where('name', $request->name)->count();
            $check_namear = DealType::where('name_ar', $request->namear)->count();
            if ($check_name == 0 && $check_namear == 0) {
                $serviceType = new DealType;
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->status = 1;
                $serviceType->save();
                echo 1;
                if (session::get('usertype') == 2) {
                    $log = array(
                        'staff_id' => session::get('id'),
                        'logDate' => date('Y-m-d'),
                        'event_name' => "Add Deal type",
                        'event_category' => 4,
                        'event_id' => $serviceType->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            } else {
                echo 2;
            }
        } else {
            $check_name = DealType::where('name', $request->name)->where('id', $request->id);
            $check_namear = DealType::where('name_ar', $request->namear)->where('id', $request->id);
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
                $serviceType = DealType::find($request->id);
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->save();
                echo 1;
                if (session::get('usertype') == 2) {
                    $log = array(
                        'staff_id' => session::get('id'),
                        'logDate' => date('Y-m-d'),
                        'event_name' => "Update Deal type",
                        'event_category' => 4,
                        'event_id' => $request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            } else {
                echo 2;
            }
        }
    }
    public function delete_dealtype(Request $request)
    {
        $id = $request->id;
        $serviceType = DealType::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Delete Deal type",
                'event_category' => 4,
                'event_id' => $request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_dealtype_status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $serviceType = DealType::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        if (session::get('usertype') == 2) {
            $log = array(
                'staff_id' => session::get('id'),
                'logDate' => date('Y-m-d'),
                'event_name' => "Change Deal type status",
                'event_category' => 4,
                'event_id' => $request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function getmultipleState(Request $request)
    {
        $country_id = $request->country_id;
        $state = State::where('country_id', $country_id)->orderBy('name', 'ASC')->get();
        //echo "<option value='All'>All</option>";
        echo '<a class="dropdown-option stateallcheck" onclick="checkallstate()" style="cursor:pointer">
        Check All
        </a>';
        foreach ($state as $value) {
            //echo "<option value='".$value->id."'>".$value->name."</option>";
            echo '<label class="dropdown-option">
              <input type="checkbox" name="state[]" class="checkstatename" onchange="checkedstatevalue()" value="' . $value->id . '">' . $value->name . '
            </label>';
        }
    }

    public function getmultipleCity(Request $request)
    {
        $state_id = $request->state_id;
        $country_id = $request->country_id;
        if ($country_id != 191) {
            if (!empty($state_id)) {
                $city = City::whereIN('state_id', $state_id)->orderBy('name', 'ASC')->get();
                echo '<a class="dropdown-option cityallcheck" onclick="checkallcity()" style="cursor:pointer">
                    Check All
                    </a>';
                foreach ($city as $value) {
                    echo '<label class="dropdown-option">
                    <input type="checkbox" name="city[]" class="checkcityname" value="' . $value->id . '">' . $value->name . '
                    </label>';
                    //echo "<option value='".$value->id."'>".$value->name."</option>";
                }
            }
        } else {
            $city = City::where('countryid', $country_id)->orderBy('name', 'ASC')->get();
            echo '<a class="dropdown-option cityallcheck" onclick="checkallcity()" style="cursor:pointer">
                Check All
                </a>';
            foreach ($city as $value) {
                echo '<label class="dropdown-option">
                <input type="checkbox" name="city[]" class="checkcityname" value="' . $value->id . '">' . $value->name . '
                </label>';
                //echo "<option value='".$value->id."'>".$value->name."</option>";
            }
        }

        //print_r($state_id);
        // if (in_array("All", $state_id)){
        //     $stateArray = array();
        //     $states = State::select('id')->where('country_id',$country_id)->get();
        //     foreach($states as $statevalue){
        //         array_push($stateArray,$statevalue->id);
        //     }
        //     $state = $stateArray;
        // }else{
        //     $state = $state_id;
        // }
        // $city = City::whereIN('state_id',$state)->get();
        // echo "<option value='All'>All</option>";
        // foreach($city as $value){
        //     echo "<option value='".$value->id."'>".$value->name."</option>";
        // }
    }

    public function filter_dealtypedata(Request $request)
    {
        if ($request->id != "") {
            $deal = Deal::where('typesofdeal', $request->id)->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        } else {
            $deal = Deal::where('status', '!=', 2)->orderBy('position', 'asc')->get();
        }

        $i = 1;
        foreach ($deal as $value) {
            $image = url('public/images/' . $value->image);
            if ($value->typesofdeal != "") {
                $dealtypename = DealType::find($value->typesofdeal)->name;
            } else {
                $dealtypename = "";
            }
            if ($value->status == 1) {
                $check = "checked";
            } else {
                $check = "";
            }
            if ($value->popular == 1) {
                $checkp = "checked";
            } else {
                $checkp = "";
            }
            $visited = Viewed::where('menu_type', 4)
                ->where('menuID', $value->id)
                ->count();
            echo '<tr id="' . $value->id . '">
                                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                                    <td>' . $i . '</td>
                                    <td>
                                        <img class="img-fluid" src="' . $image . '" alt width="100" height="100">
                                    </td>
                                    <td>' . $value->name . '</td>
                                    <td>' . $value->name_ar . '</td>
                                    <td style="display: none">' . $value->discription . '</td>
                                    <td style="display: none">' . $value->discription_ar . '</td>
                                    <td>' . $dealtypename . '</td>
                                    <td>' . $value->startDate . '</td>
                                    <td>' . $value->endDate . '</td>
                                    <td style="display: none">' . $value->tag_name . '</td>
                                    <td style="display: none">' . $value->tag_name_ar . '</td>
                                    <td>' . $visited . '</td>
                                    <td>' . $value->log . '</td>
                                    <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input flexSwitchCheckChecked" type="checkbox" role="switch" id="flexSwitchCheckChecked" data-id="' . $value->id . '" ' . $check . '>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input flexSwitchCheckCheckedpopular" type="checkbox" role="switch" id="flexSwitchCheckCheckedpopular" data-id="' . $value->id . '" ' . $checkp . '>
                                    </div>
                                </td>
                                <td><a href="' . url('admin/addDeal?key=' . base64_encode($value->id)) . '"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="' . $value->id . '"><i class="fa fa-trash"></i></a></td>

                                </tr>';

            $i++;
        }
    }

    public function getmultiplepage(Request $request)
    {

        $id = $request->id;
        if ($id == 2) {
            //brand
            $data = Brand::where('status', 1)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
            }
        }
        if ($id == 3) {
            //car
            $data = Vehicle::where('status', 1)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
            }
        }
        if ($id == 4) {
            //agent
            $data = Agent::where('status', 1)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
            }
        }
        if ($id == 5) {
            //event
            $data = Magazine::where('status', 1)->where('type', 4)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->heading . "</option>";
            }
        }
        if ($id == 6) {
            //news
            $data = Magazine::where('status', 1)->where('type', '!=', 4)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->heading . "</option>";
            }
        }
        if ($id == 7) {
            //deal
            $data = Deal::where('status', 1)->get();
            foreach ($data as $value) {
                echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
            }
        }
    }


    //testuse App\Models\Country;
    //use App\Models\State;
    //use App\Models\City;
    public function updatecountryincity()
    {
        for ($i = 7; $i <= 246; $i++) {
            $this->runUpdate($i);
        }
        // $this->runUpdate(7);
        echo 1;
        // $state = State::select('id')->where('country_id',1)->get();
        // foreach($state as $value){
        //     $update = array('countryid'=>$value->country_id);
        //     City::where('state_id',$value->id)->update($update);
        // }
    }

    public function runUpdate($countryId)
    {
        // Fetch all states of the specified country
        $stateIds = State::where('country_id', $countryId)->pluck('id');
        print_r($stateIds);
        // Update all cities in one query
        City::whereIn('state_id', $stateIds)->update(['countryid' => $countryId]);
    }
}
