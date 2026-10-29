<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    //Notification list
    public function notification(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //update
            $current_date = date('Y-m-d');
            $current_time = date('H:i');
            $sql = Notification::where('end_date','<',$current_date)->where('end_time','<=',$current_time)->update(['status'=>2]);
            $sql2 = Notification::where('end_date',$current_date)->where('end_time','<=',$current_time)->update(['status'=>2]);
            $notification = Notification::where('UserID',$userID)->where('status',1)->where('notify_type',1)->orderBy('id','DESC')->get();
            $notificationarray = array();
            foreach($notification as $value){
                $data['id'] = $value->id;
                $data['url'] = $value->url;
                $data['internal_external'] = $value->internal_external;
                $data['image'] = $value->image;
                $data['created_at'] = $value->created_at;
                if($language == 2){
                    $data['title'] = $value->title_ar;
                    $data['message'] = $value->message_ar;
                }else{
                    $data['title'] = $value->title;
                    $data['message'] = $value->message;
                }
                array_push($notificationarray,$data);
            }
            return response()->json(['success'=>'true','message'=>'','result'=>$notificationarray], 200);
        }
    }

    //Notification delete
    public function notificationdelete(Request $request){
        $userID = $request->userID;
        $notification_id = $request->notification_id;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($notification_id==null){
            $err_array[]='notification_id';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "حذف بنجاح";
            }else{
                $msg_success = "Deleted successfully.";
            }
            $notification = Notification::where('id',$notification_id)->first();
            $notification->status = 2;
            $notification->save();
            return response()->json(['success'=>'true','message'=>$msg_success], 200);
        }
    }

    //Notification Hide
    public function notification_hide(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $fav_brand = $request->fav_brand;
        $fav_car = $request->fav_car;
        $fav_agency = $request->fav_agency;
        $fav_news = $request->fav_news;
        $userValue = $request->userValue;
        $adviseValue = $request->adviseValue;
        $otherAllValue = $request->otherAllValue;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "حبنجاح";
            }else{
                $msg_success = "Successfully saved.";
            }
            $values = [
                (int) $fav_brand,
                (int) $fav_car,
                (int) $fav_agency,
                (int) $fav_news,
                (int) $userValue,
                (int) $adviseValue,
                (int) $otherAllValue
            ];
            
            
            $user = User::find($userID);
            //$user->notification_hide = implode(',', $values);
            $user->notification_hide = $fav_brand.','.$fav_car.','.$fav_agency.','.$fav_news.','.$userValue.','.$adviseValue.','.$otherAllValue;
            $user->save();
            return response()->json(['success'=>'true','message'=>$msg_success], 200);
        }
    }
    public function notification_hidedata(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            $userdata = User::where('id',$userID)->first();
            if($userdata->notification_hide!=""){
                $notification_hide = explode(',',$userdata->notification_hide);
                $arr['fav_brand'] = (int)$notification_hide[0];
                $arr['fav_car'] = (int)$notification_hide[1];
                $arr['fav_agency'] = (int)$notification_hide[2];
                $arr['fav_news'] = (int)$notification_hide[3];
                $arr['userValue'] = (int)$notification_hide[4];
                $arr['adviseValue'] = (int)$notification_hide[5];
                $arr['otherAllValue'] = (int)$notification_hide[6];
            }else{
                $arr['fav_brand'] = 0;
                $arr['fav_car'] = 0;
                $arr['fav_agency'] = 0;
                $arr['fav_news'] = 0;
                $arr['userValue'] = 0;
                $arr['adviseValue'] = 0;
                $arr['otherAllValue'] = 0;
            }
            return response()->json(['success'=>'true','message'=>"",'result'=>$arr], 200);
        }
    }

    public function pngnotification(Request $request){
        $userID = $request->userID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //update
            $notification = Notification::where('UserID',$userID)->where('status',1)->where('notify_type',2)->where('read_notify',0)->orderBy('id','DESC')->take(1);
            if($notification->count()!=0){
                $value = $notification->first();
                $data['id'] = $value->id;
                $data['url'] = $value->url;
                $data['internal_external'] = $value->internal_external;
                $data['image'] = $value->image;
                $data['created_at'] = $value->created_at;
                if($language == 2){
                    $data['title'] = $value->title_ar;
                    $data['message'] = $value->message_ar;
                }else{
                    $data['title'] = $value->title;
                    $data['message'] = $value->message;
                }
                return response()->json(['success'=>'true','message'=>'','result'=>$data], 200);
            }else{
                return response()->json(['success'=>'false','message'=>'No data found'], 200);
            }


        }
    }

    public function pngnotificationread(Request $request){
        $userID = $request->userID;
        $notificationid = $request->notificationid;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($notificationid==null){
            $err_array[]='notificationid';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //update
            $Notification = Notification::where('id',$notificationid)->first();
            $Notification->read_notify = 1;
            $Notification->save();
            return response()->json(['success'=>'true','message'=>''], 200);
        }
    }
}
