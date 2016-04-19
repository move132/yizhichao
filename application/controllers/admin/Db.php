<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bank_model');
	}
	
	public function table()
	{
		$this->load->helper('template');
		$this->resData['listHeader']['actions'][] = array(
			'name' => '备份表结构',
			'url' => $this->getSiteUrl('done'),
			'reflush' => 1,
			'data' => array('action' => 'backup_no_data')
		);
		$this->resData['listHeader']['actions'][] = array(
			'name' => '备份表数据',
			'url' => $this->getSiteUrl('done'),
			'reflush' => 1,
			'data' => array('action' => 'backup')
		);
		
		$list = $this->db->list_tables();
		if($list){			
			$show_list = array_chunk($list, $this->per_page);
			$this->resData['list'] = $show_list[$this->cur_page - 1];
		}else{
			$this->resData['list'] = $list;
		}
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			count($list)
		);
		$this->resData['offset'] = $this->offset;

		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$action = $this->input->post('action');
		switch($action){
			case 'backup':
				$this->load->dbutil();
				$backup = $this->dbutil->backup();
				$this->load->helper('file');
				if(write_file(APPPATH.BACKUP.'my_database_backup_'.formatTime(now(), 3).'.gz', $backup)){
					$this->setSuccessResponse();
				}else{
					$this->setFailResponse("备份表数据失败！");
				}
				break;
			case 'backup_no_data':
				$this->load->dbutil();
				$prefs = array(
					'add_insert' => false
				);
				$backup = $this->dbutil->backup($prefs);
				$this->load->helper('file');
				if(write_file(APPPATH.BACKUP.'my_database_no_data_backup_'.formatTime(now(), 3).'.gz', $backup)){
					$this->setSuccessResponse();
				}else{
					$this->setFailResponse("备份表结构失败！");
				}
				break;
		}
		echo $this->getResponse();
	}
}
