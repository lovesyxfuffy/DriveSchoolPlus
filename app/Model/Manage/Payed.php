<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/30
 * Time: 15:35
 */

namespace App\Model\Manage;


use Illuminate\Database\Eloquent\Model;

class Payed extends Model
{
    protected $table = "payed";

    protected $primaryKey = "id";

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','order_id', 'amount','payed_amount','way', 'create_time','school_id','status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];




}