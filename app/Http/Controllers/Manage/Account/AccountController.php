<?php

namespace App\Http\Controllers\Manage\Account;

use App\Http\Controllers\Controller;
use App\Model\Manage\Account;
use APP\Util\ResponseEntity;
use App\Util\ResponseUtil;
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
     | 登录接口
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
            return ResponseUtil::waring($this->backMeg);
        }
       // Account::where('account')

    }

    /*
     |--------------------------------------------------------------------------
     | 添加管理员接口
     |--------------------------------------------------------------------------
     */
    public function create(Request $request)
    {
        $res = $this->filter($request,[
            'account'=>'required|unique:user|filled',
            'password'=>'required|between:6,20|filled',
            'name'=>'required|filled',
        ]);
        if(!$res)
        {
            return ResponseEntity::waring($this->backMeg);
        }
        try{

            $user = new Account();
            $user->account = $request->input('account');
            $user->password = md5($request->input('password')."#".$request->input('account'));
            $user->name = $request->input('name');

            return $user->save() ? ResponseEntity::result("OK") : ResponseEntity::error("创建失败");

        }catch (\Exception $exception){
            return ResponseEntity::error("服务器异常");
        }catch (\Error $error){
            return ResponseEntity::error("服务器异常");
        }
    }
}
