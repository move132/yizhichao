<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extension extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();		
	}

	//初始化页面
	public function index()
	{
		$this->load->helper('template');

		$this->load->config('region');
		$this->resData['region'] = $this->config->item('region');
		$this->load->model('agent_promoter_model');
		if($this->input->get('parent_id')){
			$parent_id = $this->input->get('parent_id');
		}else{
			$select_data = $this->input->get('select');
			$parent_id = $select_data['parent_id'];
		}
		
		if($this->aSession['account_type'] != 1){
			$parent_id = $this->aSession['data']['id'];
		}
		$this->resData['select']['parent_id'] = $parent_id;
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$name = trim($select_data['name']);
			$tel = trim($select_data['tel']);
		}
		if(!empty($name) || !empty($tel)){
			$like['name'] = $name;
			$like['phone'] = $tel;
			$this->resData['select']['name'] = $name;
			$this->resData['select']['tel'] = $tel;
		}
		if(is_null($parent_id)){
			$this->resData['listHeader']['location'][] = array('name' => '代理列表', 'url' => '');
			$this->resData['listHeader']['actions'][] = array('name' => '推广员列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')), 'data' => array('parent_id' => -1));
			$parent_id = 0;
			$where['parent_id'] = $parent_id;
		}elseif($parent_id == -1){
			$this->resData['listHeader']['location'][] = array('name' => '推广员列表', 'url' => '');
			$this->resData['listHeader']['actions'][] = array('name' => '代理列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')), 'data' => array('parent_id' => 0));
			$where['parent_id !='] = 0;
		}else{
			$where['parent_id'] = $parent_id;
			if($parent_id == 0){
				$this->resData['listHeader']['location'][] = array('name' => '代理列表', 'url' => '');
				$this->resData['listHeader']['actions'][] = array('name' => '推广员列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')), 'data' => array('parent_id' => -1));
			}else{
				$row = $this->agent_promoter_model->getTableOne(array('id' => $parent_id));
				$this->resData['listHeader']['location'][] = array('name' => '推广员列表【'.$row['name'].'】', 'url' => '');
				if($this->aSession['account_type'] == 1){
					$this->resData['listHeader']['actions'][] = array('name' => '代理列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')), 'data' => array('parent_id' => 0));
				}				
			}
		}

		if($this->aSession['account_type'] == 1){
			$this->resData['listHeader']['actions'][] = array('name' => '添加代理', 'url' => site_url(array($this->router->directory, $this->router->class, 'add')));
		}else{
			if($this->aSession['data']['parent_id'] == 0){
				$this->resData['listHeader']['actions'][] = array('name' => '添加推广员', 'url' => site_url(array($this->router->directory, $this->router->class, 'add')));
			}
		}		

		$data = $this->agent_promoter_model->getTableList($where, $this->per_page, $this->offset ,$like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);

		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function add()
	{
		$this->load->helper('template');
		
		if($this->aSession['account_type'] == 1){
			$this->resData['listHeader']['location'][] = array('name' => '添加代理', 'url' => '');
			$this->resData['listHeader']['actions'][] = array('name' => '代理列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')));
		}else{
			if($this->aSession['data']['parent_id'] == 0){
				$this->resData['listHeader']['location'][] = array('name' => '添加推广员', 'url' => '');
				$this->resData['listHeader']['actions'][] = array('name' => '推广员列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')));
			}
		}

		$this->load->config('bank');
		$this->resData['bank'] = $this->config->item('bank');
		
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

    
	//二维码修复功能
	public function repairScan()
	{
		$this->load->model('agent_promoter_model');
		$id = (int)$this->input->post('id');
		$parent_id = (int)$this->input->post('parent_id');
		$type = (int)$this->input->post('type'); //type 1: 只修复店铺二维码   2：店铺二维码，推广二维码都修复
		$promoter_scan = getScanFileName(1,$id, $parent_id);
		$store_scan = getScanFileName(2,$id, $parent_id);
		// $promoter_scan = PHPQRCODE_AGENT_PROMOTER.$promoter_scan;
		// $store_scan = PHPQRCODE_AGENT_PROMOTER.$store_scan;
		if($type == 2){
			if(!file_exists(FCPATH.$promoter_scan) && !file_exists(FCPATH.$store_scan)){
				$this->agent_promoter_model->setScan(1, $id, $promoter_scan);
				$this->agent_promoter_model->setScan(2, $id, $store_scan);
				$this->setSuccessResponse();
				echo $this->getResponse();exit;
			}elseif(!file_exists(FCPATH.$promoter_scan)){
				$this->agent_promoter_model->setScan(1, $id, $promoter_scan);
				$this->setSuccessResponse();
				echo $this->getResponse();exit;
			}elseif(!file_exists(FCPATH.$store_scan)){
				$this->agent_promoter_model->setScan(1, $id, $promoter_scan);
				$this->setSuccessResponse();
				echo $this->getResponse();exit;
			}
		}else {
			if(!file_exists(FCPATH.$store_scan)){
				$this->agent_promoter_model->setScan(2, $id, $store_scan);
			}
		}
		$this->setSuccessResponse();
		echo $this->getResponse();
	}
	
	//下载二维码
	public function download()
	{
		$this->load->model('agent_promoter_model');
		$type = (int)$this->input->get('type');
		$id = (int)$this->input->get('id');
		$parent_id = (int)$this->input->get('parent_id');
		$file = getScanFileName($type, $id, $parent_id);
		// $file = PHPQRCODE_AGENT_PROMOTER.$file_name;
		if(! file_exists(FCPATH.$file) && ! $this->agent_promoter_model->setScan($type, $id, $file)){
			show_error("文件不存在：".$file);
		}else{
			$this->file_download(FCPATH.$file);
		}		
	}

	public function done()
	{
		$this->load->model('agent_promoter_model');
		$action = $this->input->post('action');
		switch($action){
			case 'insert':
				$this->load->helper(array('array', 'string'));
				$data = elements(
					array('name', 'phone', 'idc_number', 'region_1', 'region_2', 'region_3', 'addr_info', 'card_account', 'card_id', 'bank_id', 'promoter_fee', 'store_fee'),
					$this->input->post()
				);
				$password = '';
				if($this->aSession['account_type'] == 1){
					$action_log = "添加代理--".$data['name'].'--'.$data['phone'];
					$data['parent_id'] = 0;
					$data['admin_id'] = $this->aSession['data']['id'];
				}else{
					$action_log = "添加推广员--".$data['name'].'--'.$data['phone'];
					$data['parent_id'] = $this->aSession['data']['id'];
					$data['admin_id'] = 0;
					$data['promoter_fee'] = 0;
				}
				
				if($this->agent_promoter_model->insertTable($data, $password)){
					$id = $this->agent_promoter_model->insert_id();
					$promoter_scan = getScanFileName(1,$id);
					$store_scan = getScanFileName(2,$id);
					// $promoter_scan = PHPQRCODE_AGENT_PROMOTER.$promoter_scan;
					// $store_scan = PHPQRCODE_AGENT_PROMOTER.$store_scan;
					$this->agent_promoter_model->setScan(1, $id, $promoter_scan);
					$this->agent_promoter_model->setScan(2, $id, $store_scan);
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);

					$this->load->model('sms_model');
					$sms_data = array('password'=>$password);
					$flag = $this->sms_model->addAgent($data['phone'], $sms_data);
					if($flag){//记录发短信成功标志

					}else{//记录发短信失败标志

					}

					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加代理失败！".$error['message']);
				}
				break;
			case 'status':
				$id = $this->input->post('id');
				$name = $this->input->post('name');
				$status = $this->input->post('status');
				$update_agent = array('status' => $status);
				if($status == 1){//从新or审核开通发短信    启用
					$this->load->helper('string');
					$password = getRegisterPassword();
					$update_agent['pwd'] = $this->agent_promoter_model->getAccountPassword($password);
					$action_log = '启用代理---代理姓名-'.$name;
				}else{//禁用
					$action_log = '禁用代理---代理姓名-'.$name;
				}
				if($this->agent_promoter_model->updateTable($update_agent, array('id' => $id))){
					if($status == 1){
						$this->load->library('sms');
						$flag = $this->sms->send($this->input->post('phone'), $this->sms->getMsg(0, array('password' => $password)));
					}
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("操作代理状态失败！".$error['message']);
				}
				break;
			case 'checkPhone':
				$phone = $this->input->post('phone');
				if($phone && ! $this->agent_promoter_model->getTableOne(array('phone' => $phone))){
					echo "true";
				}else{
					echo "false";
				}
				exit();
				break;
		}
		echo $this->getResponse();
	}
}
