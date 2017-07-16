<?php

/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/5
 * Time: 23:57
 */
namespace App\Http\Controllers\Manage\Trainer;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Manage\FileService\FileController;
use App\Util\DBUtil;
use App\Util\ResponseEntity;
use App\Util\TrainerUtil;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    /*
     * 添加教练
     * */
    public function createOneTrainer(Request $request){
        $res = $this->filter($request,TrainerUtil::$CheckRules);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $input = DBUtil::convert([$request->all()],false);

        if(!DBUtil::DBA('trainer',$request->session()->get('accountId'),DBUtil::$AuthorityInsert)){
            return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
        }
        try{
            $trainer = DB::table('trainer')
                ->where('id_card',$input[0]['id_card'])
                ->where('name',$input[0]['name'])
                ->get();
            if(count($trainer) > 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'教练已经存在');
            }
            $stuRes = DB::table('trainer')->insert($input[0]);

            return $stuRes ? ResponseEntity::result('教练添加成功') : ResponseEntity::error(ResponseEntity::$statusForbidden,'请稍后重试');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
    * 查看教练
    * */
    public function getTrainerInfo(Request $request){
        $filter = $this->filter($request,[
            'page'=>'required|filled|numeric',
            'rows'=>'required|filled|numeric'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        try{
            /*
             * 判断权限的
             * */
            if(!DBUtil::DBA('trainer',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }

            $trainers = DB::table('trainer')->orderBy('id','desc');

            if(!empty($request->input('name'))){
                $trainers->where('name',$request->input('name'));
            }

            if(!empty($request->input('telephone'))){
                $trainers->where('telephone',$request->input('telephone'));
            }

            if(!empty($request->input('idCard'))){
                $trainers->where('id_card',$request->input('idCard'));
            }

            $trainers = $trainers->paginate($request->input('rows'))->toArray();
            $trainers['data'] =  DBUtil::convert(TrainerUtil::dealAgeTeachYear($trainers['data']),true);

            return ResponseEntity::result($trainers);

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }

    }
    /*
    * 修改教练信息(包括状态)
    * */
    public function editTrainerInfo(Request $request){
        $res = $this->filter($request,[
            'id' => 'filled|required'
        ]);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $input = DBUtil::convert([$request->except('id')],false);

        /*判断管理员的权限*/
        if(!DBUtil::DBA('trainer',$request->session()->get('accountId'),DBUtil::$AuthorityUpdate)){
            return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
        }

        try{
            $student = DB::table('trainer')->where('id',$request->input('id'))->get();
            if(count($student) <= 0 ){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'教练不存在');
            }

            DB::table('trainer')
                ->where('id',$request->input('id'))
                ->update($input[0]);
            return ResponseEntity::result('教练信息修改成功');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }
    /*
   * 修改教练头像
   * */
    public function editTrainerImg(Request $request){
        $filter = $this->filter($request,[
            'headImg' => 'required|image|max:20000'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        return explode('/',FileController::upload('img','avatar',$request->file('headImg')))[1];

    }

    /*
     * 预约
     * */
    public function getPreview(){

    }

}