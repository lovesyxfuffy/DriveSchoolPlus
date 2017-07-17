<?php

/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/2
 * Time: 17:02
 */
namespace App\Http\Controllers\Manage\Student;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Manage\FileService\FileController;
use App\Util\DBUtil;
use App\Util\ExcelUtil;
use App\Util\ResponseEntity;
use App\Util\StudentUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /*
     * 我们声明了一个可以添加学生的方法
     * */
    public function newStudent($students){
        return DB::table('student')->insert($students);
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

        if(!DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthorityInsert)){
            return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
        }

        try{
            $student = DB::table('student')
                ->where('id_card',$input[0]['id_card'])
                ->where('name',$input[0]['name'])
                ->get();
            if(count($student) > 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'学员已经存在');
            }
            DB::beginTransaction();
            /*
             * 获取插入学员的id
             * */
            $stuRes = DB::table('student')->insertGetId($input[0]);

            if($stuRes <= 0){
                DB::rollBack();
                return ResponseEntity::error(ResponseEntity::$statusForbidden,'学员添加失败');
            }
            /*
             * 插入操作记录
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
        }catch (\Exception $exception){
            DB::rollBack();
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            DB::rollBack();
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }

    }
    /**
     * 根据读取的excel(批量导入学生)
     * @param $request
     * @return array
     */
    public function createBatchStudent(Request $request){
        $filter = $this->filter($request,[
            'schedule'=>'required|filled|numeric',
            'stuExcel' => 'required|mimes:xlsx,xls|max:20000'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }
        //获取 excel
        $excel = $request->file('stuExcel');
//        $filename = uniqid().'.'.$excel->getClientOriginalExtension();

         Excel::load($excel,function($reader){

             $reader = $reader->getSheet(0);
             $data = $reader->toArray();

             $key = ['name','sex','age','id_card','telephone','qq','permit'];
             foreach ($data as $row){
                 $row = array_combine($key,$row);

                 $row['schedule'] = $_REQUEST['schedule'];
                 array_push($this->excelInput,$row);
             }
         },'UTF-8');

        try{
            $student = DB::table('student')->whereIn('id_card',array_column($this->excelInput,'id_card'))->get();

            if(count($student) > 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,
                    '存在相同的学生:'.$student[0]->name.'身份:'.$student[0]->id_card);
            }

            $this->importSucceed = DB::table('student')->insert($this->excelInput);

            return $this->importSucceed ? ResponseEntity::result('导入成功') :
                ResponseEntity::error(ResponseEntity::$statusForbidden,'学员导入失败');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
     * 获取学员的信息
     * */
    public function getStudentInfo(Request $request){
        $filter = $this->filter($request,[
            'page'=>'required|filled|numeric',
            'rows'=>'required|filled|numeric'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        try{
            if(!DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }

            $students = DB::table('student')->orderBy('create_time','desc');

            if(!empty($request->input('name'))){
                $students->where('name',$request->input('name'));
            }

            if(!empty($request->input('telephone'))){
                $students->where('telephone',$request->input('telephone'));
            }

            if(!empty($request->input('idCard'))){
                $students->where('id_card',$request->input('idCard'));
            }
            if(!empty($request->input('schedule'))){
                $students->where('schedule',$request->input('schedule'));
            }


            $students = $students->paginate($request->input('rows'))->toArray();

            $students['data'] =  DBUtil::convert(StudentUtil::dealAge($students['data']),true);

            return ResponseEntity::result($students);

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }

    }
    /*
     * 修改学员信息 （包括 教练、状态、场地、班型等等）
     * */
    public function editStudentInfo(Request $request){

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
            $student = DB::table('student')->where('id',$request->input('id'))->get();
            if(count($student) <= 0 ){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'学员不存在');
            }

            DB::table('student')
                ->where('id',$request->input('id'))
                ->update($input[0]);
            return ResponseEntity::result('学员信息修改成功');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }

    /*
     * 上传头像
     * */
    public function editStudentImg(Request $request){
        $filter = $this->filter($request,[
            'headImg' => 'required|image|max:20000'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        return ResponseEntity::result(explode('/',FileController::upload('img','avatar',$request->file('headImg')))[1]);
    }

    /*
     * 获取头像
     * */
    public function getStudentImg(Request $request){
        return Storage::disk('img')->get('/avatar/'.$request->input('headImg'));
    }

    /*
     * 修改学员学车进度状态  可以批量修改
     * */
    public function editStudentSchedule(Request $request){
        $filter = $this->filter($request,[
            'stuId'    => 'required|filled',
            'schedule' => 'required|filled',
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }

        try{
            /*判断管理员的权限*/
            if(!DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthorityUpdate)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }

            /*获取传递的ID*/
            $stuId = explode(',',$request->input('stuId'));
            //将获取的id，转化成Integer类型
            $stuIdArr = array();
            foreach ($stuId as $item) {
                $stuIdArr[] = intval($item);
            }

            $student = DB::table('student')
                ->whereIn('id',$stuIdArr)
                ->get();

            if(count($student) <= 0){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'学员不存在');
            }

            $updateRes = DB::table('student')
                ->whereIn('id',$stuIdArr)
                ->update(['schedule'=>$request->input('schedule')]);

            return $updateRes ? ResponseEntity::result('学员状态修改成功') : ResponseEntity::error(ResponseEntity::$statusForbidden,'已经成功修改');

        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }


    }

    /*
     * 导出学员Excel信息
     * */
    public function exportStudentInfo(Request $request){
       /* $filter = $this->filter($request,[
            'headImg' => 'required|image|max:20000'
        ]);
        if(!$filter)
        {
            return ResponseEntity::error(ResponseEntity::$statusBadRequest,$this->backMeg);
        }*/

        try{
            if(empty($request->all())){
                return ResponseEntity::error(ResponseEntity::$statusBadRequest,'参数不够');
            }
            if(!DBUtil::DBA('student',$request->session()->get('accountId'),DBUtil::$AuthoritySelect)){
                return ResponseEntity::error(ResponseEntity::$statusMethodNotAllow,"权限不足");
            }

            $students = DB::table('student');

            if(!empty($request->input('schedule'))){
                $students->where('schedule',$request->input('schedule'));
            }

            if(!empty($request->input('id'))){
                $students->whereIn('id',explode(',',$request->input('id')));
            }
            /*
             * 根据时间导出
             * */
            if(!empty($request->input('startTime'))){
                $students->where('create_time','>=',$request->input('startTime'));
            }

            if(!empty($request->input('endTime'))){
                $students->where('create_time','<=',$request->input('endTime'));
            }
            $students = $students->select(['name','sex','age','id_card','telephone','permit','create_time'])->get();
            $StudentHead =
                ["姓名",'性别','年龄','身份证号','手机号','暂住证','入学日期']
            ;

            ExcelUtil::export(StudentUtil::dealAge($students)->prepend($StudentHead),date('Y-m-d H:i:s').' StudentRecords');
        }catch (\Exception $exception){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Waring");
        }catch (\Error $error){
            return ResponseEntity::error(ResponseEntity::$statusServerError,"服务器Error");
        }
    }
    /*
     * 将查询结果从对象转化成数组
     * */
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