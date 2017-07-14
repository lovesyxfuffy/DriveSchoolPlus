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
        Excel::create($filename,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows(array_values($cellData));
            });
        })->export('xls');
    }

}
