<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function getTableOne($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_member'),$select, $where);
	}
	
	//写入表
	public function insertTable($data)
	{
		return $this->insert_entry($this->getTableName('_member'), $data);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_member'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL ,$like = NULL)
	{
// 		$this->db->order_by($order, 'desc');
		return $this->get_list($this->getTableName('_member'), $where, $limit, $offset, $like);		
	}
	

	//商铺用户列表关联主用户表
	public function getTableListJoinMember($field = '*', $where = array(), $limit = NULL, $offset = NULL, $order = NULL)
	{
		$data = array();
		$this->db->select($field);
		$this->db->from($this->getTableName('_store_member'));
		$this->db->join($this->getTableName('_member'), $this->getTableName('_store_member').'.mid = '.$this->getTableName('_member').'.id');
		$this->db->where($where);
		! is_null($order) && $this->db->order_by($this->getTableName('_store_member').'.'.$order, 'desc');
		! is_null($limit) && $this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data['list'] = $query->result_array();
	
		$this->db->from($this->getTableName('_store_member'));
		$this->db->join($this->getTableName('_member'), $this->getTableName('_store_member').'.mid = '.$this->getTableName('_member').'.id');
		$this->db->where($where);
		$data['total'] = $this->db->count_all_results();
		return $data;
	}
	
}