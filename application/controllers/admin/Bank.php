<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bank_model');
	}
	
	public function import()
	{
		$this->load->helper('file');
		$string = read_file(FCPATH.'bank.txt');
		$aBank = explode("\r", $string);
		$flag = true;
		foreach($aBank as $bank){
			$flag = $flag && $this->bank_model->insertTable(array('name' => trim($bank)));
		}
		if($flag){
			$this->bank_model->reflushBank();
		}
	}

	public function index()
	{		
		$this->load->helper('template');
				
		$this->resData['listHeader']['actions'][] = array(
			'name' => '更新银行缓存',
			'url' => $this->getSiteUrl('done'),
			'reflush' => 1,
			'data' => array('action' => 'reflush')
		);
		$this->resData['listHeader']['actions'][] = array(
			'name' => '添加银行',
			'url' => $this->getSiteUrl('add'),
		);
		

		$data = $this->bank_model->getTableList(array(), $this->per_page, $this->offset);
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
				
		$this->resData['listHeader']['actions'][] = array('name' => '银行列表', 'url' => $this->getSiteUrl('index'));

		$this->load->view($this->getTemplateFile(), $this->resData);
	}
	
	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'reflush':
				if($this->bank_model->reflushBank()){
					$this->setSuccessResponse();
				}else{
					$this->setFailResponse("更新银行信息文件缓存失败！");
				}
				break;
			case 'insert':
				$this->load->helper('array');
				$data = elements(
					array('name'),
					$this->input->post()
				);

				if($this->bank_model->insertTable($data)){
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加银行失败！".$error['message']);
				}
				break;
		}

		echo $this->getResponse();
	}
}
