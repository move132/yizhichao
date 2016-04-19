<?php
/**
 * 店铺经营类目
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_category extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Store_category_model');
	}

	public function index()
	{		
		$this->load->helper('template');
		$class_parent_id = $this->input->get('class_parent_id');
				
		$this->_setClassParentId($class_parent_id);

		$this->resData['listHeader']['actions'][] = array(
			'name' => '更新经营类目缓存',
			'url' => $this->getSiteUrl('done'),
			'reflush' => 1,
			'data' => array('action' => 'reflush')
		);
		$this->resData['listHeader']['actions'][] = array(
			'name' => '添加经营类目',
			'url' => $this->getSiteUrl('add'),
			'data' => array('class_parent_id' => $this->resData['class_parent_id'])
		);
		

		$where = array('class_parent_id' => $class_parent_id);
		$data = $this->Store_category_model->getTableList($where, $this->per_page, $this->offset);
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
		$class_parent_id = $this->input->get('class_parent_id');
				
		$this->_setClassParentId($class_parent_id);
		$this->resData['listHeader']['actions'][] = array('name' => '经营类目列表', 'url' => $this->getSiteUrl('index'));

		$this->load->view($this->getTemplateFile(), $this->resData);
	}
	
	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'reflush':
				if($this->Store_category_model->reflushStore_category()){
					$this->setSuccessResponse();
				}else{
					$this->setFailResponse("更新经营类目信息文件缓存失败！");
				}
				break;
			case 'insert':
				$this->load->helper('array');
				$data = elements(
					array('class_name', 'class_parent_id'),
					$this->input->post()
				);
				if(is_numeric($data['class_parent_id'])){
					$data['class_type'] = 0;
				}else{
					$data['class_type'] = substr_count($data['class_parent_id'], '-');
					$data['class_parent_id'] = substr($data['class_parent_id'], strrpos($data['class_parent_id'], '-') + 1);
				}

				if($this->Store_category_model->insertTable($data)){
					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加店铺失败！".$error['message']);
				}
				break;
		}

		echo $this->getResponse();
	}

	private function _setClassParentId(&$class_parent_id)
	{
		if(is_null($class_parent_id) || $class_parent_id == '0'){
			$class_parent_id = 0;
			// $this->resData['listHeader']['location'][] = array('name' => '中国', 'url' => '');
			$this->resData['class_parent_id'] = $class_parent_id;
		}else{
			$this->resData['class_parent_id'] = $class_parent_id;
			$aArea = $this->Store_category_model->getAreaByIds($class_parent_id);			
			$sTmp = '';
			foreach($aArea as $item){
				if(! empty($sTmp)){
					$sTmp .= '-'.$item['class_id'];
				}else{
					$sTmp = '0-'.$item['class_id'];
				}
				$this->resData['listHeader']['location'][] = array('name' => $item['class_name'], 'url' => $this->getSiteUrl(), 'data' => array('class_parent_id' => $sTmp));
			}
			$class_parent_id = substr($class_parent_id, strrpos($class_parent_id, '-') + 1);
		}
	}
}
