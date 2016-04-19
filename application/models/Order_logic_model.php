<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_logic_model extends My_model {
	public $atime;

	public $payment;   		//商户的公众号，支付宝，百度，京东信息

	public $sid;			//商铺ID
	public $seller_id;		//收银员ID

	public $money;			//支付金额（单位元）
	public $real_money;      //商户实际收款金额
    public $fee = 0;            //平台手续费

    public $pay_sn;         //支付订单号
    public $trade_id;       //第三方支付单号
    public $uuid;           //第三方唯一标识符
    
    public $store;          //商铺的基本信息
	
	// public $access_token;
	public $member = array();

	public function __construct()
	{
		parent::__construct();
		$this->atime = now();
	}

	public function wechat($post_data_arr)
	{
		if($post_data_arr['return_code'] == 'SUCCESS'){
        	if($post_data_arr['result_code'] == 'SUCCESS'){
                $sign = $post_data_arr['sign'];
                unset($post_data_arr['sign']);
                $signture = getWechatSign($this->payment['config']['mch_key'], $post_data_arr);
                if($sign == $signture){
                    if(strpos($post_data_arr['attach'], '_') === false){//店铺扫码
                        $this->sid = $post_data_arr['attach'];
                        $this->seller_id = 0;
                    }else{
                        list($this->sid, $this->seller_id) = explode('_', $post_data_arr['attach']);
                    }

        			$this->money = $post_data_arr['total_fee'] / 100;//元
                    $this->fee = $this->payment['fee'];
        			
        			$this->pay_sn = $post_data_arr['out_trade_no'];
        			$this->trade_id = $post_data_arr['transaction_id'];
        			$this->uuid = $post_data_arr['openid'];

                    if(strtoupper($post_data_arr['is_subscribe']) == 'Y'){//关注则获取会员信息
                        $this->load->library('wechat', $this->payment);
                        $this->load->driver('cache');
                        $access_token = $this->cache->file->get('wechat_access_token');
                        if(! $access_token){
                            $access_token = $this->wechat->get_weixin_access_token();
                            if(! $access_token || ! $this->cache->file->save('wechat_access_token', $access_token, 7000)){//获取授权码失败
                                $this->setMember($this->member);
                            }
                        }
                        $this->member = $this->wechat->get_userinfo_by_openid($this->uuid, $access_token);
                        if(! $this->member){
                            $this->setMember($this->member);
                        }
                    }else{
                        $this->setMember($this->member);
                    }
                    
	            	if($this->init()){//更新成功
						return true;	            		
	            	}else{
	            		log_message('error', '更新订单交易状态失败:'.var_dump($post_data_arr, true));
						return false;
	            	}	
        		}else{
        			log_message('error', '签名认证失败:【signture='.$signture.'】'.var_dump($post_data_arr, true));
					return false;
        		}
        	}else{
        		log_message('error', var_dump($post_data_arr, true));
				return false;
        	}
        }else{
        	log_message('error', var_dump($post_data_arr, true));
			return false;
        }
	}

	public function alipay($post_data_arr)
	{
		if(! is_null($post_data_arr['trade_status'])){
            if($post_data_arr['trade_status'] == 'TRADE_SUCCESS' || $post_data_arr['trade_status'] == 'TRADE_FINISHED'){
                $sign = $post_data_arr['sign'];
                unset($_POST['sign']);
                unset($_POST['sign_type']);
                ksort($_POST);
                if(rsaVerify(arrayToKSortstring($_POST), APPPATH . '/config/certificate/alipay/alipay_rsa_public_key.pem', $sign)){
                    list($this->sid, $this->seller_id, $this->pay_sn) = explode('_', $post_data_arr['out_trade_no']);

                    $this->money = $post_data_arr['total_fee'];//元
                    $this->fee = $this->payment['fee'];
                    
                    $this->trade_id = $post_data_arr['trade_no'];
                    $this->uuid = $post_data_arr['buyer_id'];

                    $this->member['nickname'] = $post_data_arr['buyer_email'];
                    $this->setMember($this->member);

                    if($this->init()){//更新成功
                        echo 'success';
                    }else{
                        log_message('error', '更新订单交易状态失败:'.var_dump($post_data_arr, true));
                    }
                }else{
                    log_message('error', '签名校验失败：'.var_dump($post_data_arr, true));
                }
            }										
        }else{
            log_message('error', '参数异常：'.var_dump($post_data_arr, true));
        }
	}

	//初始化为授权用户信息
    private function setMember(&$member)
    {
        $member = array(
            'nickname' => '',
            'sex' => 0,
            'headimgurl' => '',
            'email' => ''
        );
    }

	private function init()
	{
		$this->real_money = $this->money - $this->money * $this->fee;

		//事务开始
		$this->db->trans_start();
		
		$this->insertOrder();//记录交易信息
		$this->addStoreMoney();//店铺变更资金
		$this->insertStoreMember();		
		$this->addRefereeMoney();//代理/推广员分佣数据	
		$this->_reportPay();	

		//事务结束
		$this->db->trans_complete();
		if($this->db->trans_status() == false){
			return false;
		}else{
			return true;
		}
	}
	
	private function insertOrder()
	{
		$order_data = array(
			'pay_sn' => $this->pay_sn,
			'sid' => $this->sid,
			'seller_id' => $this->seller_id,
			'payment' => $this->payment['code'],
			'money' => $this->money,
			'real_money' => $this->real_money,
			'fee' => $this->fee,
			'uuid' => $this->uuid,
			'trade_id' => $this->trade_id,
			'atime' => $this->atime
		);
		$this->insert_entry($this->getTableName('_order'), $order_data);
	}
	
	private function addStoreMoney()
	{
		//更新店铺可提现金额数据，和变动商铺金额日志数据
		$store_where = array('id' => $this->sid);
    	$select = 'money,frozen_money,finish_money,bill_money,referee_id';
		$this->store = $this->find_entry($this->getTableName('_store') ,$select, $store_where);
		
		$store_money_log_data = array();
    	$store_money_log_data['sid'] = $this->sid;    	
    	$store_money_log_data['mode'] = '0';
    	$store_money_log_data['atime'] = $this->atime;
    	$store_money_log_data['remark'] = formatTime($this->atime).'交易订单金额￥'.$this->money.'元,手续费【'.$this->fee.'】,实收金额￥.'.$this->real_money.'交易号'.$this->pay_sn;
		
		$store_data = array('money' => $this->store['money'] + $this->real_money);
		$this->update($this->getTableName('_store'), $store_data, $store_where);
		$store_money_log_data['money'] = $store_data['money'];
		$store_money_log_data['frozen_money'] = $this->store['frozen_money'];
		$store_money_log_data['finish_money'] = $this->store['finish_money'];
		$store_money_log_data['bill_money'] = $this->store['bill_money'];
    	$store_money_log_data['diffmoney'] = $this->real_money;
		$this->insert_entry($this->getTableName('_store_money_log'), $store_money_log_data);
	}

    //返回平台，商铺用户信息
    private function insertStoreMember()
    {
		$member_where = array('mode' => $this->payment['mode'], 'uuid' => $this->uuid);
		$member_info = $this->find_entry($this->getTableName('_member'), '*', $member_where);
		if($member_info){
			$mid = $member_info['id'];
			if(mdate("%Y%m%d", $member_info['last_time']) != mdate("%Y%m%d")){//获取用户信息
				
			}
			$member_data = array(
				'name' => $this->member['nickname'],
				'sex' => $this->member['sex'],
				'header' => $this->member['headimgurl'],
				'money' => $member_info['money'] + $this->money,
				'num' => $member_info['num'] + 1,
				'last_time' => $this->atime,
			);
			$this->update($this->getTableName('_member'), $member_data, $member_where);
		}else{
			$member_data = array(
				'mode' => $this->payment['mode'],
				'uuid' => $this->uuid,
				'name' => $this->member['nickname'],
				'sex' => $this->member['sex'],
				'header' => $this->member['headimgurl'],
				'money' => $this->money,
				'num' => 1,
				'first_time' => $this->atime,
				'last_time' => $this->atime,
			);
			$this->insert_entry($this->getTableName('_member'), $member_data);
			$mid = $this->insert_id();
			//新用户添加数据到统计表
			$this->_reportMember();
		}
		
		//更新店铺会员表
		$store_member_where = array('sid' => $this->sid, 'mid' => $mid);
		$store_member_info = $this->find_entry($this->getTableName('_store_member'), '*', $store_member_where);
		if($store_member_info){
			$store_member_data = array(
				'money' => $store_member_info['money'] + $this->money,
				'num' => $store_member_info['num'] + 1,
				'last_time' => $this->atime,
			);
			$this->update($this->getTableName('_store_member'), $store_member_data, $store_member_where);
		}else{
			$store_member_data = array(
				'sid' => $this->sid,
				'mid' => $mid,
				'money' => $this->money,
				'num' => 1,
				'first_time' => $this->atime,
				'last_time' => $this->atime,
			);
			$this->insert_entry($this->getTableName('_store_member'), $store_member_data);
		}
    }

    //代理/推广员分佣数据
    private function addRefereeMoney()
    {
    	$this->load->config('system');
    	$system = $this->config->item('system');
    	$min_commission = floatval($system['min_commission']);
    	
		$fee = 0;
		$referee_id = $this->store['referee_id'];
		$select = 'id , promoter_fee, store_fee, parent_id , money, frozen_money, finish_money';
    	$agent_where = array('id' => $referee_id);
		$agent_promoter = $this->find_entry($this->getTableName('_agent_promoter'), $select, $agent_where);
		
    	if($agent_promoter['parent_id']){//推广员
    		$agent_data = array();
			$fee = $agent_promoter['store_fee'];
    		$agent_data['money'] = $agent_promoter['money'] + $this->money * $fee;
    		if($min_commission){//最低分佣金额判断
    			if($min_commission <= $agent_data['money']){
    				$this->update($this->getTableName('_agent_promoter'), $agent_data, $agent_where);
		    		//推广员分佣日志数据
		    		$agent_data_log = array();
					$agent_data_log['aid'] = $agent_promoter['id'];
					$agent_data_log['money'] = $agent_data['money'];
					$agent_data_log['frozen_money'] = $agent_promoter['frozen_money'];
					$agent_data_log['finish_money'] = $agent_promoter['finish_money'];
					$agent_data_log['diffmoney'] = $this->money * $fee;
					$agent_data_log['mode'] = '0';
					$agent_data_log['atime'] = $this->atime;
					$agent_data_log['remark'] = formatTime($this->atime).'交易订单金额￥'.$this->money.',分佣比例'.$fee.',分佣金额￥'.$agent_data_log['diffmoney'].'元,交易号'.$this->pay_sn;
					$this->insert_entry($this->getTableName('_agent_money_log'), $agent_data_log);		
		    		
		    		//上级代理分佣
					$referee_id = $agent_promoter['parent_id'];
					$agent_where = array('id' => $referee_id);
		    		$agent_promoter = $this->find_entry($this->getTableName('_agent_promoter'), $select, $agent_where);
    			}
    		}
    	}
		//代理 ，直接分佣
		$fee = $agent_promoter['promoter_fee'] - $fee;
		$agent_data = array();
		$agent_data['money'] = $agent_promoter['money'] + $this->money * $fee;
		if($min_commission){//最低分佣金额判断
			if($min_commission > $agent_data['money']){
				return true;
			}
		}
		$this->update($this->getTableName('_agent_promoter'), $agent_data, $agent_where);
		//分佣日志数据
		$agent_data_log = array();
		$agent_data_log['aid'] = $agent_promoter['id'];
		$agent_data_log['money'] = $agent_data['money'];
		$agent_data_log['frozen_money'] = $agent_promoter['frozen_money'];
		$agent_data_log['finish_money'] = $agent_promoter['finish_money'];
		$agent_data_log['diffmoney'] = $this->money * $fee;
		$agent_data_log['mode'] = '0';
		$agent_data_log['atime'] = $this->atime;
		$agent_data_log['remark'] = formatTime($this->atime).'交易订单金额￥'.$this->money.',分佣比例'.$fee.',分佣金额￥'.$agent_data_log['diffmoney'].'元,交易号'.$this->pay_sn;
		$this->insert_entry($this->getTableName('_agent_money_log'), $agent_data_log);
    }
    
    //更新用户统计数据表
    private function _reportMember()
    {
    	$where_report = array('day' => date('Ymd'), 'mode' => $this->payment['mode']);
		if($report = $this->find_entry($this->getTableName('_report_member'), '*', $where_report)){
			$report_data = array('num' => $report['num'] + 1);
			$this->update($this->getTableName('_report_member'), $report_data, $where_report);
		}else{
			$report_data = array('year' => mdate('%Y'), 'month' => mdate('%m'), 'day' => date('Ymd'), 'num' => 1, 'mode' => $this->payment['mode']);
			$this->insert_entry($this->getTableName('_report_member'), $report_data);
		}
    }
    
    //更新支付统计表
    private function _reportPay()
    {
    	$where_report = array('day' => date('Ymd'), 'sid' => $this->sid);
    	if($report = $this->find_entry($this->getTableName('_report_pay'), '*', $where_report)){
    		$report_data = array('num' => $report['num'] + 1, 'total' => $report['total'] + $this->money);
    		$this->update($this->getTableName('_report_pay'), $report_data, $where_report);
    	}else{
    		$report_data = array('year' => mdate('%Y'), 'month' => mdate('%m'), 'day' => date('Ymd'), 'sid' =>$this->sid , 'num' => 1, 'total' => $this->money);
    		$this->insert_entry($this->getTableName('_report_pay'), $report_data);
    	}
    }
    
}