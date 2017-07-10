<?php

/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/2
 * Time: 17:02
 */
namespace App\Http\Controllers\Manage\Student;


use App\Http\Controllers\Controller;
use App\Util\DBUtil;
use App\Util\ResponseEntity;
use App\Util\StudentUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /*
     * 我们声明了一个可以添加学生的方法
     * */
    public function newStudent($students){
        return DB::table('student')->insert($students);
    }

    /*
   * 记录管理员与学员的操作记录
   * */
    public function addAccountStudentLog($log){

    }

    /**
     * 根据输入创建一个学生
     * @param $request
     * @return array
     */
    public function createOneStudent(Request $request){


        $res = $this->filter($request,StudentUtil::$CheckRules);
        if(!$res)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        $input = DBUtil::convert([$request->all()],false);

        if(DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthorityInsert)){

            $student = DB::table('student')
                ->where('id_card',$input[0]['id_card'])
                ->where('name',$input[0]['name'])
                ->get();
            if(count($student) > 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'学员已经存在');
            }
            DB::beginTransaction();
            $stuRes = DB::table('student')->insertGetId($input[0]);

            if($stuRes <= 0){
                DB::rollBack();
                return ResponseEntity::error(ResponseEntity::$statusForbidden,'学员添加失败');
            }

            /*
             * 插入 操作记录
             * */
            $account_stu_log = ['account_id'=>$request->session()->get('accountId'),'student_id'=>$stuRes,
                'from_schedule_id'=>1,'to_schedule_id'=>1];
            $logRes = DB::table('account_student_log')
                ->insert($account_stu_log);

            if($logRes === true){
                DB::commit();
                return ResponseEntity::result('学员添加成功');
            }else{
                DB::rollBack();
                return ResponseEntity::error(ResponseEntity::$statusForbidden,'学员添加失败');
            }

        }
        return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
    }
    /**
     * 根据读取的excel(批量导入学生)
     * @param $request
     * @return array
     */
    public function createBatchStudent(Request $request){


    }

    /*
     *获取学员的信息
     * */
    public function getStudent(Request $request){

    }
    /*
     * 修改学员信息
     * */
    public function editStudent(Request $request){

    }

    /*
     * 修改学员学车进度状态  可以批量修改
     * */
    public function editStudentSchedule(Request $request){

    }

    /*
     *导出Excel信息
     * */
    public function exportStudent(){

    }

    /*
    |--------------------------------------------------------------------------
    | 理论考试部分
    |--------------------------------------------------------------------------
    */

    public function getExamRules(Request $request){


    }

    public function setExamRules(Request $request){

    }

    public function getStudentPassed(){

    }

    public function sendMessage(){

    }

    public function setOpenExamStatus(){

    }

}