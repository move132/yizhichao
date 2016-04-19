<?php
/**
 * 后台管理菜单配置
 * 规则：顶部一级导航-》左侧一级导航-》左侧二级导航
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config['menu'] = array(
	array('name' => '系统管理', 'list' => array(
			array('name' => '功能', 'class_name' => 'glyphicon-th-large', 'list' => array(
					array('name' => '提现管理', 'd' => 'admin', 'c' => 'offers', 'm' => 'index'),
					array('name' => '代理/推广员', 'd' => 'admin', 'c' => 'extension', 'm' => 'index'),
					array('name' => '店铺管理', 'd' => 'admin', 'c' => 'store', 'm' => 'index'),
					array('name' => '店员管理', 'd' => 'admin', 'c' => 'seller', 'm' => 'index'),
					array('name' => '用户管理', 'd' => 'admin', 'c' => 'member', 'm' => 'index'),
				)
			),
			array('name' => '管理', 'class_name' => 'glyphicon-th-list', 'list' => array(					
					array('name' => '消息管理', 'd' => 'admin', 'c' => 'news', 'm' => 'index'),					
					array('name' => '地区信息', 'd' => 'admin', 'c' => 'area', 'm' => 'index'),
					array('name' => '银行信息', 'd' => 'admin', 'c' => 'bank', 'm' => 'index'),
					array('name' => '店铺类型', 'd' => 'admin', 'c' => 'store_class', 'm' => 'index'),
					array('name' => '经营类目', 'd' => 'admin', 'c' => 'store_category', 'm' => 'index'),
				)
			),
			
			array('name' => '支付', 'class_name' => 'glyphicon glyphicon-yen', 'd' => 'admin', 'c' => 'payment', 'm' => 'index'),
			array('name' => '设置', 'class_name' => 'glyphicon-cog', 'list' => array(
					array('name' => '管理员', 'd' => 'admin', 'c' => 'admin', 'm' => 'index'),					
					array('name' => '系统设置', 'd' => 'admin', 'c' => 'system', 'm' => 'index'),
				)
			),
			array('name' => '日志', 'class_name' => 'glyphicon glyphicon-eye-open', 'list' => array(
					array('name' => '资金流水', 'd' => 'admin', 'c' => 'money', 'm' => 'index'),
					array('name' => '意见反馈', 'd' => 'admin', 'c' => 'feedback', 'm' => 'index'),
					array('name' => '操作日志', 'd' => 'admin', 'c' => 'log', 'm' => 'index'),
				)
			)
		)
	),
	array('name' => '统计报表', 'list' => array(
			array('name' => '统计', 'class_name' => 'glyphicon-object-align-bottom', 'list' => array(
					array('name' => '店铺分析', 'd' => 'admin', 'c' => 'store', 'm' => 'report'),
					array('name' => '支付分析', 'd' => 'admin', 'c' => 'pay', 'm' => 'report'),
					array('name' => '用户分析', 'd' => 'admin', 'c' => 'member', 'm' => 'report'),
					array('name' => '提现分析', 'd' => 'admin', 'c' => 'offers', 'm' => 'report'),
				)
			),
		)
	),
	array('name' => '工具服务', 'list' => array(
			array('name' => '工具', 'class_name' => 'glyphicon glyphicon-wrench', 'list' => array(
					array('name' => '长转短地址', 'd' => 'admin', 'c' => 'tools', 'm' => 'short_url'),
					array('name' => '支付宝当面付授权', 'd' => 'admin', 'c' => 'tools', 'm' => 'app_auth_token'),
				)
			),
			array('name' => '数据库', 'class_name' => 'glyphicon glyphicon-console', 'list' => array(
					array('name' => '表列表', 'd' => 'admin', 'c' => 'db', 'm' => 'table'),
				)
			),
			array('name' => '备份', 'class_name' => 'glyphicon glyphicon-duplicate', 'list' => array(
					array('name' => '备份列表', 'd' => 'admin', 'c' => 'backup', 'm' => 'index'),
				)
			),
		)
	),
	array('name' => '应用营销', 'list' => array(
			array('name' => '基础应用', 'class_name' => 'glyphicon glyphicon-tags', 'list' => array(
					array('name' => '优惠券', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '优惠码', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '满减/送', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '限时折扣', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '订单返现', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '团购管理', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '拍卖管理', 'd' => 'admin', 'c' => '', 'm' => ''),
				)
			),
			array('name' => '游戏应用', 'class_name' => 'glyphicon glyphicon-screenshot', 'list' => array(
					array('name' => '签到', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '投票调查', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '刮刮卡', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '疯狂猜', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '幸运大抽奖', 'd' => 'admin', 'c' => '', 'm' => ''),
					array('name' => '生肖翻翻看', 'd' => 'admin', 'c' => '', 'm' => ''),
				)
			),
		)
	),
);
