<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CarFavorite;
use App\Models\CarReview;
use App\Models\MyCar;
use App\Models\MycarDocument;
use App\Models\MycarSpecificationt;
use App\Models\MycarSpecificationtfirst;
use App\Models\MycarSpecificationtfive;
use App\Models\MycarSpecificationtfour;
use App\Models\MycarSpecificationtsecond;
use App\Models\MycarSpecificationtthree;
use App\Models\MycarSpecificationttrim;
use App\Models\MycarService;
use App\Models\MycarServiceTask;
use App\Models\BrandFavorite;
use App\Models\MyCarImage;
use App\Models\MycarspecificationEn;
use App\Models\MycarspecificationFirstEn;
use App\Models\MycarspecificationSeccondEn;
use App\Models\MycarspecificationThirdEn;
use App\Models\MycarspecificationFourEn;
use App\Models\MycarspecificationFiveEn;
use App\Models\MycarspecificationSixEn;
use App\Models\MycarspecificationSevenEn;
use App\Models\MycarspecificationExteriorcolorEn;
use App\Models\MycarspecificationInteriorcolorEn;
use App\Models\AgentFavorite;
use App\Models\NewsFavorite;
use App\Models\StaffLogEvent;
use App\Models\CarCompareByUser;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use Google\Client as GoogleClient;

class UserController extends Controller
{
    private $serviceAccountFile;
    function __construct()
    {
       $this->serviceAccountFile = storage_path('gearz.json');
    }
    public function userList(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "User Lists";
        $data['active'] = "user";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = User::where('status','!=',2)->orderBy('id','DESC')->get();
        return view('admin.userlists',$data);
    }

    public function edituser(Request $request){
        $admin_id = Session::get('id');
        $data['title'] = "Edit User";
        $data['active'] = "user";
        $data['admin']=Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['data'] = User::find($id);
        return view('admin.userform',$data);

    }
    public function insert_user(Request $request){
        $email = $request->email;
        $username = $request->name;
        $phone = $request->mobile_number;
        $DriverLicense = $request->DriverLicense;
        $DriverLicenseback = $request->DriverLicenseback;
        $license_expire_date = date('Y-m-d',strtotime($request->license_expire_date));
        $profileImage = $request->profileImage;
        $gender = $request->gender;
        $dob = date('Y-m-d',strtotime($request->dob));
        $userID = $request->id;
        $checkuser = User::where('mobile_number',$phone)->where('id',$request->id);
        if($checkuser->count() >= 1 && $request->id != $checkuser->first()->id){
            echo 2;
        }else{
            if($request->DriverLicense!=''){
                $imageName = "f".time().'.'.$request->DriverLicense->extension();
                $request->DriverLicense->move(public_path('images'), $imageName);
            }else{
                $imageName = '';
            }
            if($request->DriverLicenseback!=''){
                $DriverLicenseback = "r".time().'.'.$request->DriverLicenseback->extension();
                $request->DriverLicenseback->move(public_path('images'), $DriverLicenseback);
            }else{
                $DriverLicenseback = '';
            }
            if($request->profileImage!=''){
                $imageName1 = time().'.'.$request->profileImage->extension();
                $request->profileImage->move(public_path('images'), $imageName1);
            }else{
                $imageName1 = '';
            }
            $user = User::find($userID);
            $user->name = $username;
            $user->email = $email;
            $user->mobile_number = $phone;
            $user->gender = $gender;
            $user->dob = $dob;
            $user->license_expire_date = $license_expire_date;
            if($imageName!=''){
                $user->DriverLicense = $imageName;
            }
            if($imageName1!=''){
                $user->profileImage = $imageName1;
            }
            if($DriverLicenseback!=''){
                $user->DriverLicenseback = $DriverLicenseback;
            }
            $user->save();
            echo 1;
            if(session::get('usertype')==2){
                $log = array(
                    'staff_id'=>session::get('id'),
                    'logDate'=>date('Y-m-d'),
                    'event_name'=>"Update user",
                    'event_category'=>7,
                    'event_id'=>$userID,
                );
                // print_r($log);
                $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
            }
        }
    }

