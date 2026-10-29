<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Models\User;
class TokenCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header('Authorization')) {            
            return response()->json(['success'=>'false','message' => 'Token is required'], 401);
        }else{
            if(Auth::guard('api')->check()){
                $checktoken = User::find($request->userID);
                $split = explode(" ",$request->header('Authorization'));
                $token = end($split);
                if($checktoken->accessToken == $token){
                    //check expire                    
                    $checkexp = \DB::table('oauth_access_tokens')->where('user_id',$request->userID)->where('revoked',0)->offset(0)->limit(1)->orderBy('created_at','DESC');
                    if($checkexp->count()!=0){
                        if($checkexp->first()->expires_at >= date('Y-m-d H:i:s')){
                            return $next($request);
                        }else{
                            return response()->json(['success'=>'false','message'=>'Invalid token'], 200);
                        }
                    }else{
                        return response()->json(['success'=>'false','message'=>'Unauthorized'], 200);
                    }
                }else{
                    return response()->json(['success'=>'false','message'=>'Invalid token'], 200);
                }                
            }else{
                return response()->json(['success'=>'false','message'=>'Unauthorized'], 200);
            }
        }
       
    }

}
