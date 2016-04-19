<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_promoter_model extends My_model {
	public $status_stop = 1;//禁用
	public $status_yes = 1;//正常
	public $status_null = 2;//待审核

	public function __construct()
	{
		parent::__construct();
	}
	
	//店铺入驻地址
	public function getStoreUrl($id)
	{
		return site_url('register/store/index/'.$id);
	}
	
	//推广员入驻地址
	public function getPromoterUrl($id)
	{
		return site_url('register/promoter/index/'.$id);
	}

	public function getAccountPassword($password)
	{
		return md5($password);
	}
	
	//获取入驻店铺数
	public function getTableCount($field, $where = array(), $group = '', $row = true)
	{
		//return $this->get_count($this->getTableName('_agent_promoter'), $where);
		return $this->find_entry($this->getTableName('_agent_promoter'), $field, $where, $group, $row);
	}
	
	//写入表
	public function insertTable($data = array(), &$password)
	{		
		$password = getRegisterPassword();
		$data['atime'] = now();		
		$data['pwd'] = $this->getAccountPassword($password);
		
		return $this->insert_entry($this->getTableName('_agent_promoter'), $data);		
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_agent_promoter'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_agent_promoter'), $where, $limit, $offset, $like);		
	}
	//查询一条记录
	public function getTableOne($where, $select = '*' ){
		return $this->find_entry($this->getTableName('_agent_promoter'), $select, $where );
	}
	
	//生成二维码图片
	public function setScan($type, $id, $file)
	{
		$this->load->library('my_qrcode');
		if($type == 1){//推广员入驻 $parent_id 限代理发展推广员
			$this->my_qrcode->png($this->getPromoterUrl($id), $file);
		}elseif($type == 2){//店铺入驻 $parent_id 0表示代理发展店铺，其他表示推广员发展店铺
			$this->my_qrcode->png($this->getStoreUrl($id), $file);
		}
		return true;
	}
	
	
	
	
}