<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_token_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//写入表
	public function insertTable($data = array())
	{
		return $this->insert_entry($this->getTableName('_app_token'), $data);
	}
	
	//查询一条记录
	public function getTableOne($where = array(), $select = '*' ){
		return $this->find_entry($this->getTableName('_app_token'), $select, $where);
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_app_token'), $data, $where);
	}
	
	//删除记录
	public function deleteTable($where = array())
	{
		if(! $where){
			return false;
		}
		return $this->db->delete($this->getTableName('_app_token'), $where);
	}
	
	public function replaceTable($data = array())
	{
		return $this->db->replace($this->getTableName('_app_token'), $data);
	}
}