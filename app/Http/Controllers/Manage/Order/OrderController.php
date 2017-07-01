<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:51
 */

namespace App\Http\Controllers\Manage\Order;


use App\Http\Controllers\Controller;
use App\Model\Manage\Payed;
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

        if(DBUtil::DBA('order',$request->session()->get('accountId'),DBUtil::$AuthorityInsert)){

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
    public function getOrderAll(Request $request){
        $filter = $this->filter($request,[
            'page'=>'required|filled|numeric',
            'rows'=>'required|filled|numeric'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        /*先进行判断*/
        if(DBUtil::DBA('order',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
            try{
                $result = DB::table('order')
                    ->leftJoin('payed','order.id','=','payed.order_id')
                    ->leftJoin('field','field.id','=','order.field_id')
                    ->leftJoin('class','class.id','=','order.class_id')
                    ->leftJoin('agent','agent.id','=','order.agent_id')
                    ->orderBy('order.create_time','desc');
                /*根据支付方式查询*/
                if($request->input('payedWay')){
                    $result->where('payed.way','=',$request->input('payedWay'));
                }
                /*根据学员姓名查询*/
                if($request->input('stuName')){
                    $result->where('stu_name','=',$request->input('stuName'));
                }
                /*根据学员手机号查询*/
                if($request->input('stuTelephone')){
                    $result->where('stu_telephone','=',$request->input('stuTelephone'));
                }
                /*根据学员身份证号查询*/
                if($request->input('stuIdCard')){
                    $result->where('stu_id_card','=',$request->input('stuIdCard'));
                }
                /*根据场地查询*/
                if($request->input('fieldId')){
                    $result->where('field_id','=',$request->input('fieldId'));
                }

                $result = $result->select('order.id as order_id','stu_name','stu_id_card','stu_telephone','stu_permit','stu_qq','field.name as field_name','class.name as class_name',
                    'type as order_type','stu_cost','agent.name as agent_name','order.reduction as order_reduction','order.create_time as order_create_time',
                    'order.status as order_status','all_amount','payed_amount','way as payed_way','payed.create_time as payed_create_time','payed.status as payed_status')
                    ->paginate($request->input('rows'))->toArray();

                $result['data'] = DBUtil::convert($result['data'],true);

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
        try{




        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }
    /*
   |--------------------------------------------------------------------------
   | 修改订单
   |--------------------------------------------------------------------------
   */





}