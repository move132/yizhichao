<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends My_model {

	public function __construct()
	{
		parent::__construct();
	}
	
	//查询一条记录
	public function getReportStoreOne($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_report_store'), $select, $where );
	}
	
	//查询一条记录
	public function getReportOffersOne($where,$select = '*')
	{
		return $this->find_entry($this->getTableName('_report_offers'), $select, $where );
	}
	
	//写入商铺统计表
	public function insertReportStore($data = array())
	{
		$data['year'] = mdate("%Y");
		$data['month'] = mdate("%m");
		$data['day'] = date('Ymd');
		$data['num'] = 1;
		return $this->insert_entry($this->getTableName('_report_store'), $data);
	}
	
	//写入提现统计表
	public function insertReportOffers($data = array())
	{
		$data['year'] = mdate("%Y");
		$data['month'] = mdate("%m");
		$data['day'] = date('Ymd');
		return $this->insert_entry($this->getTableName('_report_offers'), $data);
	}
	
	//更新商铺统计表
	public function updateReportStore($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_report_store'), $data, $where);
	}
	
	//更新提现统计表
	public function updateReportOffers($data = array(), $where = array())
	{
		return $this->update($this->getTableName('_report_offers'), $data, $where);
	}
	
	
}