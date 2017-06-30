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
    function hump($un_hump_words,$separator='_')
    {
        $un_hump_words = $separator. str_replace($separator, " ", strtolower($un_hump_words));
        return ltrim(str_replace(" ", "", ucwords($un_hump_words)), $separator );
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    function unHump($hump_words,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $hump_words));
    }

}