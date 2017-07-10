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
use App\Util\PayedUtil;
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
    | 获取订单当天情况 页面上面的统计
    |--------------------------------------------------------------------------
    */
    public function getOrderStatistics(Request $request){
        $filter = $this->filter($request,[
            'createTime'=>'required|filled|date_format:Y-m-d'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        if(DBUtil::DBA('order',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
            try{
                $result = array();

                /*获取所有的number*/
                $result['OrderAllNumber'] =  $this->getOrder($request->input('createTime'))->count();
                /*获取已经取消的订单*/
                $result['OrderCanceledNumber'] =  $this->getOrder($request->input('createTime'))->where('status','=',OrderUtil::$OrderStatusCanceled)->count();
                /*获取等待确认的订单 就是已经支付完成的*/
                $result['OrderToConfirmedNumber'] = $this->getOrder($request->input('createTime'))->where('status','=',OrderUtil::$OrderStatusSucceed)->count();
                /*获取已经确认的订单*/
                $result['OrderConfirmedNumber'] = $this->getOrder($request->input('createTime'))->where('status','=',OrderUtil::$OrderStatusConfirmed)->count();

                /*获取所有订单的金额  已经支付完成的 和 已经确认的*/
                $result['OrderAllMoney'] = $this->getOrder($request->input('createTime'))
                    ->where(function ($query){
                        $query->where('status','=',OrderUtil::$OrderStatusSucceed)
                            ->orWhere('status','=',OrderUtil::$OrderStatusConfirmed);
                    })
                    ->sum('stu_cost');
                /*获取线上支付的订单的金额*/
                $result['OrderOnlineMoney'] = $this->getOrder($request->input('createTime'))
                    ->join('payed','order.id','=','order_id')
                    ->where('way','=',PayedUtil::$PayedWayOnline)
                    ->where(function ($query){
                        $query->where('order.status','=',OrderUtil::$OrderStatusSucceed)
                            ->orWhere('order.status','=',OrderUtil::$OrderStatusConfirmed);
                    })
                    ->sum('stu_cost');
                /*获取线下支付的订单的金额*/
                $result['OrderOfflineMoney'] = $this->getOrder($request->input('createTime'))
                    ->join('payed','order.id','=','order_id')
                    ->where('way','=',PayedUtil::$PayedWayOffline)
                    ->where(function ($query){
                        $query->where('order.status','=',OrderUtil::$OrderStatusSucceed)
                            ->orWhere('order.status','=',OrderUtil::$OrderStatusConfirmed);
                    })
                    ->sum('stu_cost');

                return ResponseEntity::result($result);

            }catch (\Exception $exception){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
            }catch (\Error $error){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
            }
        }
        return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");

    }
    public function getOrder($time){
        return DB::table('order')->where('order.create_time','>=',$time);
    }

    /*
    |--------------------------------------------------------------------------
    | 修改订单
    |--------------------------------------------------------------------------
    */
    public function editOrder(){

    }
    /*
     |--------------------------------------------------------------------------
     | 确认订单
     |--------------------------------------------------------------------------
    */
    public function changeOrderStatus(Request $request)
    {
        $filter = $this->filter($request,[
            'orderStatus'=>'required|filled',
            'orderId'=>'required|filled',
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        if(DBUtil::DBA('order',$request->session()->get('accountId'),DBUtil::$AuthorityUpdate)){
            try{
                /*获取订单的Id的数组  1,2,3,4,5*/
                $orderIdArr = explode(',',$request->input('orderId'));
                $result = DB::table('order')
                    ->whereIn('id',$orderIdArr);

                /*判断订单是否存在*/
                $order = $result->get();
                if(count($order) == 0){
                    return ResponseEntity::error(ResponseEntity::$statusNotFound,"订单不存在");
                }

                DB::beginTransaction();
                /*更新订单的状态*/
                $updateRes = $result->update(['status'=>$request->input('orderStatus')]);

                if(!$updateRes){
                    DB::rollback();
                    return ResponseEntity::error(ResponseEntity::$statusForbidden,"操作已完成，请不要重复操作");
                }
                /*记录更新的管理员和更新的订单*/
                $logArr = array();
                foreach ($orderIdArr as $row){
                    $rowArr['account_id'] = $request->session()->get('accountId');
                    $rowArr['order_id'] = $row;
                    $rowArr['content'] = "订单修改为".$this->orderStatus($request->input('orderStatus'));
                    array_push($logArr,$rowArr);
                }

                $logRes = DB::table('account_order_log')
                    ->insert($logArr);
                if(!$logRes){
                    DB::rollback();
                    return ResponseEntity::error(ResponseEntity::$statusForbidden,"服务器忙请稍后重试");
                }

                /*确认操作 要创建学员*/
                if($request->input('orderStatus') == OrderUtil::$OrderStatusConfirmed){
                    $studentArr = array();
                    foreach ($order as $row){
                        $stuRowArr['name'] = $row->stu_name;
                        $stuRowArr['telephone'] = $row->stu_telephone;
                        $stuRowArr['id_card'] = $row->stu_id_card;
                        $stuRowArr['qq'] = $row->stu_qq;
                        $stuRowArr['permit'] = $row->stu_permit;
                        array_push($studentArr,$stuRowArr);

                        $students = DB::table('student')->where('id_card',$stuRowArr['id_card'])->get();
                        /*检查学员是否已经存在*/
                        if(count($students) > 0){
                            return ResponseEntity::error(ResponseEntity::$statusForbidden,$stuRowArr['name']."的订单已经确认,请不要检查之后再确认。");
                        }
                    }

                    $stuRes = DB::table('student')->insert($studentArr);

                    if(!$stuRes){
                        DB::rollback();
                        return ResponseEntity::error(ResponseEntity::$statusForbidden,"服务器忙请稍后重试");
                    }
                }
                DB::commit();
                return ResponseEntity::result("操作成功(".$this->orderStatus($request->input('orderStatus')).")");

            }catch (\Exception $exception){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
            }catch (\Error $error){
                return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
            }
        }
        return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
    }

    public function orderStatus($status){
        switch ($status){
            case 1:
                return "待支付";
            case 2:
                return "已取消";
            case 3:
                return "支付成功";
            case 4:
                return "已经确认";
        }
    }

}