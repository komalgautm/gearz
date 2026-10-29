<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Admin;
use App\Models\AccessRight;
use App\Models\ManagementSection;
use App\Models\StaffLogEvent;
use App\Models\StaffLog;
use App\Modules\ExternalSystem;
class StaffController extends Controller
{
    public function stafflist(Request $request){
        $admin_id = session::get('id');
        $data['data']=Admin::where('userType', '2')->get();
        $data['admin']=Admin::find($admin_id);
        $data['active'] = "master";
        $data['active1'] = "Staff";
        return view('admin.stafflist', $data);
    }

    public function addStaff(Request $request){
        $admin_id = session::get('id');
        $id = base64_decode($request->key);
        $data['data']=Admin::where('id', $id)->first();
        $data['admin']=Admin::find($admin_id);
        $data['active'] = "master";
        $data['active1'] = "Staff";
        //$data['AccessRight']=AccessRight::where('status',1)->get();
        $data['ManagementSection']=ManagementSection::where('status',1)->get();
        return view('admin.addstaff', $data);
    }

    public function insert_staff(Request $request){
        $admin_id = session::get('id');
        $access_rights = implode(",",$request->accessright);
        $managementsection = implode(",",$request->managementsection);
        if($request->id==""){
            $check_username = Admin::where('username',$request->username)->count();
            if($check_username==0){
                $check_email = Admin::where('email',$request->email)->count();
                if($check_email==0){
                    if($request->image!=''){
                        //image upload
                        $new_width = 720;
                        $new_height = 900;
                        $file = $request->file('image');
                        $fileName = $file->getRealPath();
                        $uploadPath = public_path('img/');
                        $fileExt = $file->getClientOriginalExtension();
                        $imgname = "thump_";
                        $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                        // $imageName = 'logo'.time().'.'.$request->image->extension();      
                        // $request->image->move(public_path('img'), $imageName);
                    }else{
                        $imageName = '';
                    }
                     //image upload
                    // $imageName = 'logo'.time().'.'.$request->image->extension();      
                    // $request->image->move(public_path('img'), $imageName);

                    $admin = new Admin;
                    $admin->image = $imageName;
                    $admin->name = $request->name;
                    $admin->email = $request->email;
                    $admin->phone = $request->phone;
                    $admin->address = $request->address;
                    $admin->username = $request->username;
                    $admin->password = md5($request->password);
                    $admin->access_right = $access_rights;
                    $admin->managementsection = $managementsection;
                    $admin->userType = 2;
                    $admin->status = 1;
                    $admin->save();
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Add Staff",
                            'event_category'=>6,
                            'event_id'=>$admin->id,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }

                }else{
                    echo "3";
                }

            }else{
                echo "2";
            }
        }else{
            if($request->image!=''){
                
                //image upload
                $new_width = 720;
                $new_height = 480;
                $file = $request->file('image');
                $fileName = $file->getRealPath();
                $uploadPath = public_path('img/');
                $fileExt = $file->getClientOriginalExtension();
                $imgname = "thump_";
                $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
                // $imageName = 'logo'.time().'.'.$request->image->extension();      
                // $request->image->move(public_path('img'), $imageName);
            }else{
                $imageName = '';
            } 
            $admin = Admin::find($request->id);
            if($imageName!=''){
            $admin->image = $imageName;
            }             
            $admin->name = $request->name;
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->access_right = $access_rights;
            $admin->managementsection = $managementsection;
            $admin->status = 1;
            $admin->save();
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update Staff",
                    'event_category'=>6,
                    'event_id'=>$request->id,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }

    public function log(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "master";
        $data['active1'] = "Staff";
        $id = base64_decode($request->key);
        $data['data']=StaffLog::where('staff_id', $id)->get();
        $data['staff_name']=Admin::find($id)->name;
        $data['admin']=Admin::find($admin_id);
        return view('admin.log', $data);
    }
    public function logDetail(Request $request){
        $admin_id = session::get('id');
        $data['active'] = "master";
        $data['active1'] = "Staff";
        $id = base64_decode($request->key);
        $staff_id = base64_decode($request->id);
        $data['data']=StaffLogEvent::where('logDate', date('Y-m-d',strtotime($id)))->where('staff_id',$staff_id)->get();
        $data['admin']=Admin::find($admin_id);
        return view('admin.logDetail', $data);
    }

    public function delete_staff(Request $request){
        $id = $request->id;
        $category = Admin::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Staff",
                'event_category'=>6,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_staff_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Admin::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Staff Status",
                'event_category'=>6,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function changestaffpassword(Request $request){
        $admin = Admin::find($request->staffid);
        $admin->password = md5($request->newpassword);
        $admin->save();
        echo 1;
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Password Change",
                'event_category'=>6,
                'event_id'=>$request->staffid,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
}
