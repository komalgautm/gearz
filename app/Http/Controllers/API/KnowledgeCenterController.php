<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\User;
use App\Models\Advice;
use App\Models\SponserBanner;
use App\Models\MyCar;
use App\Models\Country;
use App\Models\AdviceReview;
use App\Models\AdviceLike;
use App\Models\Translation;
use App\Models\KnowledgeImageSection;
use App\Models\AllBannerImage;
use App\Modules\ExternalSystem;


class KnowledgeCenterController extends Controller
{
    //Advice list
    public function advice(Request $request){
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
            //banner
            $currentDate = date('Y-m-d');
            $banner = SponserBanner::where('end_date','>=',$currentDate)->where('status',1)->get();
            $bannerArray = array();
            foreach($banner as $value){
                $databanner['id'] = $value->id;
                $databanner['image'] = $value->image;
                $databanner['url'] = $value->url;
                if($language==2){
                    $databanner['name'] = $value->name_ar;
                }else{
                    $databanner['name'] = $value->name;
                }
                if($value->start_date <= $currentDate){
                    array_push($bannerArray,$databanner);
                }
            }
            $data['banner'] = $bannerArray;
            //advice
            $adviceArray = array();
            //$advice = Advice::whereIn('UserID',[$userID,0])->where('status',1)->get();
            $advice = Advice::where('status',1)->get();
            foreach($advice as $advlaue){
                $ad['id'] = $advlaue->id;
                //$ad['image'] = $advlaue->image;
                $bannerimg = AllBannerImage::where('bannertype',3)->where('bannerimageID',$advlaue->id)->orderBy('id','ASC')->take(1);
                if($bannerimg->count()!=0){
                    $ad['image'] = $bannerimg->first()->image;
                }else{
                    $ad['image'] = "";
                }
                $ad['url'] = $advlaue->url;
                $ad['sponser_icon'] = $advlaue->sponser_icon;
                $ad['expire_date'] = $advlaue->expire_date;
                if($language==2){
                    $ad['title'] = $advlaue->title_ar;
                    $ad['message'] = $advlaue->message_ar;
                    $ad['sponser_name'] = $advlaue->sponser_name_ar;
                }else{
                    $ad['title'] = $advlaue->title;
                    $ad['message'] = $advlaue->message;
                    $ad['sponser_name'] = $advlaue->sponser_name;
                }
                //rating
                $avgStar = AdviceReview::where('adviceID',$advlaue->id)->avg('Rate');
                $ad['rating'] = "".$avgStar;
                $adviceReview = AdviceReview::where('adviceID',$advlaue->id);
                $ad['reviews'] = $adviceReview->count();
                //like
                $ad['totallike'] = AdviceLike::where('likedislike',1)->where('adviceID',$advlaue->id)->count();
                $ad['like'] = AdviceLike::where('likedislike',1)->where('userID',$userID)->where('adviceID',$advlaue->id)->count();
                array_push($adviceArray,$ad);
            }
            $data['advice'] = $adviceArray;
            return response()->json(['success'=>'true','message'=>'','result'=>$data], 200);
        }
    }

    //Advice details
    public function advice_details(Request $request){
        $userID = $request->userID;
        $adviceID = $request->adviceID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($adviceID==null){
            $err_array[]='adviceID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            //advice
            $adviceArray = array();
            $advlaue = Advice::where('id',$adviceID)->first();
            $ad['id'] = $advlaue->id;
            $ad['image'] = $advlaue->image;
            $ad['url'] = $advlaue->url;
            $ad['sponser_icon'] = $advlaue->sponser_icon;
            $ad['expire_date'] = $advlaue->expire_date;
            if($language==2){
                $ad['title'] = $advlaue->title_ar;
                $ad['message'] = $advlaue->message_ar;
                $ad['sponser_name'] = $advlaue->sponser_name_ar;
            }else{
                $ad['title'] = $advlaue->title;
                $ad['message'] = $advlaue->message;
                $ad['sponser_name'] = $advlaue->sponser_name;
            }
            //rating
            $avgStar = AdviceReview::where('adviceID',$advlaue->id)->avg('Rate');
            $ad['rating'] = "".$avgStar;
            $adviceReview = AdviceReview::where('adviceID',$advlaue->id);
            $ad['reviews'] = $adviceReview->count();
            //like
            $ad['totallike'] = AdviceLike::where('likedislike',1)->where('adviceID',$advlaue->id)->count();
            $ad['like'] = AdviceLike::where('likedislike',1)->where('userID',$userID)->where('adviceID',$advlaue->id)->count();
            //review
            $review_array = array();
            foreach($adviceReview->get() as $reviewvalue){
                $ar['id'] = $reviewvalue->id;
                $ar['userID'] = $reviewvalue->userID;
                $ar['username'] = User::find($reviewvalue->userID)->name;
                $ar['Rate'] = $reviewvalue->Rate;
                $ar['title'] = $reviewvalue->title;
                $ar['message'] = $reviewvalue->message;
                $ar['created_at'] = $reviewvalue->created_at;
                array_push($review_array,$ar);
            }
            $ad['review'] = $review_array;
            //banner
            $bannerArray = array();
            $brandbanner = AllBannerImage::where('bannertype',3)->where('bannerimageID',$adviceID)->get();
            foreach($brandbanner as $bannervalue){
                $data['id'] = $bannervalue->id;
                $data['position_id'] = "";
                $data['image'] = $bannervalue->image;
                $data['start_date'] = "";
                $data['end_date'] = "";
                $data['name'] = "";
                array_push($bannerArray,$data);
            }
            $ad['bannerimg'] = $bannerArray;

            return response()->json(['success'=>'true','message'=>'','result'=>$ad], 200);
        }
    }

    //Add Advice review
    public function addadvicereview(Request $request){
        $userID = $request->userID;
        $adviceID = $request->adviceID;
        $rate = $request->rate;
        $title = $request->title;
        $message = $request->message;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($adviceID==null){
            $err_array[]='adviceID';
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
                $msg_failed = "تم التقييم مسبقا ";
            }else{
                $msg_success = "Thank you for your rating.";
                $msg_failed = "A rating has already been given for this advice.";
            }
            $check = AdviceReview::where('userID',$userID)->where('adviceID',$adviceID)->count();
            if($check==0){
                $review = new AdviceReview;
                $review->adviceID = $adviceID;
                $review->userID = $userID;
                $review->Rate = $rate;
                $review->title = $title;
                $review->message = $message;
                $review->save();
                return response()->json(['success'=>'true','message'=>$msg_success], 200);

            }else{
                return response()->json(['success'=>'false','message'=>$msg_failed], 400);
            }

        }
    }

    //Advice like dislike
    public function advicelikedislike(Request $request){
        $userID = $request->userID;
        $adviceID = $request->adviceID;
        $status = $request->status;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($adviceID==null){
            $err_array[]='adviceID';
        }
        if($status==null){
            $err_array[]='status';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "تم الإرسال.";
            }else{
                $msg_success = "Submitted successfully";
            }
            $check = AdviceLike::where('userID',$userID)->where('adviceID',$adviceID)->count();
            if($check==0){
                $review = new AdviceLike;
                $review->userID = $userID;
                $review->adviceID = $adviceID;
                $review->likedislike = 1;
                $review->save();
                return response()->json(['success'=>'true','message'=>$msg_success], 200);
            }else{
                $likeid = AdviceLike::where('userID',$userID)->where('adviceID',$adviceID)->first();
                $review = AdviceLike::find($likeid->id);
                if($likeid->likedislike==1){
                    $review->likedislike = 0;
                }else{
                    $review->likedislike = 1;
                }

                $review->save();
                return response()->json(['success'=>'true','message'=>$msg_success], 200);
            }

        }
    }

    public function delete_advice(Request $request){
        $userID = $request->userID;
        $adviceID = $request->adviceID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($adviceID==null){
            $err_array[]='adviceID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language == 2){
                $msg_success = "تم الحذف ";
            }else{
                $msg_success = "Deleted successfully.";
            }
            $Advice = new Advice;
            $Advice = Advice::find($adviceID);
            $Advice->status = 2;
            $Advice->save();
            return response()->json(['success'=>'true','message'=>$msg_success], 200);

        }
    }

    //Sponser Banner
    public function sponser_banner(Request $request){
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
            //banner
            $currentDate = date('Y-m-d');
            $banner = SponserBanner::where('end_date','>=',$currentDate)->where('status',1)->get();
            $bannerArray = array();
            foreach($banner as $value){
                $databanner['id'] = $value->id;

                $databanner['image'] = $value->image;
                $databanner['url'] = $value->url;
                if($language == 2){
                    $databanner['name'] = $value->name_ar;
                }else{
                    $databanner['name'] = $value->name;
                }
                if($value->start_date <= $currentDate){
                    array_push($bannerArray,$databanner);
                }
            }
            $data['banner'] = $bannerArray;
            return response()->json(['success'=>'true','message'=>'','result'=>$data], 200);
        }
    }

    //Translation
    public function translation(Request $request){
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
            $translation = Translation::where('status',1)->get();
            $translationArray = array();
            foreach($translation as $value){
                $databanner['id'] = $value->id;
                $databanner['image'] = $value->image;
                if($language==2){
                    $databanner['title'] = $value->titlear;
                    $databanner['message'] = $value->messagear;
                    $databanner['searchkeyword'] = $value->searchkeyword_ar;
                }else{
                    $databanner['title'] = $value->title;
                    $databanner['message'] = $value->message;
                    $databanner['searchkeyword'] = $value->searchkeyword;
                }
                array_push($translationArray,$databanner);
            }
            $data['translation'] = $translationArray;
            return response()->json(['success'=>'true','message'=>'','result'=>$data], 200);
        }
    }

    //Translation details
    public function translationdetails(Request $request){
        $userID = $request->userID;
        $translationID = $request->translationID;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($translationID==null){
            $err_array[]='translationID';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            $value = Translation::where('id',$translationID)->first();
            $data['id'] = $value->id;
            $data['image'] = $value->image;
            if($language==2){
                $data['title'] = $value->titlear;
                $data['message'] = $value->messagear;
                $data['searchkeyword'] = $value->searchkeyword_ar;
            }else{
                $data['title'] = $value->title;
                $data['message'] = $value->message;
                $data['searchkeyword'] = $value->searchkeyword;
            }
            return response()->json(['success'=>'true','message'=>'','result'=>$data], 200);
        }
    }

    //Knowledge Image section
    public function knowledge_image_section(Request $request){
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
            $recordArray = array();
            $data = KnowledgeImageSection::all();
            foreach($data as $value){
                if($language==2){
                    $arr['name']=$value->name_ar;
                }else{
                    $arr['name']=$value->name;
                }
                $arr['image']=$value->image;
                array_push($recordArray,$arr);
            }

            return response()->json(['success'=>'true','message'=>'','result'=>$recordArray], 200);
        }
    }

    public function aichat(Request $request){
        $userID = $request->userID;
        $message = $request->message;
        $language = $request->language;
        $err_array =array();
        if($userID==null){
            $err_array[]='userID';
        }
        if($message==null){
            $err_array[]='message';
        }
        if(count($err_array)>0){
            $er = implode(",", $err_array);
            return response()->json(['success'=>'false','message'=>"400 Bad Request, missing ".$er], 400);
        }else{
            if($language==2){
                $msg_failed ="لاتوجد بيانات";
            }else{
                $msg_failed ="No data found";
            }
            $chat =  ExternalSystem::chat($message);
            if($chat['status_code']==200){
                $record = json_decode($chat['body']);
                return response()->json(['success'=>'true','message'=>'','result'=>$record['choices'][0]['message']['content']], 200);


            }else{
                return response()->json(['success'=>'false','message'=>$msg_failed], 200);
            }
            //return response()->json(['success'=>'true','message'=>'','result'=>"Hello world"], 200);

        }
    }


}
