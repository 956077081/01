/*
商品管理
    商品列表    添加商品    品牌管理
订单管理
    订单列表    订单查询    订单打印
权限管理
    管理员列表  角色管理    权限管理
*/

--用户表（管理员）
CREATE TABLE `sw_manager` (
  `mg_id` int(11) NOT NULL AUTO_INCREMENT,
  `mg_name` varchar(32) NOT NULL,
  `mg_pwd` varchar(32) NOT NULL,
  `mg_time` int(10) unsigned NOT NULL COMMENT '时间',
  `mg_role_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`mg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

--角色表
CREATE TABLE `sw_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `role_auth_ids` varchar(128) NOT NULL DEFAULT '' COMMENT '权限ids,1,2,5',
  `role_auth_ac` text COMMENT '控制器-操作方法 连接的字符串 Goods-add,Order-showlist',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

--权限表
CREATE TABLE `sw_auth` (
  `auth_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `auth_name` varchar(20) NOT NULL COMMENT '权限名称',
  `auth_pid` smallint(6) unsigned NOT NULL COMMENT '父id',
  `auth_c` varchar(32) NOT NULL DEFAULT '' COMMENT '模块',
  `auth_a` varchar(32) NOT NULL DEFAULT '' COMMENT '操作方法',
  `auth_path` varchar(32) NOT NULL COMMENT '全路径',
  `auth_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '权限级别',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8


delete from sw_auth;
insert into sw_auth values (1,'商品管理',0,'','',1,0);
insert into sw_auth values (2,'订单管理',0,'','',2,0);
insert into sw_auth values (3,'权限管理',0,'','',3,0);

insert into sw_auth values (4,'商品列表',1,'Goods','showlist','1-4',1);
insert into sw_auth values (5,'添加商品',1,'Goods','addgoods','1-5',1);
insert into sw_auth values (6,'品牌管理',1,'Goods','managerbrand','1-6',1);

insert into sw_auth values (7,'订单列表',2,'Order','showlist','2-7',1);
insert into sw_auth values (8,'订单查询',2,'Order','searchorder','2-8',1);
insert into sw_auth values (9,'订单打印',2,'Order','printorder','2-9',1);

insert into sw_auth values (10,'管理员列表',3,'Administrators','showlist','3-10',1);
insert into sw_auth values (11,'角色列表',3,'Role','showlist','3-11',1);
insert into sw_auth values (12,'权限列表',3,'Authority','showlist','3-12',1);

/*
角色数据
主管（商品管理1，商品列表4，添加商品5，订单管理2，订单列表7）
经理（订单管理2，订单列表7，订单查询8，订单打印9）
*/
delete from sw_role;
insert into sw_role values (1,'主管','1,4,5,2,7','Goods-showlist,Goods-addgoods,Order-showlist');
insert into sw_role values (2,'经理','2,7,8,9','Order-showlist,Order-searchorder,Order-printorder');


