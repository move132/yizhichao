<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{
		// $this->my_model->add_action_log($this->router->class.'_'.$this->router->method, "测试信息", $this->aSession['account']);
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '店铺列表', 'url' => '');
		if($this->aSession['account_type'] != 1){
			$this->resData['listHeader']['actions'][] = array('name' => '添加', 'url' => site_url(array($this->router->directory, $this->router->class, 'add')));
		}
		$this->load->config('region');
		$this->resData['region'] = $this->config->item('region');

		$this->load->model('store_model');

		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$select_pid = trim($select_data['pid']);
			$select_name = trim($select_data['name']);
		}
		if(!empty($select_pid) || !empty($select_name) ){
			$like['pid'] = $select_pid;
			$like['name'] = $select_name;
			$this->resData['select']['select_pid'] = $select_pid;
			$this->resData['select']['select_name'] = $select_name;
		}
		if($this->aSession['account_type'] != 1){
			$where['referee_id'] = $this->aSession['data']['id'];
		}
		$data = $this->store_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	//店铺收款码
	public function createStoreQrcode()
	{
		$id = '1';
		$this->load->model('store_model');
		$this->store_model->createStoreQrcode($id, $this->store_model->store_qrcode_url);
	}

	//商铺详情
	public function view(){
		$this->load->helper(array('my', 'template'));
		
		$this->load->config('region');
		$this->resData['region'] = $this->config->item('region');
		
		$this->load->config('store_class');
		$this->resData['store_class'] = $this->config->item('store_class');
		
		$this->load->config('store_category');
		$this->resData['store_category'] = $this->config->item('store_category');
		
		$id = $this->input->get('id');
		$this->load->model('store_model');
		$this->resData['info'] =  $this->store_model->getTableOne(array('id'=>$id));

		$this->load->config('bank');
		$this->resData['bank'] = $this->config->item('bank');
		
		$this->load->view($this->getTemplateFile(), $this->resData);
		
	}
		
	public function add()
	{	
		$this->load->helper('template');
		$this->resData['listHeader']['location'][] = array('name' => '添加店铺', 'url' => '');
		$this->resData['listHeader']['actions'][] = array('name' => '店铺列表', 'url' => site_url(array($this->router->directory, $this->router->class, 'index')));

		$this->load->config('bank');
		$this->resData['bank'] = $this->config->item('bank');
		
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	public function done()
	{
		$this->load->model('store_model');
		$action = $this->input->post('action');
		switch($action){
			case 'insert':
				$this->load->helper(array('my', 'array', 'string'));
				$data = elements(
					array('name', 'region_1', 'region_2', 'region_3', 'addr_info', 'person','sex', 'tel', 'level', 'idc_number', 'card_account', 'card_id', 'bank_id'),
					$this->input->post()
				);
				$data['pid'] = makeStorePID();
				$data['referee_id'] = $this->aSession['data']['id'];
				$password = '';
				$where_seller = array('account' => $data['tel'], 'is_delete' => 0);
				if($this->my_model->find_entry($this->my_model->getTableName('_seller'), $select = 'id', $where_seller)){
					$this->setFailResponse('联系电话已存在');
					echo $this->getResponse();exit;
				}
				if($this->store_model->insertTable($data, $password)){
					$this->load->model('sms_model');
					$flag = $this->sms_model->addStore($data['tel'] ,$data=array('password' => $password,'url'=>'www.baidu.com'));
					if($flag){//记录发短信成功标志

					}else{//记录发短信失败标志

					}

					$this->setSuccessResponse();					
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("添加店铺失败！".$error['message']);
				}
				break;
			case 'status':
				$id = $this->input->post('id');
				$status = $this->input->post('status');				

				if($this->store_model->updateTable(array('status' => $status), array('id' => $id))){
					if($status == 1){//从新or审核开通发短信
						$this->load->helper('string');
						$password = getRegisterPassword();
						$this->load->model('seller_model');
						if($this->seller_model->updateTable(array('pwd' => $this->store_model->getAccountPassword($password)), array('sid' => $id))){
							$this->load->library('sms');
							$flag = $this->sms->send($this->input->post('phone'), $this->sms->getMsg(0, array('password' => $password)));
						}
					}
					
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("操作店铺状态失败！".$error['message']);
				}
				break;
		}
		echo $this->getResponse();
	}

	//店铺分析
	public function report()
	{
		$this->load->helper(array('date','my_date','array', 'template'));
		$this->load->model('store_model');

		$select_data = $this->input->get_post('select');
		$search_type = $select_data['type'];
		$search_year = $select_data['year'];
		$search_month = $select_data['month'];
		$search_week = $select_data['week'];
		if(!$select_data['type']){
			$search_type = 'week';
		}
		if(!$select_data['year']){
			$search_year = date('Y',now());
		}
		if(!$select_data['month']){
			$search_month = date('m',now());
		}
		if(!$select_data['week']){
			$search_week = getWeek_SdateAndEdate();
			$week_time_arr = explode('|', $search_week);
		}else{
			$week_time_arr = explode('|', $search_week);
		}
		//系统配置年月
	    $year_arr = getSystemYearArr($select_data['year']);
	    $month_arr = getSystemMonthArr();
	    $week_arr = getMonthWeekArr($search_year, $search_month);
		switch ($search_type){
			case 'year':
				$this->_year($search_year);
				break;
			case 'month':
				$this->_month($search_month, $search_year);
				break;
			case 'week':
				$this->_week($week_time_arr);
				break;
			case 'day':
				$this->_day($select_data);
				break;
			default:
				break;
		}
	    $this->resData['year_arr'] = $year_arr;
	    $this->resData['month_arr'] = $month_arr;
	    $this->resData['week_arr'] = $week_arr;
	    $this->resData['now_month'] = $search_month;
	    $this->resData['now_year'] = $search_year;
	    $this->resData['now_week'] = $search_week;
	    $this->resData['type'] = $search_type;
	    $this->resData['start_time'] = $select_data['start_time'];
	    $this->resData['end_time'] = $select_data['end_time'];
		echo $this->load->view($this->getTemplateFile(), $this->resData, TRUE);
	}
	
	//月动作，联动周日志
	public function monthToweek()
	{
		$this->load->helper('date', 'my_date');
		$search_year = $this->input->get_post('year');
		$search_month = $this->input->get_post('month');
		$week_arr = getMonthWeekArr($search_year,$search_month);
		$option_str = '';
		if($week_arr){
			foreach ($week_arr as $v){
				$option_str .= '<option value="'.$v['key'].'" >'.$v['val'].'</option>';
			}
			$this->setSuccessResponse($option_str);
		}else{
			$this->setFailResponse("操作异常，请重新请求页面！");
		}
		echo $this->getResponse();
	}

	private function _week($week_time_arr)
	{
		$yAxis_arr_default = array(1,2,3,4,5,6,7);
		$start_day = str_replace('-', '', $week_time_arr[0]);
		$where = array(
			'day >= ' => $start_day,
			'day <= ' => str_replace('-', '', $week_time_arr[1]),
		);   
		$data = $this->store_model->get_all('report_store',$where);
		$this->resData['item'] = $data;
		$yAxis_arr = array();
		if($data){
			foreach ($data as $v){
				$yAxis_arr[getWeek($v['day'])] = $v['num']; 
			}
		}
		$yAxis_arr = elements($yAxis_arr_default,$yAxis_arr,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str = "'".$res."'";
		//整理图表所需数据
		$legend = '';
		$this->resData['subtext'] = '按周统计';
		$this->resData['xAxis'] = getSystemWeekArr();
		$this->resData['yAxis'] =  $yAxis_arr_str;
	}

	private function _month($search_month, $search_year)
	{
		$month_num = days_in_month($search_month,$search_year);//返回某月的天数
		for($i = 1;$i<=$month_num;$i++){
			$month_array[$i] = $i; 
		}
		$xAxis_str = implode("','", $month_array);
		$xAxis = "'".$xAxis_str."'";
		$where = array('year' => $search_year,'month'=>$search_month);
		$data = $this->store_model->get_all('report_store',$where);
		$this->resData['item'] = $data;
		$yAxis_arr = array();
		if($data){
			foreach ($data as $vss){
				$yAxis_arr[intval(substr($vss['day'], 6,2))] = $vss['num'];
			}
		}
		$yAxis_arr = elements($month_array,$yAxis_arr,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str = "'".$res."'";
		$this->resData['subtext'] = '按月统计';
		$this->resData['xAxis'] = $xAxis;
		$this->resData['yAxis'] =  $yAxis_arr_str;
	}

	private function _year($search_year)
	{
		$wheres['year'] = $search_year;
		$data = $this->store_model->get_all('report_store',$wheres);
		$yAxis_arr = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,);
		if($data){
			foreach ($data as $vss){
				$yAxis_arr[intval($vss['month'])] = $yAxis_arr[intval($vss['month'])]+$vss['num'];
			}
		}
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str = "'".$res."'";
		foreach ($yAxis_arr as $k=>$v){
			if(!$v){
				unset($yAxis_arr[$k]);
			}
		}
		$month_str_arr = array('一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');
		$this->resData['month_str_arr'] = $month_str_arr;
		$this->resData['items'] = $yAxis_arr;
		$this->resData['subtext'] = '按年统计';
		$this->resData['xAxis'] = getSystemMonth();
		$this->resData['yAxis'] =  $yAxis_arr_str;
	}

	private function _day($select_data)
	{
		$xAxis_arr_str = '';
		$yAxis_arr_str = '';
		$step = 13*24*3600;//跨年
		if(!$select_data['start_time'] && !$select_data['end_time']){//都没设置  查找当前时间前14天条数据
			$sday = str_replace('-', '', date('Y-m-d',now()));
			$eday = str_replace('-', '', date('Y-m-d',now()-$step));
			$where = array('day >= ' => $sday,'day <=' => $eday);
			$data_day = $this->store_model->get_all('report_store',$where);  
		}elseif(!$select_data['start_time']){//开始时间没有设置   查找结束时间前14天条数据
			$eday = str_replace('/', '', $select_data['end_time']);
			$sday = str_replace('-','',date('Y-m-d',strtotime($eday)-$step));  
			$where = array('day >= ' => $sday,'day <=' => $eday);
			$data_day = $this->store_model->get_all('report_store',$where);
		}elseif(!$select_data['end_time']){//结束时间没有设置  查找开始时间--当前时间的所有数据
			$sday = str_replace('/', '', $select_data['start_time']);
			$eday = str_replace('-', '', date('Y-m-d',strtotime($sday)+$step));
			$where = array('day >= ' => $sday,'day <=' => $eday);
			$data_day = $this->store_model->get_all('report_store',$where);
		}else{//查找开始时间---结束时间的数据
			$where_day_start = str_replace('/', '', $select_data['start_time']);
			$where_day_end = str_replace('/', '', $select_data['end_time']);
			$where = array('day >=' => $where_day_start,'day <=' => $where_day_end);
			$data_day = $this->store_model->get_all('report_store',$where);
		}
		if($data_day){
			foreach ($data_day as $v){
				$yAxis_arr[$v['day']] = $v['num'];
				$xAxis_arr[$v['day']] = substr($v['day'],4,2).'-'.substr($v['day'],6,2);
			}
			ksort($yAxis_arr);
			ksort($xAxis_arr);
			$y_res = implode("','", $yAxis_arr);
			$yAxis_arr_str = "'".$y_res."'";
			$x_res = implode("','", $xAxis_arr);
			$xAxis_arr_str = "'".$x_res."'";
		}
		//整理图表所需数据
		$this->resData['item'] = $data_day;
		$this->resData['subtext'] = '按日统计';
		$this->resData['xAxis'] = $xAxis_arr_str;
		$this->resData['yAxis'] =  $yAxis_arr_str;
	}

	private function test_data_script()
	{
		$aDate = date_range('2015-11-1', mdate("%Y-%m-%d"), true, 'Ymd');
		foreach($aDate as $day){
			$data = array(
				'year' => mdate('%Y', strtotime($day)),
				'month' => mdate('%m', strtotime($day)),
				'day' => $day,
				'rid' => mt_rand(1, 50),
				'num' => mt_rand(1, 1024)
			);
			$this->my_model->insert_entry($this->my_model->getTableName('_report_store'), $data);
		}
	}
	
	//店铺对应的资金流
	public function store_money(){
		$sid = $this->input->get_post('sid');
		$store_name = $this->input->get_post('store_name');
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '资金流列表【'.$store_name.'】', 'url' => '');
		$this->load->model('money_model');
		
		$where = array();
		$where['sid'] = $sid;
		$data = $this->money_model->getTableList($where, $this->per_page, $this->offset);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
				site_url(array($this->router->directory, $this->router->class, $this->router->method)),
				$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
		
		
		
	}
	
	//店铺对应的店员
	public function store_seller(){
		$sid = $this->input->get_post('sid');
		$store_name = $this->input->get_post('store_name');
		
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '店员列表【'.$store_name.'】', 'url' => '');
		
		$this->load->model('seller_model');
		$where = array();
		$where['sid'] = $sid;
		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$select_name = trim($select_data['name']);
			$like['nickname'] = $select_name;
			$this->resData['select']['name'] = $select_name;
		}
	 	$data = $this->seller_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
				site_url(array($this->router->directory, $this->router->class, $this->router->method)),
				$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}
}
