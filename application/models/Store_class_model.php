<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_class_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		return $this->get_list($this->getTableName('_store_class'), $where, $limit, $offset);		
	}
	
	//写入表
	public function insertTable($data = array())
	{
		return $this->insert_entry($this->getTableName('_store_class'), $data);
	}
	
	//更新银行文件缓存
	public function reflushStore_class()
	{
		$this->load->helper('file');
		$jsFile = FCPATH.'static/js/store_class.js';
		$phpFile = APPPATH.'config/store_class.php';
		
		$data = $this->get_all($this->getTableName('_store_class'));
		if($data){
			$res = array();
			foreach($data as $item){
				$res[$item['id']] = $item['name'];
			}
			$flag = write_file($jsFile, 'var store_class = '.json_encode($res));
			$flag = $flag && write_file($phpFile, "<?php\r\ndefined('BASEPATH') OR exit('No direct script access allowed');\r\n".'$config[\'store_class\'] = '.var_export($res, true).';');
			
			return $flag;
		}
		return false;
	}
}