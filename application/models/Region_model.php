<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Region_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		return $this->get_list($this->getTableName('_region'), $where, $limit, $offset);		
	}
	
	//写入表
	public function insertTable($data = array())
	{
		return $this->insert_entry($this->getTableName('_region'), $data);
	}
	
	public function getAreaByIds($class_parent_id)
	{
		$where = array('key' => 'class_id', 'val' => explode('-', $class_parent_id));
		return $this->get_rows($this->getTableName('_region'), $where, 'in');
	}
	
	//更新地区文件缓存
	public function reflushRegion()
	{
		$this->load->helper('file');
		$jsFile = FCPATH.'static/js/region.js';
		$appFile = FCPATH.'static/js/region_app.js';
		$phpFile = APPPATH.'config/region.php';
		
		$data = $this->get_all($this->getTableName('_region'));
		if($data){
			$res = array();
			foreach($data as $item){
				$res[$item['class_parent_id']][$item['class_id']] = $item['class_name'];//array('class_id' => $item['class_id'], 'class_name' => $item['class_name']);
			}
			$flag = write_file($jsFile, 'var regions = '.json_encode($res));
			$flag = $flag && write_file($phpFile, "<?php\r\ndefined('BASEPATH') OR exit('No direct script access allowed');\r\n".'$config[\'region\'] = '.var_export($res, true).';');
			if($flag){//提供app地区库文件
				$appData = array();
				
				foreach($data as $key=>$item){//1级
					if($item['class_type'] == 1){
						$appData[] = array('value' => $item['class_id'], 'text' => $item['class_name'], 'children' => array());
						unset($data[$key]);
					}
				}
				
				foreach($appData as $k=>$row){//2级
					foreach($data as $key=>$item){
						if($row['value'] == $item['class_parent_id']){
							$appData[$k]['children'][] = array('value' => $item['class_id'], 'text' => $item['class_name'], 'children' => array());
							unset($data[$key]);
						}
					}
				}
				
				foreach($data as $key=>$item){//3级
					foreach($appData as $k=>$row){
						if($row['children']){
							foreach($row['children'] as $i=>$arr){
								if($arr['value'] == $item['class_parent_id']){
									$appData[$k]['children'][$i]['children'][] = array('value' => $item['class_id'], 'text' => $item['class_name']);
								}
							}
						}
					}
				}
				write_file($appFile, 'module.exports = '.json_encode($appData));
			}
			return $flag;
		}
		return false;
	}
}