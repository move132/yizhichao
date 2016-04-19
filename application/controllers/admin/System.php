<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('setting_model');
	}

	//初始化页面
	public function index()
	{
		$this->load->helper('template');
		
		$this->resData['system'] = $this->setting_model->getTableAll();
		
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'site':
				$this->load->helper('array');
				$data = elements(
					array('site_name', 'site_logo', 'icp_number', 'site_phone', 'site_satus', 'reson', 'app_token_day'),
					$this->input->post()
				);

				$flag = TRUE;
				foreach($data as $name => $value){
					$flag = $flag && $this->setting_model->updateTable(array('value' => $value), array('name' => $name));
				}
				if($flag){
					$this->setting_model->reflushSetting();
					//添加日志
					$action_log = '站点设置--令牌时效-'.$data['app_token_day'].',开启状态-'.$data['site_satus'];
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->setting_model->error();
					$this->setFailResponse("更新系统设置失败！".$error['message']);
				}
				break;
			case 'storeLevel':
				$storeLevel = $this->input->post('storeLevel');
				$where = array('name' => 'storeLevel');
				$row = $this->setting_model->getTableOne($where);
				$data = array();
				
				if($row['value']){
					$data = json_decode($row['value'], true);
				}
				array_push($data, $storeLevel);
				if($this->setting_model->updateTable(array('value' => json_encode($data)), $where)){
					$this->setting_model->reflushSetting();
					$this->setSuccessResponse();
				}else{
					$error = $this->setting_model->error();
					$this->setFailResponse("更新店铺等级失败！".$error['message']);
				}
				break;
			case 'offers':
				$this->load->helper('array');
				$data = elements(
					array('min_commission', 'offers_frequency', 'min_offers'),
					$this->input->post()
				);

				$flag = TRUE;
				foreach($data as $name => $value){
					$flag = $flag && $this->setting_model->updateTable(array('value' => $value), array('name' => $name));
				}
				if($flag){
					$this->setting_model->reflushSetting();
					//添加日志
					$action_log = '提现规则--分佣最低额度-'.$data['min_offers'].'
							,提现频率-'.$data['offers_frequency'].'
							,提现最低额度-'.$data['min_commission'];
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->setting_model->error();
					$this->setFailResponse("更新提现规则失败！".$error['message']);
				}
				break;
		}
		echo $this->getResponse();
	}
}
