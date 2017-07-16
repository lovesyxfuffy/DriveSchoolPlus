<?php

namespace App\Util;
use Maatwebsite\Excel\Facades\Excel;


class ExcelUtil
{
    /*
     * 导出Excel文件
     * */
    //Excel文件导出功能 By Laravel学院
    public static function export($cellData,$filename){
        //对数据进行处理，转化成数组
        $cellDataArr = array();
        foreach ($cellData as $item){
            $cellDataArr[] = (array)$item;
        }
        Excel::create($filename,function($excel) use ($cellDataArr){
            $excel->sheet('score', function($sheet) use ($cellDataArr){
                $sheet->rows(array_values($cellDataArr));
            });
        })->export('xls');
    }
}
