<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bank_model');
	}
	
	public function index()
	{
		$this->load->helper(array('template','file', 'number'));
		
		$list = get_dir_file_info(APPPATH.BACKUP);
		// var_export($list);exit();
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

	public function download()
	{
		$filename = $this->input->get_post('filename');
		$file = APPPATH.BACKUP.$filename;
		if(! file_exists($file)){
			show_error("文件不存在：".$filename);
		}else{
			$this->file_download($file);
		}
	}

	public function done()
	{
		$action = $this->input->get_post('action');
		switch($action){
			case 'unlink':
				$filename = $this->input->get_post('filename');
				$file = APPPATH.BACKUP.$filename;
				if(file_exists($file)){
					if(! unlink($file)){
						$this->setFailResponse("删除文件失败！");
					}else{
						$this->setSuccessResponse();
					}
				}else{
					$this->setSuccessResponse();
				}
				break;
		}
		echo $this->getResponse();
	}
}
