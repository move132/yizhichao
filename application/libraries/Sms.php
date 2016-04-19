<?php
/**
 * @package		CodeIgniter
 * @author		曹学欢
 * @copyright	深圳市北大红杉网络科技股份有限公司
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 手机短信类
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		曹学欢
 * @link		
 */

class Sms {
	public $server_name = '【商贸通收银台】';
	private $msgTemplate = array(
		'您的代理账号已开通，请使用手机号登录，登录密码#password#。登录地址：#url#',//管理员添加
		'您的推广员账号已开通，请使用手机号登录，登录密码#password#。登录地址：#url#',//代理添加
		'您的店铺账号已开通，请使用手机号登录，登录密码#password#，店长密码#shopowner_password#。APP下载地址：#url#',//代理添加 or 推广员添加
		'您的推广员申请审核通过，请使用手机号登录，登录密码#password#。登录地址：#url#',//代理审核【推广链接/二维码注册】
		'入驻店铺申请审核通过，请使用手机号登录，登录密码#password#，店长密码#shopowner_password#。APP下载地址：#url#',//代理 or 推广员审核【推广链接/二维码注册】
		'#account#已经添加你为#store#的收款app收银员，请使用手机号登录，登录密码#password#。登录地址：#url#',//店长邀请
		'您刚刚进行了重置密码操作，新密码为#password#，如非本人操作请忽略。',//重置收银员登录密码
		'您刚刚进行了重置店长密码操作，新密码为#password#',//重置店长密码	
	);
	// 短信发送接口地址
	private $_url = 'http://yunpian.com/v1/sms/send.json';
	// 用户apikey
	private $_apikey = '604da3f7f7de0a0bc3b934dc58720855';
	//短信模式0正常模式，1测试模式
	private $_sms_mode = 0;

	public function getMsg($msg_id, $data = array()){
		$str = isset($this->msgTemplate[$msg_id]) ? $this->msgTemplate[$msg_id] : '';
		if(empty($str)){
			return $str;
		}
		foreach($data as $key=>$val){
			$str = str_replace('#'.$key.'#', $val, $str);
		}
		return $str;
	}
	
	/**
	 * 发送手机短信
	 * @param unknown $mobile 手机号
	 * @param unknown $content 短信内容
	 */
	public function send($mobile,$content) {
		if($this->_sms_mode){
			return true;
		}else{
			return $this->_sendYunpian($mobile,$content);
		}
	}

	/**
	 * 云片网短信发送接口  	曹学欢
	 * @param unknown $mobile 手机号 多个号码以,分隔
	 * @param unknown $content 短信内容
	 */
	public function _sendYunpian($mobile,$content) 
	{
		if(!empty($mobile) && !empty($content)){
			$data = array('text' => $this->server_name.$content, 'apikey'=>$this->_apikey, 'mobile'=>$mobile);
			$ret = $this->sock_post($data);
			if(empty($ret)){
				log_message('error', '发送短信通信失败');
				return false;
			}
			if($ret['code']){
				log_message('error', '发送短信报错：'.$ret['msg']);
				return false;
			}
			return true;
		}
		return false;
	}
	// 云片网接口使用发送函数
	private function sock_post($data)
	{
		$ch = curl_init();
		/* 设置验证方式 */
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
		/* 设置返回结果为流 */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		/* 设置超时时间*/
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		/* 设置通信方式 */
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_URL, $this->_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$json_data =  curl_exec($ch);
		$array = json_decode($json_data,true);
		return $array;
	}
}