    public function change_user_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $user = User::find($id);
        $user->status = $status;
        $user->save();
        if($status==1){
            if($user->language==2){
                $notimessage ="تم تفعيل حسابك من قبل المسؤول";
                $notititle ="تم تفعيل الحساب";
                $logout="0";
            }else{
                $notimessage ="Your account is activated by Admin";
                $notititle ="Account is Activated";
                $logout="0";
            }
        }else{
            if($user->language==2){
                $notimessage ="تم إلغاء تنشيط حسابك بواسطة المسؤول";
                $notititle ="تم إلغاء تنشيط الحساب";
                $logout="1";
            }else{
                $notimessage ="Your account is deactivated by Admin";
                $notititle ="Account is Deactivated";
                $logout="1";
            }
        }
        $devicetoken = $user->deviceToken;
        $this->send_notification($devicetoken, $notimessage, $notititle,$logout);
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change user Status",
                'event_category'=>7,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function userDetail(Request $request){
        $admin_id = Session::get('id');
        $user_id = base64_decode($request->key);
        $data['title'] = "User List";
        $data['active'] = "user";
        $data['admin']=Admin::find($admin_id);
        $data['data'] = User::where('id',$user_id)->first();
        $data['favorite'] = CarFavorite::where('userID',$user_id)->get();
        $data['brandfavorite'] = BrandFavorite::where('userID',$user_id)->where('favorite',1)->get();
        $data['agentfavorite'] = AgentFavorite::where('userID',$user_id)->where('favorite',1)->get();
        $data['newsfavorite'] = NewsFavorite::where('userID',$user_id)->where('favorite',1)->get();
        $data['CarCompareByUser'] = CarCompareByUser::where('userID',$user_id)->get();
        $data['review'] = CarReview::where('userID',$user_id)->get();
        return view('admin.userdetail',$data);
    }

    public function mycar(Request $request){
        $admin_id = Session::get('id');
        $user_id = base64_decode($request->key);
        $data['title'] = "My Car";
        $data['active'] = "user";
        $data['admin']=Admin::find($admin_id);
        $data['my_carlist'] = MyCar::where('userID',$user_id)->where('status',1)->get();
        return view('admin.mycar',$data);
    }

    public function mycarservice(Request $request){
        $admin_id = Session::get('id');
        $carid = base64_decode($request->key);
        $data['title'] = "My Car Service";
        $data['active'] = "user";
        $data['admin'] = Admin::find($admin_id);
        $data['data'] = MyCar::find($carid);
        $data['MyCarImage'] = MyCarImage::where('mycarID',$carid);
        // use App\Models\MycarspecificationEn;
        // use App\Models\MycarspecificationFirstEn;
        // use App\Models\MycarspecificationSeccondEn;
        // use App\Models\MycarspecificationThirdEn;
        // use App\Models\MycarspecificationFourEn;
        // use App\Models\MycarspecificationFiveEn;
        // use App\Models\MycarspecificationSixEn;
        // use App\Models\MycarspecificationSevenEn;
        // use App\Models\MycarspecificationExteriorcolorEn;
        // use App\Models\MycarspecificationInteriorcolorEn;
        $data['MycarSpecificationt'] = MycarSpecificationt::where('mycarID',$carid);
        $data['MycarSpecificationtfirst'] = MycarSpecificationtfirst::where('mycarID',$carid);
        $data['MycarSpecificationtsecond'] = MycarSpecificationtsecond::where('mycarID',$carid);
        $data['MycarSpecificationtthree'] = MycarSpecificationtthree::where('mycarID',$carid);
        $data['MycarSpecificationtfour'] = MycarSpecificationtfour::where('mycarID',$carid);
        $data['MycarSpecificationtfive'] = MycarSpecificationtfive::where('mycarID',$carid);
        $data['MycarSpecificationttrim'] = MycarSpecificationttrim::where('mycarID',$carid);
        $data['MycarService'] = MycarService::where('mycarID',$carid)->orderBy('serviceDate','DESC')->get();
        return view('admin.myservice',$data);
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
    function send_notification($deviceToken, $msg, $title,$logout)
    {
        $json='{"key":"1","key2":"10"}';
        $accessToken = $this->getAccessToken();
        $url = 'https://fcm.googleapis.com/v1/projects/gearz-bc901/messages:send'; // Replace YOUR_PROJECT_ID with your actual project ID
        $notification = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $msg,

                ],
                'data' => [
                    'logout' => $logout,
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
