<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
//use think\Url;

Route::get('/',function (){
    return "首页";
});

//Route::rule('new/:p1/:p2','admin/Index/admin');
//Url::build('admin/Index/admin','p1=1&p2=2&name=thinkphp');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    /*'__alias__' => [
        'user' => 'index/Index',
        'admin' => 'admin/Index',
    ],*/
//    '__miss__' => 'common/Index/_miss',
    '[hello]' => [
        ':id' => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
//    'new/:p1/:p2' => ['admin/Index/admin', ['method' => 'get', 'ext' => 'html']],
];
