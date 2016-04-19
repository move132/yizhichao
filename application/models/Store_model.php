<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends My_model {
	public $status_stop = 1;//禁用
	public $status_yes = 1;//正常
	public $status_null = 2;//待审核
	public $store_qrcode_url = '';//店铺收款码地址

	public function __construct()
	{
		parent::__construct();
	}

	public function getAccountPassword($password)
	{
		return md5($password);
	}
	
	//获取入驻店铺数
	public function getTableCount($field, $where = array(), $group = '', $row = true)
	{
		//return $this->get_count($this->getTableName('_store'), $where);
		return $this->find_entry($this->getTableName('_store'), $field, $where, $group, $row);
	}
	
	//写入表
	public function insertTable($data = array(), &$password)
	{		
		$password = getRegisterPassword();
		$data['atime'] = now();
		// 事物开始
		$stor_data = $data;
		$seller_data =array() ;
		$seller_data['sex'] = $data['sex'];
		$seller_data['atime'] = $data['atime'];
		$seller_data['nickname'] = $data['tel'];
		$seller_data['account'] = $data['tel'];
		$seller_data['pwd'] = $this->getAccountPassword($password);
		$seller_data['shopowner'] = '1';
	    unset($stor_data['sex']);
		$this->db->trans_start ();
		$this->insert_entry($this->getTableName('_store'), $stor_data);
		$seller_data['sid'] =  $this->insert_id();
		$this->insert_entry($this->getTableName('_seller'), $seller_data);
		//事务结束
		$this->db->trans_complete ();
		if ($this->db->trans_status () == false) {
			return false;
		} else {			
			return true;
		}
		
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_store'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_store'), $where, $limit, $offset ,$like);		
	}
	//查询一条记录
	public function getTableOne($where, $select = '*' ){
		return $this->find_entry($this->getTableName('_store'), $select, $where );
	}
	
	//生成门店二维码
	public function createStoreQrcode($id, $url)
	{
		$file_name = $this->_getScanFileName($id);
		$file = PHPQRCODE_STORE.$file_name;
		if(file_exists(FCPATH.$file)){
			return true;
		}
		$this->load->library('my_qrcode');

		$this->my_qrcode->png($url, $file);
		return true;
	}
	
	private function _getScanFileName($id)
	{
		$file_name = 'qrcode_store_'.$id.'.png';

		return $file_name;
	}
	
	//测试添加数据
	public function insertOrder($seller_data)
	{
		$this->insert_entry($this->getTableName('_order'), $seller_data);
	}
	
	
	
	
	
	
	
}