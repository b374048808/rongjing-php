<!--
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 16:12:30
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 15:08:11
 * @Description:
-->

## 更新历史

升级说明：

mysql、及其他代码变动

### v1.0.1

updated 2022.6.1

```
#房屋列表
CREATE TABLE `rf_rj_house_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '户主（单位信息）',
  `province_id` int(11) DEFAULT '0' COMMENT '省',
  `city_id` int(10) DEFAULT '0' COMMENT '城市',
  `area_id` int(10) DEFAULT '0' COMMENT '地区',
  `cover` varchar(100) DEFAULT '' COMMENT '封面',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号码',
  `address` varchar(100) DEFAULT '' COMMENT '默认地址',
  `year` int(4) DEFAULT NULL COMMENT '建筑年份',
  `area` decimal(10,2) DEFAULT NULL COMMENT '面积',
  `nature` tinyint(4) DEFAULT '0' COMMENT '房屋性质',
  `layer` tinyint(4) DEFAULT '0' COMMENT '结构层数',
  `news` char(10) DEFAULT NULL COMMENT '房屋朝向',
  `type` varchar(50) DEFAULT '0' COMMENT '结构类型',
  `roof` varchar(50) DEFAULT '0' COMMENT '屋面形式',
  `lng` double(10,6) DEFAULT NULL COMMENT '经度',
  `lat` double(10,6) DEFAULT NULL COMMENT '纬度',
  `layout_cover` json DEFAULT NULL COMMENT '建筑物图',
  `plan_cover` json DEFAULT NULL COMMENT '平面图',
  `floor` tinyint(4) NOT NULL DEFAULT '0' COMMENT '楼板形式',
  `wall` tinyint(4) NOT NULL DEFAULT '0' COMMENT '墙体形式',
  `basement` tinyint(4) NOT NULL DEFAULT '0' COMMENT '地下室',
  `beam` tinyint(4) NOT NULL DEFAULT '0' COMMENT '圈梁',
  `column` tinyint(4) NOT NULL DEFAULT '0' COMMENT '构造柱',
  `side` varchar(50) DEFAULT NULL COMMENT '周边环境',
  `property_nature` varchar(50) DEFAULT NULL COMMENT '产权性质',
  `room` int(10) DEFAULT '1' COMMENT '间   数',
  `base_form` varchar(50) DEFAULT NULL COMMENT '基础形式',
  `use_change` varchar(50) DEFAULT NULL COMMENT '用途变更',
  `disasters` varchar(50) DEFAULT NULL COMMENT '灾  害',
  `detect_scope` varchar(50) DEFAULT NULL COMMENT '鉴定范围',
  `property_card` varchar(50) DEFAULT NULL COMMENT '产权证号',
  `land_card` varchar(50) DEFAULT NULL COMMENT '地号',
  `history` varchar(140) DEFAULT NULL COMMENT '历史情况',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '录入人员id',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='房屋_列表';

#项目列表
CREATE TABLE `rf_rj_project_item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '项目名称',
  `type` tinyint(3) DEFAULT '1' COMMENT '项目类型',
  `entrust` varchar(140) DEFAULT NULL COMMENT '委托方',
  `belonger` varchar(10) DEFAULT '' COMMENT '归属人',
  `contact` varchar(10) DEFAULT '' COMMENT '联系人',
  `mobile` varchar(20) DEFAULT '' COMMENT '联系方式',
  `province_id` int(11) DEFAULT '0' COMMENT '省',
  `city_id` int(10) DEFAULT '0' COMMENT '城市',
  `area_id` int(10) DEFAULT '0' COMMENT '地区',
  `address` varchar(100) DEFAULT '' COMMENT '默认地址',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `demand` char(140) DEFAULT NULL COMMENT '项目需求',
  `survey` char(140) DEFAULT NULL COMMENT '项目概况',
  `file` json DEFAULT NULL COMMENT '附件',
  `user_id` int(10) DEFAULT NULL COMMENT '创建员工ID',
  `money` decimal(10,2) DEFAULT NULL COMMENT '金额',
  `number` char(50) DEFAULT NULL COMMENT '编号',
  `struct_type` int(10) DEFAULT NULL COMMENT '结构类型',
  `event_time` int(10) unsigned DEFAULT '0' COMMENT '立项时间',
  `images` json DEFAULT NULL COMMENT '附件照片',
  `steps_name` varchar(50) DEFAULT NULL COMMENT '步骤NAME',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `verify` tinyint(3) DEFAULT '0' COMMENT '审核状态',
  `collect_money` decimal(10,2) DEFAULT NULL COMMENT '收款金额',
  `collection` json DEFAULT NULL COMMENT '收款凭证',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测_项目';

#项目-合同
CREATE TABLE `rf_rj_project_item_contract` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT '项目',
  `money` decimal(10,2) DEFAULT NULL COMMENT '金额',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '人员',
  `event_time` int(10) unsigned DEFAULT NULL COMMENT '签约日期',
  `file` json DEFAULT NULL COMMENT '合同文件',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目_合同';

#项目日志
CREATE TABLE `rf_rj_project_item_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `map_id` int(10) DEFAULT '0' COMMENT '关联id',
  `verify` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `steps_name` tinyint(3) NOT NULL DEFAULT '0' COMMENT '步骤状态',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip地址',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测项目_审核日志';

