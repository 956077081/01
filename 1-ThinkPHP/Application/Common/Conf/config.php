<?php
return array(
	//'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'   =>  true,
     'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sw_',    // 数据库表前缀
    
    'MODULE_ALLOW_LIST' => array('Home','Admin'),
    'DEFAULT_MODULE'    => 'Home',
    
    'URL_ROUTER_ON'         =>  true,   // 是否开启URL路由
    'URL_ROUTE_RULES'       =>  array(
        'show'  =>  'goods/showlist',
    ), // 默认路由规则 针对模块
);