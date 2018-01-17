<?php
/**
 * Created by PhpStorm.
 * User: ocean
 * Date: 18-1-5
 * Time: 下午8:10
 */

namespace app\common\controller;

class Index
{
    public function index()
    {
        return "common->Index->index";
    }

    public function _miss()
    {
        return "404页面没找到，This is not the web page you are looking for";
    }
}