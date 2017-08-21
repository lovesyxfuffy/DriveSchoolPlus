<?php

/**
 * Created by PhpStorm.
 * User: yujingyang
 * Date: 2017/7/16
 * Time: 19:28
 */

namespace App\Http\Controllers\Manage\System;

use Illuminate\Support\Facades\DB;

class SystemController
{
    public function getMenu($viewId)
    {
        $menu = DB::table('menu')->get();
        $menuArray = array();
        $menuArray[0] = null;
        foreach ($menu as $row) {
            $menuArray[$row->id] = $row;
            $row->child = [];
        }
        $breadCrumb =  new \stdClass();
        foreach ($menu as $row) {
            if ($row->fatherMenuId != 0) {
                array_push($menuArray[$row->fatherMenuId]->child, $row);
            }
            if ($row->id == $viewId) {
                for ($i = $row; $i != null && $i->id > 0; $i = $menuArray[$i->fatherMenuId]) {
                    $current = $breadCrumb;
                    $breadCrumb = new \stdClass();
                    $breadCrumb->name = $i->name;
                    $breadCrumb->url = $i->menuUrl;
                    $breadCrumb->child = $current;
                }
            }
        }
        $menuResult = [];
        $result = new \stdClass();
        $i = 0;
        foreach ($menu as $row) {
            if ($row->fatherMenuId == 0) {
                $menuResult[$i++] = $row;
            }

        }
        $result->menuResult = $menuResult;
        $result->breadCrumb = $breadCrumb;
        return json_encode($result);
    }


    public function test()
    {
        return date("Y-m-d h:i:s");
    }
}