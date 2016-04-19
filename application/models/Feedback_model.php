<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function getTableOne($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_feedback'),$select, $where);
	}
	
	//写入表
	public function insertTable($data)
	{
		$data['atime'] = now();
		return $this->insert_entry($this->getTableName('_feedback'), $data);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_feedback'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL ,$like = NULL)
	{
// 		$this->db->order_by($order, 'desc');
		return $this->get_list($this->getTableName('_feedback'), $where, $limit, $offset, $like);		
	}
	
	
	
	
	
	
	
	
}