<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payment_model');
	}

	//初始化页面
	public function index()
	{
		$this->load->helper('template');
		
		$this->resData['payment'] = $this->payment_model->getTableAll();

		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'alipay':
			case 'wechat':
			case 'baidu':
			case 'jingdong':
				$this->load->helper('array');
				$param = elements(
					array('is_open', 'fee', 'app_id', 'app_key', 'mch_id', 'mch_key', 'mode'),
					$this->input->post()
				);
				$data = array(
					'code' => $action,
					'is_open' => $param['is_open'],
					'fee' => $param['fee'],
					'mode' => $param['mode'],
					'config' => json_encode(array(
						'app_id' => $param['app_id'],
						'app_key' => $param['app_key'],
						'mch_id' => $param['mch_id'],
						'mch_key' => $param['mch_key']
						)
					)
				);
				$action_log = '支付---类型-'.$action.'是否开启-'.$data['is_open'].'费用-'.$data['fee'];
				if($this->payment_model->replaceTable($data)){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->payment_model->reflush();
					$this->setSuccessResponse();
				}else{
					$error = $this->payment_model->error();
					$this->setFailResponse("更新支付宝设置失败！".$error['message']);
				}				
				break;
		}
		echo $this->getResponse();
	}
}
