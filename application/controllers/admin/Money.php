<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Money extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{
		// set_time_limit(0);$this->test_store_script();$this->test_agent_script();exit();
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '资金流列表', 'url' => '');
		$this->load->model('money_model');

		$where = array();
 		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$select_name = trim($select_data['name']);
			$like[$this->money_model->getTableName('_store').'.name'] = $select_name;
			$this->resData['select']['name'] = $select_name;
		}
		
		$field = $this->money_model->getTableName('_store_money_log').'.*,'.$this->money_model->getTableName('_store').'.name';
		$data = $this->money_model->getTableListJoinStore($field, $where, $this->per_page, $this->offset,$like);
		
// 		$data = $this->money_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
		
	}

	private function test_store_script()
	{
		for($i = 0; $i < 10000; $i++){
			$data = array(
				'sid' => mt_rand(1, 2),
				'money' => mt_rand(1, 10000),
				'frozen_money' => mt_rand(0, 500),
				'finish_money' => mt_rand(0, 1000),
				'bill_money' => mt_rand(0, 10000),
				'diffmoney' => mt_rand(1, 1000),
				'mode' => mt_rand(0, 1),
				'atime' => mt_rand(microtime('-365day'), now()),
				'remark' => '测试脚本数据'
			);

			$this->my_model->insert_entry($this->my_model->getTableName('_store_money_log'), $data);
		}
	}

	private function test_agent_script()
	{
		for($i = 0; $i < 10000; $i++){
			$data = array(
				'aid' => mt_rand(1, 2),
				'money' => mt_rand(1, 10000),
				'frozen_money' => mt_rand(0, 500),
				'finish_money' => mt_rand(0, 1000),
				'diffmoney' => mt_rand(1, 1000),
				'mode' => mt_rand(0, 1),
				'atime' => mt_rand(microtime('-365day'), now()),
				'remark' => '测试脚本数据'
			);

			$this->my_model->insert_entry($this->my_model->getTableName('_agent_money_log'), $data);
		}
	}
}
