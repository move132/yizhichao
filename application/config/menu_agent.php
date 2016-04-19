<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['menu'] = array(
	array('name' => '系统管理', 'list' => array(
			array('name' => '功能', 'class_name' => 'glyphicon-th-large', 'list' => array(
					array('name' => '推广员', 'd' => 'agent', 'c' => 'extension', 'm' => 'index'),
					array('name' => '推广赚钱', 'd' => 'agent', 'c' => 'share', 'm' => 'index'),
				)
			),
			array('name' => '管理', 'class_name' => 'glyphicon-th-list', 'list' => array(					
					array('name' => '店铺管理', 'd' => 'agent', 'c' => 'store', 'm' => 'index'),
					array('name' => '提现管理', 'd' => 'agent', 'c' => 'offers', 'm' => 'index'),					
				)
			),
			array('name' => '设置', 'class_name' => 'glyphicon-cog', 'list' => array(
					array('name' => '提现账户', 'd' => 'agent', 'c' => 'account', 'm' => 'index'),
					array('name' => '提现申请', 'd' => 'agent', 'c' => 'offers', 'm' => 'apply'),
				)
			),
			array('name' => '日志', 'class_name' => 'glyphicon-cog', 'list' => array(
					array('name' => '资金流水', 'd' => 'agent', 'c' => 'money', 'm' => 'index'),
					array('name' => '消息动态', 'd' => 'agent', 'c' => 'news', 'm' => 'index'),
				)
			),
			array('name' => '工具', 'class_name' => 'glyphicon glyphicon-thumbs-up', 'list' => array(
					array('name' => '帮助与反馈', 'd' => 'agent', 'c' => 'feedback', 'm' => 'index'),
				)
			)
		)
	),
	array('name' => '工具营销', 'list' => array()),
);
