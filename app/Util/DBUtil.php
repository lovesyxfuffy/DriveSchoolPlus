<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:45
 */

namespace App\Util;

class DBUtil
{
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


    /*对表进行 INSERT 验证*/
    static function insert($table,$accountId){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        return decbin($level[0]->level) & decbin(self::$AuthorityInsert) ? true : false;
    }
    /*对表进行 DELETE 验证*/
    static function delete($table,$accountId){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        return decbin($level[0]->level) & decbin(self::$AuthorityInsert) ? true : false;
    }
    /*对表进行 UPDATE 验证*/
    static function update($table,$accountId){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        return decbin($level[0]->level) & decbin(self::$AuthorityInsert) ? true : false;
    }
    /*对表进行 SELECT 验证*/
    static function select($table,$accountId){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        return decbin($level[0]->level) & decbin(self::$AuthorityInsert) ? true : false;
    }

    /*对表进行 权限的 验证*/
    static function DBA($table,$accountId){
        $level = self::getLevel($table,$accountId);

        /*判断用户是否有插入权限*/
        return decbin($level[0]->level) & decbin(self::$AuthorityInsert) ? true : false;
    }

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
     *处理结果数据   $operation = true 是下划线转驼峰   false是驼峰转成下划线
     * */
    static function convert($result,$operation){
        //遍历$result 下划线转驼峰
        $resultArr = array();

        foreach ($result as $row){
            foreach ($row as $key=>$value){
                if($operation){
                    $row[self::toHump($key)] = $value;
                }else{
                    $row[self::unHump($key)] = $value;
                }
                if (self::unHump($key) != $key || self::toHump($key) != $key){
                    unset($row[$key]);
                }
            }
            array_push($resultArr,$row);
        }
        return $resultArr;
    }
}