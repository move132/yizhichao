<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Money_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询详情
	public function getTableOne($where)
	{
		return $this->find_entry($this->getTableName('_store_money_log'), '*', $where);
	}
	
	//写入表
	public function insertTable($data = array())
	{
		$data['atime'] = now();
		return $this->insert_entry($this->getTableName('_store_money_log'), $data);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_store_money_log'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL,$like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_store_money_log'), $where, $limit, $offset,$like);		
	}
	
	//店铺资金流表关联店铺表
	public function getTableListJoinStore($field = '*', $where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$data = array();
	
		$this->db->select($field);
		$this->db->from($this->getTableName('_store_money_log'));
		$this->db->join($this->getTableName('_store'), $this->getTableName('_store_money_log').'.sid = '.$this->getTableName('_store').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data['list'] = $query->result_array();
	
		$this->db->from($this->getTableName('_store_money_log'));
		$this->db->join($this->getTableName('_store'), $this->getTableName('_store_money_log').'.sid = '.$this->getTableName('_store').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$data['total'] = $this->db->count_all_results();
	
		return $data;
	}
	
	//店铺资金流表关联代理/推广员表
	public function getTableListJoinAgent($field = '*', $where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$data = array();
		
		$this->db->select($field);
		$this->db->from($this->getTableName('_agent_money_log'));
		$this->db->join($this->getTableName('_agent_promoter'), $this->getTableName('_agent_money_log').'.aid = '.$this->getTableName('_agent_promoter').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->order_by($this->getTableName('_agent_money_log').'.atime','desc');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data['list'] = $query->result_array();
	
		$this->db->from($this->getTableName('_agent_money_log'));
		$this->db->join($this->getTableName('_agent_promoter'), $this->getTableName('_agent_money_log').'.aid = '.$this->getTableName('_agent_promoter').'.id');
		$this->db->where($where);
		$this->db->like($like);
		$data['total'] = $this->db->count_all_results();
	
		return $data;
	}
	
	
}