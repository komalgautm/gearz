<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use App\Models\Category;
use App\Models\ServiceType;
use App\Models\StaffLogEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use App\Models\MycarServiceTask;
use App\Models\ServiceIcon;
use App\Modules\ExternalSystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceTypeController extends Controller
{
    public function serviceType(){
        $admin_id = Session::get('id');
        $data['title'] = "Service Types";
        $data['active'] = "master";
        $data['active1'] = "ServiceType";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = ServiceType::where('status','!=',2)->get();
        return view('admin.servicetype',$data);
    }
    public function addserviceType(Request $request){
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Service Type";
        }else{
            $data['title'] = "Edit Service Type";
        }
        $data['active'] = "master";
        $data['active1'] = "ServiceType";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = ServiceType::find($id);
        return view('admin.addServiceType',$data);
    }

    public function insert_serviceType(Request $request){
        $validator = Validator::make($request->all(),
            [
                //'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:max_width=40,max_height=40'
            ]
        );
        if ($validator->fails()){
            $errors = $validator->errors();
            echo 3;
        }else{
            if($request->image!=''){
                $new_width = 100;
                $new_height = 100;
                $file = $request->file('image');
                $fileName = $file->getRealPath();
                $uploadPath = public_path('images/');
                $fileExt = $file->getClientOriginalExtension();
                $imgname = "thump_";
                $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                // $imageName = time().'.'.$request->image->extension();
                // $request->image->move(public_path('images'), $imageName);
            }else{
                $imageName = '';
            }
            if($request->id==""){
                $check_name = ServiceType::where('name',$request->name)->count();
                $check_namear = ServiceType::where('name_ar',$request->namear)->count();
                if($check_name==0 && $check_namear==0){
                    $serviceType = new ServiceType;
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    $serviceType->image = $imageName;
                    $serviceType->status = 1;
                    $serviceType->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Service Type",
                            'event_category'=>2,
                            'event_id'=>$serviceType->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 2;
                }
            }else{
                $check_name = ServiceType::where('name',$request->name)->where('id',$request->id);
                $check_namear = ServiceType::where('name_ar',$request->namear)->where('id',$request->id);
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
                    $serviceType = new ServiceType;
                    $serviceType = ServiceType::find($request->id);
                    $serviceType->name = $request->name;
                    $serviceType->name_ar = $request->namear;
                    if($imageName!=""){
                        $serviceType->image = $imageName;
                    }
                    $serviceType->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Update Service Type",
                            'event_category'=>2,
                            'event_id'=>$request->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }else{
                    echo 2;
                }
                // if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                //    // echo 2;
                //     $st = 2;
                // }else{
                //     $serviceType = new ServiceType;
                //     $serviceType = ServiceType::find($request->id);
                //     $serviceType->name = $request->name;
                //     $serviceType->name_ar = $request->namear;
                //     if($imageName!=""){
                //         $serviceType->image = $imageName;
                //     }
                //     $serviceType->save();
                //     echo 1;
                // }
            }
        }
    }
    public function delete_serviceType(Request $request){
        $id = $request->id;
        $serviceType = ServiceType::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Service Type",
                'event_category'=>2,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_servicetype_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $serviceType = ServiceType::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Service Type Status",
                'event_category'=>2,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    //service
    public function service(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Services";
        $data['active'] = "master";
        $data['active1'] = "Service";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = MycarServiceTask::where('status','!=',2)->where('userID',0)->get();
        return view('admin.service',$data);
    }

    public function addservice(Request $request){
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Service";
        }else{
            $data['title'] = "Edit Service";
        }
        $data['active'] = "master";
        $data['active1'] = "Service";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = MycarServiceTask::find($id);
        return view('admin.addService',$data);
    }

    public function insert_service(Request $request){
        if($request->id==""){
            $check_name = MycarServiceTask::where('name',$request->name)->where('userID',0)->count();
            $check_namear = MycarServiceTask::where('name_ar',$request->namear)->where('userID',0)->count();
            if($check_name==0 && $check_namear==0){
                $serviceType = new MycarServiceTask;
                $serviceType->userID = 0;
                $serviceType->mycarID = 0;
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->service_type = 1;
                $serviceType->status = 1;
                $serviceType->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Service",
                        'event_category'=>3,
                        'event_id'=>$serviceType->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = MycarServiceTask::where('name',$request->name)->where('id',$request->id);
            $check_namear = MycarServiceTask::where('name_ar',$request->namear)->where('id',$request->id);
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
                $serviceType = MycarServiceTask::find($request->id);
                $serviceType->name = $request->name;
                $serviceType->name_ar = $request->namear;
                $serviceType->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Service",
                        'event_category'=>3,
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

    public function delete_service(Request $request){
        $id = $request->id;
        $serviceType = MycarServiceTask::find($id);
        $serviceType->status = 2;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Service",
                'event_category'=>3,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_service_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $serviceType = MycarServiceTask::find($id);
        $serviceType->status = $status;
        $serviceType->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Service status",
                'event_category'=>3,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    //service icons
    public function serviceIcons(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Service Icons";
        $data['active'] = "master";
        $data['active1'] = "ServiceIcons";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = ServiceIcon::where('status','!=',2)->get();
        return view('admin.serviceIcons',$data);
    }

    public function addServiceIcon(Request $request){
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Service Icon";
        }else{
            $data['title'] = "Edit Service Icon";
        }
        $data['active'] = "master";
        $data['active1'] = "ServiceIcons";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = ServiceIcon::find($id);
        return view('admin.addServiceIcon',$data);
    }

    public function insert_service_icon(Request $request){
        // echo"<pre>";print_r($request->all());die;
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
            $imageName = $request->pre_icon;
        }
        if($request->id==""){
            $check_name = ServiceIcon::where('name_en',$request->name)->count();
            $check_namear = ServiceIcon::where('name_ar',$request->namear)->count();
            if($check_name==0 && $check_namear==0){
                $serviceIcon = new ServiceIcon;
                $serviceIcon->icon = $imageName;
                $serviceIcon->name_en = $request->name;
                $serviceIcon->name_ar = $request->namear;
                $serviceIcon->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Service Icon",
                        'event_category'=>3,
                        'event_id'=>$serviceIcon->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
            $check_name = ServiceIcon::where('name_en',$request->name)->where('id',$request->id);
            $check_namear = ServiceIcon::where('name_ar',$request->namear)->where('id',$request->id);
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
                $serviceIcon = ServiceIcon::find($request->id);
                $serviceIcon->icon = $imageName;
                $serviceIcon->name_en = $request->name;
                $serviceIcon->name_ar = $request->namear;
                $serviceIcon->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Service Icon",
                        'event_category'=>3,
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
    
    public function delete_service_icon(Request $request){
        $id = $request->id;
        $serviceIcon = ServiceIcon::find($id);
        $serviceIcon->status = 2;
        $serviceIcon->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Service Icon",
                'event_category'=>3,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_service_icon_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $serviceIcon = ServiceIcon::find($id);
        $serviceIcon->status = $status;
        $serviceIcon->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Service Icon status",
                'event_category'=>3,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
}
