<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\MyCar;
use App\Models\InternalAppPage;
use App\Models\StaffLogEvent;
use Google\Client as GoogleClient;
use App\Modules\ExternalSystem;
//use Google_Service_Drive;

class NotificationController extends Controller
{
    private $serviceAccountFile;
    function __construct()
    {
       $this->serviceAccountFile = storage_path('gearz.json');
    }
    public function notification(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Notifications";
        $data['active'] = "master";
        $data['active1'] = "Notification";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = Notification::where('status',1)->orderBy('id','DESC')->get();
        return view('admin.notification',$data);
    }

    public function notificationdetails(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Notification Detail";
        $data['active'] = "master";
        $data['active1'] = "Notification";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['language'] = base64_decode($request->lang);
        $data['data'] = Notification::where('id',$id)->first();
        return view('admin.notificationdetails',$data);
    }


    public function sendnotification(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Send Notification";
        $data['active'] = "master";
        $data['active1'] = "Notification";
        $data['admin']=Admin::find($admin_id);
        $data['user'] = User::where('status',1)->get();
        $data['brand'] = Brand::where('status',1)->get();
        $data['InternalAppPage']=InternalAppPage::where('id', '!=', 5)->get();
        return view('admin.sendnotification',$data);
    }

    public function getuser(Request $request){
        $type = $request->type;
        $value = $request->value;
        if($value==1){
            if($type==1){
                $user = User::get();
            }else if($type==2){
                $user = User::where('deviceType',2)->get();
            }else if($type==3){
                $user = User::where('deviceType',1)->get();
            }else if($type==4){
                $user = User::where('status',1)->get();
            }else if($type==5){
                $user = User::where('status',0)->get();
            }
            echo "<option value='0'>All</option>";
            foreach($user as $value){
                echo "<option value='".$value->id."'>".$value->name."</option>";
            }
        }else if($value==2){
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
        }else if($value==3){
            $gender = $request->gender;
            if($gender==1){
                $user = User::where('gender',1)->get();
            }else if($gender==2){
                $user = User::where('gender',2)->get();
            }else if($gender==3){
                $user = User::where('gender',3)->get();
            }
            //echo "<option value='0'>All</option>";
            foreach($user as $value){
                echo "<option value='".$value->id."'>".$value->name."</option>";
            }
        }


    }

