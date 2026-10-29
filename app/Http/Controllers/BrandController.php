<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as session;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AllBannerImage;
use App\Models\StaffLogEvent;
use App\Models\Viewed;
use App\Models\MyCar;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Redirect;
use Illuminate\Validation\ValidationException;
use App\Modules\ExternalSystem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function brands(){
        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['Category'] = Category::where('status','!=',2)->get();

        $brandpopcount = Brand::where('status','!=',2)->where('popular',1)->count();
        if($brandpopcount!=0){
            $data['data'] = Brand::where('status','!=',2)->orderby('popular','DESC')->orderby('position','ASC')->get();
        }else{
            $data['data'] = Brand::where('status','!=',2)->orderby('name','ASC')->get();
        }
        return view('admin.brands', $data);
    }

    public function brandsdetails(Request $request){
        $data['active'] = "brand";
        $data['title'] = "Brand Detail";
        $admin_id = session::get('id');
        $data['admin'] = Admin::find($admin_id);
        $id = base64_decode($request->key);
        $data['language'] = base64_decode($request->lang);
        $data['data'] = Brand::where('id',$id)->first();
        $data['brandbanner'] = AllBannerImage::where('bannertype',1)->where('bannerimageID',$id)->get();
        return view('admin.brandsdetails', $data);
    }

    public function add_allbrand($year){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://carapi.app/api/makes?sort=&make=&year='.$year,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        return $res->data;
    }

    public function yearlist(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://carapi.app/api/years',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function addBrand(Request $request){
        $data['active'] = "brand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['category'] = Category::where('status',1)->get();
        $id = base64_decode($request->key);
        $data['data'] = Brand::find($id);
        $data['brandbanner'] = AllBannerImage::where('bannertype',1)->where('bannerimageID',$id)->get();
        $data['country'] = Country::where('status',1)->orderby('name','ASC')->get();
        return view('admin.addBrand', $data);
    }

    public function insert_brand(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                // 'images' => 'required|array',
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=1/1',
                'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:2000|dimensions:ratio=16/9',
            ], [
                'image.dimensions' => 'Logo must have a 1:1 aspect ratio.',
                'image.mimes' => 'Only JPEG, JPG, PNG, or GIF images are allowed.',
                'image.max' => 'Logo size must not exceed 2MB.',
                'images.*.dimensions' => 'Each image must have a 16:9 aspect ratio.',
                'images.*.mimes' => 'Only JPEG, JPG, PNG, or GIF formats are allowed.',
                'images.*.max' => 'Each image must not exceed 2MB.',
                'images.*.image' => 'Each file must be a valid image.',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }else{
                //excel upload
                if ($request->hasFile('excel')) {
                    // $file = $request->file('excel');
                    $uploadPath = public_path('brand/excel/');

                    // Ensure directory exists
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // // Temporarily store original file
                    // $tempPath = $file->getPathName();
                    
                    // // Load the Excel file
                    // $spreadsheet = IOFactory::load($tempPath);

                    // // Save as CSV
                    // $filename = 'brand_excel_' . time() . '.csv';
                    // $csvPath = $uploadPath . $filename;

                    // $writer = IOFactory::createWriter($spreadsheet, 'Csv');
                    // $writer->setDelimiter(',');
                    // $writer->setEnclosure('"');
                    // $writer->setLineEnding("\r\n");
                    // $writer->setSheetIndex(0);
                    // $writer->save($csvPath);

                    $file = $request->file('excel');
                    $filename = 'brand_excel_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('brand/excel/'), $filename);

                    $excelName = $filename;
                } else {
                    $excelName = '';
                }
                if ($request->hasFile('checklist_excel')) {
                    // $file = $request->file('checklist_excel');
                    $uploadPath = public_path('brand/checklist_excel/');

                    // Ensure directory exists
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $file = $request->file('checklist_excel');
                    $filename = 'brand_checklist_excel_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('brand/checklist_excel/'), $filename);

                    $checklistExcelName = $filename;
                } else {
                    $checklistExcelName = '';
                }
                if($request->id==""){
                    $check_name = Brand::where(['name'=>$request->name, 'status'=>1])->count();
                    if($check_name==0){
                        //image upload
                        if($request->hasFile('image')){
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
                            $imageName = "";
                        }

                        //$imageName = time().'.'.$request->image->extension();
                        //$request->image->move(public_path('images'), $imageName);
                        //tag icon
                        if($request->hasFile('tag_icon')){
                            // $new_widtht = 100;
                            // $new_heightt = 100;
                            // $filet = $request->file('tag_icon');
                            // $fileNamet = $filet->getRealPath();
                            // $uploadPatht = public_path('images/');
                            // $fileExtt = $filet->getClientOriginalExtension();
                            // $imgnamet = "tagthump_";
                            // $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
                            $tag_icon = 't'.time().'.'.$request->tag_icon->extension();
                            $request->tag_icon->move(public_path('images'), $tag_icon);
                        }else{
                            $tag_icon='';
                        }
                        //sponser_icon
                        if($request->hasFile('sponser_icon')){
                            // $new_widths = 100;
                            // $new_heights = 100;
                            // $files = $request->file('sponser_icon');
                            // $fileNames = $files->getRealPath();
                            // $uploadPaths = public_path('images/');
                            // $fileExts = $files->getClientOriginalExtension();
                            // $imgnames = "sponthump_";
                            // $sponser_icon = ExternalSystem::saveresizeimage($new_widths,$new_heights,$fileNames,$uploadPaths,$fileExts,$imgnames);
                            $sponser_icon = 's'.time().'.'.$request->sponser_icon->extension();
                            $request->sponser_icon->move(public_path('images'), $sponser_icon);
                        }else{
                            $sponser_icon='';
                        }

                        $brand = new Brand;
                        $brand->name = $request->name;
                        $brand->name_ar = $request->namear;
                        $brand->categoryID = $request->category;
                        $brand->logo = $imageName;
                        $brand->excel = $excelName;
                        $brand->checklist_excel = $checklistExcelName;
                        $brand->description = $request->info;
                        $brand->description_ar = $request->infoar;
                        $brand->website = $request->website;
                        $brand->whatsapp = $request->whatsapp;
                        $brand->youtube = $request->youtube;
                        $brand->twitter = $request->twitter;
                        $brand->linkedin = $request->linkedin;
                        $brand->facebook = $request->facebook;
                        $brand->instagram = $request->instagram;
                        $brand->tag_icon = $tag_icon;
                        $brand->tag_name = $request->tag_name;
                        $brand->tag_name_ar = $request->tag_namear;
                        $brand->sponser_icon = $sponser_icon;
                        $brand->sponser_name = $request->sponser_name;
                        $brand->sponser_name_ar = $request->sponser_namear;
                        $brand->countryid = $request->country;
                        $brand->status = 1;
                        $brand->save();
                        //image upload
                        if(!empty($request->images)){
                            $i=1;
                            foreach($request->images as $imagevalue){
                                //upload images
                                // $new_widthss = 720;
                                // $new_heightss = 480;
                                // $filess = $imagevalue;
                                // $fileNamess = $filess->getRealPath();
                                // $uploadPathss = public_path('images/');
                                // $fileExtss = $filess->getClientOriginalExtension();
                                // $imgnamess = $i."sponthump_";
                                // $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                                $imageNames = $i.time().'.'.$imagevalue->extension();
                                $imagevalue->move(public_path('images'), $imageNames);
                                $AllBannerImage = new AllBannerImage;
                                $AllBannerImage->bannerimageID = $brand->id;
                                $AllBannerImage->image = $imageNames;
                                $AllBannerImage->bannertype = 1;
                                $AllBannerImage->save();
                                $i++;
                            }
                        }
                        if(session::get('usertype')==2){
                            $log = array(
                                'staff_id'=>session::get('id'),
                                'logDate'=>date('Y-m-d'),
                                'event_name'=>"Add Brand",
                                'event_category'=>11,
                                'event_id'=>$brand->id,
                            );
                            // print_r($log);
                            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                        }
                        echo 1;
                    }else{
                        echo 2;
                    }
                }else{
                    $check_name = Brand::where('name',$request->name)->where('id',$request->id);
                    if($check_name->count() >= 1 && $request->id != $check_name->first()->id){
                        echo 2;
                    }else{
                        //image upload
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
                            $imageName = "";
                        }

                        //$imageName = time().'.'.$request->image->extension();
                        //$request->image->move(public_path('images'), $imageName);
                        //tag icon
                        if($request->hasFile('tag_icon')){
                            // $new_widtht = 100;
                            // $new_heightt = 100;
                            // $filet = $request->file('tag_icon');
                            // $fileNamet = $filet->getRealPath();
                            // $uploadPatht = public_path('images/');
                            // $fileExtt = $filet->getClientOriginalExtension();
                            // $imgnamet = "tagthump_";
                            // $tag_icon = ExternalSystem::saveresizeimage($new_widtht,$new_heightt,$fileNamet,$uploadPatht,$fileExtt,$imgnamet);
                            $tag_icon = 't'.time().'.'.$request->tag_icon->extension();
                            $request->tag_icon->move(public_path('images'), $tag_icon);
                        }else{
                            $tag_icon='';
                        }
                        //sponser_icon
                        if($request->hasFile('sponser_icon')){
                            // $new_widths = 100;
                            // $new_heights = 100;
                            // $files = $request->file('sponser_icon');
                            // $fileNames = $files->getRealPath();
                            // $uploadPaths = public_path('images/');
                            // $fileExts = $files->getClientOriginalExtension();
                            // $imgnames = "sponthump_";
                            // $sponser_icon = ExternalSystem::saveresizeimage($new_widths,$new_heights,$fileNames,$uploadPaths,$fileExts,$imgnames);
                            $sponser_icon = 's'.time().'.'.$request->sponser_icon->extension();
                            $request->sponser_icon->move(public_path('images'), $sponser_icon);
                        }else{
                            $sponser_icon='';
                        }

                        $brand = new Brand;
                        $brand = Brand::find($request->id);
                        $brand->name = $request->name;
                        $brand->name_ar = $request->namear;
                        $brand->categoryID = $request->category;
                        $brand->description = $request->info;
                        $brand->description_ar = $request->infoar;
                        $brand->website = $request->website;
                        $brand->whatsapp = $request->whatsapp;
                        $brand->youtube = $request->youtube;
                        $brand->twitter = $request->twitter;
                        $brand->linkedin = $request->linkedin;
                        $brand->facebook = $request->facebook;
                        $brand->instagram = $request->instagram;
                        if($excelName!=''){
                            $brand->excel = $excelName;
                        }
                        if($checklistExcelName!=''){
                            $brand->checklist_excel = $checklistExcelName;
                        }
                        if($imageName!=''){
                            $brand->logo = $imageName;
                        }
                        if($tag_icon!=''){
                            $brand->tag_icon = $tag_icon;
                        }
                        if($sponser_icon!=''){
                            $brand->sponser_icon = $sponser_icon;
                        }
                        $brand->tag_name = $request->tag_name;
                        $brand->tag_name_ar = $request->tag_namear;
                        $brand->sponser_name = $request->sponser_name;
                        $brand->sponser_name_ar = $request->sponser_namear;
                        $brand->countryid = $request->country;
                        $brand->save();
                        //images
                        if(!empty($request->images)){
                            $i=1;
                            foreach($request->images as $imagevalue){
                            //upload images
                                // $new_widthss = 720;
                                // $new_heightss = 480;
                                // $filess = $imagevalue;
                                // $fileNamess = $filess->getRealPath();
                                // $uploadPathss = public_path('images/');
                                // $fileExtss = $filess->getClientOriginalExtension();
                                // $imgnamess = $i."sponthump_";
                                // $imageNames = ExternalSystem::saveresizeimage($new_widthss,$new_heightss,$fileNamess,$uploadPathss,$fileExtss,$imgnamess);
                                $imageNames = $i.time().'.'.$imagevalue->extension();
                                $imagevalue->move(public_path('images'), $imageNames);
                                $AllBannerImage = new AllBannerImage;
                                $AllBannerImage->bannerimageID = $request->id;
                                $AllBannerImage->image = $imageNames;
                                $AllBannerImage->bannertype = 1;
                                $AllBannerImage->save();
                                $i++;
                            }
                        }
                        if(session::get('usertype')==2){
                            $log = array(
                                'staff_id'=>session::get('id'),
                                'logDate'=>date('Y-m-d'),
                                'event_name'=>"Update Brand",
                                'event_category'=>11,
                                'event_id'=>$request->id,
                            );
                            // print_r($log);
                            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
                        }
                        echo 1;
                    }

                }
            }
        } catch (\Throwable $e) {
            \Log::error('Brand insert failed: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    public function delete_brand(Request $request){
        $id = $request->id;
        $category = Brand::find($id);
        $category->status = 2;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Brand",
                'event_category'=>11,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function change_brand_status(Request $request){
        $id = $request->id;
        $status = $request->status;
        $category = Brand::find($id);
        $category->status = $status;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Change Brand Status",
                'event_category'=>11,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }
    public function add_popular_brand(Request $request){
        $checkpop = Brand::where('popular',1)->count();
        if($checkpop==0){
            $position = 1;
        }else{
            $pop = Brand::where('popular',1)->limit(1)->orderBy('position','DESC')->first();
            $position = $pop->position+1;
        }
        $id = $request->id;
        $status = $request->status;
        $category = Brand::find($id);
        $category->popular = $status;
        $category->position = $position;
        $category->save();
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Add Brand in Popular List",
                'event_category'=>11,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function popularBrands(Request $request){
        $data['active'] = "pbrand";
        $admin_id = session::get('id');
        $data['admin']=Admin::find($admin_id);
        $data['Category'] = Category::where('status','!=',2)->get();
        $data['data'] = Brand::where('status','!=',2)->where('popular',1)->orderBy('position','ASC')->get();
        return view('admin.popularBrands', $data);
    }

    public function updateBrandOrder(Request $request){
        $arr=$request->allData;
        $num=0;
        for($i=0;$i<count($arr);$i++)
        {
            $brand = Brand::find($arr[$i]);
            $brand->position = $num=$num+1;
            $brand->save();
        }
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Update Brand Order",
                'event_category'=>11,
                'event_id'=>0,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }
    }

    public function deleteimage(Request $request){
        AllBannerImage::where('id',$request->id)->delete();
        echo 1;
        if(session::get('usertype')==2){
            $log = array(
                'staff_id'=>session::get('id'),
                'logDate'=>date('Y-m-d'),
                'event_name'=>"Delete Brand Banner Image",
                'event_category'=>11,
                'event_id'=>$request->id,
            );
            // print_r($log);
            $StaffLogEvent = StaffLogEvent::createStaffLogEvent($log);
        }

    }

    public function filter_Categorydata(Request $request){
        $data = Brand::where('status','!=',2)->where('categoryID',$request->id)->orderby('popular','DESC')->orderby('position','ASC')->get();
        $i=1;
        foreach($data as $value){
            if($value->logo!=''){ $img= url("public/images/".$value->logo); }else{ $img=url("public/img/image-preview.png"); }
            if($value->status==1){ $statuschecked= "checked"; }else{ $statuschecked=""; }
            if($value->popular==1){ $popular= "checked"; }else{ $popular=""; }
            if($value->categoryID==1){ $addmod = '<a href="'.url('admin/carmodel?key='.base64_encode($value->id)).'" class="btn btn-warning">ADD MODEL</a>'; }else{ $addmod = ""; }

            echo '<tr id="'.$value->id.'">
                    <td><i class="fa fa-arrow-up"></i><br>Move<br><i class="fa fa-arrow-down"></i></td>
                    <td>'.$i.'</td>
                    <td>'.Category::where('id',$value->categoryID)->value('name').'</td>
                    <td>
                        <img class="img-fluid" src="'.$img.'" alt width="100" height="100">
                    </td>
                    <td>'.$value->name.'</td>
                    <td>'.$value->name_ar.'</td>
                    <td>'.Viewed::where('menu_type',1)->where('menuID',$value->id)->count().'</td>
                    <td>'.MyCar::where('make_id',$value->id)->distinct()->count('userID').'</td>
                    <td>'.$value->log.'</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input flexSwitchCheckChecked" type="checkbox" role="switch" id="flexSwitchCheckChecked" data-id="'.$value->id.'" '.$statuschecked.'>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input flexSwitchCheckCheckedpopular" type="checkbox" role="switch" id="flexSwitchCheckCheckedpopular" data-id="'.$value->id.'" '.$popular.'>
                        </div>
                    </td>
                    <td>'.$addmod.'</td>
                    <td><a href="'.url('admin/addBrand?key='.base64_encode($value->id)).'"><i class="fa fa-edit"></i></a> | <a class="delete" data-id="'.$value->id.'"><i class="fa fa-trash"></i></a></td>

                </tr>';
                $i++;
        }
    }

}
