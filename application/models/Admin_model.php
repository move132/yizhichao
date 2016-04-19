<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//写入表
	public function insertTable($data = array())
	{
		$data['atime'] = now();
		$data['password'] = $this->setAdminPassword($data['password']);
		return $this->insert_entry($this->getTableName('_admin'), $data);
	}
	
	public function setAdminPassword($password)
	{
		return md5($password);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_admin'), $data, $where);
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_admin'), $where, $limit, $offset, $like);		
	}
	//查询一条记录
	public function getTableOne($where, $select = '*' ){
		return $this->find_entry($this->getTableName('_admin'), $select, $where );
	}
	
	
	
	
}