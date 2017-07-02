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
    /*学员的状态*/
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



}