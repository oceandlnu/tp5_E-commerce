<?php
/**
 * Created by PhpStorm.
 * User: ocean
 * Date: 18-1-6
 * Time: 上午9:35
 */

namespace app\index\controller;

use think\Request;

class Error
{
    public function index(Request $request)
    {
        $cityName = $request->controller();
        return $this->city($cityName);
    }

    protected function city($name)
    {
        return '当前城市' . $name;
    }

    public function _empty()
    {
        return '没找到方法';
    }
}