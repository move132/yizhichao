<?php
/**
 * 店铺类型
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Store_class extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Store_class_model');
	}
	
	public function import()
	{
		$this->load->helper('file');
		$string = read_file(FCPATH.'store_class.txt');
		$aBank = explode("\r", $string);
		$flag = true;
		foreach($aBank as $bank){
			$flag = $flag && $this->Store_class_model->insertTable(array('name' => trim($bank)));
		}
		if($flag){
			$this->Store_class_model->reflushStore_class();
		}
	}

	public function index()
	{		
		$this->load->helper('template');
				
		$this->resData['listHeader']['actions'][] = array(
			'name' => '更新店铺类型缓存',
			'url' => $this->getSiteUrl('done'),
			'reflush' => 1,
			'data' => array('action' => 'reflush')
		);
		$this->resData['listHeader']['actions'][] = array(
			'name' => '添加店铺类型',
			'url' => $this->getSiteUrl('add'),
		);
		

		$data = $this->Store_class_model->getTableList(array(), $this->per_page, $this->offset);
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
				
		$this->resData['listHeader']['actions'][] = array('name' => '店铺类型列表', 'url' => $this->getSiteUrl('index'));

		$this->load->view($this->getTemplateFile(), $this->resData);
	}
	
	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'reflush':
				if($this->Store_class_model->reflushStore_class()){
					$this->setSuccessResponse();
				}else{
					$this->setFailResponse("更新店铺类型信息文件缓存失败！");
				}
				break;
			case 'insert':
				$this->load->helper('array');
				$data = elements(
					array('name'),
					$this->input->post()
				);

				if($this->Store_class_model->insertTable($data)){
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加店铺类型失败！".$error['message']);
				}
				break;
		}

		echo $this->getResponse();
	}
}
