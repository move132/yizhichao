<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询详情
	public function getTableOne($where)
	{
		return $this->find_entry($this->getTableName('_news'), '*', $where);
	}
	
	//写入表
	public function insertTable($data = array())
	{
		$data['atime'] = now();
		return $this->insert_entry($this->getTableName('_news'), $data);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_news'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL, $like = NULL, $select = '*')
	{
		$this->db->select($select);
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_news'), $where, $limit, $offset,$like);		
	}
	
	//查询，消息列表关联seller_news 表
	public function getTableListJoinSeller($field = '*', $where = array(), $limit = NULL, $offset = NULL, $order = NULL)
	{
		$data = array();
		$this->db->select($field);
		$this->db->from($this->getTableName('_news'));
		$this->db->join($this->getTableName('_news_seller'), $this->getTableName('_order').'.seller_id = '.$this->getTableName('_seller').'.id');
		$this->db->where($where);
		! is_null($order) && $this->db->order_by($this->getTableName('_order').'.'.$order, 'desc');
		! is_null($limit) && $this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	
	
	
}