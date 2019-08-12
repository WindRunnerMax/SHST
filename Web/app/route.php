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
//use think/Route;
// Route::get('h5','index/Index/index');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    "classroom/[:idleTime]" => "index/sw/classroom",
    "funct/sw/table/[:zc]" => "funct/sw/table",
    "funct/sw/signalTable/[:zc]" => "funct/sw/signalTable",
    "funct/sw/signalTable2/[:zc]" => "funct/sw/signalTable2",
    "funct/sw/grade/[:sy]" => "funct/sw/grade",
    "index/asw/table/[:zc]" => "index/asw/table",
    "index/asw/grade/[:sy]" => "index/asw/grade",
    "bookdetail/:id" => "index/sw/bookdetail",
];

// use think/Route;
// Route::rule('h5',/public/Index/index);
