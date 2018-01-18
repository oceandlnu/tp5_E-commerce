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
use app\admin\model\Cate;

Route::get('/', function () {
    $cates = Cate::all();
    return view('index@index/index', ['cates' => $cates]);
});

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    /*'__alias__' => [
        'index' => 'index/index',
        'admin' => 'admin/index',
    ],*/
//    '__miss__' => 'common/Index/_miss',
    '[hello]' => [
        ':id' => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
];
