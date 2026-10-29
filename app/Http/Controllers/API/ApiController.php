<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Agent;
use App\Models\Magazine;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\VehicleExteriorColors;
use App\Models\VehicleInteriorColors;
use App\Models\VehicleTrimBody;
use App\Models\VehicleTrimEngine;
use App\Models\VehicleTrimMileage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CarImage;
use App\Models\CarFavorite;
use App\Models\Deal;
use App\Models\DealDetail;
use App\Models\WelcomeImage;
use App\Models\ImageSetting;
use App\Models\Viewed;
use App\Models\BrandFavorite;
use App\Models\MyCar;
use App\Models\CarReview;
use App\Models\AgentFavorite;
use App\Models\NewsFavorite;
use App\Models\DealType;
use App\Models\VehicleType;
use App\Models\Category;
use App\Models\MycarService;
use App\Models\AgentReview;
use App\Models\AgentBanner;
use App\Models\MagazineSubcategory;
use App\Models\AllBannerImage;
use App\Models\CarJatoSpecification;
use App\Models\IntroVideo;
use App\Models\PopularCar;
use Auth;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use App\Models\CarVideo;
use App\Modules\ExternalSystem;
use App\Models\ApiTrimList;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceIcon;

class ApiController extends Controller
{
    private $serviceAccountFile;
    function __construct()
    {
        $this->serviceAccountFile = storage_path('gearz.json');
    }
    //User Registration
    public function registerUser(Request $request)
    {
        $err_array = array();
        // $email = $request->email;
        // $username = $request->username;
        $phone = $request->phone;
        // $dob = $request->dob;
        $deviceType = $request->deviceType;
        // $gender = $request->gender;
        $language = $request->language;
        $countryCode = $request->countryCode;
        $countryName = $request->countryName;
        // $DriverLicense = $request->DriverLicense;
        // $DriverLicenseback = $request->DriverLicenseback;
        // $license_expire_date = $request->license_expire_date;
        $deviceModel = $request->deviceModel;
        // if ($email == null) {
        //     $err_array[] = 'email';
        // }
        // if ($username == null) {
        //     $err_array[] = 'username';
        // }
        // if($gender==null){
        //     $err_array[]='gender';
        // }
        if ($phone == null) {
            $err_array[] = 'phone';
        }
        // if($dob==null){
        //     $err_array[]='dob';
        // }
        if ($deviceType == null) {
            $err_array[] = 'deviceType';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التسجيل بنجاح";
                $msg_failed = "الرقم مسجل مسبقا ";
            } else {
                $msg_success = "You have successfully registered";
                $msg_failed = "Mobile number already exists.";
            }
            $checkuser = User::where('mobile_number', $phone)->where('status', 1)->count();
            if ($checkuser == 0) {
                // if ($request->DriverLicense != '') {
                //     $imageName = "f" . time() . '.' . $request->DriverLicense->extension();
                //     $request->DriverLicense->move(public_path('images'), $imageName);
                // } else {
                //     $imageName = '';
                // }
                // if ($request->DriverLicenseback != '') {
                //     $DriverLicenseback = "r" . time() . '.' . $request->DriverLicenseback->extension();
                //     $request->DriverLicenseback->move(public_path('images'), $DriverLicenseback);
                // } else {
                //     $DriverLicenseback = '';
                // }
                $user = User::create([
                    // 'name' => $request->username,
                    'name' => '',
                    // 'email' => $request->email,
                    'email' => '',
                    'mobile_number' => $phone,
                    // 'gender' => $gender,
                    'gender' => '',
                    // 'dob' => $dob,
                    'dob' => '',
                    'deviceType' => $deviceType,
                    'language' => $language,
                    'countryCode' => $countryCode,
                    'countryName' => $countryName,
                    // 'license_expire_date' => $license_expire_date,
                    'license_expire_date' => '',
                    'password' => \Hash::make('123456'),
                    // 'DriverLicense' => $imageName,
                    'DriverLicense' => '',
                    // 'DriverLicenseback' => $DriverLicenseback,
                    'DriverLicenseback' => '',
                    'deviceModel' => $deviceModel,
                    'status' => 1
                ]);
                $users = User::find($user->id);
                $tokenResult = $users->createToken('GearzappToken');
                $tokens = $tokenResult->token;
                $tokens->expires_at = Carbon::now()->addWeeks(1);
                $tokens->save();
                $userdata = User::find($user->id);
                $userdata->accessToken = $tokenResult->accessToken;
                $userdata->save();

                return response()->json(['success' => 'true', 'message' => $msg_success, 'token' => $tokenResult->accessToken, 'userID' => $user->id], 200);
            } else {
                return response()->json(['success' => 'false', 'message' => $msg_failed], 200);
            }
        }
    }

    //User Login
    public function login(Request $request)
    {
        $err_array = array();
        $phone = $request->phone;
        $deviceType = $request->deviceType;
        $language = $request->language;
        $deviceModel = $request->deviceModel;
        if ($phone == null) {
            $err_array[] = 'phone';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم تسجيل الدخول بنجاح";
                $msg_failed = "ارقم الجوال غير مسجل ";
            } else {
                $msg_success = "You have successfully logged in.";
                $msg_failed = "This mobile number is not registered.";
            }
            $data = [
                "mobile_number" => $request->phone,
                "password" => '123456',
                "status" => 1
            ];
            if (auth()->attempt($data)) {
                // $check_already_login = auth()->user()->is_login;
                // if($check_already_login==1){
                //     if($language==2){
                //         return response()->json(['success'=>'false','message'=>"هذا الرقم مسجل على جهاز آخر.",'login'=>3], 200);
                //     }else{
                //         return response()->json(['success'=>'false','message'=>"This number is already logged in on another device.",'login'=>3], 200);
                //     }


                // }else{
                if ($phone != 9711987132) {
                    $user = User::find(auth()->user()->id);
                    $tokenResult = $user->createToken('GearzappToken');
                    $tokens = $tokenResult->token;
                    $tokens->expires_at = Carbon::now()->addWeeks(1);
                    $tokens->save();
                    $users = auth()->user();
                    $userdata = new User;
                    $userdata = User::find(auth()->user()->id);
                    $userdata->accessToken = $tokenResult->accessToken;
                    $userdata->deviceToken = $request->deviceToken;
                    $userdata->deviceType = $request->deviceType;
                    $userdata->language = $request->language;
                    $userdata->deviceModel = $request->deviceModel;
                    $userdata->is_login = 1;
                    $userdata->save();
                    if ($user->deviceModel == $request->deviceModel) {
                        $suc = 1;
                    } else {
                        $suc = 0;
                    }
                    return response()->json(['success' => 'true', 'message' => $msg_success, 'token' => $tokenResult->accessToken, 'userID' => $user->id, 'login' => $suc], 200);
                } else {
                    return response()->json(['success' => 'true', 'message' => $msg_success, 'token' => "", 'userID' => 43, 'login' => 1], 200);
                }
                //}
            } else {
                return response()->json(['success' => 'false', 'message' => $msg_failed, 'login' => 3], 200);
            }
        }
    }

    //device verification
    public function device_verification(Request $request)
    {
        $err_array = array();
        $phone = $request->phone;
        $language = $request->language;
        $deviceModel = $request->deviceModel;
        if ($phone == null) {
            $err_array[] = 'phone';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $check_device = User::where('mobile_number', $phone);
            if ($check_device->count() != 0) {
                if ($check_device->first()->deviceModel == $deviceModel) {
                    $suc = 1;
                } else {
                    $suc = 0;
                }
            } else {
                $suc = 0;
            }
            if ($suc == 1) {
                return response()->json(['success' => 'true', 'message' => "", 'login' => 1], 200);
            } else {
                return response()->json(['success' => 'true', 'message' => "", 'login' => 0], 200);
            }
        }
    }

    //Get user profile
    public function getUserProfile(Request $request)
    {
        $err_array = array();
        $userID = $request->userID;
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $checkuser = User::where('id', $userID)->where('status', 1);
            if ($checkuser->count() != 0) {
                $row = $checkuser->first();
                $data['id'] = $row->id;
                $data['username'] = $row->name;
                $data['gender'] = $row->gender;
                $data['dob'] = $row->dob;
                $data['email'] = $row->email;
                $data['mobile_number'] = $row->mobile_number;
                $data['DriverLicense'] = $row->DriverLicense;
                $data['DriverLicenseback'] = $row->DriverLicenseback;
                $data['license_expire_date'] = $row->license_expire_date;
                $data['location'] = $row->location;
                $data['profileImage'] = $row->profileImage;
                $data['countryCode'] = $row->countryCode;
                $data['countryName'] = $row->countryName;
                return response()->json(['success' => 'true', 'message' => '', 'result' => $data], 200);
            } else {
                return response()->json(['success' => 'false', 'message' => ''], 200);
            }
        }
    }

    //Update User Profile
    public function UpdateProfile(Request $request)
    {
        $err_array = array();
        $email = $request->email;
        $username = $request->username;
        $phone = $request->phone;
        $DriverLicense = $request->DriverLicense;
        $DriverLicenseback = $request->DriverLicenseback;
        $license_expire_date = $request->license_expire_date;
        $profileImage = $request->profileImage;
        $gender = $request->gender;
        $dob = $request->dob;
        $userID = $request->userID;
        $language = $request->language;
        if ($email == null) {
            $err_array[] = 'email';
        }
        if ($username == null) {
            $err_array[] = 'username';
        }
        if ($phone == null) {
            $err_array[] = 'phone';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث بنجاح.";
                $msg_failed = "هذا الرقم مُستخدم من قِبل مستخدم آخر.";
            } else {
                $msg_success = "You have successfully updated.";
                $msg_failed = "This mobile number is already used by another user.";
            }
            $checkuser = User::where('mobile_number', $phone)->where('id', $request->userID);
            if ($checkuser->count() >= 1 && $request->userID != $checkuser->first()->id) {
                return response()->json(['success' => 'false', 'message' => $msg_failed], 200);
            } else {
                if ($request->DriverLicense != '') {
                    $imageName = "f" . time() . '.' . $request->DriverLicense->extension();
                    $request->DriverLicense->move(public_path('images'), $imageName);
                } else {
                    $imageName = '';
                }
                if ($request->DriverLicenseback != '') {
                    $DriverLicenseback = "r" . time() . '.' . $request->DriverLicenseback->extension();
                    $request->DriverLicenseback->move(public_path('images'), $DriverLicenseback);
                } else {
                    $DriverLicenseback = '';
                }
                if ($request->profileImage != '') {
                    $imageName1 = time() . '.' . $request->profileImage->extension();
                    $request->profileImage->move(public_path('images'), $imageName1);
                } else {
                    $imageName1 = '';
                }
                $user = new User;
                $user = User::find($userID);
                $user->name = $username;
                $user->email = $email;
                $user->mobile_number = $phone;
                $user->gender = $gender;
                $user->dob = $dob;
                $user->license_expire_date = $license_expire_date;
                $user->status = 1;
                if ($imageName != '') {
                    $user->DriverLicense = $imageName;
                }
                if ($imageName1 != '') {
                    $user->profileImage = $imageName1;
                }
                if ($DriverLicenseback != '') {
                    $user->DriverLicenseback = $DriverLicenseback;
                }
                $user->save();
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            }
        }
    }

    //Dashboard
    public function dashboard(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $currentDate = date('Y-m-d');
            $banner = Banner::where('end_date', '>=', $currentDate)->where('status', 1)->orderBy('position_id', 'asc')->get();
            $bannerArray = array();
            foreach ($banner as $value) {
                $data['id'] = $value->id;
                $data['position_id'] = $value->position_id;
                $data['image'] = $value->image;
                $data['url'] = $value->url;
                $data['internal_external'] = $value->internal_external;
                if ($language == 2) {
                    $data['name'] = $value->name_ar;
                } else {
                    $data['name'] = $value->name;
                }
                $data['start_date'] = $value->start_date;
                $data['end_date'] = $value->end_date;
                if ($value->start_date <= $currentDate) {
                    array_push($bannerArray, $data);
                }
            }
            //popularbrand
            $popularCarBrand = Brand::where('status', 1)
                ->where('popular', 1)
                ->whereNotNull('excel')
                ->where('categoryID', 1)
                ->orderBy('position', 'ASC')
                ->get();

            $popularOtherBrand = Brand::where('status', 1)
                ->where('popular', 1)
                ->where('categoryID', '!=', 1)
                ->orderBy('position', 'ASC')
                ->get();

            $popular = $popularCarBrand->merge($popularOtherBrand);

            $popularBrandArray = array();
            foreach ($popular as $popValue) {
                $datap['id'] = $popValue->id;
                if ($language == 2) {
                    $datap['name'] = $popValue->name_ar;
                } else {
                    $datap['name'] = $popValue->name;
                }
                $datap['logo'] = $popValue->logo;
                array_push($popularBrandArray, $datap);
            }

            //agencies
            $agencies = Agent::where('status', 1)->where('popular', 1)->orderBy('position', 'ASC')->get();
            $agenciesArray = array();
            foreach ($agencies as $aggvalue) {
                $dataa['id'] = $aggvalue->id;
                if ($language == 2) {
                    $dataa['name'] = $aggvalue->name_ar;
                } else {
                    $dataa['name'] = $aggvalue->name;
                }
                $dataa['logo'] = $aggvalue->logo;
                if ($language == 2) {
                    $dataa['description'] = $aggvalue->discription_ar;
                } else {
                    $dataa['description'] = $aggvalue->description;
                }

                $dataa['images'] = $aggvalue->images;
                $dataa['tag_icon'] = $aggvalue->tag_icon;
                if ($language == 2) {
                    $dataa['tag_name'] = $aggvalue->tag_name_ar;
                } else {
                    $dataa['tag_name'] = $aggvalue->tag_name;
                }

                $checkagentfav = AgentFavorite::where('userID', $userID)->where('agentID', $aggvalue->id)->count();
                if ($checkagentfav != 0) {
                    $dataa['favorite'] = 1;
                } else {
                    $dataa['favorite'] = 0;
                }
                $avgagentStar = AgentReview::where('agentID', $aggvalue->id)->avg('rate');
                $dataa['rating'] = "" . number_format($avgagentStar, 1, '.', '');
                $agentReview = AgentReview::where('agentID', $aggvalue->id);
                $dataa['reviews'] = $agentReview->count();
                array_push($agenciesArray, $dataa);
            }
            //hotnews
            $check_news = Magazine::where('home', 1)->where('status', 1)->where('position', '!=', 0)->count();
            if ($check_news == 0) {
                $news = Magazine::where('home', 1)->where('status', 1)->orderBy('postedDate', 'ASC')->get();
            } else {
                $news = Magazine::where('home', 1)->where('status', 1)->orderBy('position', 'ASC')->get();
            }
            $newsarray = array();
            foreach ($news as $nvalue) {
                $datanews['id'] = $nvalue->id;
                $bannerimage = AllBannerImage::where('bannertype', 4)->where('bannerimageID', $nvalue->id)->orderBy('id', 'DESC')->skip(0)->take(1);
                if ($bannerimage->count() != 0) {
                    $datanews['image'] = $bannerimage->first()->image;
                } else {
                    $datanews['image'] = $nvalue->image;
                }
                //$datanews['image'] = $nvalue->image;
                $datanews['videourl'] = $nvalue->videourl;
                $datanews['postedDate'] = $nvalue->postedDate;
                $datanews['tag_icon'] = $nvalue->tag_icon;
                if ($language == 2) {
                    $datanews['heading'] = $nvalue->heading_ar;
                    $datanews['description'] = $nvalue->description_ar;
                    $datanews['postedBy'] = $nvalue->postedBy_ar;
                    $datanews['tag_name'] = $nvalue->tag_name_ar;
                } else {
                    $datanews['heading'] = $nvalue->heading;
                    $datanews['description'] = $nvalue->description;
                    $datanews['postedBy'] = $nvalue->postedBy;
                    $datanews['tag_name'] = $nvalue->tag_name;
                }
                $checknewsfav = NewsFavorite::where('userID', $userID)->where('newsID', $nvalue->id)->count();
                if ($checknewsfav != 0) {
                    $datanews['favorite'] = 1;
                } else {
                    $datanews['favorite'] = 0;
                }
                $views = Viewed::where('menu_type', 2)->where('menuID', $nvalue->id)->count();
                if ($views == 0) {
                    $datanews['totalviews'] = $nvalue->totalview;
                } else {
                    $datanews['totalviews'] = $views + $nvalue->totalview;
                }
                //category
                $datanews['categoryID'] = $nvalue->subcategoryID;
                if ($nvalue->subcategoryID != "") {
                    $checkcategory = MagazineSubcategory::where('id', $nvalue->subcategoryID)->count();
                    if ($checkcategory != 0) {
                        if ($language == 2) {
                            $datanews['categoryName'] = MagazineSubcategory::where('id', $nvalue->subcategoryID)->first()->name_ar;
                        } else {
                            $datanews['categoryName'] = MagazineSubcategory::where('id', $nvalue->subcategoryID)->first()->name;
                        }
                    } else {
                        $datanews['categoryName'] = "";
                    }
                } else {
                    $datanews['categoryName'] = "";
                }
                //$datanews['totalviews'] = Viewed::where('menu_type',2)->where('menuID',$nvalue->id)->count();
                array_push($newsarray, $datanews);
            }
            //popularcar
            // $vehicle = CarJatoSpecification::where('status', 1)->where('popular', 1)->orderBy('position', 'ASC')->get();
            $vehicle = PopularCar::where('status', 1)->orderBy('position', 'ASC')->get();
            $vehicleArray = array();

            foreach ($vehicle as $key => $value) {
                
                $brand = Brand::find($value->brand_id);

                if (!$brand || empty($brand->excel)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Data not found.'
                    ]);
                }

                $filePath = public_path('brand/excel/' . $brand->excel);

                // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

                if (!file_exists($filePath)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Excel file does not exist in the specified path.'
                    ], 404);
                }

                $filteredRows = [];

                if (($handle = fopen($filePath, "r")) !== false) {
                    $sample = fgets($handle);
                    rewind($handle);
                    $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                    $rawHeaders = fgetcsv($handle, 0, $delimiter);
                    $headers = array_map(function ($header) {
                        $header = trim($header);
                        $header = preg_replace('/\s+/', '_', $header); // spaces to _
                        $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                        return $header;
                    }, $rawHeaders);

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        if (count($row) !== count($headers)) {
                            continue;
                        }

                        $row = array_map('trim', $row);
                        $assocRow = array_combine($headers, $row);

                        if (
                            isset($assocRow['Make']) &&
                            $assocRow['Make'] == $value->make &&
                            isset($assocRow['Model']) &&
                            $assocRow['Model'] == $value->model &&
                            isset($assocRow['Trim']) &&
                            $assocRow['Trim'] == $value->trim &&
                            isset($assocRow['Model_Year']) &&
                            $assocRow['Model_Year'] == $value->year
                        ) {
                            $formatted = [
                                'make' => $assocRow['Make'],
                                'model' => $assocRow['Model'],
                                'trim' => $assocRow['Trim'],
                                'model_year' => $assocRow['Model_Year'],
                                'kmpl' => $assocRow['Horsepower_(hp)'],
                                'cc' => $assocRow['Engine_Displacement_(cc)'],
                                'geartype' => $assocRow['Transmission_Type'],
                                'rating' => $assocRow['Safety_Rating'],
                            ];

                            $filteredRows[] = $formatted;
                        }

                    }

                    fclose($handle);
                }

                if (count($filteredRows) == 0){
                    PopularCar::delete($value->id);
                }
                
                foreach ($filteredRows as $carvalue) {
                    $datavehicle['id'] = $value->brand_id;
                    $datavehicle['make'] = $carvalue['make'];
                    $datavehicle['model'] = $carvalue['model'];
                    $datavehicle['trim'] = $carvalue['trim'];
                    $datavehicle['year'] = $carvalue['model_year'];
                    $datavehicle['kmpl'] = $carvalue['kmpl'] . " HP";
                    $datavehicle['cc'] = $carvalue['cc'] . " KW";
                    $datavehicle['geartype'] = $carvalue['geartype'];
                    // $datavehicle['favorite'] = 0;
                    $datavehicle['rating'] = $carvalue['rating'];
                    $datavehicle['reviews'] = 0;
                    // $datavehicle['id'] = $carvalue->id;
                    // $CarImage = CarImage::where('vehicleID', $carvalue->id);
                    // if ($CarImage->count() != 0) {
                    //     if ($CarImage->first()->image_type == 1) {
                    //         $datavehicle['image'] = url('public/images/car/' . $CarImage->first()->image);
                    //     } else {
                    //         $datavehicle['image'] = $CarImage->first()->image;
                    //     }
                    // } else {
                        $datavehicle['image'] = "";
                    // }
                    // if ($language == 2) {
                    //     //$datavehicle['name'] = $carvalue->name_ar;
                    //     $datavehicle['name'] = $carvalue->name;
                    // } else {
                    //     $datavehicle['name'] = $carvalue->name;
                    // }

                    // $datavehicle['modelID'] = $carvalue->modelID;
                    // $datavehicle['year'] = $carvalue->year;
                    // $datavehicle['kmpl'] = $carvalue->maximumPowerhpPs . " HP";
                    // $datavehicle['cc'] = $carvalue->maximumPowerKw . " KW";
                    // $datavehicle['geartype'] = $carvalue->transmissionToDisplay;
                    $checkfav = CarFavorite::where(['userID' => $userID, 'make' => $value->make, 'model' => $value->model, 'trim' => $value->trim, 'year' => $value->year])->count();
                    // $checkfav = CarFavorite::where('userID', $userID)->where('vehicleID', $carvalue->id)->count();
                    if ($checkfav != 0) {
                        $datavehicle['favorite'] = 1;
                    } else {
                        $datavehicle['favorite'] = 0;
                    }
                    // $avgStar = CarReview::where('vehicleID', $carvalue->id)->avg('rate');
                    // $datavehicle['rating'] = "" . $avgStar;
                    // $CarReview = CarReview::where('vehicleID', $carvalue->id);
                    // $datavehicle['reviews'] = $CarReview->count();
                    //$datavehicle['geartypes'] = $VehicleTrimEngine->transmission;
                    array_push($vehicleArray, $datavehicle);
                }
            }
            //topDeals
            $currentDate = date('Y-m-d');
            $deal = Deal::where('endDate', '>=', $currentDate)->where('status', 1)->where('popular', 1)->orderBy('position', 'ASC')->get();
            //$deal = Deal::where('status',1)->where('popular',1)->get();
            $dealArray = array();
            foreach ($deal as $dealvalue) {
                $datadeal['id'] = $dealvalue->id;
                $datadeal['image'] = $dealvalue->image;
                $datadeal['tag_icon'] = $dealvalue->tag_icon;
                $datadeal['typesofdeal'] = $dealvalue->typesofdeal;
                // $datadeal['tag_name'] = $dealvalue->tag_name;
                if ($language == 2) {
                    $datadeal['name'] = $dealvalue->name_ar;
                    $datadeal['tag_name'] = $dealvalue->tag_name_ar;
                    if ($dealvalue->typesofdeal != "") {
                        $datadeal['typesofdeal_name'] = DealType::find($dealvalue->typesofdeal)->name_ar;
                    } else {
                        $datadeal['typesofdeal_name'] = "";
                    }
                } else {
                    $datadeal['name'] = $dealvalue->name;
                    $datadeal['tag_name'] = $dealvalue->tag_name;
                    if ($dealvalue->typesofdeal != "") {
                        $datadeal['typesofdeal_name'] = DealType::find($dealvalue->typesofdeal)->name;
                    } else {
                        $datadeal['typesofdeal_name'] = "";
                    }
                }
                array_push($dealArray, $datadeal);
            }
            $arr['banner'] = $bannerArray;
            $arr['popularcar'] = $vehicleArray;
            $arr['popularbrand'] = $popularBrandArray;
            $arr['agencies'] = $agenciesArray;
            $arr['topDeals'] = $dealArray;
            $arr['hotnews'] = $newsarray;
            $carprofile = MyCar::where('userID', $userID)->where('status', 1)->count();
            if ($carprofile == 0) {
                $arr['carprofile'] = 0;
                //$arr['odometerupdate'] = 1;
            } else {
                $arr['carprofile'] = 1;
            }
            //odo meter last fill
            $checkodometer = MycarService::where('userID', $userID)
                ->whereNotNull('serviceDate')
                ->whereNotNull('odometer')
                ->where('serviceDate', '!=', '')
                ->whereRaw("DATE(serviceDate) = serviceDate")
                ->orderBy('serviceDate', 'DESC')
                ->skip(0)
                ->take(1);
            // return $checkodometer->get();
            if ($checkodometer->count() == 0) {
                $arr['odometerupdate'] = 1;
            } else {
                $servicedate = $checkodometer->first()->serviceDate;
                $servicedates = date('Y-m-d', strtotime($servicedate . ' + 7 days'));
                if (date('Y-m-d') >= $servicedates) {
                    $arr['odometerupdate'] = 2;
                    $arr['mycarID'] = $checkodometer->first()->mycarID;
                } else {
                    $arr['odometerupdate'] = 1;
                }
            }

            $arr['location'] = User::find($userID)->location;
            return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
        }
    }

    //City List
    public function city(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $country = $request->country;
        $page = $request->page;
        $filter = $request->filter;
        $search = $request->search;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($country == null) {
            $err_array[] = 'country';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($filter == 0) {
                $pagedata = 30;
                $showdata = $pagedata * $page;
                $showdatafrom = $pagedata * $page - 1;

                $country = Country::where('name', ucwords($country))->first();
                $stateArray = array();
                $states = State::select('id')->where('country_id', $country->id)->get();
                foreach ($states as $statevalue) {
                    array_push($stateArray, $statevalue->id);
                }
                // $data['popularcity'] = City::select('id', 'name')->whereIN('state_id', $stateArray)->where('status', 1)->where('popular', 1)->get();

                // $data['allcity'] = City::select('id', 'name')->whereIN('state_id', $stateArray)->where('status', 1)->skip($showdatafrom)->take($showdata)->get();
                $allcity = City::select('id', 'name')->whereIN('state_id', $stateArray)->where('status', 1)->get();
                
                $total = count($allcity);
                $page = (int) $request->input('page', 1);
                $limit = (int) $request->input('limit', 30);
                if ($limit <= 0) {
                    $limit = 30; // fallback to default to prevent division by zero
                }
                $offset = ($page - 1) * $limit;

                $paginatedData = array_slice($allcity->toArray(), $offset, $limit);

                // 📦 Paginated Response
                return response()->json([
                    'success' => 'true',
                    'message' => '',
                    'result' => $paginatedData,
                    'pagination' => [
                        'total' => $total,
                        'page' => $page,
                        'limit' => $limit,
                        'total_pages' => ceil($total / $limit),
                        'has_next' => $offset + $limit < $total,
                        'has_previous' => $page > 1,
                    ]
                ], 200);
                
                // $totalcity =  City::select('id', 'name')->whereIN('state_id', $stateArray)->where('status', 1)->count();
                // $totalvalue = round($totalcity / 30);
                // return response()->json(['success' => 'true', 'message' => '', 'result' => $allcity, 'total_page' => $totalvalue], 200);
            } else {
                $data['popularcity'] = [];
                // $data['allcity'] = City::select('id', 'name')->where('name', ucfirst($search))->where('status', 1)->get();
                
                $allcity = City::select('id', 'name')->where('name', ucfirst($search))->where('status', 1)->get();
                
                // $totalvalue = "0";
                // return response()->json(['success' => 'true', 'message' => '', 'result' => $allcity, 'pagination' => []], 200);
                return response()->json([
                    'success' => 'true',
                    'message' => '',
                    'result' => $allcity,
                    'pagination' => [
                        'total' => 1,
                        'page' => 1,
                        'limit' => 30,
                        'total_pages' => 1,
                        'has_next' => true,
                        'has_previous' => true,
                    ]
                ], 200);
            }
        }
    }

    //Add car as favorite
    public function addCarfavorites(Request $request)
    {
        $userID = $request->userID;
        // $vehicle_id = $request->vehicle_id;
        // $favorite = $request->favorite;
        $make = $request->make;
        $model = $request->model;
        $trim = $request->trim;
        $year = $request->year;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($make == null) {
            $err_array[] = 'make';
        }
        if ($model == null) {
            $err_array[] = 'model';
        }
        if ($trim == null) {
            $err_array[] = 'trim';
        }
        if ($year == null) {
            $err_array[] = 'year';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم إضافة المركبة إلى المفضلة";
                $msg_failed = "لقد تمت إزالة السيارة من المفضلة لديك.";
            } else {
                $msg_success = "The vehicle has been added to your favorites.";
                $msg_failed = "The vehicle has been removed from your favorites.";
            }
            
            $check = CarFavorite::where(['userID' => $userID, 'make' => $make, 'model' => $model, 'trim' => $trim, 'year' => $year]);
            if ($check->count() == 0) {
                $favorites = new CarFavorite;
                $favorites->userID = $userID;
                $favorites->make = $make;
                $favorites->model = $model;
                $favorites->trim = $trim;
                $favorites->year = $year;
                $favorites->save();
                //send notification
                if ($language == 2) {
                    // $carname = CarJatoSpecification::where('id', $vehicle_id)->first()->name_ar;
                    $msgnotification = $trim . "تمت إضافة السيارة إلى المفضلة.";
                    $titlenotification = "تم إضافة المركبة إلى المفضلة";
                } else {
                    // $carname = CarJatoSpecification::where('id', $vehicle_id)->first()->name;
                    $msgnotification = $trim . " vehicle has been added to your favorites.";
                    $titlenotification = "Vehicle Added to Favorites";
                }
                $devicetoken = User::where('id', $userID)->first()->deviceToken;
                //notification_hide $fav_brand.','.$fav_car.','.$fav_agency.','.$fav_news;
                $notification_hide = User::where('id', $userID)->first()->notification_hide;
                if (strlen($notification_hide) >= 7) {
                    $notiarray = explode(',', $notification_hide);
                    if ($notiarray[1] == 0) {
                        $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                    }
                } else {
                    $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                }
                //$this->send_notification($devicetoken, $msgnotification, $titlenotification);
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            } else {
                $check->delete();
                //send notification
                if ($language == 2) {
                    // $carname = CarJatoSpecification::where('id', $vehicle_id)->first()->name_ar;
                    $msgnotification = $trim . "تمت إزالة السيارة من المفضلة لديك.";
                    $titlenotification = "تمت إزالة السيارة من المفضلة";
                } else {
                    // $carname = CarJatoSpecification::where('id', $vehicle_id)->first()->name;
                    $msgnotification = $trim . " vehicle has been removed from your favorites.";
                    $titlenotification = "Vehicle Removed from Favorites";
                }
                $devicetoken = User::where('id', $userID)->first()->deviceToken;
                //notification_hide $fav_brand.','.$fav_car.','.$fav_agency.','.$fav_news;
                $notification_hide = User::where('id', $userID)->first()->notification_hide;
                if (strlen($notification_hide) >= 7) {
                    $notiarray = explode(',', $notification_hide);
                    if ($notiarray[1] == 0) {
                        $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                    }
                } else {
                    $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                }
                return response()->json(['success' => 'true', 'message' => $msg_failed], 200);
            }
        }
    }

    //car favorite list
    public function favorites_list(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $favoritearray = array();
            $favorite = CarFavorite::where('userID', $userID)->orderBy('id', 'DESC')->get();
            foreach ($favorite as $value) {
                $brand = Brand::where('name', $value->make)->first();
                // $checkcarspe = CarJatoSpecification::where('id', $value->vehicleID)->count();
                // if ($checkcarspe != 0) {
                    $data['id'] = $brand->id;
                    $data['userID'] = $value->userID;
                    $data['make'] = $value->make;
                    $data['model'] = $value->model;
                    $data['trim'] = $value->trim;
                    $data['year'] = $value->year;
                    // $data['vehicleID'] = $value->vehicleID;
                    // $vehicle = CarJatoSpecification::where('id', $value->vehicleID)->first();
                    // $data['name'] = $vehicle->name;
                    // $data['year'] = $vehicle->year;
                    // $data['modelID'] = $vehicle->modelID;
                    // $CarImage = CarImage::where('vehicleID', $value->vehicleID);
                    // if ($CarImage->count() != 0) {
                    //     if ($CarImage->first()->image_type == 1) {
                    //         $data['image'] = url('public/images/car/' . $CarImage->first()->image);
                    //     } else {
                    //         $data['image'] = $CarImage->first()->image;
                    //     }
                    // } else {
                        $data['image'] = "";
                    // }

                    // $avgStar = CarReview::where('vehicleID', $value->vehicleID)->avg('rate');
                    // $data['rating'] = "" . $avgStar;
                    $data['rating'] = 4;
                    // $CarReview = CarReview::where('vehicleID', $value->vehicleID);
                    // $data['reviews'] = $CarReview->count();
                    $data['reviews'] = 45;
                    $data['price'] = "12000";

                    // $data['kmpl'] = $vehicle->maximumPowerhpPs . " HP";
                    // $data['cc'] = $vehicle->maximumPowerKw . " Kw";
                    // $data['geartype'] = $vehicle->transmissionToDisplay;

                    $data['kmpl'] = "500 HP";
                    $data['cc'] = "250 Kw";
                    $data['geartype'] = "Manual";
                    array_push($favoritearray, $data);
                // }
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $favoritearray], 200);
        }
    }

    //All deal List
    public function alldeals(Request $request)
    {
        $userID = $request->userID;
        $locationID = $request->locationID;
        $filterIdList = $request->filterIdList;
        //$filterIdList = [1,2,3];
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($locationID == null) {
            $err_array[] = 'locationID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            // print_r($filterIdList);
            // die;
            $deal_details = DealDetail::where('cityID', $locationID)->orWhere('cityID', '')->get();
            $dealArray = array();
            foreach ($deal_details as $dvalue) {
                //echo $dvalue->dealID."-----";
                $currentDate = date('Y-m-d');
                //$deal = Deal::where('id',$dvalue->dealID)->where('status',1)->where('whereshow',3)->orWhere('whereshow',1)->first();
                $deals = Deal::where('id', $dvalue->dealID)
                    ->where('status', 1)
                    ->where(function ($query) {
                        $query->where('whereshow', 3)
                            ->orWhere('whereshow', 1);
                    })
                    ->count();
                if ($deals != 0) {
                    $deal = Deal::where('id', $dvalue->dealID)
                        ->where('status', 1)
                        ->where(function ($query) {
                            $query->where('whereshow', 3)
                                ->orWhere('whereshow', 1);
                        })
                        ->first();
                    $datadeal['position'] = $deal->position;
                    $datadeal['id'] = $deal->id;

                    $datadeal['startDate'] = $deal->startDate;
                    $datadeal['endDate'] = $deal->endDate;
                    $datadeal['image'] = $deal->image;
                    $datadeal['tag_icon'] = $deal->tag_icon;

                    $future = strtotime(date('Y-m-d')); //Future date.
                    $timefromdb = strtotime($deal->endDate);
                    $timeleft = $timefromdb - $future;
                    $daysleft = round((($timeleft / 24) / 60) / 60);
                    $datadeal['remaning_day'] = $daysleft;
                    $datadeal['typesofdeal'] = $deal->typesofdeal;
                    if ($language == 2) {
                        $datadeal['name'] = $deal->name_ar;
                        $datadeal['discription'] = $deal->discription_ar;
                        $datadeal['tag_name'] = $deal->tag_name_ar;
                        if ($deal->typesofdeal != "") {
                            $datadeal['typesofdeal_name'] = DealType::where('id', $deal->typesofdeal)->value('name_ar');
                        } else {
                            $datadeal['typesofdeal_name'] = "";
                        }
                    } else {
                        $datadeal['name'] = $deal->name;
                        $datadeal['discription'] = $deal->discription;
                        $datadeal['tag_name'] = $deal->tag_name;
                        if ($deal->typesofdeal != "") {
                            $datadeal['typesofdeal_name'] = DealType::where('id', $deal->typesofdeal)->value('name');
                        } else {
                            $datadeal['typesofdeal_name'] = "";
                        }
                    }
                    //array_push($dealArray,$datadeal);
                    if ($deal->endDate >= $currentDate) {
                        if (empty($filterIdList)) {
                            array_push($dealArray, $datadeal);
                        } else {
                            //$filterIdListarray=explode(",",$filterIdList);
                            if (in_array($deal->typesofdeal, $filterIdList)) {
                                array_push($dealArray, $datadeal);
                            }
                        }
                    }
                }
            }
            //die;
            //topDeals
            $currentDate = date('Y-m-d');
            $deals = Deal::where('endDate', '>=', $currentDate)->where('status', 1)->where('popular', 1)->get();
            $dealArrays = array();
            foreach ($deals as $dealvalues) {
                $datadeals['id'] = $dealvalues->id;
                $datadeals['image'] = $dealvalues->image;
                array_push($dealArrays, $datadeals);
                // if($dealvalues->startDate<=$currentDate){
                //     array_push($dealArrays,$datadeals);
                // }
            }
            sort($dealArray);
            $arr['all'] = $dealArray;
            $arr['banner'] = $dealArrays;
            return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
        }
    }

    //Deals details
    public function dealsdetail(Request $request)
    {
        $userID = $request->userID;
        $dealID = $request->dealID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($dealID == null) {
            $err_array[] = 'dealID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $currentDate = date('Y-m-d');
            $deal = Deal::where('id', $dealID)->where('endDate', '>=', $currentDate)->where('status', 1)->first();
            if ($deal->startDate <= $currentDate) {
                $datadeal['id'] = $deal->id;
                //$datadeal['name'] = $deal->name;
                //$datadeal['discription'] = $deal->discription;
                $datadeal['startDate'] = $deal->startDate;
                $datadeal['endDate'] = $deal->endDate;
                $datadeal['image'] = $deal->image;
                $datadeal['internalpage'] = $deal->internalpage;
                $datadeal['pageid'] = $deal->pageid;
                $future = strtotime(date('Y-m-d')); //Future date.
                $timefromdb = strtotime($deal->endDate);
                $timeleft = $timefromdb - $future;
                $daysleft = round((($timeleft / 24) / 60) / 60);
                $datadeal['remaning_day'] = $daysleft;
                $datadeal['dealType'] = $deal->dealType;
                $datadeal['tag_icon'] = $deal->tag_icon;
                //$datadeal['tag_name'] = $deal->tag_name;
                if ($language == 2) {
                    $datadeal['name'] = $deal->name_ar;
                    $datadeal['discription'] = $deal->discription_ar;
                    $datadeal['tag_name'] = $deal->tag_name_ar;
                } else {
                    $datadeal['name'] = $deal->name;
                    $datadeal['discription'] = $deal->discription;
                    $datadeal['tag_name'] = $deal->tag_name;
                }

                $deal_details = DealDetail::where('dealID', $dealID)->first();
                if ($deal->dealType == 1) {
                    if ($deal_details->dealTypeID != "") {
                        $datadeal['dealTypeid'] = $deal_details->dealTypeID;
                        if ($language == 2) {
                            $datadeal['dealTypename'] = Brand::find($deal_details->dealTypeID)->name_ar;
                        } else {
                            $datadeal['dealTypename'] = Brand::find($deal_details->dealTypeID)->name;
                        }
                    } else {
                        $datadeal['dealTypeid'] = "";
                        if ($language == 2) {
                            $datadeal['dealTypename'] = "";
                        } else {
                            $datadeal['dealTypename'] = "";
                        }
                    }
                } else {
                    if ($deal_details->dealTypeID != "") {
                        $datadeal['dealTypeid'] = $deal_details->dealTypeID;
                        if ($language == 2) {
                            $datadeal['dealTypename'] = Agent::find($deal_details->dealTypeID)->name_ar;
                        } else {
                            $datadeal['dealTypename'] = Agent::find($deal_details->dealTypeID)->name;
                        }
                    } else {
                        $datadeal['dealTypeid'] = "";
                        if ($language == 2) {
                            $datadeal['dealTypename'] = "";
                        } else {
                            $datadeal['dealTypename'] = "";
                        }
                    }

                    // $datadeal['dealTypeid']= $deal_details->dealTypeID;
                    // if($language==2){
                    //     $datadeal['dealTypename']= Agent::find($deal_details->dealTypeID)->name_ar;
                    // }else{
                    //     $datadeal['dealTypename']= Agent::find($deal_details->dealTypeID)->name;
                    // }

                }
                return response()->json(['success' => 'true', 'message' => '', 'result' => $datadeal], 200);
            }
        }
    }

    //welcome Image
    public function welcomeimage(Request $request)
    {
        $currentDate = date('Y-m-d');
        $language = $request->language;
        $banner = WelcomeImage::where('end_date', '>=', $currentDate)->where('status', 1)->orderBy('position_id', 'asc')->get();
        $bannerArray = array();
        foreach ($banner as $value) {
            $data['id'] = $value->id;
            //$data['position_id'] = $value->position_id;
            $data['image'] = $value->image;
            if ($language == 2) {
                $data['name'] = $value->name_ar;
                $data['discription'] = $value->discription_ar;
            } else {
                $data['name'] = $value->name;
                $data['discription'] = $value->discription;
            }
            $data['url'] = $value->url;
            $data['start_date'] = $value->start_date;
            $data['end_date'] = $value->end_date;
            $data['image_time'] = $value->image_time;
            $data['automatic'] = $value->skipable;
            $data['internal_external'] = $value->internal_external;
            $img =  explode(".", $value->image);
            $data['type'] = end($img);
            if ($value->start_date <= $currentDate) {
                array_push($bannerArray, $data);
            }
        }
        //image setting
        $ImageSetting = ImageSetting::find(1);
        $backgroundimage = $ImageSetting->backgroundimage;
        $sliding = $ImageSetting->sliding;
        return response()->json(['success' => 'true', 'message' => '', 'result' => $bannerArray, 'skippable' => 0, 'sliding' => 0, 'background_image' => $backgroundimage], 200);
    }

    //welcome Image
    public function welcometutorial()
    {
        $intro_video = IntroVideo::find(1);
        $data = [
            // 'video' => 'https://cdn.pixabay.com/video/2015/11/27/1406-147169807_medium.mp4',
            'video' => url('public/video/intro_video') . '/' . $intro_video->video
        ];
        return response()->json(['success' => 'true', 'message' => '', 'data' => $data, 'is_skipable' => $intro_video->is_skipable, 'skip_time' => $intro_video->skip_time], 200);
    }

    //welcome Image
    public function isRepeatIntroVideo()
    {
        $intro_video = IntroVideo::find(1);
        $is_repeat = $intro_video->is_repeat;
        return response()->json(['success' => 'true', 'message' => '', 'is_repeat' => $is_repeat], 200);
    }

    //logout
    public function logout(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم تسجيل الخروج";
            } else {
                $msg_success = "Logged out successfully.";
            }
            if (Auth::check()) {
                Auth::user()->AauthAcessToken()->delete();
            }
            //remove device token
            if ($userID != 43) {
                $user = User::find($userID);
                $user->deviceToken = '';
                $user->accessToken = '';
                $user->is_login = 0;
                $user->save();
            }
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    //User viewed
    public function viewed(Request $request)
    {
        $userID = $request->userID;
        $menutype = $request->menutype;
        $language = $request->language;
        $id = $request->id;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($menutype == null) {
            $err_array[] = 'menutype';
        }
        if ($id == null) {
            $err_array[] = 'id';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم الحفظ";
            } else {
                $msg_success = "Successfully saved.";
            }
            $checkip = Viewed::where('userID', $userID)->where('menuID', $id)->where('menu_type', $menutype)->count();
            if ($checkip == 0) {
                $viewed = new Viewed;
                $viewed->menu_type = $request->menutype;
                $viewed->ip_address = $_SERVER['REMOTE_ADDR'];
                $viewed->userID = $userID;
                $viewed->menuID = $request->id;
                $viewed->save();
            }
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    //Add Brand as favorite
    public function addbrandfavorites(Request $request)
    {
        $userID = $request->userID;
        $brandID = $request->brandID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($brandID == null) {
            $err_array[] = 'brandID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم اضافة البراند الى المفضلة";
                $msg_failed = "تم حذف البراند من المفضلة";
            } else {
                $msg_success = "The brand has been added to your favorites.";
                $msg_failed = "The brand has been removed from your favorites.";
            }
            $check = BrandFavorite::where('userID', $userID)->where('brandID', $brandID)->count();
            if ($check == 0) {
                $favorites = new BrandFavorite;
                $favorites->brandID = $brandID;
                $favorites->userID = $userID;
                $favorites->favorite = 1;
                $favorites->save();
                //send notification
                if ($language == 2) {
                    $carname = Brand::where('id', $brandID)->first()->name_ar;
                    $msgnotification = $carname . " تم اضافة البراند الى المفضلة";
                    $titlenotification = "تم اضافة البراند الى المفضلة";
                } else {
                    $carname = Brand::where('id', $brandID)->first()->name;
                    $msgnotification = $carname . " Brand added to your favorites.";
                    $titlenotification = "Brand Added to Favorites.";
                }
                $devicetoken = User::where('id', $userID)->first()->deviceToken;
                //notification_hide $fav_brand.','.$fav_car.','.$fav_agency.','.$fav_news;
                $notification_hide = User::where('id', $userID)->first()->notification_hide;
                if (strlen($notification_hide) >= 7) {
                    $notiarray = explode(',', $notification_hide);
                    if ($notiarray[0] == 0) {
                        $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                    }
                } else {
                    $this->send_notification($devicetoken, $msgnotification, $titlenotification);
                }
                //$this->send_notification($devicetoken, $msgnotification, $titlenotification);
                return response()->json(['success' => 'true', 'message' => $msg_success], 200);
            } else {
                $favorites = BrandFavorite::where('userID', $userID)->where('brandID', $brandID)->delete();
                return response()->json(['success' => 'true', 'message' => $msg_failed], 200);
            }
        }
    }

    //Brand favorite list
    public function brandfavorites_list(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $favoritearray = array();
            $favorite = BrandFavorite::where('userID', $userID)->orderBy('id', 'DESC')->get();
            foreach ($favorite as $value) {
                $data['id'] = $value->id;
                $data['userID'] = $value->userID;
                $data['brandID'] = $value->brandID;
                $vehicle = Brand::where('id', $value->brandID)->first();
                $data['image'] = $vehicle->logo;
                $data['tag_icon'] = $vehicle->tag_icon;
                if ($language == 2) {
                    $data['name'] = $vehicle->name_ar;
                    $data['description'] = $vehicle->description_ar;
                    $data['tag_name'] = $vehicle->tag_name_ar;
                } else {
                    $data['name'] = $vehicle->name;
                    $data['description'] = $vehicle->description;
                    $data['tag_name'] = $vehicle->tag_name;
                }
                array_push($favoritearray, $data);
            }
            return response()->json(['success' => 'true', 'message' => "", 'result' => $favoritearray], 200);
        }
    }

    //Update user location
    public function update_userlocation(Request $request)
    {
        $userID = $request->userID;
        $locationID = $request->locationID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($locationID == null) {
            $err_array[] = 'locationID';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث بنجاح";
            } else {
                $msg_success = "Successfully updated";
            }
            $user = User::find($userID);
            $user->location = $locationID;
            $user->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    //Update user language
    public function update_userlanguage(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            if ($language == 2) {
                $msg_success = "تم التحديث بنجاح.";
            } else {
                $msg_success = "Successfully updated";
            }
            $user = User::find($userID);
            $user->language = $language;
            $user->save();
            return response()->json(['success' => 'true', 'message' => $msg_success], 200);
        }
    }

    //home search
    public function home_search(Request $request)
    {
        $userID = $request->userID;
        $searchtext = $request->searchtext;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if ($searchtext == null) {
            $err_array[] = 'searchtext';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //agencies
            if ($language == 2) {
                $agencies = Agent::where('status', 1)->where('name_ar', 'like', '%' . $searchtext . '%')->orWhere('discription_ar', 'like', '%' . $searchtext . '%')->get();
            } else {
                $agencies = Agent::where('status', 1)->where('name', 'like', '%' . $searchtext . '%')->orWhere('description', 'like', '%' . $searchtext . '%')->get();
            }
            $agenciesArray = array();
            foreach ($agencies as $aggvalue) {
                $dataa['id'] = $aggvalue->id;
                if ($language == 2) {
                    $dataa['name'] = $aggvalue->name_ar;
                } else {
                    $dataa['name'] = $aggvalue->name;
                }
                $dataa['logo'] = $aggvalue->logo;
                if ($language == 2) {
                    $dataa['description'] = $aggvalue->discription_ar;
                } else {
                    $dataa['description'] = $aggvalue->description;
                }

                $dataa['images'] = $aggvalue->images;
                $dataa['tag_icon'] = $aggvalue->tag_icon;
                if ($language == 2) {
                    $dataa['tag_name'] = $aggvalue->tag_name_ar;
                } else {
                    $dataa['tag_name'] = $aggvalue->tag_name;
                }

                $checkagentfav = AgentFavorite::where('userID', $userID)->where('agentID', $aggvalue->id)->count();
                if ($checkagentfav != 0) {
                    $dataa['favorite'] = 1;
                } else {
                    $dataa['favorite'] = 0;
                }
                array_push($agenciesArray, $dataa);
            }

            //hotnews
            if ($language == 2) {
                $news = Magazine::where('status', 1)->where('heading_ar', 'like', '%' . $searchtext . '%')->orWhere('description_ar', 'like', '%' . $searchtext . '%')->get();
            } else {
                $news = Magazine::where('status', 1)->where('heading', 'like', '%' . $searchtext . '%')->orWhere('description', 'like', '%' . $searchtext . '%')->get();
            }
            $newsarray = array();
            foreach ($news as $nvalue) {
                $datanews['id'] = $nvalue->id;
                $datanews['image'] = $nvalue->image;
                $datanews['videourl'] = $nvalue->videourl;
                $datanews['postedDate'] = $nvalue->postedDate;
                $datanews['tag_icon'] = $nvalue->tag_icon;
                if ($language == 2) {
                    $datanews['heading'] = $nvalue->heading_ar;
                    $datanews['description'] = $nvalue->description_ar;
                    $datanews['postedBy'] = $nvalue->postedBy_ar;
                    $datanews['tag_name'] = $nvalue->tag_name_ar;
                } else {
                    $datanews['heading'] = $nvalue->heading;
                    $datanews['description'] = $nvalue->description;
                    $datanews['postedBy'] = $nvalue->postedBy;
                    $datanews['tag_name'] = $nvalue->tag_name;
                }
                $checknewsfav = NewsFavorite::where('userID', $userID)->where('newsID', $nvalue->id)->count();
                if ($checknewsfav != 0) {
                    $datanews['favorite'] = 1;
                } else {
                    $datanews['favorite'] = 0;
                }
                $views = Viewed::where('menu_type', 2)->where('menuID', $nvalue->id)->count();
                if ($views == 0) {
                    $datanews['totalviews'] = $nvalue->totalview;
                } else {
                    $datanews['totalviews'] = $views + $nvalue->totalview;
                }
                //$datanews['totalviews'] = Viewed::where('menu_type',2)->where('menuID',$nvalue->id)->count();
                //category
                $datanews['categoryID'] = $nvalue->subcategoryID;
                if ($nvalue->subcategoryID != "") {
                    $checkcategory = MagazineSubcategory::where('id', $nvalue->subcategoryID)->count();
                    if ($checkcategory != 0) {
                        if ($language == 2) {
                            $datanews['categoryName'] = MagazineSubcategory::where('id', $nvalue->subcategoryID)->first()->name_ar;
                        } else {
                            $datanews['categoryName'] = MagazineSubcategory::where('id', $nvalue->subcategoryID)->first()->name;
                        }
                    } else {
                        $datanews['categoryName'] = "";
                    }
                } else {
                    $datanews['categoryName'] = "";
                }
                array_push($newsarray, $datanews);
            }
            //popularcar
            // if($language==2){
            //     $vehicle = Vehicle::where('status',1)->where('name_ar', 'like', '%' . $searchtext . '%')->orWhere('description_ar', 'like', '%' . $searchtext . '%')->get();
            // }else{
            //     $vehicle = Vehicle::where('status',1)->where('name', 'like', '%' . $searchtext . '%')->orWhere('description', 'like', '%' . $searchtext . '%')->get();
            // }
            // $vehicle = CarJatoSpecification::where('status', 1)->where('name', 'like', '%' . $searchtext . '%')->get();
            // $vehicleArray = array();
            // foreach ($vehicle as $carvalue) {
            //     $datavehicle['id'] = $carvalue->id;
            //     $datavehicle['year'] = $carvalue->year;
            //     $CarImage = CarImage::where('vehicleID', $carvalue->id);
            //     if ($CarImage->count() != 0) {
            //         if ($CarImage->first()->image_type == 1) {
            //             $datavehicle['image'] = url('public/images/car/' . $CarImage->first()->image);
            //         } else {
            //             $datavehicle['image'] = $CarImage->first()->image;
            //         }
            //     } else {
            //         $datavehicle['image'] = "";
            //     }
            //     // $CarImage = CarImage::where('vehicleID',$carvalue->id)->where('type',1);
            //     // if($CarImage->count()!=0){
            //     //     $datavehicle['image'] =$CarImage->first()->image;
            //     // }else{
            //     //     $datavehicle['image'] ="";
            //     // }
            //     if ($language == 2) {
            //         $datavehicle['name'] = $carvalue->name;
            //     } else {
            //         $datavehicle['name'] = $carvalue->name;
            //     }

            //     $datavehicle['modelID'] = $carvalue->modelID;
            //     // $VehicleTrimEnginecount = VehicleTrimEngine::where('vehicleID',$carvalue->id);
            //     // if($VehicleTrimEnginecount->count()!=0){
            //     //     $VehicleTrimEngine = $VehicleTrimEnginecount->first();
            //     //     if($VehicleTrimEngine->transmission!=''){
            //     //         if (str_contains($VehicleTrimEngine->transmission, 'automatic')) {
            //     //             if($language==2){
            //     //                 $geartype = "تلقائي";
            //     //             }else{
            //     //                 $geartype = "Automatic";
            //     //             }

            //     //         }else{
            //     //             if($language==2){
            //     //                 $geartype = "يدوي";
            //     //             }else{
            //     //                 $geartype = "Manual";
            //     //             }

            //     //         }
            //     //     }else{
            //     //         $geartype = "";
            //     //     }
            //     // }
            //     // $VehicleTrimEngine = VehicleTrimEngine::where('vehicleID',$carvalue->id)->first();
            //     // if()
            //     // if (str_contains($VehicleTrimEngine->transmission, 'automatic')) {
            //     //    $geartype = "Automatic";
            //     // }else{
            //     //     $geartype = "Manual";
            //     // }
            //     $datavehicle['kmpl'] = $carvalue->maximumPowerhpPs . " HP";
            //     $datavehicle['cc'] = $carvalue->maximumPowerKw . " Kw";
            //     $datavehicle['geartype'] = $carvalue->transmissionToDisplay;
            //     $checkfav = CarFavorite::where('userID', $userID)->where('vehicleID', $carvalue->id)->count();
            //     if ($checkfav != 0) {
            //         $datavehicle['favorite'] = 1;
            //     } else {
            //         $datavehicle['favorite'] = 0;
            //     }
            //     $avgStar = CarReview::where('vehicleID', $carvalue->id)->avg('rate');
            //     $datavehicle['rating'] = "" . $avgStar;
            //     $CarReview = CarReview::where('vehicleID', $carvalue->id);
            //     $datavehicle['reviews'] = $CarReview->count();
            //     //$datavehicle['geartypes'] = $VehicleTrimEngine->transmission;
            //     array_push($vehicleArray, $datavehicle);
            // }

            // $vehicles = PopularCar::where('status',1)->where('make', 'like', '%' . $searchtext . '%')->orWhere('model', 'like', '%' . $searchtext . '%')->orWhere('trim', 'like', '%' . $searchtext . '%')->get();
            // $vehicleArray = array();
            // foreach ($vehicles as $vehicle) {
            //     $datavehicle['id'] = $vehicle->id;
            //     $datavehicle['brand_id'] = $vehicle->year;
            //     $datavehicle['make'] = $vehicle->make;
            //     $datavehicle['model'] = $vehicle->model;
            //     $datavehicle['trim'] = $vehicle->trim;
            //     $datavehicle['year'] = $vehicle->year;
            //     $datavehicle['kmpl'] = "";
            //     $datavehicle['cc'] = "";
            //     $datavehicle['geartype'] = "";
            //     $datavehicle['favorite'] = 0;
            //     $datavehicle['rating'] = "";
            //     $datavehicle['reviews'] = 0;
            //     $CarImage = CarImage::where([
            //         'brand_id' => $vehicle->brand_id, 
            //         'make' => $vehicle->make, 
            //         'model' => $vehicle->model, 
            //         'trim' => $vehicle->trim, 
            //         'year' => $vehicle->year
            //     ]);
            //     $brandDetails = Brand::find($vehicle->brand_id);
            //     if ($CarImage->count() != 0) {
            //         if ($CarImage->first()->image_type == 1) {
            //             $datavehicle['image'] = url('public/images/' . $CarImage->first()->image);
            //         } else {
            //             $datavehicle['image'] = $CarImage->first()->image ?? "";
            //         }
            //     } else {
            //         $datavehicle['image'] = $brandDetails->logo ? url('public/images/' . $brandDetails->logo) : url("public/img/image-preview.png");
            //     }
            //     // $CarImage = CarImage::where('vehicleID',$carvalue->id)->where('type',1);
            //     // if($CarImage->count()!=0){
            //     //     $datavehicle['image'] =$CarImage->first()->image;
            //     // }else{
            //     //     $datavehicle['image'] ="";
            //     // }
            //     // if ($language == 2) {
            //     //     $datavehicle['name'] = $carvalue->name;
            //     // } else {
            //     //     $datavehicle['name'] = $carvalue->name;
            //     // }

            //     // $datavehicle['modelID'] = $carvalue->modelID;
            //     // $VehicleTrimEnginecount = VehicleTrimEngine::where('vehicleID',$carvalue->id);
            //     // if($VehicleTrimEnginecount->count()!=0){
            //     //     $VehicleTrimEngine = $VehicleTrimEnginecount->first();
            //     //     if($VehicleTrimEngine->transmission!=''){
            //     //         if (str_contains($VehicleTrimEngine->transmission, 'automatic')) {
            //     //             if($language==2){
            //     //                 $geartype = "تلقائي";
            //     //             }else{
            //     //                 $geartype = "Automatic";
            //     //             }

            //     //         }else{
            //     //             if($language==2){
            //     //                 $geartype = "يدوي";
            //     //             }else{
            //     //                 $geartype = "Manual";
            //     //             }

            //     //         }
            //     //     }else{
            //     //         $geartype = "";
            //     //     }
            //     // }
            //     // $VehicleTrimEngine = VehicleTrimEngine::where('vehicleID',$carvalue->id)->first();
            //     // if()
            //     // if (str_contains($VehicleTrimEngine->transmission, 'automatic')) {
            //     //    $geartype = "Automatic";
            //     // }else{
            //     //     $geartype = "Manual";
            //     // }
            //     // $datavehicle['kmpl'] = $carvalue->maximumPowerhpPs . " HP";
            //     // $datavehicle['cc'] = $carvalue->maximumPowerKw . " Kw";
            //     // $datavehicle['geartype'] = $carvalue->transmissionToDisplay;
            //     // $checkfav = CarFavorite::where('userID', $userID)->where('vehicleID', $carvalue->id)->count();
            //     // if ($checkfav != 0) {
            //     //     $datavehicle['favorite'] = 1;
            //     // } else {
            //     //     $datavehicle['favorite'] = 0;
            //     // }
            //     // $avgStar = CarReview::where('vehicleID', $carvalue->id)->avg('rate');
            //     // $datavehicle['rating'] = "" . $avgStar;
            //     // $CarReview = CarReview::where('vehicleID', $carvalue->id);
            //     // $datavehicle['reviews'] = $CarReview->count();
            //     //$datavehicle['geartypes'] = $VehicleTrimEngine->transmission;
            //     array_push($vehicleArray, $datavehicle);
            // }





            $vehicle = PopularCar::where('status',1)->where('make', 'like', '%' . $searchtext . '%')->orWhere('model', 'like', '%' . $searchtext . '%')->orWhere('trim', 'like', '%' . $searchtext . '%')->get();
            $vehicleArray = array();

            foreach ($vehicle as $key => $value) {
                
                $brand = Brand::find($value->brand_id);

                if (!$brand || empty($brand->excel)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Data not found.'
                    ]);
                }

                $filePath = public_path('brand/excel/' . $brand->excel);

                // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

                if (!file_exists($filePath)) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Excel file does not exist in the specified path.'
                    ], 404);
                }

                $filteredRows = [];

                if (($handle = fopen($filePath, "r")) !== false) {
                    $sample = fgets($handle);
                    rewind($handle);
                    $delimiter = strpos($sample, ';') !== false ? ';' : ',';

                    $rawHeaders = fgetcsv($handle, 0, $delimiter);
                    $headers = array_map(function ($header) {
                        $header = trim($header);
                        $header = preg_replace('/\s+/', '_', $header); // spaces to _
                        $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                        return $header;
                    }, $rawHeaders);

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        if (count($row) !== count($headers)) {
                            continue;
                        }

                        $row = array_map('trim', $row);
                        $assocRow = array_combine($headers, $row);
                        $price = '';
                        foreach ($assocRow as $k => $v) {
                            if (stripos($k, 'price') !== false && !empty($v)) {
                                $price = $v;
                                break;
                            }
                        }
                        if (
                            isset($assocRow['Make']) &&
                            $assocRow['Make'] == $value->make &&
                            isset($assocRow['Model']) &&
                            $assocRow['Model'] == $value->model &&
                            isset($assocRow['Trim']) &&
                            $assocRow['Trim'] == $value->trim &&
                            isset($assocRow['Model_Year']) &&
                            $assocRow['Model_Year'] == $value->year
                        ) {
                            $formatted = [
                                'make' => $assocRow['Make'],
                                'model' => $assocRow['Model'],
                                'trim' => $assocRow['Trim'],
                                'model_year' => $assocRow['Model_Year'],
                                'kmpl' => $assocRow['Horsepower_(hp)'],
                                'cc' => $assocRow['Engine_Displacement_(cc)'],
                                'geartype' => $assocRow['Transmission_Type'],
                                'rating' => $assocRow['Safety_Rating'],
                                'price' => $price ?? "",
                            ];

                            $filteredRows[] = $formatted;
                        }

                    }

                    fclose($handle);
                }

                if (count($filteredRows) == 0){
                    PopularCar::delete($value->id);
                }
                
                foreach ($filteredRows as $carvalue) {
                    $datavehicle['id'] = $value->brand_id;
                    $datavehicle['make'] = $carvalue['make'];
                    $datavehicle['model'] = $carvalue['model'];
                    $datavehicle['trim'] = $carvalue['trim'];
                    $datavehicle['year'] = $carvalue['model_year'];
                    $datavehicle['kmpl'] = $carvalue['kmpl'] . " HP";
                    $datavehicle['cc'] = $carvalue['cc'] . " KW";
                    $datavehicle['geartype'] = $carvalue['geartype'];
                    // $datavehicle['favorite'] = 0;
                    $datavehicle['rating'] = $carvalue['rating'];
                    $datavehicle['reviews'] = 0;
                    $datavehicle['price'] = $carvalue['price'];
                    // $datavehicle['id'] = $carvalue->id;
                    // $CarImage = CarImage::where('vehicleID', $carvalue->id);
                    $brandDetails = Brand::find($value->brand_id);
                    $CarImage = CarImage::where([
                        'brand_id' => $value->brand_id, 
                        'make' => $carvalue['make'], 
                        'model' => $carvalue['model'], 
                        'trim' => $carvalue['trim'], 
                        'year' => $carvalue['model_year']
                    ]);
                    if ($CarImage->count() != 0) {
                        if ($CarImage->first()->image_type == 1) {
                            $datavehicle['image'] = url('public/images/' . $CarImage->first()->image);
                        } else {
                            $datavehicle['image'] = $CarImage->first()->image ?? "";
                        }
                    } else {
                        $datavehicle['image'] = $brandDetails->logo ? url('public/images/' . $brandDetails->logo) : url("public/img/image-preview.png");
                    }
                    // if ($language == 2) {
                    //     //$datavehicle['name'] = $carvalue->name_ar;
                    //     $datavehicle['name'] = $carvalue->name;
                    // } else {
                    //     $datavehicle['name'] = $carvalue->name;
                    // }

                    // $datavehicle['modelID'] = $carvalue->modelID;
                    // $datavehicle['year'] = $carvalue->year;
                    // $datavehicle['kmpl'] = $carvalue->maximumPowerhpPs . " HP";
                    // $datavehicle['cc'] = $carvalue->maximumPowerKw . " KW";
                    // $datavehicle['geartype'] = $carvalue->transmissionToDisplay;
                    $checkfav = CarFavorite::where(['userID' => $userID, 'make' => $value->make, 'model' => $value->model, 'trim' => $value->trim, 'year' => $value->year])->count();
                    // $checkfav = CarFavorite::where('userID', $userID)->where('vehicleID', $carvalue->id)->count();
                    if ($checkfav != 0) {
                        $datavehicle['favorite'] = 1;
                    } else {
                        $datavehicle['favorite'] = 0;
                    }
                    // $avgStar = CarReview::where('vehicleID', $carvalue->id)->avg('rate');
                    // $datavehicle['rating'] = "" . $avgStar;
                    // $CarReview = CarReview::where('vehicleID', $carvalue->id);
                    // $datavehicle['reviews'] = $CarReview->count();
                    //$datavehicle['geartypes'] = $VehicleTrimEngine->transmission;
                    array_push($vehicleArray, $datavehicle);
                }
            }









            //review
            $review_array = array();
            foreach ($vehicleArray as $vechrev) {
                // $car_review = CarReview::where('vehicleID', $vechrev['id'])->limit(1)->offset(0)->orderBy('id', 'DESC');
                $car_review = CarReview::where('userID', $userID)->limit(1)->offset(0)->orderBy('id', 'DESC');
                if ($car_review->count() != 0) {
                    $car_reviews = $car_review->first();
                    $datareview['name'] = $vechrev['make'];
                    $datareview['rate'] = $car_reviews->rate;
                    $datareview['title'] = $car_reviews->title;
                    $datareview['description'] = $car_reviews->message;
                    $datareview['username'] = User::find($car_reviews->userID)->name;
                    $datareview['reviewDate'] = date('M d, Y', strtotime($car_reviews->created_at));
                    array_push($review_array, $datareview);
                }
            }

            $arr['popularcar'] = $vehicleArray;
            $arr['agencies'] = $agenciesArray;
            $arr['hotnews'] = $newsarray;
            $arr['review'] = $review_array;
            return response()->json(['success' => 'true', 'message' => '', 'result' => $arr], 200);
        }
    }

    public function dealtype(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $dealtype = DealType::where('status', 1)->get();
            $recordArray = array();
            foreach ($dealtype as $value) {
                $data['id'] = $value->id;
                if ($language == 2) {
                    $data['name'] = $value->name_ar;
                } else {
                    $data['name'] = $value->name;
                }
                array_push($recordArray, $data);
            }
            return response()->json(['success' => 'true', 'message' => '', 'result' => $recordArray], 200);
        }
    }

    public function car_fillter(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //VehicleType
            $VehicleTypeArray = array();
            $VehicleType = VehicleType::where('status', 1)->get();
            foreach ($VehicleType as $value) {
                $vehicle_data['id'] = $value->id;
                if ($language == 2) {
                    $vehicle_data['name'] = $value->name_ar;
                } else {
                    $vehicle_data['name'] = $value->name;
                }
                $vehicle_data['image'] = $value->image;
                array_push($VehicleTypeArray, $vehicle_data);
            }
            //fule type
            if ($language == 2) {
                $fuletype = ["ديزل" => 'diesel.png', "بنزين" => 'petrol-station.png', "غاز طبيعي مضغوط" => 'gas-station.png', "كهرباء" => 'electric.png', "هجين" => 'hybrid.png'];
            } else {
                //$fuletype = ['Diesel'=>'1','Petrol'=>'2','CNG'=>'3','Electric'=>'4'];
                $fuletype = ['Diesel' => 'diesel.png', 'Petrol' => 'petrol-station.png', 'Gas' => 'gas-station.png', 'Electric' => 'electric.png', 'Hybrid' => 'hybrid.png'];
            }
            $i = 1;
            $fuletypeArray = array();
            foreach ($fuletype as $key => $fulevalue) {
                $fule_data['id'] = $i;
                $fule_data['name'] = $key;
                $fule_data['image'] = $fulevalue;
                array_push($fuletypeArray, $fule_data);
                $i++;
            }
            //seats
            if ($language == 2) {
                $seats = "عدد المقاعد";
            } else {
                $seats = "Seater";
            }
            $j = 1;
            $seatsArray = array();
            $seatdata = [2, 4, 5, 6, 7, 8, 9, 10, 12];
            foreach ($seatdata as $seatvalue) {
                $data_seats['id'] = $j;
                $data_seats['name'] = $seatvalue . ' ' . $seats;
                array_push($seatsArray, $data_seats);
                $j++;
            }
            //cylender
            if ($language == 2) {
                $cyliender = "اسطوانة";
            } else {
                $cyliender = "Cylinder";
            }
            $k = 1;
            $cylenderArray = array();
            $cylenderdata = [1, 3, 4, 6, 8, 10, 12];
            foreach ($cylenderdata as $cylindervalue) {
                $data_cyliender['id'] = $k;
                $data_cyliender['name'] = $cylindervalue . ' ' . $cyliender;
                array_push($cylenderArray, $data_cyliender);
                $k++;
            }
            //transmission
            if ($language == 2) {

                $transmission = ['تلقائي', 'يدوي'];
            } else {
                $transmission = ['Automatic', 'Manual'];
            }
            $l = 1;
            $transmissionArray = array();
            foreach ($transmission as $transmissionvalue) {
                $transmission_data['id'] = $l;
                $transmission_data['name'] = $transmissionvalue;
                array_push($transmissionArray, $transmission_data);
                $l++;
            }
            $recordArray['VehicleTypeArray'] = $VehicleTypeArray;
            $recordArray['fuletypeArray'] = $fuletypeArray;
            $recordArray['seatsArray'] = $seatsArray;
            $recordArray['cylenderArray'] = $cylenderArray;
            $recordArray['transmissionArray'] = $transmissionArray;
            return response()->json(['success' => 'true', 'message' => '', 'result' => $recordArray], 200);
        }
    }

    public function Category(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            $categoryArray = array();
            $category = Category::where('status', 1)->get();
            foreach ($category as $value) {
                $data['id'] = $value->id;
                if ($language == 2) {
                    $data['name'] = $value->name_ar;
                } else {
                    $data['name'] = $value->name;
                }
                array_push($categoryArray, $data);
            }
            return response()->json(['success' => 'true', 'message' => '', 'result' => $categoryArray], 200);
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
        $json = json_encode(['key' => '1', 'key2' => '10', 'internal_external' => "", 'page' => "", 'image' => ""]);
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
        if ($httpcode === 200) {
            echo "Notification sent successfully\n";
        } else {
            echo "Failed to send notification\n";
            echo "HTTP Status Code: $httpcode\n";
            echo "Response: $result\n";
        }
        curl_close($ch);
        return $result;
    }

    function testNotification(){
        // $devicetoken = "fcBmphGNQ0y57KG1aSAT2u:APA91bFYNAhtMeCEBVWzrnxY8Gh8VD41SD8aIcGIN3O3ikuONUibL61OMWLuBklLmYh5b1guB7rwlEvmxJ300CpkreE3Hrs_C5Lj6FWgjnww_q4_cBl32fw";
        $devicetoken = "di-XAhVgyUl-pDTq9PlGUw:APA91bEi2QPXqb_2u5eLhxb7Q7dxdFonFz3ROmee-3Yxx4ovP6pA_Bw2SPhiBoO0qVkc5rpm3Z4kT-KNxvuFnezZuDA0zTFMmqOBGCjj8qelLOZi80D52T4";
        $msgnotification = "msg";
        $titlenotification = "title";
        $this->send_notification($devicetoken, $msgnotification, $titlenotification);
    }

    function get_cityid(Request $request)
    {
        $userID = $request->userID;
        $language = $request->language;
        $city_name = $request->city_name;
        $country_name = $request->country_name;
        $state_name = $request->state_name;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //$city_id = City::where('name',ucfirst($city_name))->first()->id;
            $city_id = City::where('name', ucfirst($city_name));
            if ($city_id->count() == 0) {
                $country = Country::where("name", ucfirst($country_name));
                if ($country->count() == 0) {
                    $countryinst = new Country;
                    $countryinst->shortname = '';
                    $countryinst->name = $country_name;
                    $countryinst->phonecode = '';
                    $countryinst->status = '1';
                    $countryinst->save();
                    $country_id = $countryinst->id;
                } else {
                    $country_id = $country->first()->id;
                }

                //state
                $state = State::where("name", ucfirst($state_name));
                if ($state->count() == 0) {
                    $stateinsert = new State;
                    $stateinsert->name = $state_name;
                    $stateinsert->country_id = $country_id;
                    $stateinsert->status = 1;
                    $stateinsert->save();
                    $state_id = $stateinsert->id;
                } else {
                    $state_id = $state->first()->id;
                }
                //city
                $city_insert = new City;
                $city_insert->name = $city_name;
                $city_insert->state_id = $state_id;
                $city_insert->popular = 0;
                $city_insert->status = 1;
                $city_insert->save();
                $cityid = $city_insert->id;
            } else {
                $cityid = $city_id->first()->id;
            }
            return response()->json(['success' => 'true', 'message' => '', 'city_id' => $cityid], 200);
        }
    }

    public function save_click(Request $request)
    {
        $userID = $request->userID;
        $menuID = $request->menuID;
        $id = $request->id;
        $language = $request->language;
        $err_array = array();
        if ($userID == null) {
            $err_array[] = 'userID';
        }
        if ($menuID == null) {
            $err_array[] = 'menuID';
        }
        if ($id == null) {
            $err_array[] = 'id';
        }
        if ($language == null) {
            $err_array[] = 'language';
        }
        if (count($err_array) > 0) {
            $er = implode(",", $err_array);
            return response()->json(['success' => 'false', 'message' => "400 Bad Request, missing " . $er], 400);
        } else {
            //1=Banner 2=Welcome image 3=Brand 4=News 5=car 6=Model 7=Agent 8=Deals 9=Agent Banner
            switch ($menuID) {
                case "1":
                    $table = \App\Models\Banner::class;
                    break;
                case "2":
                    $table = \App\Models\WelcomeImage::class;
                    break;
                case "3":
                    $table = \App\Models\Brand::class;
                    break;
                case "4":
                    $table = \App\Models\Magazine::class;
                    break;
                case "5":
                    $table = \App\Models\CarJatoSpecification::class;
                    break;
                case "6":
                    $table = \App\Models\CarModel::class;
                    break;
                case "7":
                    $table = \App\Models\Agent::class;
                    break;
                case "8":
                    $table = \App\Models\Deal::class;
                    break;
                case "9":
                    $table = \App\Models\AgentBanner::class;
                    break;
                default:
                    $table = null;
            }
            if ($table !== null) {
                $getdata = $table::where('id', $id);
                if ($getdata->count() != 0) {
                    $update = $table::find($id);
                    $update->log = $getdata->first()->log + 1;
                    $update->save();
                }
            }
            return response()->json(['success' => 'true', 'message' => 'Successfully saved.'], 200);
        }
    }

    public function getCarBrandDetails(Request $request)
    {
        $userID = $request->userID;
        $brand_id = $request->brand_id;
        $model = $request->model;
        $year = $request->year;
        $trim = $request->trim;
        $err_array = [];

        if (!$userID) {
            $err_array[] = 'userID';
        }
        if (!$brand_id) {
            $err_array[] = 'brand_id';
        }
        if (!$model) {
            $err_array[] = 'model';
        }
        if (!$year) {
            $err_array[] = 'year';
        }
        if (!$trim) {
            $err_array[] = 'trim';
        }

        if (!empty($err_array)) {
            $missing = implode(",", $err_array);
            return response()->json([
                'success' => 'false',
                'message' => "400 Bad Request, missing " . $missing
            ], 400);
        }

        $brand = Brand::find($brand_id);

        if (!$brand || empty($brand->excel)) {
            return response()->json([
                'success' => 'false',
                'message' => 'Data not found.'
            ]);
        }

        $filePath = public_path('brand/excel/' . $brand->excel);

        // $filePath = public_path('brand/excel/brand_excel_1749622807.csv');

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => 'false',
                'message' => 'Excel file does not exist in the specified path.'
            ]);
        }

        $filteredRows = [];

        if (($handle = fopen($filePath, "r")) !== false) {
            $sample = fgets($handle);
            rewind($handle);
            $delimiter = strpos($sample, ';') !== false ? ';' : ',';

            $rawHeaders = fgetcsv($handle, 0, $delimiter);
            $headers = array_map(function ($header) {
                $header = trim($header);
                $header = preg_replace('/\s+/', '_', $header); // spaces to _
                $header = str_replace(['/', '\\', ':', '?', '"', '<', '>', '|'], '', $header); // remove special chars
                return $header;
            }, $rawHeaders);

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (count($row) !== count($headers)) {
                    continue;
                }

                $row = array_map('trim', $row);
                $assocRow = array_combine($headers, $row);

                if (
                    isset($assocRow['Model']) &&
                    $assocRow['Model'] == $model &&
                    isset($assocRow['Model_Year']) &&
                    $assocRow['Model_Year'] == $year &&
                    isset($assocRow['Trim']) &&
                    $assocRow['Trim'] == $trim
                ) {
                    $make = $assocRow['Make'];
                    $existApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

                    if ($existApiTrimList->isEmpty()) {
                        $responseData = ExternalSystem::trim_list($year, $make, $model);
                        $trims = $responseData['data'] ?? [];

                        foreach ($trims as $apitrim) {
                            $saveTrim = new ApiTrimList;
                            $saveTrim->year = '';
                            $saveTrim->make = $make;
                            $saveTrim->model = $model;
                            $saveTrim->trim = $apitrim;
                            $saveTrim->save();
                        }
                    }

                    $ApiTrimList = ApiTrimList::where(['make' => $make, 'model' => $model])->get();

                    $matchedTrim = null;
                    foreach ($ApiTrimList as $singleapitrim) {
                        if (stripos($singleapitrim->trim, $trim) !== false) {
                            $matchedTrim = $singleapitrim->trim;
                            break;
                        }
                    }

                    $photo = [];

                    if (!$matchedTrim) {
                        $dbImages = CarImage::where([
                            'brand_id'=>$brand_id,
                            'make'=>$assocRow['Make'],
                            'model'=>$assocRow['Model'],
                            'trim'=>$assocRow['Trim'],
                            'year'=>$assocRow['Model_Year']
                            ])->whereIn('type',[1,2,3])->get();

                        foreach ($dbImages as $img) {
                            $photo[] = ['image' => url('public/images').'/'. $img->image];
                        }
                    }else{

                        $trimFolder = urlencode($matchedTrim);
                        $uploadPath = public_path("images/car/$make/$model/$trimFolder");

                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        $files = File::files($uploadPath);

                        if (count($files) == 0) {
                            $responseData = ExternalSystem::vehicle_image($make, $model, $matchedTrim, $year);
                            $allImages = [];

                            foreach (['exterior', 'interior', 'colors'] as $category) {
                                if (!empty($responseData['data']['images'][$category])) {
                                    foreach ($responseData['data']['images'][$category] as $imageUrl) {
                                        $allImages[] = $imageUrl;
                                        if (count($allImages) == 10) break 2;
                                    }
                                }
                            }

                            foreach ($allImages as $imageUrl) {
                                try {
                                    $contents = file_get_contents($imageUrl);
                                    $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                                    $filename = 'img_' . time() . '_' . Str::random(5) . '.' . $ext;
                                    file_put_contents($uploadPath . '/' . $filename, $contents);
                                } catch (\Exception $e) {
                                    \Log::error("Failed to download image: $imageUrl - " . $e->getMessage());
                                }
                            }
                        }

                        $files = File::files($uploadPath);

                        if (count($files) > 0) {
                            foreach ($files as $file) {
                                $relativePath = str_replace(public_path(), '', $file->getPathname());
                                $photo[] = ['image' => url('public' . $relativePath)];
                            }
                        } else {
                            
                            $dbImages = CarImage::where([
                                'brand_id'=>$brand_id,
                                'make'=>$assocRow['Make'],
                                'model'=>$assocRow['Model'],
                                'trim'=>$assocRow['Trim'],
                                'year'=>$assocRow['Model_Year']
                                ])->whereIn('type',[1,2,3])->get();

                            foreach ($dbImages as $img) {
                                $photo[] = ['image' => url('public/images').'/'. $img->image];
                            }
                        }
                    }

                    $carvideos = CarVideo::where([
                        'brand_id'=>$brand_id,
                        'make'=>$assocRow['Make'],
                        'model'=>$assocRow['Model'],
                        'trim'=>$assocRow['Trim'],
                        'year'=>$assocRow['Model_Year']
                        ])->get();

                    $video = array();
                    foreach ($carvideos as $carvideo) {
                        $video[] = [
                            'video' => url('public/videos').'/'.$carvideo->video,
                            'description' => $carvideo->description,
                        ];
                    }

                    // $car360 = CarImage::where([
                    //     'brand_id'=>$brand_id,
                    //     'make'=>$assocRow['Make'],
                    //     'model'=>$assocRow['Model'],
                    //     'trim'=>$assocRow['Trim'],
                    //     'year'=>$assocRow['Model_Year'],
                    //     'type'=>4
                    //     ])->first();

                    // $car360image = isset($car360) ? url('public/images').'/'.$car360->image : '';
                    $car360image = CarImage::where([
                        'brand_id'=>base64_decode($request->key),
                        'make'=>$request->make,
                        'model'=>$request->model,
                        'trim'=>$request->trim,
                        'year'=>$request->year,
                        'type'=>4
                        ])->orderBy('sequence', 'asc')->get();

                    $stockNumber = "stock_".$request->make.$request->model.$request->trim.$request->year;
        
                    $car360ViewData = ExternalSystem::car_360View_by_Spyne($car360image,$stockNumber);
                    
                    $url       = $car360ViewData->url ?? 'https://assets.spyne.ai/360?sku_id=35c31a0f812644cb8e4e7cc0522a6af1&showHotspot=true';
                    $thumbnail = $car360ViewData->thumbnail ?? 'https://spyne-media.s3.amazonaws.com/2025-07-23/9c8bbcff-5dca-41d6-b77f-8c5c4fec6c13.jpg';

                    $formatted = [
                        'make' => $assocRow['Make'],
                        'model' => $assocRow['Model'],
                        'trim' => $assocRow['Trim'],
                        'body_style' => $assocRow['Body_Style'],
                        'model_year' => $assocRow['Model_Year'],
                        'image' => count($photo) > 0 ? $photo[0]['image'] : url("public/img/image-preview.png"),
                        'performance' => [
                            [
                                'label1' => 'Engine Type',
                                'value1' => $assocRow['Engine_Type'] ?? '',
                                'label2' => 'Engine Displacement (cc)',
                                'value2' => $assocRow['Engine_Displacement_(cc)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Transmission Type',
                                'value1' => $assocRow['Transmission_Type'] ?? '',
                                'label2' => 'Cylinders',
                                'value2' => $assocRow['Cylinders'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Top Speed (km/h)',
                                'value1' => $assocRow['Top_Speed_(kmh)'] ?? '',
                                'label2' => 'Number of Gears',
                                'value2' => $assocRow['Number_of_Gears'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Horsepower (hp)',
                                'value1' => $assocRow['Horsepower_(hp)'] ?? '',
                                'label2' => 'Torque (Nm)',
                                'value2' => $assocRow['Torque_(Nm)'] ?? '',
                                'label3' => 'Towing Capacity (kg)',
                                'value3' => $assocRow['Towing_Capacity_(kg)'] ?? ''
                            ],
                            [
                                'label1' => 'Drivetrain',
                                'value1' => $assocRow['Drivetrain'] ?? '',
                                'label2' => '0-100 km/h (seconds)',
                                'value2' => $assocRow['0-100_kmh_(seconds)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fuel Type',
                                'value1' => $assocRow['Fuel_Type'] ?? '',
                                'label2' => 'Charging Time (hours)',
                                'value2' => $assocRow['Charging_Time_(hours)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'CO2 Emissions (g/km)',
                                'value1' => $assocRow['CO2_Emissions_(gkm)'] ?? '',
                                'label2' => 'Emission Standard',
                                'value2' => $assocRow['Emission_Standard'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Electric Range (km)',
                                'value1' => $assocRow['Electric_Range_(km)'] ?? '',
                                'label2' => 'Battery & Group Size',
                                'value2' => $assocRow['Battery_&_Group_Size'] ?? '',
                                'label3' => 'Battery Health Indicator',
                                'value3' => $assocRow['Battery_Health_Indicator'] ?? ''
                            ]
                        ],
                        'size_and_efficiency' => [
                            [
                                'label1' => 'Wheelbase (mm)',
                                'value1' => $assocRow['Wheelbase_(mm)'] ?? '',
                                'label2' => 'Height (mm)',
                                'value2' => $assocRow['Height_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Length (mm)',
                                'value1' => $assocRow['Length_(mm)'] ?? '',
                                'label2' => 'Width (mm)',
                                'value2' => $assocRow['Width_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fuel Tank Capacity (L)',
                                'value1' => $assocRow['Fuel_Tank_Capacity_(L)'] ?? '',
                                'label2' => 'Fuel Economy Avg (km/L)',
                                'value2' => $assocRow['Fuel_Economy_Combined_(kmL)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Gross Vehicle Weight (kg)',
                                'value1' => $assocRow['Gross_Vehicle_Weight_(kg)'] ?? '',
                                'label2' => 'Cargo Volume (L)',
                                'value2' => $assocRow['Cargo_Volume_(L)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Turning Circle (m)',
                                'value1' => $assocRow['Turning_Circle_(m)'] ?? '',
                                'label2' => 'Ground Clearance (mm)',
                                'value2' => $assocRow['Ground_Clearance_(mm)'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'safety' => [
                            [
                                'label1' => 'Safety Rating',
                                'value1' => $assocRow['Safety_Rating'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Automatic Emergency Braking',
                                'value1' => $assocRow['Automatic_Emergency_Braking'] ?? '',
                                'label2' => 'Number of Airbags',
                                'value2' => $assocRow['Number_of_Airbags'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Blind Spot Monitoring',
                                'value1' => $assocRow['Blind_Spot_Monitoring'] ?? '',
                                'label2' => 'Parking Assist / Rear Camera',
                                'value2' => $assocRow['Parking_Assist__Rear_Camera'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Adaptive Cruise Control',
                                'value1' => $assocRow['Adaptive_Cruise_Control'] ?? '',
                                'label2' => 'Lane Keeping Assist',
                                'value2' => $assocRow['Lane_Keeping_Assist'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Rain-Sensing Wipers',
                                'value1' => $assocRow['Rain-Sensing_Wipers'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'comfort_and_tech' => [
                            [
                                'label1' => 'Apple CarPlay / Android Auto',
                                'value1' => $assocRow['Apple_CarPlay__Android_Auto'] ?? '',
                                'label2' => 'Digital Instrument Cluster / Display Size',
                                'value2' => $assocRow['Digital_Instrument_Cluster__Display_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Dual-Zone / Tri-Zone Climate Control',
                                'value1' => $assocRow['Dual-Zone__Tri-Zone_Climate_Control'] ?? '',
                                'label2' => 'Information system Size',
                                'value2' => $assocRow['Information_system_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Number of USB Ports',
                                'value1' => $assocRow['Number_of_USB_Ports'] ?? '',
                                'label2' => 'Remote Start / Keyless Entry',
                                'value2' => $assocRow['Remote_Start__Keyless_Entry'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Number of Speakers',
                                'value1' => $assocRow['Number_of_Speakers'] ?? '',
                                'label2' => 'Power Tailgate',
                                'value2' => $assocRow['Power_Tailgate'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Sound System Brand & Specs',
                                'value1' => $assocRow['Sound_System_Brand_&_Specs'] ?? '',
                                'label2' => 'Wireless Charging',
                                'value2' => $assocRow['Wireless_Charging'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Seat Material',
                                'value1' => $assocRow['Seat_Material'] ?? '',
                                'label2' => 'Sunroof / Panoramic Roof',
                                'value2' => $assocRow['Sunroof__Panoramic_Roof'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Seating Capacity',
                                'value1' => $assocRow['Seating_Capacity'] ?? '',
                                'label2' => 'Interior Color Combinations',
                                'value2' => $assocRow['Interior_Color_Combinations'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Fold-Flat Rear Seats / Split Ratio',
                                'value1' => $assocRow['Fold-Flat_Rear_Seats__Split_Ratio'] ?? '',
                                'label2' => 'Heated/Ventilated Seats',
                                'value2' => $assocRow['HeatedVentilated_Seats'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Ambient Lighting',
                                'value1' => $assocRow['Ambient_Lighting'] ?? '',
                                'label2' => 'Fog Lights',
                                'value2' => $assocRow['Fog_Lights'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'design' => [
                            [
                                'label1' => 'Wheel Size (inches)',
                                'value1' => $assocRow['Wheel_Size_(inches)'] ?? '',
                                'label2' => 'Tire Size',
                                'value2' => $assocRow['Tire_Size'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Wheel Type',
                                'value1' => $assocRow['Wheel_Type'] ?? '',
                                'label2' => 'Taillights Type',
                                'value2' => $assocRow['Taillights_Type'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Headlights Type',
                                'value1' => $assocRow['Headlights_Type'] ?? '',
                                'label2' => 'Side Mirror Functions',
                                'value2' => $assocRow['Side_Mirror_Functions'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Available Packages',
                                'value1' => $assocRow['Available_Packages'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'ownership' => [
                            [
                                'label1' => 'Owner Ratings',
                                'value1' => $assocRow['Owner_Ratings'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Average Resale Value (5 years)',
                                'value1' => $assocRow['Average_Resale_Value_(5_years)'] ?? '',
                                'label2' => 'Parts Availability Index',
                                'value2' => $assocRow['Parts_Availability_Index'] ?? '',
                                'label3' => '',
                                'value3' => ''
                            ],
                            [
                                'label1' => 'Common Problems/Recalls',
                                'value1' => $assocRow['Common_ProblemsRecalls'] ?? '',
                                'label2' => '',
                                'value2' => '',
                                'label3' => '',
                                'value3' => ''
                            ]
                        ],
                        'photo' => $photo,
                        'video' => $video,
                        // '360' => $car360image,
                        '360' => $url,
                        '360thumbnail' => $thumbnail
                    ];

                    $filteredRows = $formatted;
                }

            }

            fclose($handle);
        }

        return response()->json([
            'success' => 'true',
            'message' => '',
            'data' => $filteredRows ?? json_decode('{}')
        ]);
    }

    public function getServiceIconList(Request $request)
    {
        // Validate input
        $request->validate([
            'userID'   => 'required',
            'language' => 'required|in:1,2', // assuming 1 = English, 2 = Arabic
        ]);

        // Fetch active service icons
        $serviceIcons = ServiceIcon::where('status', 1)->take(4)->get();

        // Ensure we have at least 4 icons
        if ($serviceIcons->count() < 4) {
            return response()->json([
                'success' => 'false',
                'message' => 'Insufficient service icons found.'
            ], 500);
        }

        // Prepare dynamic response
        $response = ['success' => 'true', 'message' => ''];

        foreach ($serviceIcons as $index => $icon) {
            $key = $index + 1;
            $response["name{$key}"] = $request->language == 1 ? $icon->name_en : $icon->name_ar;
            $response["icon{$key}"] = url('public/images/' . $icon->icon);
        }

        return response()->json($response);
    }


}
