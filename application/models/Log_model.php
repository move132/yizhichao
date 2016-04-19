<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_action_log'), $where, $limit, $offset);		
	}
}