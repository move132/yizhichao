<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_model extends CI_Model {
	protected $_news = 'news';//平台消息表
	protected $_news_seller = 'news_seller';//店员产科消息表
	protected $_news_agent = 'news_agent';//店员产科消息表
	protected $_store_money_log = 'store_money_log';//店铺资金明细表
	protected $_store = 'store';//店铺表
	protected $_store_member = 'store_member';//店铺用户表
	protected $_sessions = 'sessions';//登陆session表
	protected $_seller = 'seller';//店员表
	protected $_offers = 'offers';//提现明细表
	protected $_offers_run = 'offers_run';//提现队列
	protected $_member = 'member';//会员表
	protected $_goods_category = 'goods_category';//商品分类表
	protected $_goods = 'goods';//商品表
	protected $_order = 'order';//订单表
	protected $_region = 'region';//地区表
	protected $_action_log = 'action_log';//操作日志表
	protected $_app_token = 'app_token';//app接口授权表
	protected $_setting = 'setting';//app接口授权表
	protected $_bank = 'bank';//银行表
	protected $_admin = 'admin';//系统管理员表
	protected $_payment = 'payment';//支付配置表
	protected $_agent_promoter = 'agent_promoter';//代理/推广员表
	protected $_agent_money_log = 'agent_money_log';//代理/推广员资金日志表
	protected $_report_member = 'report_member';//用户分析表
	protected $_report_offers = 'report_offers';//提现分析表
	protected $_report_pay = 'report_pay';//支付分析表
	protected $_report_store = 'report_store';//店铺分析表
	protected $_feedback = 'feedback';//反馈表
	protected $_refund = 'refund';//退款表
	protected $_store_category = 'store_category';//经营类目
	protected $_store_class = 'store_class';//店铺类型

	public function __construct()
	{
		parent::__construct();
	}

	public function getTableName($_table)
	{
		return $this->$_table;
	}

	public function error()
	{
		return $this->db->error();
	}


	/* 查询辅助函数 */

	//当执行 INSERT 语句时，这个方法返回新插入行的ID
	public function insert_id()
	{
		return $this->db->insert_id();
	}

	//当执行 INSERT、UPDATE 等写类型的语句时，这个方法返回受影响的行数
	public function affected_rows()
	{
		return $this->db->affected_rows();
	}

	//该方法返回上一次执行的查询语句（是查询语句，不是结果）
	public function last_query()
	{
		return $this->db->last_query();
	}

	//该方法用于获取数据表的总行数，第一个参数为表名
	public function count_all($table)
	{
		return $this->db->count_all($table);
	}

	//该方法输出你正在使用的数据库平台（MySQL，MS SQL，Postgres 等）
	public function platform()
	{
		return $this->db->platform();
	}

	//该方法输出你正在使用的数据库版本
	public function version()
	{
		return $this->db->version();
	}


	/* 查询构造器类 */
	/*public function select($table, $limit = NULL, $offset = NULL)
	{
		$this->db->select($field);
		$query = $this->db->get($table, $limit, $offset);
		return $query->result_array();
	}*/

	/* 查询记录
	 * @ $row默认单条【false多条】
	 */
	public function find_entry($table, $select = '*', $where = array(), $group = '', $row = true)
	{
		$this->db->select($select);
		$this->db->where($where);
		if(!empty($group)){
			$this->db->group_by($group);
		}
		$query = $this->db->get($table);
		if($row){
			return $query->unbuffered_row('array');
		}else{
			return $query->result_array();
		}
		
	}
	
	//更新表
	public function update($table, $data, $where)
	{
		return $this->db->update($table, $data, $where);
	}
	
	//该方法执行 SELECT 语句并返回查询结果，可以得到一个表的所有数据
	public function get_all($table, $where = NULL, $order = NULL, $limit = NULL, $offset = NULL)
	{
		if($where){
			$this->db->where($where);
		}
		if($order){
			$this->db->order_by($order, 'desc');
		}
		$query = $this->db->get($table, $limit, $offset);
		return $query->result_array();
	}
	
	//写入表
	public function insert_entry($table, $data = array())
	{
		return $this->db->insert($table, $data);
	}
	
	//查询满足条件的记录数
	public function get_count($table, $where = array(),$like = NULL)
	{
		$this->db->where($where);
		if($like){
			$this->db->like($like);
		}
		return $this->db->count_all_results($table);
	}
	
	public function get_rows($table, $where = array(), $type = '=', $limit = NULL, $offset = NULL, $like = NULL)
	{
		if($type == 'in'){
			$this->db->where_in($where['key'], $where['val']);
		}elseif($type == 'or'){
			
		}else{
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		$query = $this->db->get($table, $limit, $offset);
		return $query->result_array();
	}
	
	//查询列表
	public function get_list($table, $where = array(), $limit = NULL, $offset = NULL,$like= NULL)
	{
		$data = array();
		
		$data['list'] = $this->get_rows($table, $where, '=', $limit, $offset, $like);
		$data['total'] = $this->get_count($table, $where ,$like);
		
		return $data;
	}
	
	public function replace_table($table, $data = array())
	{
		return $this->db->replace($table, $data);
	}
	
	/**
	 * 记录管理员操作信息
	 * @$action 操作动作[eg:class_method]
	 * @remark 备注信息
	 * @account 操作账号
	 */
	public function add_action_log($action, $remark, $account)
	{
		$data = array(
			'action' => $action,
			'remark' => $remark,
			'atime' => now(),
			'account' => $account
		);
		return $this->insert_entry($this->getTableName('_action_log'), $data);
	}
	/**
	 *	查询管理员操作信息列表
	 */
	public function get_action_log_list($where = array(), $limit = NULL, $offset = NULL,$like = NULL)
	{
		$this->db->order_by('atime', 'desc');
		return $this->get_list($this->getTableName('_action_log'), $where, $limit, $offset , $like);
	}
}