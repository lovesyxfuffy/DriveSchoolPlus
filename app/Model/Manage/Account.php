<?php
/**
 * Created by PhpStorm.
 * User: zhaoshuai
 * Date: 2017/6/29
 * Time: 17:09
 */

namespace App\Model\Manage;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $table = "account";

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
        'id','account', 'password','role_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];


}
