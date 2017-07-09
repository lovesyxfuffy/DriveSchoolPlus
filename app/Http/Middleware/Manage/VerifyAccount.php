<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 09:30
 */

namespace App\Http\Middleware\Manage;


use App\Util\ResponseEntity;
use Closure;

class VerifyAccount
{
    /*
     * 验证管理员登录状态的
     *
     * */
    /**
     * 返回请求过滤器
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request,Closure $next){

        if($request->session()->exists('accountId')){
            return $next($request);
        }elseif ($request->cookie('account') && $request->cookie('password')){
            $account = Account::where('account',$request->cookie('account'))
                ->where('password',$request->cookie('password'))
                ->get();
            if(count($account) > 0){
                $request->session()->put('accountId',$account[0]['id']);
                return $next($request);
            }
        }
        return ResponseEntity::error(ResponseEntity::$statusAuthFail,'登录状态失效，请重新登录');
    }
}