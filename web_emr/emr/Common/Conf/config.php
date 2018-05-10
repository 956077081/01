<?php
return array(
	
     
    
	/* web服务器配置 */
	'WEB_HOST'	=> "127.0.0.1",
	'DB_HOST'	=> 'localhost',
	/* 数据库配置 */
	'DB_PARAMS'             =>array('persist'=>true),
	'DB_TYPE'               => 'mysqli',
	'DB_NAME'               => 'web_emr',
	'DB_USER'               => 'root',
	'DB_PWD'                => '123456',
	'DB_PORT'               => 3306,
	'DB_PREFIX'             => '',
    'DB_CHARSET'            => 'utf8',
	//路由格式
        'URL_MODEL'         => '2',
	'SHOW_PAGE_TRACE'       => false,//日志文件
	'SESSION_OPTIONS'       => array('expire'=>86400), // session 配置数组 支持type name id path expire domian 等参数
       'DEFAULT_MODULE'        =>  'Home',  // 默认模块
        'MODULE_ALLOW_LIST'=>array('Home','MenzhenYishi','ZhuyuanYishi'),//设置默认的访问路径
       'DEFAULT_CONTROLLER'    =>  'System', // 默认控制器名称
       'DEFAULT_ACTION'        =>  'index', // 默认操作名称
	/* 电子病历相关配置项 */
	 'software_title'=>'在线电子病历系统',
	 'hospital_name'=>'XX县区域医疗电子病历',
	 'hospital_provience'=>'XX省',
	 'hospital_city'=>'XX市',
	 'hospital_sub_city'=>'XX县',
	 'hospital_location_code'=>'844900',

	/*是否开启痕迹记录功能
		on:开启
		off:关闭
	*/
	'revise_mode' => 'off',
	
	
	/* 安全项设置 */
	'url_debug_mode'=> true,

	/*是否开启病历的多媒体引擎
		on:开启
		off:关闭
	*/
	'multi_media_engine' => 'off',
	
	/*是否开启病历自动保存
		true:开启
		false:关闭
	*/
	'is_auto_save' => true,
	
	/*自动保存时间
	*/
	'auto_save_interval' => 40000,
	
	/*是否使用模板表头
		如果开启的话，保存模板的时候就会自动增加格式化表头，便于病历格式的统一
	*/
	'if_user_muban_biaotou' => 'true',
	/*
	*电子病历 模板的文档类型,以，隔开（默认：病案首页,入院记录,病程记录,出院记录,知情同意书,治疗处置记录,护理记录）可根据需要往后添加
	*/
	'muban_bingli_type' => "病案首页,入院记录,病程记录,出院记录,知情同意书,治疗处置记录,护理记录",
);