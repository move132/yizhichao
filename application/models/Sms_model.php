<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends My_model {
	public $phone;

	public function __construct($phone = NULL)
	{
		parent::__construct();		
		$this->load->library('sms');
	}
	
	//重置密码，不更新数据库，登录再更新
	public function resetPassword($password)
	{
		return $this->sms->send($this->phone, $this->sms->getMsg(6, array('password' => $password)));
	}
	
	/*
	 * 店长邀请收银员，发送短信
	 * @$phone = 接收信息手机号码
	 * @data = array('account' = '收银员名称','store'=>'商品名称','password'=>'密码');	
	 */
	public function invite_seller($phone ,$data)
	{
		$data['url'] = 'http://w.url.cn/s/AAPVFo7';
		return $this->sms->send($phone, $this->sms->getMsg(5, $data));
	}
	
	//重置店长密码，会更新数据库
	public function resetShopownerPwd($password)
	{
		return $this->sms->send($this->phone, $this->sms->getMsg(7, array('password' => $password)));
	}
	
	/*
	 * 代理/推广员添加商铺  
	 * @$phone = 接收信息手机号码
	 * @data = array('password'=>'密码','url'=>'www.baidu.com');
	 */
	public function addStore($phone ,$data)
	{
		$data['url'] = 'http://w.url.cn/s/AAPVFo7';
		return $this->sms->send($phone, $this->sms->getMsg(2, $data));
	}
	
	/*
	 * 添加推广员
	* @$phone = 接收信息手机号码
	* @data = array('password'=>'密码','url'=>'www.baidu.com');
	*/
	public function addPromoter($phone ,$data)
	{
		$data['url'] = site_url('work/login/index');
		return $this->sms->send($phone, $this->sms->getMsg(1, $data));
	}
	
	/*
	 * 添加代理
	* @$phone = 接收信息手机号码
	* @data = array('password'=>'密码','url'=>'www.baidu.com');
	*/
	public function addAgent($phone ,$data)
	{
		$data['url'] = site_url('work/login/index');
		return $this->sms->send($phone, $this->sms->getMsg(0, $data));
	}

	/*
	 * 代理/推广员审核商铺  
	 * @$phone = 接收信息手机号码
	 * @data = array('password'=>'密码','url'=>'www.baidu.com');
	 */
	public function checkStore($phone ,$data)
	{
		$data['url'] = 'http://w.url.cn/s/AAPVFo7';
		return $this->sms->send($phone, $this->sms->getMsg(4, $data));
	}
	
	/*
	 * 审核推广员
	* @$phone = 接收信息手机号码
	* @data = array('password'=>'密码','url'=>'www.baidu.com');
	*/
	public function checkPromoter($phone ,$data)
	{
		$data['url'] = site_url('work/login/index');
		return $this->sms->send($phone, $this->sms->getMsg(3, $data));
	}
}