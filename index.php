<?php
//入口文件
//定义项目名称和路径
// define('APP_NAME', 'myapp');
// define('APP_PATH', './application/');
// //开启调试模式
// define('APP_DEBUG',true);
// // 加载框架入口文件
// require("./public/index.html");
// header("Location: ./public/"); 
// 定义应用目录为apps
define('APP_PATH', __DIR__ . '/app/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
?>