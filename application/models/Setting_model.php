<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询一条记录
	public function getTableOne($where = array(), $select = '*' ){
		return $this->find_entry($this->getTableName('_setting'), $select, $where);
	}
	
	//查询所有信息
	public function getTableAll($where = array(), $limit = NULL, $offset = NULL)
	{
		$ret = array();
		$data = $this->get_all($this->getTableName('_setting'));
		if($data){
			foreach($data as $item){
				$ret[$item['name']] = $item['value'];
			}
		}
		return $ret;
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_setting'), $data, $where);
	}
	
	//更新地区文件缓存
	public function reflushSetting()
	{
		$this->load->helper('file');
		$phpFile = APPPATH.'config/system.php';
		
		$res = $this->getTableAll();
		if($res){
			$flag = write_file($phpFile, "<?php\r\ndefined('BASEPATH') OR exit('No direct script access allowed');\r\n".'$config[\'system\'] = '.var_export($res, true).';');
			if($flag){
				$this->load->library('ftp');
				$this->ftp->chmod($phpFile, 0755);
			}
			return $flag;
		}
		return false;
	}
}