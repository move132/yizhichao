<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//添加商品分类
	public function insertCategory($data = array())
	{
		$re =  $this->insert_entry($this->getTableName('_goods_category'), $data);
		if($re){
			return $this->insert_id();
		}else{
			return false;
		}
	}
	
	//获取一条记录
	public function getTableOne($table,$where,$select = '*')
	{
		return $this->find_entry($this->getTableName($table),$select, $where);
	}
	
	//添加商品
	public function insertGoods($data = array())
	{
		return $this->insert_entry($this->getTableName('_goods'), $data);
	}
	
	//查询所有分类列表
	public function getCategoryList($where = array(), $limit = NULL, $offset = NULL)
	{
		return $this->get_list($this->getTableName('_goods_category'), $where, $limit, $offset);		
	}
	
	//查询所有商品
	public function getGoodsList($where = array(), $limit = NULL, $offset = NULL)
	{
		return $this->get_list($this->getTableName('_goods'), $where, $limit, $offset);
	}
	
	public function updateCategoryTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_goods_category'), $data, $where);
	}
	
	public function updateGoodsTable($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_goods'), $data, $where);
	}
	
	//删除商品
	public function delete($table,$where)
	{
		$this->db->where($where);
		return $this->db->delete($this->getTableName($table));
	
	}
	
}