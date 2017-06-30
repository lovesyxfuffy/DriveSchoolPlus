<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:51
 */

namespace App\Http\Controllers\Manage\Order;


use App\Http\Controllers\Controller;
use App\Util\DBUtil;
use App\Util\OrderUtil;
use App\Util\ResponseEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Order Controller
    |--------------------------------------------------------------------------
   */
    /*
     |--------------------------------------------------------------------------
     | 创建订单
     |--------------------------------------------------------------------------
     */
    public function createOrder(Request $request){
        /*
         * 验证order
         * */
        $res = $this->filter($request,OrderUtil::$CheckRules);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $res = DBUtil::convert([$request->all()],false);

        if(DBUtil::insert('order',$request->session()->get('accountId'))){

            try{
                $result = DB::table('order')
                    ->insert($res);
                return $result? ResponseEntity::result("提交订单成功") : ResponseEntity::error(ResponseEntity::$statusServerError,"提交订单失败，请重试。");
            }catch (\Exception $exception){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
            }catch (\Error $error){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
            }
        }
        return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
    }

    /*
    |--------------------------------------------------------------------------
    | 获取所有订单
    |--------------------------------------------------------------------------
    */
    public function getOrder(Request $request){
        $filter = $this->filter($request,[
            'page'=>'required|filled|numeric',
            'rows'=>'required|filled|numeric'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        if(DBUtil::select('order',$request->session()->get('accountId'))){

            try{
                $result = DB::table('order')
                    ->orderBy('create_time','desc')
                    ->paginate($request->input('rows'));

                return ResponseEntity::result($result);
            }catch (\Exception $exception){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
            }catch (\Error $error){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
            }
        }
        return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
    }
    /*
    |--------------------------------------------------------------------------
    | 获取订单当天情况
    |--------------------------------------------------------------------------
    */
    public function getOrderStatistics(Request $request){


    }
    /*
   |--------------------------------------------------------------------------
   | 修改订单
   |--------------------------------------------------------------------------
   */





}