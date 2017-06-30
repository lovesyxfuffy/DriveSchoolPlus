<?php

namespace App\Http\Controllers\Manage\Account;

use App\Http\Controllers\Controller;
use App\Model\Manage\Account;
use App\Util\ResponseEntity;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Account Controller
    |--------------------------------------------------------------------------
    */

    /*
     |--------------------------------------------------------------------------
     | 管理员登录接口
     |--------------------------------------------------------------------------
     */
    public function login(Request $request)
    {

        $res = $this->filter($request,[
            'account'=>'required|filled',
            'password'=>'required|filled',
        ]);
        if(!$res)
        {
            return ResponseEntity::error(405,$this->backMeg);
        }

        try{
            $account = Account::where('account',$request->input('account'))
                ->where('password',md5($request->input('password')."#".$request->input('account')))
                ->get();
            
            if(count($account) > 0)
            {
                $request->session()->put('accountId',$account[0]['id']);
                return ResponseEntity::result("OK")->withCookie('account',$account[0]['id'],30)
                    ->withCookie('password',md5($request->input('password')."#".$request->input('account')),30);
            }

            return ResponseEntity::error(ResponseEntity::$statusAuthFail,"用户名或密码错误");

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }

    }


    /*
     |--------------------------------------------------------------------------
     | 添加管理员接口
     |--------------------------------------------------------------------------
     */
    public function create(Request $request)
    {
        $res = $this->filter($request,[
            'account'=>'required|unique:account|between:6,20|filled',
            'password'=>'required|between:6,20|filled',
            'name'=>'required|filled',
        ]);
        if(!$res)
        {
            return ResponseEntity::error(405,$this->backMeg);
        }

        try{
            $user = new Account();
            $user->account = $request->input('account');
            $user->password = md5($request->input('password')."#".$request->input('account'));
            $user->name = $request->input('name');

            return $user->save() ? ResponseEntity::result("OK") : ResponseEntity::error(ResponseEntity::$statusForbidden,"创建失败");

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Warning");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
     |--------------------------------------------------------------------------
     | 获取管理员个人信息的接口
     |--------------------------------------------------------------------------
     */
    public function getMyInfo(Request $request)
    {
        try{
            $user = Account::findOrFail($request->session()->get('accountId'));

            return $user ? ResponseEntity::result($user) : ResponseEntity::error(ResponseEntity::$statusNotFound,"信息获取失败");

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Warning");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }
}
