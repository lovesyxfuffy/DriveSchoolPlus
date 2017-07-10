<?php

namespace App\Http\Controllers;

use app\Util\DBUtil;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $filterFail = false;
    public $backMeg;

    //验证 表单
    public function filter(Request $request,$arr)
    {
        $validator =  Validator::make($request->all(),$arr);

        if($validator->fails())
        {
            $this->backMeg = implode($validator->errors()->all(),',');
            $this->filterFail = true;
            return false;
        }
        return true;
        $user = DBUtil::convert(DBUtil::select()->where());
    }

}
