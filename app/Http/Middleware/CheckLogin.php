<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Models\Admin;
use App\Models\AccessRight;
class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //get entered url        
        $path = $request->path();
        $adminUser = $request->session()->get('adminUser');
        $adminPassword = $request->session()->get('adminPassword');
        $gearzapp_admin_user_id = $request->session()->get('gearzapp_admin_user_id');
        $gearzapp_admin_user_type = $request->session()->get('gearzapp_admin_user_type');
        if($adminUser=="" AND  $adminPassword=="")
        {
            return redirect('/admin');
        }else if($gearzapp_admin_user_type==2){
            if($path != '/'){                
                $path = preg_replace('/\d/', '', $path);                
                    //echo $path; die;
                $res = $this->checkPermission($path,$gearzapp_admin_user_id);
                //die;
                if($res==1){
                    return $next($request); 
                }else{
                    return redirect('/admin/permissiondenied');
                    //echo "Permission denied"; die;
                }
                           
            }

        }else{
            return $next($request);
        }
        
    }
    function checkPermission($path,$gearzapp_admin_user_id){
        //return true; //by passing route check 
        $user_rights = Admin::where('id',$gearzapp_admin_user_id)->first()->access_right;
        $user_rights = explode(',',$user_rights);
        $rights      = AccessRight::select('id','route')->whereIn('id',$user_rights)->get()->toArray();
       //echo '<pre>'; print_r( $user_rights ); die;
        foreach ($rights as $key => $right) {
            //echo '<pre>';print_r($right['route']);
            // echo $path;
            // die;

            if(strpos($right['route'], $path) !== false) { 
                return 1;    
            }
        }
        return 2;
    }
}
