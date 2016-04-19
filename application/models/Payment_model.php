<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Model extends My_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询一条记录
	public function getTableOne($where = array(), $select = '*' ){
		return $this->find_entry($this->getTableName('_payment'), $select, $where);
	}
	
	//查询所有信息
	public function getTableAll($where = array(), $limit = NULL, $offset = NULL)
	{
		$ret = array();
		$rows = $this->get_all($this->getTableName('_payment'));
		if($rows){
			foreach($rows as $item){
				$item['config'] = json_decode($item['config'], true);
				$ret[$item['code']] = $item;
			}
		}
		return $ret;
	}
	
	public function replaceTable($data = array())
	{
		return $this->db->replace($this->getTableName('_payment'), $data);
	}
	
	//更新文件缓存
	public function reflush()
	{
		$this->load->helper('file');
		$phpFile = APPPATH.'config/payment.php';
		
		$data = $this->getTableAll();
		if($data){
			$res = $data;
			$flag = write_file($phpFile, "<?php\r\ndefined('BASEPATH') OR exit('No direct script access allowed');\r\n".'$config[\'payment\'] = '.var_export($res, true).';');
			if($flag){
				$this->load->library('ftp');
				$this->ftp->chmod($phpFile, 0755);
			}
			return $flag;
		}
		return false;
	}
}