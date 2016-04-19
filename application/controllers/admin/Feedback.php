<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{	
		$this->load->helper('template');

		$this->resData['listHeader']['location'][] = array('name' => '反馈列表', 'url' => '');

		$this->load->model('feedback_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data['time_start']){
			$select_time_start = strtotime($select_data['time_start']);
		}
		if($select_data['time_end']){
			$select_time_end = strtotime($select_data['time_end']);
		}
		if($select_data['keyword']){
			$select_keyword = trim($select_data['keyword']);
		}
		if(!empty($select_keyword)){
			$like['content'] = $select_keyword;
			$this->resData['select']['title'] = $select_keyword;
		}
		if(!empty($select_time_start) || !empty($select_time_end)){
			$where = array('atime >=' => isset($select_time_start)? $select_time_start : now(), 'atime <= ' => isset($select_time_end)?$select_time_end:now());
			$this->resData['select']['select_time_start'] = $select_data['time_start'];
			$this->resData['select']['select_time_end'] = $select_data['time_end'];
		}

		$data = $this->feedback_model->getTableList($where, $this->per_page, $this->offset,$like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);

		$this->load->view($this->getTemplateFile(), $this->resData);
	}
}
