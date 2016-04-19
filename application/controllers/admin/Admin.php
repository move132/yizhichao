<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{
		$this->load->helper('template');
		$this->resData['listHeader']['location'][] = array('name' => '管理员列表', 'url' => '');
		$this->resData['listHeader']['actions'][] = array('name' => '添加', 'url' => $this->getSiteUrl('add'));

		$this->load->model('admin_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$like['name'] = trim($select_data['name']);
			$this->resData['select']['name'] = trim($select_data['name']);
		}
		$data = $this->admin_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function add()
	{	
		$this->resData['listHeader']['location'][] = array('name' => '添加管理员', 'url' => '');
		$this->resData['listHeader']['actions'][] = array('name' => '管理员列表', 'url' => $this->getSiteUrl('index'));
		$this->load->helper('template');
		$this->load->view($this->getTemplateFile(), $this->resData);
	}
	
	//详情
	public function view()
	{
		$id = $this->input->get_post('id');
		$this->load->model('admin_model');
		$where = array('id' => $id);
		$select = '*';
		$admin_data = $this->admin_model->getTableOne($where,$select);
		$this->resData['list'] = $admin_data;
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$this->load->model('admin_model');
		$action = $this->input->post('action');
		switch($action){
			case 'insert':
				$this->load->helper('array');
				$data = elements(
					array('account', 'password', 'name', 'email'),
					$this->input->post()
				);
				$action_log = '添加管理员---管理员姓名-'.$data['name'];
				if($this->admin_model->insertTable($data)){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加管理员失败！".$error['message']);
				}
				break;
			case 'status':
				$id = $this->input->post('id');
				$name = $this->input->post('name');
				$status = $this->input->post('status');
				if($status == 1){
					$action_log = '禁用管理员---被禁用管理员姓名-'.$name;
				}else{
					$action_log = '启用管理员---被启用管理员姓名-'.$name;
				}
				if($this->admin_model->updateTable(array('disable' => $status), array('id' => $id))){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("操作管理员状态失败！".$error['message']);
				}
				break;
			case 'update':
				$id = $this->input->post('id');
				$name = $this->input->post('name');
				$data['password'] = md5(RESET_ADMIN_PASSWORD);
				$action_log = '重置管理员密码---被重置管理员姓名-'.$name;
				if($this->admin_model->updateTable($data,array('id'=>$id))){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("重置管理员密码失败！".$error['message']);
				}
				break;
		}
		echo $this->getResponse();
	}
}
