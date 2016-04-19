<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//获取消息表的所有内容
	public function getTableOne($where=array(),$select='*')
	{
		$this->db->order_by('id','desc');
		return $this->find_entry($this->getTableName('_seller'),$select, $where);
	}
	
	//写入表
	public function insertTable($data)
	{
		return $this->insert_entry($this->getTableName('_seller'), $data);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_seller'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_seller'), $where, $limit, $offset,$like);		
	}
	
	//删除收银员
	public function deleteSeller($where)
	{
		return $this->db->delete($this->getTableName('_seller'), $where);
	}
	
	//店铺表关联店铺表
	public function getTableListJoinStore($field = '*', $where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$data = array();
	
		$this->db->select($field);
		$this->db->from($this->getTableName('_seller'));
		$this->db->join($this->getTableName('_store'), $this->getTableName('_seller').'.sid = '.$this->getTableName('_store').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data['list'] = $query->result_array();
	
		$this->db->from($this->getTableName('_seller'));
		$this->db->join($this->getTableName('_store'), $this->getTableName('_seller').'.sid = '.$this->getTableName('_store').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$data['total'] = $this->db->count_all_results();
	
		return $data;
	}
	
	
	
	
	
	
}