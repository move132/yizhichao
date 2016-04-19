<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers_model extends My_model {
	public $state_commit = 1;//提交成功
	public $state_yes = 2;//平台同意
	public $state_no = 3;//平台拒绝
	public $state_success = 4;//转账成功
	public $state_fail = 5;//转账失败

	public function __construct()
	{
		parent::__construct();
	}
	
	//获取申请提现数据
	public function getTableCount($field, $where = array())
	{		
		return $this->find_entry($this->getTableName('_offers'), $field, $where);
	}
	
	//商铺提现
	public function addOffers($money, $store){
		$offers_data = array();
		$offers_data['money'] = $money;
		$offers_data['tid'] = makePaySn($this->sid);
		$offers_data['beforemoney'] = $store['money'];  //提现前的金额
		$offers_data['aftermoney'] = $store['money'] - $money; //提现后金额 
		$offers_data['atime'] = now();
		$offers_data['sid'] = $this->sid;
		$offers_data['state'] = $this->state_commit;
		
		$store_data = array();
		if($store['first_offers_time']==0){ //第一次提现
			$store_data['first_offers_time'] = now();
			$store_data['last_offers_time'] = now();
			$store_data['today_offers_num'] = 1;
		}else{
			if(date('d',$store['last_offers_time']) == date('d')){//最后提现时间为今天
				$store_data['last_offers_time'] = now();
				$store_data['today_offers_num'] = $store['today_offers_num'] + 1;
			}else{
				$store_data['last_offers_time'] = now();
				$store_data['today_offers_num'] = 1;
			}
		}
		$store_data['money'] = $offers_data['aftermoney'];//$store['money'] - $money;
		$store_data['frozen_money'] = $store['frozen_money'] + $money;
		$where = array('id' => $this->sid);
		
		$store_money_log_data = array();
		$store_money_log_data['sid'] = $this->sid; 
		$store_money_log_data['money'] = $offers_data['aftermoney'];
		$store_money_log_data['frozen_money'] = $store_data['frozen_money'];
		$store_money_log_data['finish_money'] = $store['finish_money'];
		$store_money_log_data['bill_money'] = $store['bill_money'];
		$store_money_log_data['diffmoney'] = $money;
		$store_money_log_data['mode'] = '1';
		$store_money_log_data['atime'] = now();
		$store_money_log_data['remark'] = formatTime(now()).'申请提现金额￥'.$money.'元,交易号'.$offers_data['tid'];
		//事务开始
		$this->db->trans_start ();
		$this->insert_entry($this->getTableName('_offers'), $offers_data);
		$this->update($this->getTableName('_store'), $store_data, $where);
		$this->insert_entry($this->getTableName('_store_money_log'), $store_money_log_data);
		//事务结束
		$this->db->trans_complete ();
		if ($this->db->trans_status () == false) {
			return false;
		} else {
			return true;
		}
	}
	
	//查询列表
	public function getTableList($where = array(), $limit = NULL, $offset = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_offers'), $where, $limit, $offset);
	}
	
	//提现申请关联店铺表
	public function getTableListJoinStore($field = '*', $where = array(), $limit = NULL, $offset = NULL, $like = NULL)
	{
		$data = array();
		if(isset($where['sid']) && $where['sid'] == 0){
			$_table = '_agent_promoter';
			$_id = 'aid';
		}else{
			$_table = '_store';
			$_id = 'sid';
		}
	
		$this->db->select($field);
		$this->db->order_by($this->getTableName('_offers').'.atime','desc');
		$this->db->from($this->getTableName('_offers'));
		$this->db->join($this->getTableName($_table), $this->getTableName('_offers').'.'.$_id.' = '.$this->getTableName($_table).'.id');
		$this->db->where($where);
		! is_null($like) && $this->db->like($like);
		! is_null($limit) && $this->db->limit($limit, $offset);
		$query = $this->db->get();
		$data['list'] = $query->result_array();
		
		$this->db->from($this->getTableName('_offers'));
		$this->db->join($this->getTableName($_table), $this->getTableName('_offers').'.'.$_id.' = '.$this->getTableName($_table).'.id');
		$this->db->where($where);
		! is_null($like) && $this->db->like($like);
		$data['total'] = $this->db->count_all_results();
		
		return $data;
	}
	
	//更新表
	public function updateTable($data = array(), $where = array())
	{
		if(isset($data['state'])){
			$now = now();
			if($data['state'] == $this->state_no || $data['state'] == $this->state_fail || $data['state'] == $this->state_success){
				$row = $this->getTableOne($where);
				if(! $row){
					return false;
				}
				
				$this->load->model('store_model');
				$store = $this->store_model->getTableOne(array('id' => $row['sid']));
				if(! $store){
					return false;
				}
				
				$store_data = array();
				$store_where = array('id' => $row['sid']);
				
				$store_money_log_data = array();
				$store_money_log_data['sid'] = $row['sid'];
				
				if($data['state'] == $this->state_success){
					$store_data['frozen_money'] = $store['frozen_money'] - $row['money'];
					$store_data['finish_money'] = $store['finish_money'] + $row['money'];
					
					$store_money_log_data['money'] = $store['money'];
					$store_money_log_data['frozen_money'] = $store_data['frozen_money'];
					$store_money_log_data['finish_money'] = $store_data['finish_money'];
					$store_money_log_data['bill_money'] = $store['bill_money'];
					$store_money_log_data['diffmoney'] = $row['money'];
					$store_money_log_data['mode'] = '1';
					$store_money_log_data['atime'] = $now;
					$store_money_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现转账成功'.$row['money'].'元';
					
					$data['otime'] = $now;
				}else{
					$store_data['money'] = $store['money'] + $row['money'];
					$store_data['frozen_money'] = $store['frozen_money'] - $row['money'];
					
					$store_money_log_data['money'] = $store_data['money'];
					$store_money_log_data['frozen_money'] = $store_data['frozen_money'];
					$store_money_log_data['finish_money'] = $store['finish_money'];
					$store_money_log_data['bill_money'] = $store['bill_money'];
					$store_money_log_data['diffmoney'] = $row['money'];
					$store_money_log_data['mode'] = '0';
					$store_money_log_data['atime'] = $now;
					if($data['state'] == $this->state_no){
						$data['stime'] = $now;
						$store_money_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现拒绝退回'.$row['money'].'元';
					}else{
						$data['otime'] = $now;
						$store_money_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现转账失败退回'.$row['money'].'元';
					}
				}
				
				//事务开始
				$this->db->trans_start();
				$this->update($this->getTableName('_offers'), $data, $where);
				$this->update($this->getTableName('_store'), $store_data, $store_where);
				$this->insert_entry($this->getTableName('_store_money_log'), $store_money_log_data);
				//事务结束
				$this->db->trans_complete();
				if($this->db->trans_status() == false){
					return false;
				}else{
					return true;
				}
			}else{
				$data['stime'] = $now;
				return $this->update($this->getTableName('_offers'), $data, $where);
			}
		}
		
		return false;
	}
	
	//查询一条记录
	public function getTableOne($where, $select = '*' ){
		return $this->find_entry($this->getTableName('_offers'), $select, $where );
	}
	
	//查询所有关联店铺的订单
	public function getOrderList($where = array(), $limit = NULL, $offset = NULL ){
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_order'), $where, $limit, $offset);
	}
	
	//查询店铺的日累计，月累计
	public function getMoney($field,$where){
		$this->db->select($field);
		return $this->get_list($this->getTableName('_order'), $where);
	}
	
	//代理/推广员提现更新状态
	public function updateTableAgent($data = array(), $where = array())
	{
		if(isset($data['state'])){
			$now = now();
			if($data['state'] == $this->state_no || $data['state'] == $this->state_fail || $data['state'] == $this->state_success){
				$row = $this->getTableOne($where);
				if(! $row){
					return false;
				}
	
				$this->load->model('agent_promoter_model');
				$agent_promoter = $this->agent_promoter_model->getTableOne(array('id' => $row['aid']));
				if(! $agent_promoter){
					return false;
				}
	
				$agent_promoter_data = array();
				$agent_promoter_where = array('id' => $row['aid']);
	
				$agent_promoter_log_data = array();
				$agent_promoter_log_data['aid'] = $row['aid'];
	
				if($data['state'] == $this->state_success){
					$agent_promoter_data['frozen_money'] = $agent_promoter['frozen_money'] - $row['money'];
					$agent_promoter_data['finish_money'] = $agent_promoter['finish_money'] + $row['money'];
						
					$agent_promoter_log_data['money'] = $agent_promoter['money'];
					$agent_promoter_log_data['frozen_money'] = $agent_promoter_data['frozen_money'];
					$agent_promoter_log_data['finish_money'] = $agent_promoter_data['finish_money'];
					$agent_promoter_log_data['diffmoney'] = $row['money'];
					$agent_promoter_log_data['mode'] = '1';
					$agent_promoter_log_data['atime'] = $now;
					$agent_promoter_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现转账成功'.$row['money'].'元';
						
					$data['otime'] = $now;
				}else{
					$agent_promoter_data['money'] = $agent_promoter['money'] + $row['money'];
					$agent_promoter_data['frozen_money'] = $agent_promoter['frozen_money'] - $row['money'];
						
					$agent_promoter_log_data['money'] = $agent_promoter['money'];
					$agent_promoter_log_data['frozen_money'] = $agent_promoter_data['frozen_money'];
					$agent_promoter_log_data['finish_money'] = $agent_promoter['finish_money'];
					$agent_promoter_log_data['diffmoney'] = $row['money'];
					$agent_promoter_log_data['mode'] = '0';
					$agent_promoter_log_data['atime'] = $now;
					if($data['state'] == $this->state_no){
						$data['stime'] = $now;
						$agent_promoter_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现拒绝退回'.$row['money'].'元';
					}else{
						$data['otime'] = $now;
						$agent_promoter_log_data['remark'] = date('Y-m-d H:i:s',$now).'申请提现转账失败退回'.$row['money'].'元';
					}
				}
	
				//事务开始
				$this->db->trans_start();
				$this->update($this->getTableName('_offers'), $data, $where);
				$this->update($this->getTableName('_agent_promoter'), $agent_promoter_data, $agent_promoter_where);
				$this->insert_entry($this->getTableName('_agent_money_log'), $agent_promoter_log_data);
				//事务结束
				$this->db->trans_complete();
				if($this->db->trans_status() == false){
					return false;
				}else{
					return true;
				}
			}else{
				$data['stime'] = $now;
				return $this->update($this->getTableName('_offers'), $data, $where);
			}
		}
		return false;
	}
	
	//代理、推广员提现
	public function agentAddOffers($money, $agent){
		$offers_data = array();
		$offers_data['money'] = $money;
		$offers_data['tid'] = makePaySn($agent['id']);
		$offers_data['beforemoney'] = $agent['money'];  //提现前的金额
		$offers_data['aftermoney'] = $agent['money'] - $money; //提现后金额
		$offers_data['atime'] = now();
		$offers_data['aid'] = $agent['id'];
		$offers_data['state'] = $this->state_commit;
	
		$agent_data = array();
		if($agent['first_offers_time']==0){ //第一次提现
			$agent_data['first_offers_time'] = now();
			$agent_data['last_offers_time'] = now();
			$agent_data['today_offers_num'] = 1;
		}else{
			if(date('d',$agent['last_offers_time']) == date('d')){//最后提现时间为今天
				$agent_data['last_offers_time'] = now();
				$agent_data['today_offers_num'] = $agent['today_offers_num'] + 1;
			}else{
				$agent_data['last_offers_time'] = now();
				$agent_data['today_offers_num'] = 1;
			}
		}
		$agent_data['money'] = $offers_data['aftermoney'];//$store['money'] - $money;
		$agent_data['frozen_money'] = $agent['frozen_money'] + $money;
		$where = array('id' => $agent['id']);
	
		$agent_money_log_data = array();
		$agent_money_log_data['aid'] = $agent['id'];
		$agent_money_log_data['money'] = $offers_data['aftermoney'];
		$agent_money_log_data['frozen_money'] = $agent_data['frozen_money'];
		$agent_money_log_data['finish_money'] = $agent['finish_money'];
		$agent_money_log_data['diffmoney'] = $money;
		$agent_money_log_data['mode'] = '1';
		$agent_money_log_data['atime'] = now();
		$agent_money_log_data['remark'] = formatTime(now()).'申请提现金额￥'.$money.'元,交易号'.$offers_data['tid'];
		//事务开始
		$this->db->trans_start ();
		$this->insert_entry($this->getTableName('_offers'), $offers_data);
		$this->update($this->getTableName('_agent_promoter'), $agent_data, $where);
		$this->insert_entry($this->getTableName('_agent_money_log'), $agent_money_log_data);
		//事务结束
		$this->db->trans_complete ();
		if ($this->db->trans_status () == false) {
			return false;
		} else {
			return true;
		}
	}
	
	
}