#项目-任务
CREATE TABLE `rf_rj_project_service` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned DEFAULT '0' COMMENT '项目ID',
  `cate_id` int(10) DEFAULT '0' COMMENT '分类id',
  `manager` int(10) NOT NULL COMMENT '负责人',
  `contact` varchar(10) DEFAULT '' COMMENT '联系人',
  `mobile` varchar(20) DEFAULT '' COMMENT '联系方式',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `user_id` int(10) DEFAULT NULL COMMENT '发布者',
  `members` json DEFAULT NULL COMMENT '参与人员',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `audit` tinyint(4) DEFAULT '0' COMMENT '状态',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目_任务派单';

#项目-房屋关联
CREATE TABLE `rf_rj_project_item_house_map` (
  `item_id` int(10) NOT NULL DEFAULT '0' COMMENT '项目id',
  `house_id` int(10) NOT NULL DEFAULT '0' COMMENT '建筑物id',
  PRIMARY KEY (`item_id`,`house_id`),
  KEY `item_id` (`item_id`) USING BTREE,
  KEY `house_id` (`house_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='房屋_项目';

#项目-步骤
CREATE TABLE `rf_rj_project_item_steps` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标识',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `sort` int(5) DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测项目_步骤';

#项目-步骤-赋权员工
CREATE TABLE `rf_rj_project_item_steps_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `step_id` int(10) DEFAULT '0' COMMENT '步骤id',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测项目_步骤负责人';


#项目-任务-日志
CREATE TABLE `rf_rj_project_service_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '1' COMMENT '人员ID',
  `pid` int(10) NOT NULL COMMENT '任务id',
  `verify` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip地址',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测任务_审核记录';

#项目-任务-日志
CREATE TABLE `rf_rj_project_service_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '1' COMMENT '人员ID',
  `pid` int(10) NOT NULL COMMENT '任务id',
  `verify` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip地址',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='任务_日志';

#公司-员工
CREATE TABLE `rf_rj_company_worker` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(20) DEFAULT '' COMMENT '帐号',
  `password_hash` varchar(150) DEFAULT '' COMMENT '密码',
  `auth_key` varchar(32) DEFAULT '' COMMENT '授权令牌',
  `password_reset_token` varchar(150) DEFAULT '' COMMENT '密码重置令牌',
  `type` tinyint(1) DEFAULT '1' COMMENT '类别[1:普通员工;10管理员]',
  `nickname` varchar(100) DEFAULT '' COMMENT '昵称',
  `realname` varchar(100) DEFAULT '' COMMENT '真实姓名',
  `dept_id` int(11) DEFAULT NULL COMMENT '部门',
  `head_portrait` varchar(150) DEFAULT '' COMMENT '头像',
  `current_level` tinyint(4) DEFAULT '1' COMMENT '当前级别',
  `gender` tinyint(1) unsigned DEFAULT '0' COMMENT '性别[0:未知;1:男;2:女]',
  `qq` varchar(20) DEFAULT '' COMMENT 'qq',
  `email` varchar(60) DEFAULT '' COMMENT '邮箱',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `visit_count` int(10) unsigned DEFAULT '1' COMMENT '访问次数',
  `home_phone` varchar(20) DEFAULT '' COMMENT '家庭号码',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号码',
  `role` smallint(6) DEFAULT '10' COMMENT '权限',
  `last_time` int(10) DEFAULT '0' COMMENT '最后一次登录时间',
  `last_ip` varchar(16) DEFAULT '' COMMENT '最后一次登录ip',
  `province_id` int(10) DEFAULT '0' COMMENT '省',
  `city_id` int(10) DEFAULT '0' COMMENT '城市',
  `area_id` int(10) DEFAULT '0' COMMENT '地区',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '上级id',
  `level` tinyint(4) unsigned DEFAULT '1' COMMENT '级别',
  `tree` varchar(750) NOT NULL DEFAULT '' COMMENT '树',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `mobile` (`mobile`),
  KEY `pid` (`pid`),
  KEY `tree` (`tree`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理_员工表';

#公司-员工-token
CREATE TABLE `rf_rj_company_worker_api_access_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `refresh_token` varchar(60) DEFAULT '' COMMENT '刷新令牌',
  `access_token` varchar(60) DEFAULT '' COMMENT '授权令牌',
  `member_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) DEFAULT '' COMMENT '授权对象openid',
  `group` varchar(100) DEFAULT '' COMMENT '组别',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_token` (`access_token`),
  UNIQUE KEY `refresh_token` (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工api_授权秘钥表';

#项目额外信息
CREATE TABLE `rf_rj_project_item_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) NOT NULL COMMENT '绑定项目',
  `day` int(10) DEFAULT '0' COMMENT '周期天数',
  `is_device` tinyint(4) DEFAULT '0' COMMENT '动态监测[0:人工监测,1:动态监测]',
  `device_num` int(10) DEFAULT NULL COMMENT '要求设备数量',
  `type` json DEFAULT NULL COMMENT '类型',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='监测项目_配置表';

CREATE TABLE `rf_rj_company_worker_mini_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `open_id` varchar(140) NOT NULL COMMENT '用户',
  `member_id` int(11) unsigned DEFAULT NULL COMMENT '用户',
  `target_id` int(10) DEFAULT '0' COMMENT '目标id',
  `target_type` varchar(100) DEFAULT '' COMMENT '目标类型',
  `action` varchar(100) DEFAULT '' COMMENT '动作',
  `is_read` tinyint(2) DEFAULT '0' COMMENT '是否已读 1已读',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='员工_订阅消息';

CREATE TABLE `rf_rj_company_worker_mini_message_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `pid` int(10) NOT NULL COMMENT '消息id',
  `error_code` int(10) DEFAULT '0' COMMENT '报错code',
  `error_msg` varchar(200) DEFAULT '' COMMENT '报错信息',
  `error_data` longtext COMMENT '报错日志',
  `use_time` int(10) DEFAULT '0' COMMENT '使用时间',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip地址',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态(-1:已删除,0:禁用,1:正常)',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  `message_data` json DEFAULT NULL COMMENT '发送内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小程序_消息发送日志';

CREATE TABLE `rf_rj_project_item_steps_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT '0' COMMENT '关联id',
  `member_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `ip` varchar(30) DEFAULT '' COMMENT 'ip地址',
  `verify` tinyint(3) NOT NULL DEFAULT '0' COMMENT '审核',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]',
  `created_at` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned DEFAULT '0' COMMENT '修改时间',
  `steps_name` varchar(50) DEFAULT NULL COMMENT '原先步骤',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目_步骤日志';

CREATE TABLE `rf_rj_project_item_number` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '项目ID',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_read` tinyint(3) DEFAULT '0' COMMENT '确认盖章',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目-报告编号';

#项目撤销申请
CREATE TABLE `rf_rj_project_item_remove` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '项目ID',
  `result` tinyint(3) DEFAULT '0' COMMENT '结果',
  `is_read` tinyint(3) DEFAULT '0' COMMENT '已处理',
  `description` char(140) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='项目-撤回申请';

```
