<?php
/**
 * Created by PhpStorm.
 * User: ocean
 * Date: 18-1-17
 * Time: 上午10:10
 */

namespace app\admin\model;

use think\Model;
use think\Session;
use think\Cookie;


class Album extends Model
{
    protected $table = 'shop_album';

    /**
     * 初始化
     */
    protected static function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
}