<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Agent;
use App\Models\AgentBanner;
use App\Models\AgentBrand;
use App\Models\AgentBranch;
use App\Models\BranchBrand;
use App\Models\ServiceType;
use App\Models\ServiceBranch;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Deal;
use App\Models\DealDetail;
use App\Models\AgentFavorite;
use App\Models\AllBannerImage;
use App\Models\AgentReview;
use App\Models\BranchWorkingDays;
use Google\Client as GoogleClient;

class AgentController extends Controller
{
    private $serviceAccountFile;
    function __construct()
    {
       $this->serviceAccountFile = storage_path('gearz.json');
    }
    //All agent list
    public function allaggent(Request $request){
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
            $currentDate = date('Y-m-d');
            $banner = AgentBanner::where('end_date','>=',$currentDate)->where('status',1)->orderBy('position_id', 'asc')->get();
            $bannerArray = array();
            foreach($banner as $value){
                $data['id'] = $value->id;
                $data['position_id'] = $value->position_id;
                $data['image'] = $value->image;
                $data['internal_external'] = $value->internal_external;
                $data['url'] = $value->url;
                $data['start_date'] = $value->start_date;
                $data['end_date'] = $value->end_date;
                if($language==2){
                    $data['name'] = $value->name_ar;
                }else{
                    $data['name'] = $value->name;
                }
                if($value->start_date<=$currentDate){
                    array_push($bannerArray,$data);
                }
            }
            //agencies
            $agencies = Agent::where('status',1)->orderBy('position', 'asc')->get();
            $agenciesArray = array();
            foreach($agencies as $aggvalue){
                $dataa['id'] = $aggvalue->id;
                $dataa['logo'] = $aggvalue->logo;
                $dataa['images'] = $aggvalue->images;
                $dataa['type'] = $aggvalue->type;
                $dataa['tag_icon'] = $aggvalue->tag_icon;
                $dataa['website'] = $aggvalue->website;
                if($language==2){
                    $dataa['name'] = $aggvalue->name_ar;
                    $dataa['description'] = $aggvalue->discription_ar;
                    $dataa['tag_name'] = $aggvalue->tag_name_ar;
                }else{
                    $dataa['name'] = $aggvalue->name;
                    $dataa['description'] = $aggvalue->description;
                    $dataa['tag_name'] = $aggvalue->tag_name;
                }
                $checkagentfav = AgentFavorite::where('userID',$userID)->where('agentID',$aggvalue->id)->count();
                if($checkagentfav!=0){
                    $dataa['favorite']=1;
                }else{
                    $dataa['favorite']=0;
                }
                array_push($agenciesArray,$dataa);
            }
            $arr['banner'] = $bannerArray;
            $arr['agencies'] = $agenciesArray;
            return response()->json(['success'=>'true','message'=>'','result'=>$arr], 200);
        }

    }

    public function branchservicetypes(Request $request){
        $userID = $request->userID;
        $agentID = $request->agentID;
        $language = $request->language;
        $err_array = [];

        if($userID==null){
            $err_array[]='userID';
        }

        if ($agentID == null) {
            $err_array[] = 'agentID';
        }

        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json([
                'success' => 'false',
                'message' => "400 Bad Request, missing " . $er
            ], 400);
        }

        $branches = AgentBranch::where('agentID', $agentID)->where('status', 1)->pluck('id');

        $serviceIDs = ServiceBranch::whereIn('branchId', $branches)
            ->pluck('serviceID')
            ->unique()
            ->toArray();

        $services = ServiceType::whereIn('id', $serviceIDs)->get();

        $result = [];

        foreach ($services as $service) {
            $result[] = [
                'serviceid'     => $service->id,
                'servicename'   => $language == 2 ? $service->name_ar : $service->name,
            ];
        }

        return response()->json([
            'success' => 'true',
            'services' => $result
        ]);
    }

    //Agent details
    public function agentdetails(Request $request){
        $userID = $request->userID;
        $agentID = $request->agentID;
        $language = $request->language;
        $location = $request->location;
        $lat2 = $request->lat;
        $lon2 = $request->lon;
        $filter = $request->filter;
        $distance = $request->distance;
        $service = $request->service;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($agentID==null){
            $err_array[]='agentID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //about
            $agent = Agent::where('id',$agentID)->where('status',1)->first();
            if(!$agent){
                return response()->json(['success'=>'false','message'=>'Agent not found'], 404);
            }
            $datap['id'] = $agent->id;
            $datap['logo'] = $agent->logo;
            $datap['images'] = $agent->images;
            $datap['email'] = $agent->email;
            $datap['contact'] = $agent->contact;
            $datap['website'] = $agent->website;
            $datap['youtube'] = $agent->youtube;
            $datap['twitter'] = $agent->twitter;
            $datap['linkedin'] = $agent->linkedin;
            $datap['facebook'] = $agent->facebook;
            $datap['instagram'] = $agent->instagram;
            $datap['snap_chat'] = $agent->snap_chat;
            $datap['type'] = $agent->type;
            $datap['tag_icon'] = $agent->tag_icon;
            if($language==2){
                $datap['name'] = $agent->name_ar;
                $datap['description'] = $agent->discription_ar;
                $datap['tag_name'] = $agent->tag_name_ar;
            }else{
                $datap['name'] = $agent->name;
                $datap['description'] = $agent->description;
                $datap['tag_name'] = $agent->tag_name;
            }
            $checkagentfav = AgentFavorite::where('userID',$userID)->where('agentID',$agent->id)->count();
            if($checkagentfav!=0){
                $datap['favorite']=1;
            }else{
                $datap['favorite']=0;
            }
            //brand
            $agentBrand = AgentBrand::where('agentID',$agentID)->get();
            $brandArray = array();
            
            foreach($agentBrand as $vagent){
                $brand = Brand::where('id',$vagent->brandID)->where('status',1);
                if($brand->count()!=0){
                    $brandvalue = $brand->first();
                    $dataa['id'] = $brandvalue->id;
                    $dataa['logo'] = $brandvalue->logo;
                    if($language==2){
                        $dataa['name'] = $brandvalue->name_ar;
                        $dataa['description'] = $brandvalue->description_ar;
                    }else{
                        $dataa['name'] = $brandvalue->name;
                        $dataa['description'] = $brandvalue->description;
                    }
                    array_push($brandArray,$dataa);
                }
            }
            //branch
            $branch = AgentBranch::where('agentID',$agentID)->where('city',$location)->where('status',1)->get();
            $brancharray = array();
            foreach($branch as $branchValue){
                
                $branchServiceIDs = ServiceBranch::where('branchId', $branchValue->id)->pluck('serviceID')->toArray();

                if (!empty($service)) {
                    $serviceArray = explode(",", $service);
                    $matched = false;
                    foreach ($serviceArray as $svc) {
                        if (in_array($svc, $branchServiceIDs)) {
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) {
                        continue; 
                    }
                }

                $dataBranch['id'] = $branchValue->id;
                $dataBranch['servicetypeID'] = "";
                $dataBranch['servicetypeName'] = "";
                $dataBranch['servicetypeimage'] = $branchValue->image;
                $dataBranch['contact'] = $branchValue->contact;
                $dataBranch['email'] = $branchValue->email;
                $dataBranch['start_day'] = $branchValue->start_day;
                $dataBranch['end_day'] = $branchValue->end_day;
                $dataBranch['start_time'] = $branchValue->start_time;
                $dataBranch['end_time'] = $branchValue->end_time;
                $dataBranch['country'] = $branchValue->country;
                $dataBranch['state'] = $branchValue->state;
                $dataBranch['city'] = $branchValue->city;
                $dataBranch['address'] = $branchValue->address;
                $dataBranch['latitude'] = $branchValue->latitude;
                $dataBranch['longitude'] = $branchValue->longitude;
                if($language==2){
                    $dataBranch['name'] = $branchValue->name_ar;
                }else{
                    $dataBranch['name'] = $branchValue->name;
                }
                $dataBranch['countryName'] = optional(Country::find($branchValue->country))->name ?? "";
                $dataBranch['stateName'] = optional(State::find($branchValue->state))->name ?? "";
                $dataBranch['cityName'] = optional(City::find($branchValue->city))->name ?? "";
                //brand
                $branchBrandArray= array();
                $branchBrand = BranchBrand::where('branchId',$branchValue->id)->get();
                foreach($branchBrand as $branchval){
                    $databr = Brand::find($branchval->brandId);
                    if(!$databr){
                        continue;
                    }
                    $databranchs['id'] = $databr->id;
                    $databranchs['logo'] = $databr->logo;
                    if($language==2){
                        $databranchs['name'] = $databr->name_ar;
                    }else{
                        $databranchs['name'] = $databr->name;
                    }
                    array_push($branchBrandArray,$databranchs);
                }
                $dataBranch['brand'] = $branchBrandArray;
                //service
                $servicebranchArray = array();
                $servicebranch = ServiceBranch::where('branchId',$branchValue->id)->get();
                foreach($servicebranch as $servalue){
                    $ser = ServiceType::find($servalue->serviceID);
                    if(!$ser){
                        continue;
                    }
                    $dataservice['serviceid'] = $ser->id;
                    $dataservice['serviceimage'] = $ser->image;
                    if($language==2){
                        $dataservice['servicename'] = $ser->name_ar;
                    }else{
                        $dataservice['servicename'] = $ser->name;
                    }
                    array_push($servicebranchArray,$dataservice);
                }
                $dataBranch['service'] = $servicebranchArray;
                //distance
                $distancebranch = 0;
                if($lat2!=''){
                    $unit = "K";
                    $distancebranch = $this->getDistance($branchValue->latitude,$branchValue->longitude,$lat2,$lon2,$unit);
                    $dataBranch['distance'] = $distancebranch;
                }else{
                    $dataBranch['distance'] = "0";
                }
                if($filter == 1){
                    if($distancebranch <= $distance){
                        array_push($brancharray,$dataBranch);
                    }

                }else{
                    array_push($brancharray,$dataBranch);
                }
            }
            //banner
            // $currentDate = date('Y-m-d');
            // $banner = AgentBanner::where('end_date','>=',$currentDate)->where('status',1)->orderBy('position_id', 'asc')->get();
            // $bannerArray = array();
            // foreach($banner as $value){
            //     $data['id'] = $value->id;
            //     $data['position_id'] = $value->position_id;
            //     $data['image'] = $value->image;
            //     $data['start_date'] = $value->start_date;
            //     $data['end_date'] = $value->end_date;
            //     if($language==2){
            //         $data['name'] = $value->name_ar;
            //     }else{
            //         $data['name'] = $value->name;
            //     }
            //     if($value->start_date<=$currentDate){
            //         array_push($bannerArray,$data);
            //     }
            // }
            //banner
            $bannerArray = array();
            $brandbanner = AllBannerImage::where('bannertype',2)->where('bannerimageID',$agentID)->get();
            foreach($brandbanner as $bannervalue){
                $data['id'] = $bannervalue->id;
                $data['position_id'] = "";
                $data['image'] = $bannervalue->image;
                $data['start_date'] = "";
                $data['end_date'] = "";
                $data['name'] = "";
                array_push($bannerArray,$data);
            }
            //deal
            $deal_details = DealDetail::distinct()->where('dealTypeID',$agentID)->where('dealType',2)->get(['dealID']);
            $dealArray = array();
            //print_r($deal_details);
            foreach($deal_details as $dvalue){
                $currentDate = date('Y-m-d');
                //echo $dvalue->dealID;
                //$deal = Deal::where('id',$dvalue->dealID)->where('status',1)->first();
                $deals = Deal::where('id', $dvalue->dealID)
                ->where('status', 1)
                ->where(function ($query) {
                    $query->where('whereshow', 3)
                        ->orWhere('whereshow', 2);
                })
                ->count();
                if($deals!=0){
                    $deal = Deal::where('id', $dvalue->dealID)
                    ->where('status', 1)
                    ->where(function ($query) {
                        $query->where('whereshow', 3)
                            ->orWhere('whereshow', 2);
                    })
                    ->first();
                    $datadeal['id'] = $deal->id;
                    $datadeal['startDate'] = $deal->startDate;
                    $datadeal['endDate'] = $deal->endDate;
                    $datadeal['image'] = $deal->image;
                    $datadeal['tag_icon'] = $deal->tag_icon;
                    $future = strtotime(date('Y-m-d')); //Future date.
                    $timefromdb = strtotime($deal->endDate);
                    $timeleft = $timefromdb-$future;
                    $daysleft = round((($timeleft/24)/60)/60);
                    $datadeal['remaning_day']= $daysleft;
                    if($language==2){
                        $datadeal['name'] = $deal->name_ar;
                        $datadeal['discription'] = $deal->discription_ar;
                        $datadeal['tag_name'] = $deal->tag_name_ar;
                    }else{
                        $datadeal['name'] = $deal->name;
                        $datadeal['discription'] = $deal->discription;
                        $datadeal['tag_name'] = $deal->tag_name;
                    }
                    if($deal->endDate>=$currentDate){
                        array_push($dealArray,$datadeal);
                    }
                }
            }
            $arr['about'] = $datap;
            $arr['brand'] = $brandArray;
            $arr['branch'] = $brancharray;
            $arr['deals'] = $dealArray;
            $arr['banner'] = $bannerArray;
            //review
            $reviews = AgentReview::where('agentID',$agentID)->orderBy('id','DESC')->get();
            $reviewArrays = array();
            foreach($reviews as $revValues){
                $datarevs['id'] = $revValues->id;
                $datarevs['userID'] = $revValues->userID;
                $datarevs['username'] = User::find($revValues->userID)->name;
                $datarevs['rate'] = $revValues->rate;
                $datarevs['title'] = $revValues->title;
                $datarevs['message'] = $revValues->message;
                $datarevs['create_date'] = date('M d,Y',strtotime($revValues->created_at));
                array_push($reviewArrays,$datarevs);
            }
            $arr['review'] = $reviewArrays;
            $avgStar = AgentReview::where('agentID',$agentID)->avg('rate');
            $arr['rating'] = "".number_format($avgStar, 1, '.', '');
            $CarReview = AgentReview::where('agentID',$agentID);
            $arr['reviews'] = $CarReview->count();
            return response()->json(['success'=>'true','message'=>'','result'=>$arr], 200);
        }
    }

    //Agent Branch details
    public function agentbranchdetails(Request $request){
        $userID = $request->userID;
        $agentBranchID = $request->agentBranchID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($agentBranchID==null){
            $err_array[]='agentBranchID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //branch
            $agentBranch = AgentBranch::find($agentBranchID);
            $dataBranch['id'] = $agentBranch->id;
            if($language==2){
                $dataBranch['name'] = $agentBranch->name_ar;
                $dataBranch['countryName'] = Country::find($agentBranch->country)->name_ar;
                $dataBranch['stateName'] = State::find($agentBranch->state)->name_ar;
                $dataBranch['cityName'] = City::find($agentBranch->city)->name_ar;
            }else{
                $dataBranch['name'] = $agentBranch->name;
                $dataBranch['countryName'] = Country::find($agentBranch->country)->name;
                $dataBranch['stateName'] = State::find($agentBranch->state)->name;
                $dataBranch['cityName'] = City::find($agentBranch->city)->name;
            }
            $dataBranch['image'] = $agentBranch->image;
            $dataBranch['contact'] = $agentBranch->contact;
            $dataBranch['email'] = $agentBranch->email;
            $dataBranch['latitude'] = $agentBranch->latitude;
            $dataBranch['longitude'] = $agentBranch->longitude;
            //brand
            $branchBrandArray= array();
            $branchBrand = BranchBrand::where('branchId',$agentBranch->id)->get();
            foreach($branchBrand as $branchval){
                $databr = Brand::find($branchval->brandId);
                $databranchs['id'] = $databr->id;
                $databranchs['logo'] = $databr->logo;
                if($language==2){
                    $databranchs['name'] = $databr->name_ar;
                }else{
                    $databranchs['name'] = $databr->name;
                }
                array_push($branchBrandArray,$databranchs);
            }
            $dataBranch['brand'] = $branchBrandArray;
            //service
            $servicebranchArray = array();
            $servicebranch = ServiceBranch::where('branchId',$agentBranch->id)->get();
            foreach($servicebranch as $servalue){
                $ser = ServiceType::find($servalue->serviceID);
                $dataservice['serviceid'] = $ser->id;
                $dataservice['serviceimage'] = $ser->image;
                if($language==2){
                    $dataservice['servicename'] = $ser->name_ar;
                }else{
                    $dataservice['servicename'] = $ser->name;
                }
                array_push($servicebranchArray,$dataservice);
            }
            $dataBranch['service'] = $servicebranchArray;
            //working days
            $workingDaysArray = array();
            $workingDays = BranchWorkingDays::where('branchId',$agentBranch->id)->get();
            foreach($workingDays as $workingDay){
                $day = (int)$workingDay->day; 

                $dayName = match ($day) {
                    1 => 'Sunday',
                    2 => 'Monday',
                    3 => 'Tuesday',
                    4 => 'Wednesday',
                    5 => 'Thursday',
                    6 => 'Friday',
                    7 => 'Saturday',
                    default => 'Unknown',
                };

                $workingDaysArray[] = [
                    'day' => $dayName,
                    'first_shift_start_time' => $workingDay->first_shift_start_time ?? '',
                    'first_shift_end_time' => $workingDay->first_shift_end_time ?? '',
                    'second_shift_start_time' => $workingDay->second_shift_start_time ?? '',
                    'second_shift_end_time' => $workingDay->second_shift_end_time ?? '',
                ];
            }
            $dataBranch['workingDays'] = $workingDaysArray;
            return response()->json(['success'=>'true','message'=>'','result'=>$dataBranch], 200);
        }
    }

    //Add agent favorite
    public function addagentfavorites(Request $request){
        $userID = $request->userID;
        $agentID = $request->agentID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($agentID==null){
            $err_array[]='agentID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "تم إضافة الوكيل الى المفضلة ";
                $msg_failed = "تم حذف الوكيل من المفضلة ";
            }else{
                $msg_success = "The agent has been added to your favorites.";
                $msg_failed = "The agent has been removed from your favorites.";
            }
            $check = AgentFavorite::where('userID',$userID)->where('agentID',$agentID)->count();
            if($check==0){
                $favorites = new AgentFavorite;
                $favorites->agentID = $agentID;
                $favorites->userID = $userID;
                $favorites->favorite = 1;
                $favorites->save();
                //send notification
                if($language == 2){
                    $carname = Agent::where('id',$agentID)->first()->name_ar;
                    $msgnotification = $carname." تم إضافة الوكيل الى المفضلة ";
                    $titlenotification = "تم إضافة الوكيل الى المفضلة ";
                }else{
                    $carname = Agent::where('id',$agentID)->first()->name;
                    $msgnotification = $carname." agent has been added to your favorites.";
                    $titlenotification = "Agent Added to Favorites";
                }
                $devicetoken = User::where('id',$userID)->first()->deviceToken;
                //notification_hide $fav_brand.','.$fav_car.','.$fav_agency.','.$fav_news;
                $notification_hide = User::where('id',$userID)->first()->notification_hide;
                if(strlen($notification_hide)>=7){
                    $notiarray = explode(',',$notification_hide);
                    if($notiarray[2]==0){
                        $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                    }
                }else{
                    $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                }


                return response()->json(['success'=>'true','message'=>$msg_success], 200);

            }else{
                $favorites = AgentFavorite::where('userID',$userID)->where('agentID',$agentID)->delete();
                return response()->json(['success'=>'true','message'=>$msg_failed], 200);
            }

        }
    }

    //Agent favorite list
    public function agentfavorites_list(Request $request){
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
            $favoritearray= array();
            $favorite = AgentFavorite::where('userID',$userID)->orderBy('id','DESC')->get();
            foreach($favorite as $value){
                $data['id'] = $value->id;
                $data['userID'] = $value->userID;
                $data['agentID'] = $value->agentID;
                $vehicle = Agent::where('id',$value->agentID)->first();
                $data['image'] =$vehicle->logo;
                $data['tag_icon'] =$vehicle->tag_icon;
                $data['type'] =$vehicle->type;
                $data['favorite'] =1;
                if($language==2){
                    $data['name'] = $vehicle->name_ar;
                    $data['description'] =$vehicle->discription_ar;
                    $data['tag_name'] =$vehicle->tag_name_ar;
                }else{
                    $data['name'] = $vehicle->name;
                    $data['description'] =$vehicle->description;
                    $data['tag_name'] =$vehicle->tag_name;
                }
                array_push($favoritearray, $data);
            }
            return response()->json(['success'=>'true','message'=>"",'result'=>$favoritearray], 200);
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

    public function AgentReview(Request $request){
        $userID = $request->userID;
        $agentID = $request->agentID;
        $rate = $request->rate;
        $title = $request->title;
        $message = $request->message;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($agentID==null){
            $err_array[]='agentID';
        }
        if($rate==null){
            $err_array[]='rate';
        }
        if($title==null){
            $err_array[]='title';
        }
        if($message==null){
            $err_array[]='message';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "شكرا لك ";
                $msg_failed = "تم تقييم الوكيل مسبقا";
            }else{
                $msg_success = "Thank you for your rating.";
                $msg_failed = "A rating has already been given for this agent.";
            }
            $check = AgentReview::where('userID',$userID)->where('agentID',$agentID)->count();
            if($check==0){
                $review = new AgentReview;
                $review->agentID = $agentID;
                $review->userID = $userID;
                $review->rate = $rate;
                $review->title = $title;
                $review->message = $message;
                $review->save();
                return response()->json(['success'=>'true','message'=>$msg_success], 200);
            }else{
                return response()->json(['success'=>'false','message'=>$msg_failed], 400);
            }

        }
    }

    public function getDistance($lat1,$lon1,$lat2,$lon2,$unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
    }
}
