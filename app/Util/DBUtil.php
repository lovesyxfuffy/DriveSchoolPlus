<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:45
 */

namespace App\Util;

use Illuminate\Support\Facades\DB;

class DBUtil
{
    /*
     * 对于数据库表权限的操作
     * */
    static $AuthoritySelect = 1;
    static $AuthorityInsert = 2;
    static $AuthorityUpdate = 4;
    static $AuthorityDelete = 8;

    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     */
    static function toHump($un_hump_words,$separator='_')
    {
        $un_hump_words = $separator. str_replace($separator, " ", strtolower($un_hump_words));
        return ltrim(str_replace(" ", "", ucwords($un_hump_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    static function unHump($hump_words,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $hump_words));
    }


    /*对表进行 权限的 验证*/
    static function DBA($table,$accountId,$Authority){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        if(count($level) > 0){
            return ($level[0]->level & $Authority) == $Authority;
        }
        return false;
    }

    /*
     * 获取管理员的对应 操作数据库的能力
     * */
    static function getLevel($table,$accountId){
        return DB::table('account')
            ->join('role_database','role_database.role_id','=','account.role_id')
            ->join('database','database.id','=','role_database.database_id')
            ->where('account.id',$accountId)
            ->where('database.database_name',$table)
            ->select('role_database.level')
            ->get();
    }
    /*
     *处理结果数据  遍历$result 驼峰转下划线  $operation = true 是下划线转驼峰   false是驼峰转成下划线
     * */
    static function convert($result,$operation){
        $resultArr = array();

        foreach ($result as $row){
            $rowArr = array();
            foreach ($row as $key=>$value){
                if($operation){
                    $rowArr[self::toHump($key)] = $value;
                }else{
                    $rowArr[self::unHump($key)] = $value;
                }
            }
//            $resultArr->prepend($row);
            array_push($resultArr,$rowArr);
        }
        return $resultArr;
    }

    static function convertToHump($result){
        $resultArr = collect();
        foreach ($result as $row){
            return $row;
            $resultArr->prepend($row);
//            array_push($resultArr,$row);
        }
        return $resultArr;
    }
}