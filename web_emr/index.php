
<?php
// 检测PHP环境
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);
define("APP_NAME", "web_emr");  
// 定义应用目录
define('APP_PATH','./emr/');
define("THINK_PATH", "./ThinkPHP/");
// 引入ThinkPHP入口文件
require THINK_PATH . 'ThinkPHP.php';
