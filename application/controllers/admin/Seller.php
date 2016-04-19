<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	//初始化页面
	public function index()
	{
		// set_time_limit(0);$this->test_seller_script();exit();
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '店员列表', 'url' => '');
		
		$this->load->model('seller_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$select_name = trim($select_data['name']);
			$like['nickname'] = $select_name;
			$this->resData['select']['name'] = $select_name;
		}
		
		$field = $this->seller_model->getTableName('_seller').'.*,'.$this->seller_model->getTableName('_store').'.name';
		$data = $this->seller_model->getTableListJoinStore($field, $where, $this->per_page, $this->offset,$like);
// 		$data = $this->seller_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
				site_url(array($this->router->directory, $this->router->class, $this->router->method)),
				$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	private function test_seller_script()
	{
		$pwd = md5(123456);
		for($i = 0; $i < 1000; $i++){
			$data = array(
				'sid' => mt_rand(1, 2),
				'nickname' => 'robot_'.makeStorePID(),
				'account' => makeStorePID(),
				'pwd' => $pwd,
				'points' => mt_rand(0, 10000),
				'sex' => mt_rand(0, 2),
				'shopowner' => mt_rand(0, 1),
				'status' => mt_rand(0, 1),
				'atime' => mt_rand(microtime('-365day'), microtime('-1day'))
			);
			if($data['status']){
				$data['stime'] = $data['atime'] + mt_rand(1, 100000000);
				$data['ltime'] = $data['stime'] + mt_rand(1, 100000000);
			}

			$this->my_model->insert_entry($this->my_model->getTableName('_seller'), $data);
		}
	}
}
