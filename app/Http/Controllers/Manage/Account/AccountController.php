<?php

namespace App\Http\Controllers\Auth;

use App\Account;
use App\Http\Controllers\Controller;
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
            return $this->stdResponse();
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
            'account'=>'required|unique:user|numeric|digits:12|filled',
            'password'=>'required|between:6,20|filled',
            'name'=>'required|filled',
        ]);
        if(!$res)
        {
            return $this->stdResponse();
        }
        //验证用户 token
        if(!$this->check_token($request->input('api_token')))
        {
            return $this->stdResponse(-3);
        }

        try{

            $user = new User();
            $user->schoolnum = $request->input('schoolnum');
            $user->password = md5($request->input('password')."#".$request->input('schoolnum'));
            $user->campus = $request->input('campus');
            $user->realname = $request->input('realname');
            $user->tel = $request->input('tel');

        }catch (\Exception $exception){
            return $this->stdResponse("-4");
        }catch (\Error $error){
            return $this->stdResponse("-12");
        }
        return $this->stdResponse("-6");
    }

}
