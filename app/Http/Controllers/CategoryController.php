<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Language;
use App\Models\StaffLogEvent;
class CategoryController extends Controller
{
    public function category(){
        $admin_id = Session::get('id');
        $data['title'] = "Categories";
        $data['active'] = "master";
        $data['active1'] = "Category";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = Category::where('status','!=',2)->get();
        return view('admin.category',$data);
    }
    public function addCategory(Request $request){
        $admin_id = Session::get('id');
        if($request->key==""){
            $data['title'] = "Add Category";
        }else{
            $data['title'] = "Edit Category";
        }
        $data['active'] = "master";
        $data['active1'] = "Category";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = Category::find($id);
        return view('admin.addCategory',$data);
    }

    public function insert_category(Request $request){
        if($request->id==""){
            $check_name = Category::where('name',$request->name)->count();
            if($check_name==0){
                $category = new Category;
                $category->name = $request->name;
                $category->name_ar = $request->namear;
                $category->status = 1;
                $category->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Add Category",
                        'event_category'=>1,
                        'event_id'=>$category->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else{
                echo 2;
            }
        }else{
           $check_name = Category::where('name',$request->name)->count();
           $check_namear = Category::where('name_ar',$request->namear)->count();
            if($check_name==0){
                $category = new Category;
                $category = Category::find($request->id);
                $category->name = $request->name;
                $category->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Category",
                        'event_category'=>1,
                        'event_id'=>$request->id,
                    );
                    // print_r($log);
                    $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                }
            }else if($check_namear==0){
                $category = new Category;
                $category = Category::find($request->id);
                $category->name_ar = $request->namear;
                $category->save();
                echo 1;
                if(session::get('usertype')==2){
                    $log = array(
                        'staff_id'=>session::get('id'),
                        'logDate'=>date('Y-m-d'),
                        'event_name'=>"Update Category",
                        'event_category'=>1,
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
    public function delete_category(Request $request){
        $id = $request->id;
        $category = Category::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Category",
                'event_category'=>1,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function change_category_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Category::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Category Status",
                'event_category'=>1,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
}
