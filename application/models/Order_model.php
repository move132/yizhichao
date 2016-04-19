<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends My_model {
	public function __construct()
	{
		parent::__construct();
	}
	
	//获取交易次数和金额
	public function getTableCount($field, $where = array())
	{		
		return $this->find_entry($this->getTableName('_order'), $field, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_order'), $where, $limit, $offset);
	}
	
	//查询一条记录
	public function getTableOne($where, $select = '*' ){
		return $this->find_entry($this->getTableName('_order'), $select, $where );
	}
	
	//查询所有关联店铺的订单
	public function getOrderList($where = array(), $limit = NULL, $offset = NULL ){
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_order'), $where, $limit, $offset);
	}
	
	//订单表关联收银员表
	public function getTableListJoinSeller($field = '*', $where = array(), $limit = NULL, $offset = NULL, $order = NULL)
	{
		$data = array();
		$this->db->select($field);
		$this->db->from($this->getTableName('_order'));
		$this->db->join($this->getTableName('_seller'), $this->getTableName('_order').'.seller_id = '.$this->getTableName('_seller').'.id');
		$this->db->where($where);
		! is_null($order) && $this->db->order_by($this->getTableName('_order').'.'.$order, 'desc');
		! is_null($limit) && $this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	//获取对款信息
	public function getRefund($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_refund'), $select, $where );
	}
	
	//判断店长密码是否正确
	public function getTableOne_seller($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_seller'), $select, $where );
	}
	
	//申请退款
	public function refundDone($data)
	{
		//事务开始
		$where = array('order_id' => $data['order_id']);
		$this->db->trans_start ();
		$this->insert_entry($this->getTableName('_refund'), $data);
		$this->update($this->getTableName('_order'), array('status' => 2), $where);
		//事务结束
		$this->db->trans_complete ();
		if ($this->db->trans_status () == false) {
			return false;
		} else {
			return true;
		}
	}
}