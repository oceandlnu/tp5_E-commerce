<?php
/**
 * Created by PhpStorm.
 * User: ocean
 * Date: 18-3-6
 * Time: 下午9:55
 */

namespace app\admin\model;

use think\Model;
//use think\Session;
//use think\Cookie;


class Order extends Model
{
    protected $table = 'shop_order';

    /**
     * 初始化
     */
    protected static function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
}