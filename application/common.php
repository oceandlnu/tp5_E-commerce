<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\admin\model\Album;

function alertMes($mes)
{
    echo "<script>alert('{$mes}')</script>";
}

//显示分页
function showPage($page, $totalPage, $url = null, $where = null, $sep = "&nbsp;")
{
    $whereCon = ($where == null) ? null : "&" . $where;
    $index = ($page == 1) ? "首页" : "<a href='{$url}?page=1{$whereCon}'>首页</a>";
    $last = ($page == $totalPage) ? "尾页" : "<a href='{$url}?page={$totalPage}{$whereCon}'>尾页</a>";
    $prev = ($page == 1) ? "上一页" : "<a href='{$url}?page=" . ($page - 1) . "{$whereCon}'>上一页</a>";
    $next = ($page == $totalPage) ? "下一页" : "<a href='{$url}?page=" . ($page + 1) . "{$whereCon}'>下一页</a>";
    $str = "总共{$totalPage}页/当前是第{$page}页";
    $p = "";
    for ($i = 1; $i <= $totalPage; $i++) {
        //当前页面无链接
        if ($page == $i) {
            $p .= "[{$i}]";
        } else {
            $p .= "<a href='{$url}?page={$i}{$where}'>[{$i}]</a>";
        }
    }
    $pageStr = $str . $sep . $index . $sep . $prev . $sep . $p . $sep . $next . $sep . $last;
    return $pageStr;
}

//根据pid获取图片
function getImgByProId($pid)
{
    $rows = Album::where('pid', $pid)->column('albumPath');
    return $rows;
}