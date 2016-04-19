<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{	
		$this->load->helper('template');

		$this->resData['listHeader']['location'][] = array('name' => '消息列表', 'url' => '');
		$this->resData['listHeader']['actions'][] = array('name' => '添加', 'url' => site_url(array($this->router->directory, $this->router->class, 'add')));

		$this->load->model('news_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data['time_start']){
			$select_time_start = strtotime($select_data['time_start']);
		}
		if($select_data['time_end']){
			$select_time_end = strtotime($select_data['time_end']);
		}
		if($select_data['news_title']){
			$select_news_title = trim($select_data['news_title']);
		}
		if(!empty($select_news_title)){
			$like['title'] = $select_news_title;
			$this->resData['select']['title'] = $select_news_title;
		}
		if(!empty($select_time_start) || !empty($select_time_end)){
			$where = array('atime >=' => isset($select_time_start)? $select_time_start : now(), 'atime <= ' => isset($select_time_end)?$select_time_end:now());
			$this->resData['select']['select_time_start'] = $select_data['time_start'];
			$this->resData['select']['select_time_end'] = $select_data['time_end'];
		}

		$data = $this->news_model->getTableList($where, $this->per_page, $this->offset,$like);
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
				$action_log = '添加消息---消息标题-'.$data['title'];
				if($this->news_model->insertTable($data)){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加消息失败！".$error['message']);
				}
				break;
			case 'status':
				$id = $this->input->post('id');
				$status = $this->input->post('status');
				if($status == 1){
					$action_log = '删除消息---消息ID-'.$id;
				}else{
					$action_log = '恢复消息---消息ID-'.$id;
				}
				if($this->news_model->updateTable(array('is_close' => $status), array('id' => $id))){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
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
				$action_log = '修改消息---消息ID-'.$id;
				if($this->news_model->updateTable($data,array('id'=>$id))){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
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