    public function insertNotification(Request $request){
        $user = $request->user;
        $title = $request->name;
        $titlear = $request->namear;
        $message = $request->info;
        $messagear = $request->infoar;
        $usertype = $request->usertype;
        $internal_external = $request->inlineRadioOptions;
        //$url = $request->url;
        $image = $request->image;
        $end_date = $request->end_date;
        $end_time = $request->end_time;
        $current_date = date('Y-m-d');
        $current_time = date('H:i');
        $brand = $request->brand;
        $notifytype = $request->notifytype;
        if($request->inlineRadioOptions==2){
            $page = $request->page;
        }else{
            $page = $request->url;
        }
        // if($notifytype==2){
        //     $notify_type_time =$request->notify_type_time;
        // }else{
        //     $notify_type_time = "";
        // }
        $notify_type_time = 0;
        //image upload
        if($request->image!=''){
            $new_width = 720;
            $new_height = 480;
            $file = $request->file('image');
            $fileName = $file->getRealPath();
            $uploadPath = public_path('images/');
            $fileExt = $file->getClientOriginalExtension();
            $imgname = "thump_";
            $imageName = ExternalSystem::saveresizeimage($new_width,$new_height,$fileName,$uploadPath,$fileExt,$imgname);
            // $imageName = time().'.'.$request->image->extension();
            // $request->image->move(public_path('images'), $imageName);
        }else{
            $imageName='';
        }

        //die;
        if($notifytype==1){
            $ready=1;
            if($end_time!=''){
                if($current_date==$end_date){
                    if($current_time <= $end_time){
                        $ready = 2;
                    }else{
                        $ready = 1;
                    }
                }else{
                    $ready = 1;
                }

            }else{
                $ready = 1;
            }
            if($ready==1){

                if(in_array("0", $user))
                {
                    if($usertype==1){
                        $userlist = User::get();
                    }else if($usertype==2){
                        $userlist = User::where('deviceType',2)->get();
                    }else if($usertype==3){
                        $userlist = User::where('deviceType',1)->get();
                    }else if($usertype==4){
                        $userlist = User::where('status',1)->get();
                    }else if($usertype==5){
                        $userlist = User::where('status',0)->get();
                    }else if($usertype==6){
                        $mycar = MyCar::where('make_id',$brand)->get();
                        $userarray = array();
                        foreach($mycar as $carval){
                            if(!in_array($carval->userID,$userarray)){
                                array_push($userarray,$carval->userID);
                            }
                        }
                        foreach($userarray as $userval){
                            $uval = User::where('id',$userval)->first();
                            $devicetoken = $uval->deviceToken;
                            $userlanguage = $uval->language;
                            //$this->sendNotifications($devicetoken,$title,$message);
                            $notification = new Notification;
                            $notification->UserID = $userval;
                            $notification->title = $title;
                            $notification->message = $message;
                            $notification->title_ar = $titlear;
                            $notification->message_ar = $messagear;
                            $notification->type = $usertype;
                            $notification->url = $page;
                            $notification->image = $imageName;
                            $notification->end_date = $end_date;
                            $notification->end_time = $end_time;
                            $notification->internal_external = $request->inlineRadioOptions;
                            $notification->notify_type = $request->notifytype;
                            $notification->notify_type_time = $notify_type_time;
                            $notification->status = 1;
                            $notification->save();
                            if($userlanguage==2){
                                $notimessage = $messagear;
                                $notititle = $titlear;
                            }else{
                                $notimessage = $message;
                                $notititle = $title;
                            }
                            if($notifytype==1){
                                //$this->send_notification($devicetoken, $notimessage, $notititle,$imageName,$page,$internal_external);
                            }

                        }
                    }
                    //$userlist = User::where('status',1)->get();
                    if($usertype!=6){
                        foreach($userlist as $value){
                            $devicetoken = $value->deviceToken;
                            $userlanguages = $value->language;
                            //$this->sendNotifications($devicetoken,$title,$message);
                            //new
                            $notification = new Notification;
                            $notification->UserID = $value->id;
                            $notification->title = $title;
                            $notification->message = $message;
                            $notification->title_ar = $titlear;
                            $notification->message_ar = $messagear;
                            $notification->type = $usertype;
                            $notification->url = $page;
                            $notification->image = $imageName;
                            $notification->end_date = $end_date;
                            $notification->end_time = $end_time;
                            $notification->internal_external = $request->inlineRadioOptions;
                            $notification->notify_type = $request->notifytype;
                            $notification->notify_type_time = $notify_type_time;
                            $notification->status = 1;
                            $notification->save();
                            if($userlanguages==2){
                                $notimessages = $messagear;
                                $notititles = $titlear;
                            }else{
                                $notimessages = $message;
                                $notititles = $title;
                            }
                            if($notifytype == 1){
                                //$this->send_notification($devicetoken, $notimessages, $notititles,$imageName,$page,$internal_external);
                            }
                        }
                    }
                    // $notification = new Notification;
                    // $notification->UserID = 0;
                    // $notification->title = $title;
                    // $notification->message = $message;
                    // $notification->type = $usertype;
                    // $notification->url = $url;
                    // $notification->image = $imageName;
                    // $notification->end_date = $end_date;
                    // $notification->end_time = $end_time;
                    // $notification->status = 1;
                    // $notification->save();
                    //$this->send_notification($devicetoken, $message, $title);
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Send Notification",
                            'event_category'=>5,
                            'event_id'=>0,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }

                }else{
                    foreach($user as $value){
                        $userlist = User::find($value);
                        $devicetoken = $userlist->deviceToken;
                        $userlanguages = $userlist->language;
                        //$this->sendNotifications($devicetoken,$title,$message);
                        $notification = new Notification;
                        $notification->UserID = $value;
                        $notification->title = $title;
                        $notification->message = $message;
                        $notification->title_ar = $titlear;
                        $notification->message_ar = $messagear;
                        $notification->type = $usertype;
                        $notification->url = $page;
                        $notification->image = $imageName;
                        $notification->end_date = $end_date;
                        $notification->end_time = $end_time;
                        $notification->internal_external = $request->inlineRadioOptions;
                        $notification->notify_type = $request->notifytype;
                        $notification->notify_type_time = $notify_type_time;
                        $notification->status = 1;
                        $notification->save();
                        if($userlanguages==2){
                            $notimessages = $messagear;
                            $notititles = $titlear;
                        }else{
                            $notimessages = $message;
                            $notititles = $title;
                        }
                        if($notifytype == 1){
                            //$this->send_notification($devicetoken, $notimessages, $notititles,$imageName,$page,$internal_external);
                        }

                    }
                    echo 1;
                    if(session::get('usertype')==2){
                        $log = array(
                            'staff_id'=>session::get('id'),
                            'logDate'=>date('Y-m-d'),
                            'event_name'=>"Send Notification",
                            'event_category'=>5,
                            'event_id'=>0,
                        );
                        // print_r($log);
                        $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                    }
                }
            }else{
                echo 2;
            }
        }else{
            $userlist = User::where('status',1)->where('is_login',1)->get();
            foreach($userlist as $value){
                $notification = new Notification;
                $notification->UserID = $value->id;
                $notification->title = $title;
                $notification->message ="";
                $notification->title_ar = $titlear;
                $notification->message_ar = "";
                $notification->type = 0;
                $notification->url = $page;
                $notification->image = $imageName;
                //$notification->end_date = "";
                //$notification->end_time = "";
                $notification->internal_external = $request->inlineRadioOptions;
                $notification->notify_type = $request->notifytype;
                $notification->notify_type_time = 0;
                $notification->status = 1;
                $notification->save();
            }
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Send PNG Notification",
                    'event_category'=>5,
                    'event_id'=>0,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }

        }
    }

    public function delete_notification(Request $request){
        $id = $request->id;
        $category = Notification::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Notification",
                'event_category'=>5,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
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
    function send_notification($deviceToken, $msg, $title,$imageName,$page,$internal_external)
    {
        $json = json_encode(['key' => '1', 'key2' => '10','internal_external' => $internal_external,'page' => $page,'image' => $imageName]);
        $accessToken = $this->getAccessToken();
        $url = 'https://fcm.googleapis.com/v1/projects/gearz-bc901/messages:send'; // Replace YOUR_PROJECT_ID with your actual project ID
        $notification = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $msg,
                    'image' => $imageName,
                ],
                'data' => [
                    'internal_external' => $internal_external,
                    'page' => $page,
                    'image' => $imageName,
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
