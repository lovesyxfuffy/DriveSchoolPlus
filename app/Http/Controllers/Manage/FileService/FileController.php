<?php

/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/7/10
 * Time: 18:41
 */
namespace App\Http\Controllers\Manage\FileService;



use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /*
     * 上传文件的服务接口
     * */
    public static function upload($disk,$filename,$fileContent){

        return Storage::disk($disk)->put($filename,$fileContent);
    }
}