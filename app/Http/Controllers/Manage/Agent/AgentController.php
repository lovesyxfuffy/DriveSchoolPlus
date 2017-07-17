<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/16
 * Time: 16:19
 */

namespace App\Http\Controllers\Manage\Agent;


use App\Http\Controllers\Controller;
use App\Util\AgentUtil;
use App\Util\DBUtil;
use App\Util\ResponseEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    /*
     * 创建一个代理
     * */
    public function createOneAgent(Request $request){
        $res = $this->filter($request,AgentUtil::$CheckRules);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $input = DBUtil::convert([$request->all()],false);

        try{
            if(!DBUtil::DBA('agent',$request->session()->get('accountId'),DBUtil::$AuthorityInsert)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }
            $agent = DB::table('agent')
                ->where('id_card',$input[0]['id_card'])
                ->where('name',$input[0]['name'])
                ->get();
            if(count($agent) > 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'代理已经存在');
            }
            $stuRes = DB::table('agent')->insert($input[0]);

            return $stuRes ? ResponseEntity::result('代理添加成功') : ResponseEntity::error(ResponseEntity::$statusForbidden,'请稍后重试');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
     * 获取代理信息
     * */
    public function getAgentInfo(Request $request){
        $filter = $this->filter($request,[
            'page'=>'required|filled|numeric',
            'rows'=>'required|filled|numeric'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        try{
            if(!DBUtil::DBA('agent',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }

            $agents = DB::table('agent')->orderBy('create_time','desc');

            if(!empty($request->input('name'))){
                $agents->where('name',$request->input('name'));
            }

            if(!empty($request->input('telephone'))){
                $agents->where('telephone',$request->input('telephone'));
            }

            if(!empty($request->input('idCard'))){
                $agents->where('id_card',$request->input('idCard'));
            }
            if(!empty($request->input('schedule'))){
                $agents->where('schedule',$request->input('schedule'));
            }

            $agents = $agents->paginate($request->input('rows'))->toArray();

            return ResponseEntity::result($agents);

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
     * 修改代理信息 （包括 状态）
     * */
    public function editAgentInfo(Request $request){
        $res = $this->filter($request,[
            'id' => 'filled|required'
        ]);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $input = DBUtil::convert([$request->except('id')],false);

        /*判断管理员的权限*/
        if(!DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthorityUpdate)){
            return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
        }

        try{
            $agent = DB::table('agent')->where('id',$request->input('id'))->get();
            if(count($agent) <= 0 ){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'代理不存在');
            }

            DB::table('agent')
                ->where('id',$request->input('id'))
                ->update($input[0]);
            return ResponseEntity::result('代理信息修改成功');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

}