<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{	
		$this->load->helper('template');
		$this->resData['listHeader']['location'][] = array('name' => '日志列表', 'url' => '');
	//	$this->resData['listHeader']['actions'][] = array('name' => '添加', 'url' => site_url(array($this->router->directory, $this->router->class, 'add')));
		$this->load->model('my_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get_post('select');
		if($select_data){
			$select_account = trim($select_data['account']);
			$time_start = trim($select_data['time_start']);
			$time_end = trim($select_data['time_end']);
			$select_time_start = strtotime($time_start);
			$select_time_end = strtotime($time_end);
		}
		if(!empty($select_account)){
			$like['account'] = $select_account;
			$this->resData['select']['select_account'] = $select_account;
		}
		if(!empty($select_time_start) || !empty($select_time_end)){
			$where = array('atime >=' => isset($select_time_start)?$select_time_start:now(), 'atime <= ' => isset($select_time_end)?$select_time_end:now());
			$this->resData['select']['select_time_start'] = $time_start;
			$this->resData['select']['select_time_end'] = $time_end;
		}
	
		$data = $this->my_model->get_action_log_list($where, $this->per_page, $this->offset,$like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		
		$this->load->view($this->getTemplateFile(), $this->resData);
	}
	//详情页面
	public function view(){
		$this->load->helper('template');
		$id = $this->input->get('id');
		$this->load->model('news_model');
		$new =  $this->news_model->getTableOne(array('id'=>$id));
		$this->resData['list'] = $new;
		
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function add()
	{	
		$this->load->helper('template');
		$id = $this->input->get('id');
		if( intval($id)){
			$this->load->model('news_model');
			$new =  $this->news_model->getTableOne(array('id'=>$id));     
			$this->resData['list'] = $new;
			$this->resData['listHeader']['location'][] = array('name' => '编辑消息', 'url' => '');
			$this->resData['listHeader']['actions'][] = array('name' => '消息列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')));
		}else{
			$this->resData['listHeader']['location'][] = array('name' => '添加消息', 'url' => '');
			$this->resData['listHeader']['actions'][] = array('name' => '消息列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')));
		}
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$this->load->model('news_model');
		$action = $this->input->post('action');
		switch($action){
			case 'insert':
				$this->load->helper(array('array'));
				$data = elements(
					array('title', 'content'),
					$this->input->post()
				);
				$data['account']=$this->aSession['account'];
				if($this->news_model->insertTable($data)){
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加消息失败！".$error['message']);
				}
				break;
			case 'status':
				$id = $this->input->post('id');
				$status = $this->input->post('status');
				if($this->news_model->updateTable(array('is_close' => $status), array('id' => $id))){
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("操作消息状态失败！".$error['message']);
				}
				break;
			case 'update':
				$this->load->helper(array('array'));
				$id = $this->input->post('id');
				$data = elements(
						array('title', 'content'),
						$this->input->post()
				);
				$data['account']=$this->aSession['account'];
				if($this->news_model->updateTable($data,array('id'=>$id))){
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加消息失败！".$error['message']);
				}
				break;
		}
		echo $this->getResponse();
	}
}
