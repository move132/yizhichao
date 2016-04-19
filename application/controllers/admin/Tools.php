<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function short_url()
	{
		$this->load->helper('template');

		echo $this->load->view($this->getTemplateFile(), $this->resData, true);
	}

	public function app_auth_token()
	{
		$this->load->helper('template');

		echo $this->load->view($this->getTemplateFile(), $this->resData, true);
	}

	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'short_url':
				$long_url = $this->input->post('long_url');
				$this->load->library('wechat', $this->config->item('wechat', 'payment'));
				$this->load->driver('cache');
				$access_token = $this->cache->file->get('wechat_access_token');
				if(! $access_token){
					$access_token = $this->wechat->get_weixin_access_token();
					if(empty($access_token)){
						$this->setFailResponse("获取微信公众号授权码失败");
						echo $this->getResponse();exit();
					}
					$this->cache->file->save('wechat_access_token', $access_token, 7000);
				}
				$this->setSuccessResponse(array('url' => $this->wechat->url_long_to_short(site_url('admin/agent/index/1'), $access_token)));
				break;
			case 'refresh_token':
				$this->load->driver('cache');
				$info = $this->cache->file->get('alipay_open_auth_token_app_response');
				if(! $info){
					$this->setFailResponse("第三方应用授权信息为空 或 已过期！");
					echo $this->getResponse();exit();
				}
				$this->load->helper('payment');
				$arr = json_decode($info, true);
				// var_export($arr);
				$url = 'https://openapi.alipay.com/gateway.do';
				$data = array(
					'app_id' => ALIPAY_THIRD_PARTY_APP_APPID,//$arr['auth_app_id'],
					'method' => 'alipay.open.auth.token.app',
					'charset' => 'gbk',
					'sign_type' => 'RSA',
					'timestamp' => formatTime(getTimeStamp()),
					'version' => '1.0',
					'biz_content' => json_encode(array('grant_type' => 'refresh_token', 'refresh_token' => $arr['app_refresh_token'])),
				);

				$data['sign'] = rsaSign(arrayToKSortstring($data), APPPATH.'config/certificate/alipay/My_private.pem');
		        $url .= '?'.http_build_query($data);
				$str = file_get_contents_hsy($url);
				
				$res = json_decode($str, true);
				if(isset($res['alipay_open_auth_token_app_response']['app_auth_token'])){
					if($this->cache->file->save('alipay_open_auth_token_app_response', json_encode($res['alipay_open_auth_token_app_response']), 365 * 86400)){
						$this->setSuccessResponse();
					}else{
						$this->setFailResponse("写缓存文件失败！");
					}
				}else{
					log_message('error', "[response]:".$str);
					$this->setFailResponse("接口异常！");
				}
				break;
		}

		echo $this->getResponse();
	}
}
