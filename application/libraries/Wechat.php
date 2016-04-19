<?php
/**
 * @package		CodeIgniter
 * @author		曹学欢
 * @copyright	深圳市北大红杉网络科技股份有限公司
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 微信接口类
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		曹学欢
 * @link		
 */

class Wechat {
	public $open_info;//开放平台信息

	public $dir = 'cache/wechat/';
	public $diff_time = 10;
	public $retry = 3;

	public function __construct($wechat)
	{
		$this->open_info = $wechat;
	}
	
	
	/**
	 * 微信下单接口
	 * @$data 下单所需要的参数 array()
	 * @$key  公众号商铺的钥匙 mch_key
	 */
	public function order($data = array()){
		$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
		$param = array(
			'appid' => $this->open_info['config']['app_id'],
			'mch_id' => $this->open_info['config']['mch_id'],
			'nonce_str' => getNonceStr(),
			'body' => $data['body'],
			'attach' => $data['attach'],
			'out_trade_no' => $data['pay_sn'],
			'total_fee' => $data['money'] * 100,//分
			'spbill_create_ip' => $data['ip'],
			'notify_url' => $data['notify_url'],
			'trade_type' => $data['trade_type'],
			'product_id' => isset($data['product_id']) ? $data['product_id'] : '',
			'openid' => isset($data['openid']) ? $data['openid'] : ''
		);
		$param['sign'] = getWechatSign($this->open_info['config']['mch_key'], $param);
		$xml = arrayToxml($param);
		$res = file_get_contents_hsy($url, 'POST', $xml);	
		$result = array();
		if($res){
			$res_obj = xmlToarray($res);
			if($res_obj['return_code'] == 'SUCCESS'){
				if($res_obj['result_code'] == 'SUCCESS'){
					$prepay_id = $res_obj['prepay_id'];
					$code_url = isset($res_obj['code_url']) ? $res_obj['code_url'] : '';
					$result = array('code' => 0, 'prepay_id' => $prepay_id, 'code_url' => $code_url);
				}else{
					$result = array('code' => 1, 'return_msg' => $res_obj['err_code_des']);
				}
			}else{
				$result = array('code' => 1, 'return_msg' => $res_obj['return_msg']);
			}
		}else{
			$result = array('code' => 1, 'return_msg' => '网络异常，稍后重试');
		}
		return $result;
	}
	
	//关注公众号获取用户信息
	public function get_userinfo_by_openid($openid, $access_token)
	{
		$row = array();
		if(! $openid || ! $access_token){
			return $row;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$res = file_get_contents_hsy($url);
		if($res){
			$res_arr = json_decode($res, true);
			if(!isset($res_arr['errcode'])){
				return $res_arr;
			}
		}
		log_message('error', 'get_userinfo_by_openid: '.$res);
		return $row;
	}

	//长链接转短链接
	public function url_long_to_short($url, $access_token)
	{
		if(empty($url) || empty($access_token)){
			return $url;
		}
		$data = array(
			'action' => 'long2short',
			'long_url' => $url
		);
		$res = file_get_contents_hsy('https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.$access_token, 'POST', json_encode($data));
		if($res){
			$res_arr = json_decode($res, true);
			if(isset($res_arr['short_url'])){
				return $res_arr['short_url'];
			}			
		}
		log_message('error', 'url_long_to_short: '.$res);
		return $url;
	}
	
	//获取公众号access_token
	public function get_weixin_access_token()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->open_info['config']['app_id'].'&secret='.$this->open_info['config']['app_key'];
		$access_token = '';
		$i = $this->retry;
		while($i){
			$i--;
			$res = file_get_contents_hsy($url);
			if($res){
				$res_arr = json_decode($res, true);
				if(isset($res_arr['access_token'])){
					$access_token = $res_arr['access_token'];
					break;
				}
			}
		}

		empty($access_token) && log_message('error', 'get_weixin_access_token: '.$res);
		return $access_token;		
	}
	
	//授权登录页
	public function member_connect($redirect = '', $state = '', $scope = 'snsapi_base'){//snsapi_userinfo
		$redirect = empty($redirect) ? current_url() : $redirect;//最终显示地址
		$redirect_uri = urlencode($redirect);
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->open_info['config']['app_id'].'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
		redirect($url);
		exit();
	}
	
	//获取登陆授权
	public function get_wechat_web_access_token($code)
	{
		$ret = array();
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->open_info['config']['app_id'].'&secret='.$this->open_info['config']['app_key'].'&code='.$code.'&grant_type=authorization_code';
		$i = $this->retry;
		while($i){
			$i--;
			$res = file_get_contents_hsy($url);
			if($res){
				$res_arr = json_decode($res, true);
				if(isset($res_arr['access_token'])){
					$ret = $res_arr;
					break;
				}
			}
		}
		
		empty($ret) && log_message('error', 'get_wechat_web_access_token: '.$res);
		return $ret;
	}
	
	//JS-SDK使用权限签名算法
	public function get_jsapi_ticket($access_token){
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
		$jsapi_ticket = '';
		$i = $this->retry;
		while($i){
			$i--;
			$res = file_get_contents_hsy($url);
			if($res){
				$res_arr = json_decode($res, true);
				if(isset($res_arr['ticket'])){
					$jsapi_ticket = $res_arr['ticket'];
					break;
				}
			}
		}
		
		empty($jsapi_ticket) && log_message('error', 'get_jsapi_ticket: '.$res);
		return $jsapi_ticket;
	}
	
	//门店二维码
	public function store_qrcode_url($sid, $param = array())
	{
		$param['appid'] = $this->open_info['config']['app_id'];
		$param['mch_id'] = $this->open_info['config']['mch_id'];
		$param['product_id'] = $sid;
		$param['time_stamp'] = getTimeStamp();
		$param['nonce_str'] = getNonceStr();

		$sign = getWechatSign($this->open_info['config']['mch_key'], $param);

		return 'weixin://wxpay/bizpayurl?sign='.$sign.'&appid='.$param['appid'].'&mch_id='.$param['mch_id'].'&product_id='.$param['product_id'].'&time_stamp='.$param['time_stamp'].'&nonce_str='.$param['nonce_str'];
	}
}