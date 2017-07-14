<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/1
 * Time: 22:23
 */

namespace App\Util;


class StudentUtil
{
    /*学员的学习*/
    static $statusNoPermit = ['level'=>'1','msg'=>'代办暂住证'];
    static $statusOwnPermit = ['level'=>'2','msg'=>'自带暂住证'];
    static $statusNoPE = ['level'=>'3','msg'=>'未体检'];
    static $statusNotifyPE = 4;
    static $statusAcceptPE = 5;
    static $statusRefusePE = 6;
    static $statusUnqualifiedPE = 7;
    //科目一
    static $statusSubjectOneReady = 8;
    static $statusSubjectOneNotify = 9;
    static $statusSubjectOneUnqualified = 10;
    //科目二
    static $statusSubjectTwoExercise = 11;
    static $statusSubjectTwoUnqualified = 12;
    //科目三
    static $statusSubjectThreeExercise = 13;
    static $statusSubjectThreeUnqualified = 14;
    //科目四
    static $statusSubjectFourReady = 15;
    static $statusSubjectFourNotify = 16;
    static $statusSubjectFourUnqualified = 17;
    //完成
    static $statusLicense = 18;
    static $statusFinish = 19;

    static $CheckRules = [
        'headImg'  =>'required|filled',
        'name'    =>'required|filled|max:30',
        'age'      =>'required|filled|numeric',
        'sex'      =>'required|filled|numeric',
        'idCard'   =>'required|filled|max:18',
        'telephone'=>'required|filled',
        'permit'   =>'required|filled',
        'qq'       =>'required|filled|numeric',
        'fieldId'  =>'required|filled|numeric',
    ];

    /*
     * 获取考试通过的条件
     *
     * @return Array
     * */
    static function getExamRules(){
        return explode(',',DB::table('school_settings')->find(1)->toArray());
    }

    /*
     * 处理学生年龄（因为学生年龄存储的是年限）
     * */
    static function dealAge($student){
        foreach ($student as $row){
            $row->age = date('Y') - $row->age;
        }
        return $student;
    }

}