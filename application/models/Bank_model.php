<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		return $this->get_list($this->getTableName('_bank'), $where, $limit, $offset);		
	}
	
	//写入表
	public function insertTable($data = array())
	{
		return $this->insert_entry($this->getTableName('_bank'), $data);
	}
	
	//更新银行文件缓存
	public function reflushBank()
	{
		$this->load->helper('file');
		$phpFile = APPPATH.'config/bank.php';
		
		$data = $this->get_all($this->getTableName('_bank'));
		if($data){
			$res = array();
			foreach($data as $item){
				$res[$item['id']] = $item;
			}
			
			$flag = write_file($phpFile, "<?php\r\ndefined('BASEPATH') OR exit('No direct script access allowed');\r\n".'$config[\'bank\'] = '.var_export($res, true).';');
			if($flag){
				$this->load->library('ftp');
				$this->ftp->chmod($phpFile, 0755);
			}
			return $flag;
		}
		return false;
	}
}