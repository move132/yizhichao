<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//excel导入导出
	public function excel()
	{
		$offers_type = $this->input->get('offers_type');//提现类型【店铺，代理/推广员】
		$offers_status = $this->input->get('offers_status');//提现状态【导入，导出】
		switch($offers_type){
			case 'store':
				if($offers_status == 'import'){
					$this->store_import();
				}elseif($offers_status == 'export'){
					$this->store_export();
				}
				break;
			case 'agent':
				if($offers_status == 'import'){
					$this->agent_import();
				}elseif($offers_status == 'export'){
					$this->agent_export();
				}
				break;
		}

		/*$headArr = array("序号","登录账号","登陆密码","姓名","邮箱","添加时间","登录次数","最后一次登录","登录时间","状态");
        $data = $this->my_model->get_all($this->my_model->getTableName('_admin'));

        $this->load->library('my_excel');
        // $this->my_excel->writerExcel($headArr, $data, '北大');exit();
        $file = FCPATH.'2016_04_01 (2).xlsx';        
        var_export($this->my_excel->readerExcel($file));*/
	}

	//店铺提现批量导入
	private function store_import()
	{
		echo 'store_import';
	}

	//店铺提现批量导出
	private function store_export()
	{
		$this->load->model('offers_model');
		$headArr = array(
			"申请号", "提现金额", "申请时间",
			"商户号", "商户名称", "详细地址", "联系人", "联系电话",
			"开卡账户", "开卡账号"
		);
		$where = array('aid ' => 0, 'state' => 1);
		$field  = $this->offers_model->getTableName('_offers').'.tid,'.$this->offers_model->getTableName('_offers').'.money';
		$field .= ','.$this->offers_model->getTableName('_offers').'.atime,'.$this->offers_model->getTableName('_store').'.pid';
		$field .= ','.$this->offers_model->getTableName('_store').'.name,'.$this->offers_model->getTableName('_store').'.addr_info';
		$field .= ','.$this->offers_model->getTableName('_store').'.person,'.$this->offers_model->getTableName('_store').'.tel';
		$field .= ','.$this->offers_model->getTableName('_store').'.card_account,'.$this->offers_model->getTableName('_store').'.card_id';

        $data = $this->offers_model->getTableListJoinStore($field, $where);

        $this->load->library('my_excel');
        $this->my_excel->writerExcel($headArr, $data['list'], '店铺提现未审核');
	}

	//代理/推广员提现批量导入
	private function agent_import()
	{
		echo 'agent_import';
	}

	//代理/推广员提现批量导出
	private function agent_export()
	{
		$this->load->model('offers_model');
		$headArr = array(
			"申请号", "提现金额", "申请时间",
			"代理/推广员", "详细地址", "联系电话",
			"开卡账户", "开卡账号"
		);
		$where = array('sid' => 0, 'state' => 1);
		$field  = $this->offers_model->getTableName('_offers').'.tid,'.$this->offers_model->getTableName('_offers').'.money';
		$field .= ','.$this->offers_model->getTableName('_offers').'.atime,'.$this->offers_model->getTableName('_agent_promoter').'.name';
		$field .= ','.$this->offers_model->getTableName('_agent_promoter').'.addr_info,'.$this->offers_model->getTableName('_agent_promoter').'.phone';
		$field .= ','.$this->offers_model->getTableName('_agent_promoter').'.card_account,'.$this->offers_model->getTableName('_agent_promoter').'.card_id';

        $data = $this->offers_model->getTableListJoinStore($field, $where);

        $this->load->library('my_excel');
        $this->my_excel->writerExcel($headArr, $data['list'], '代理/推广员提现未审核');
	}

	//初始化页面
	public function index()
	{
		// set_time_limit(0);$this->test_offers_script();exit();
		$this->load->helper('template');
		$this->resData['listHeader']['location'][] = array('name' => '提现列表', 'url' => '');

		$this->load->model('offers_model');
		$where = array();
		$like = array();
		$select_data = $this->input->get('select');
		
		if(is_null($select_data)){
			$where['aid'] = 0;
			$select_data = array('offers_type' => 'store');//默认店铺
		}else{
			switch($select_data['offers_type']){
				case 'store':
					$where['aid'] = 0;
					break;
				case 'agent':
					$where['sid'] = 0;
					break;
				default :
					$this->load->view($this->getTemplateFile(), $this->resData);
					break;
			}

			if(isset($select_data['keyword']) && $keyword = trim($select_data['keyword'])){
				$like['name'] = $keyword;
			}

			if(isset($select_data['time_start']) && ! empty($select_data['time_start'])){
				$where[$this->offers_model->getTableName('_offers').'.atime >'] = strtotime($select_data['time_start']);
			}
			if(isset($select_data['time_end']) && ! empty($select_data['time_end'])){
				$where[$this->offers_model->getTableName('_offers').'.atime <'] = strtotime($select_data['time_end']);
			}
		}

		$this->resData['select'] = $select_data;

		if($select_data['offers_type'] == 'agent'){
			$field = $this->offers_model->getTableName('_offers').'.*,'.$this->offers_model->getTableName('_agent_promoter').'.name';
			$field .= ','.$this->offers_model->getTableName('_agent_promoter').'.phone';
			$field .= ','.$this->offers_model->getTableName('_agent_promoter').'.frozen_money,'.$this->offers_model->getTableName('_agent_promoter').'.finish_money';
		}else{			
			$field = $this->offers_model->getTableName('_offers').'.*,'.$this->offers_model->getTableName('_store').'.name';
			$field .= ','.$this->offers_model->getTableName('_store').'.person,'.$this->offers_model->getTableName('_store').'.tel';
			$field .= ','.$this->offers_model->getTableName('_store').'.frozen_money,'.$this->offers_model->getTableName('_store').'.finish_money';
		}

		$data = $this->offers_model->getTableListJoinStore($field, $where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	private function test_offers_script()
	{
		for($i = 0; $i < 1000; $i++){
			$id = mt_rand(1, 2);
			$data = array(
				'money' => mt_rand(10, 10000),
				'atime' => mt_rand(strtotime('-365day'), now()),
				'sid' => ($id == 1) ? mt_rand(1, 2) : 0,
				'aid' => ($id == 2) ? mt_rand(1, 2) : 0,
				'state' => mt_rand(1, 5),
			);
			$data['tid'] = makePaySn($data['sid']);
			$data['beforemoney'] = $data['money'] + mt_rand(1, 1000);
			$data['aftermoney'] = $data['beforemoney'] - $data['money'];
			if($data['state'] == 2 || $data['state'] == 3){
				$data['stime'] = $data['atime'] + mt_rand(0, 5) * 3600 * 24;
			}
			if($data['state'] == 4 || $data['state'] == 5){
				$data['stime'] = $data['atime'] + mt_rand(0, 5) * 3600 * 24;
				$data['otime'] = $data['stime'] + mt_rand(0, 1000000);
			}

			$this->my_model->insert_entry($this->my_model->getTableName('_offers'), $data);
		}
	}

	public function done()
	{
		$this->load->model('offers_model');
		$action = $this->input->post('action');
		switch($action){
			case 'state':
				$id = $this->input->post('id');
				$status = $this->input->post('status');
				$updateArray = array('state' => $this->offers_model->state_no);
				$action_log = '提现操作---平台拒绝/商铺ID-'.$id;
				// $updateArray = array('state' => $this->offers_model->state_fail);
				if($status == 'yes'){
					$updateArray['state'] = $this->offers_model->state_yes;
					$action_log = '提现操作---平台同意/商铺ID-'.$id;
					// $updateArray['state'] = $this->offers_model->state_success;
				}
				if($this->offers_model->updateTable($updateArray, array('id' => $id, 'state' => $this->offers_model->state_commit))){
					$this->my_model->add_action_log($this->router->directory.'_'.$this->router->class.'_'.$this->router->method, $action_log, $this->aSession['account']);
					$this->setSuccessResponse();
				}else{
					$error = $this->my_model->error();
					$this->setFailResponse("处理提现申请失败！".$error['message']);
				}
				break;
		}
		echo $this->getResponse();
	}

	//提现分析
	public function report()
	{
		$this->load->helper('template');
		$this->load->model('my_model');
		$this->load->helper(array('date','my_date','array'));
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
			$search_week = getWeek_SdateAndEdate(now());
			$week_time_arr = explode('|', $search_week);
		}else{
			$week_time_arr = explode('|', $search_week);
		}
		//系统配置年月
	    $year_arr = getSystemYearArr();
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

	private function _year($search_year)
	{
		$where = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_store_num = '';
		$yAxis_arr_str_agent_num = '';
		$yAxis_arr_str_promoter_num = '';
		$yAxis_arr_str_store_total = '';
		$yAxis_arr_str_agent_total = '';
		$yAxis_arr_str_promoter_total = '';
		$wheres['year'] = $search_year;
		$data = $this->my_model->get_all('report_offers',$wheres);
		$yAxis_arr_store_num = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,);
		$yAxis_arr_store_total = $yAxis_arr_store_num;
		$yAxis_arr_agent_num = $yAxis_arr_store_num;
		$yAxis_arr_agent_total = $yAxis_arr_store_num;
		$yAxis_arr_promoter_num = $yAxis_arr_store_num;
		$yAxis_arr_promoter_total = $yAxis_arr_store_num;
		$data_array = array();
		if($data){
			foreach ($data as $v_y){
				$yAxis_arr_store_num[intval($v_y['month'])] += $v_y['store_num'];
				$yAxis_arr_agent_num[intval($v_y['month'])] += $v_y['agent_num'];
				$yAxis_arr_promoter_num[intval($v_y['month'])] += $v_y['promoter_num'];
				$yAxis_arr_store_total[intval($v_y['month'])] += $v_y['store_total'];
				$yAxis_arr_agent_total[intval($v_y['month'])] += $v_y['agent_total'];
				$yAxis_arr_promoter_total[intval($v_y['month'])] += $v_y['promoter_total'];
					
				$data_array[intval($v_y['month'])]['store_num'] = $yAxis_arr_store_num[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['agent_num'] = $yAxis_arr_agent_num[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['promoter_num'] = $yAxis_arr_promoter_num[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['store_total'] = $yAxis_arr_store_total[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['agent_total'] = $yAxis_arr_agent_total[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['promoter_total'] = $yAxis_arr_promoter_total[intval($v_y['month'])];
			}
		}
		$res = implode("','", $yAxis_arr_store_num);
		$yAxis_arr_str_store_num = "'".$res."'";
		$res = implode("','", $yAxis_arr_agent_num);
		$yAxis_arr_str_agent_num = "'".$res."'";
		$res = implode("','", $yAxis_arr_promoter_num);
		$yAxis_arr_str_promoter_num = "'".$res."'";
			
		$res = implode("','", $yAxis_arr_store_total);
		$yAxis_arr_str_store_total = "'".$res."'";
		$res = implode("','", $yAxis_arr_agent_total);
		$yAxis_arr_str_agent_total = "'".$res."'";
		$res = implode("','", $yAxis_arr_promoter_total);
		$yAxis_arr_str_promoter_total = "'".$res."'";
		
		$month_str_arr = array('一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');
		$this->resData['month_str_arr'] = $month_str_arr;
		$this->resData['items'] = $data_array;
		$this->resData['subtext'] = '按年统计';
		$this->resData['xAxis'] = getSystemMonth();
		$this->resData['yAxis_store_num'] =  $yAxis_arr_str_store_num;
		$this->resData['yAxis_agent_num'] =  $yAxis_arr_str_agent_num;
		$this->resData['yAxis_promoter_num'] =  $yAxis_arr_str_promoter_num;
		$this->resData['yAxis_store_total'] =  $yAxis_arr_str_store_total;
		$this->resData['yAxis_agent_total'] =  $yAxis_arr_str_agent_total;
		$this->resData['yAxis_promoter_total'] =  $yAxis_arr_str_promoter_total;
	}
	
	private function _month($search_month,$search_year)
	{
		$where = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_store_num = '';
		$yAxis_arr_str_agent_num = '';
		$yAxis_arr_str_promoter_num = '';
		$yAxis_arr_str_store_total = '';
		$yAxis_arr_str_agent_total = '';
		$yAxis_arr_str_promoter_total = '';
		$month_num = days_in_month($search_month,$search_year);//返回某月的天数
		for($i = 1;$i<=$month_num;$i++){
			$month_array[$i] = $i;
		}
		$xAxis_str = implode("','", $month_array);
		$xAxis = "'".$xAxis_str."'";
		$where = array('year' => $search_year,'month'=>$search_month);
		$data = $this->my_model->get_all('report_offers',$where);
		$yAxis_arr_store_num = array();
		$yAxis_arr_agent_num = array();
		$yAxis_arr_promoter_num = array();
		$yAxis_arr_store_total = array();
		$yAxis_arr_agent_total = array();
		$yAxis_arr_promoter_total = array();
		if($data){
			foreach ($data as $v_m){
				$yAxis_arr_store_num[intval(substr($v_m['day'], 6,2))] = $v_m['store_num'];
				$yAxis_arr_agent_num[intval(substr($v_m['day'], 6,2))] = $v_m['agent_num'];
				$yAxis_arr_promoter_num[intval(substr($v_m['day'], 6,2))] = $v_m['promoter_num'];
		
				$yAxis_arr_store_total[intval(substr($v_m['day'], 6,2))] = $v_m['store_total'];
				$yAxis_arr_agent_total[intval(substr($v_m['day'], 6,2))] = $v_m['agent_total'];
				$yAxis_arr_promoter_total[intval(substr($v_m['day'], 6,2))] = $v_m['promoter_total'];
			}
		}
		$yAxis_arr = elements($month_array,$yAxis_arr_store_num,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_store_num = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_agent_num,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_agent_num = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_promoter_num,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_promoter_num = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_store_total,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_store_total = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_agent_total,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_agent_total = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_promoter_total,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_promoter_total = "'".$res."'";
		
		$this->resData['item'] = $data;
		$this->resData['subtext'] = '按月统计';
		$this->resData['xAxis'] = $xAxis;
		$this->resData['yAxis_store_num'] =  $yAxis_arr_str_store_num;
		$this->resData['yAxis_agent_num'] =  $yAxis_arr_str_agent_num;
		$this->resData['yAxis_promoter_num'] =  $yAxis_arr_str_promoter_num;
		$this->resData['yAxis_store_total'] =  $yAxis_arr_str_store_total;
		$this->resData['yAxis_agent_total'] =  $yAxis_arr_str_agent_total;
		$this->resData['yAxis_promoter_total'] =  $yAxis_arr_str_promoter_total;
	}
	
	private function _week($week_time_arr)
	{
		$where = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_store_num = '';
		$yAxis_arr_str_agent_num = '';
		$yAxis_arr_str_promoter_num = '';
		$yAxis_arr_str_store_total = '';
		$yAxis_arr_str_agent_total = '';
		$yAxis_arr_str_promoter_total = '';
		$start_day = str_replace('-', '', $week_time_arr[0]);
		$where = array(
				'day >= ' => $start_day,
				'day <= ' => str_replace('-', '', $week_time_arr[1]),
		);
		$data = $this->my_model->get_all('report_offers',$where);
		//三种类型
		$yAxis_arr_store_num = array(1 => 0 , 2 => 0 , 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
		$yAxis_arr_agent_num = $yAxis_arr_store_num;
		$yAxis_arr_promoter_num = $yAxis_arr_store_num;
		$yAxis_arr_store_total = $yAxis_arr_store_num;
		$yAxis_arr_agent_total = $yAxis_arr_store_num;
		$yAxis_arr_promoter_total = $yAxis_arr_store_num;
		if($data){
			foreach ($data as $v){
				$yAxis_arr_store_num[getWeek($v['day'])] = $v['store_num'];
				$yAxis_arr_agent_num[getWeek($v['day'])] = $v['agent_num'];
				$yAxis_arr_promoter_num[getWeek($v['day'])] = $v['promoter_num'];
				$yAxis_arr_store_total[getWeek($v['day'])] = $v['store_total'];
				$yAxis_arr_agent_total[getWeek($v['day'])] = $v['agent_total'];
				$yAxis_arr_promoter_total[getWeek($v['day'])] = $v['promoter_total'];
			}
		}
		//提现次数
		$res = implode("','", $yAxis_arr_store_num);
		$yAxis_arr_str_store_num = "'".$res."'";
		$res = implode("','", $yAxis_arr_agent_num);
		$yAxis_arr_str_agent_num = "'".$res."'";
		$res = implode("','", $yAxis_arr_promoter_num);
		$yAxis_arr_str_promoter_num = "'".$res."'";
		//提现总金额
		$res = implode("','", $yAxis_arr_store_total);
		$yAxis_arr_str_store_total = "'".$res."'";
		$res = implode("','", $yAxis_arr_agent_total);
		$yAxis_arr_str_agent_total = "'".$res."'";
		$res = implode("','", $yAxis_arr_promoter_total);
		$yAxis_arr_str_promoter_total = "'".$res."'";
		//整理图表所需数据
		$this->resData['item'] = $data;
		$this->resData['subtext'] = '按周统计';
		$this->resData['xAxis'] = getSystemWeekArr();
		$this->resData['yAxis_store_num'] =  $yAxis_arr_str_store_num;
		$this->resData['yAxis_agent_num'] =  $yAxis_arr_str_agent_num;
		$this->resData['yAxis_promoter_num'] =  $yAxis_arr_str_promoter_num;
		$this->resData['yAxis_store_total'] =  $yAxis_arr_str_store_total;
		$this->resData['yAxis_agent_total'] =  $yAxis_arr_str_agent_total;
		$this->resData['yAxis_promoter_total'] =  $yAxis_arr_str_promoter_total;
	}
	
	private function _day($select_data)
	{
		$where = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_store_num = '';
		$yAxis_arr_str_agent_num = '';
		$yAxis_arr_str_promoter_num = '';
		$yAxis_arr_str_store_total = '';
		$yAxis_arr_str_agent_total = '';
		$yAxis_arr_str_promoter_total = '';
		$step = 13*24*3600;//跨14天
		if(!$select_data['start_time'] && !$select_data['end_time']){//都没设置  查找当前时间前14天条数据
			$sday = str_replace('-', '', date('Y-m-d',now()));
			$eday = str_replace('-', '', date('Y-m-d',now()-$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_offers',$where);  
		}elseif(!$select_data['start_time']){//开始时间没有设置   查找结束时间前14天条数据
			$eday = str_replace('/', '', $select_data['end_time']);
			$sday = str_replace('-','',date('Y-m-d',strtotime($eday)-$step));  
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_offers',$where);
		}elseif(!$select_data['end_time']){//结束时间没有设置  查找开始时间--当前时间的所有数据
			$sday = str_replace('/', '', $select_data['start_time']);
			$eday = str_replace('-', '', date('Y-m-d',strtotime($sday)+$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_offers',$where);
		}else{//查找开始时间---结束时间的数据
			$where_day_start = str_replace('/', '', $select_data['start_time']);
			$where_day_end = str_replace('/', '', $select_data['end_time']);
			$where = array('day >=' => $where_day_start,'day <=' => $where_day_end);
			$data_day = $this->my_model->get_all('report_offers',$where);
		}
		if($data_day){
			foreach ($data_day as $v){
				$xAxis_arr[$v['day']] = substr($v['day'],4,2).'-'.substr($v['day'],6,2);
				@$yAxis_arr_store_num[$v['day']] += $v['store_num'];
				@$yAxis_arr_agent_num[$v['day']] += $v['agent_num'];
				@$yAxis_arr_promoter_num[$v['day']] += $v['promoter_num'];
				@$yAxis_arr_store_total[$v['day']] += $v['store_total'];
				@$yAxis_arr_agent_total[$v['day']] += $v['agent_total'];
				@$yAxis_arr_promoter_total[$v['day']] += $v['promoter_total'];
			}
			ksort($yAxis_arr_store_num);
			ksort($yAxis_arr_agent_num);
			ksort($yAxis_arr_promoter_num);
			ksort($yAxis_arr_store_total);
			ksort($yAxis_arr_agent_total);
			ksort($yAxis_arr_promoter_total);
			ksort($xAxis_arr);
			//提现次数
			$res = implode("','", $yAxis_arr_store_num);
			$yAxis_arr_str_store_num = "'".$res."'";
			$res = implode("','", $yAxis_arr_agent_num);
			$yAxis_arr_str_agent_num = "'".$res."'";
			$res = implode("','", $yAxis_arr_promoter_num);
			$yAxis_arr_str_promoter_num = "'".$res."'";
			//提现总金额
			$res = implode("','", $yAxis_arr_store_total);
			$yAxis_arr_str_store_total = "'".$res."'";
			$res = implode("','", $yAxis_arr_agent_total);
			$yAxis_arr_str_agent_total = "'".$res."'";
			$res = implode("','", $yAxis_arr_promoter_total);
			$yAxis_arr_str_promoter_total = "'".$res."'";
			$x_res = implode("','", $xAxis_arr);
			$xAxis_arr_str = "'".$x_res."'";
		}
		
		//整理图表所需数据
		$this->resData['item'] = $data_day;
		$this->resData['subtext'] = '按日统计';
		$this->resData['xAxis'] = $xAxis_arr_str;
		$this->resData['yAxis_store_num'] =  $yAxis_arr_str_store_num;
		$this->resData['yAxis_agent_num'] =  $yAxis_arr_str_agent_num;
		$this->resData['yAxis_promoter_num'] =  $yAxis_arr_str_promoter_num;
		$this->resData['yAxis_store_total'] =  $yAxis_arr_str_store_total;
		$this->resData['yAxis_agent_total'] =  $yAxis_arr_str_agent_total;
		$this->resData['yAxis_promoter_total'] =  $yAxis_arr_str_promoter_total;
	}
	
	private function test_data_script()
	{
		$aDate = date_range('2015-11-1', mdate("%Y-%m-%d"), true, 'Ymd');
		foreach($aDate as $day){
			$data = array(
				'year' => mdate('%Y', strtotime($day)),
				'month' => mdate('%m', strtotime($day)),
				'day' => $day,
				'store_num' => mt_rand(1, 1024),
				'agent_num' => mt_rand(1, 1024),
				'promoter_num' => mt_rand(1, 1024)				
			);
			$data['store_total'] = $data['store_num'] * mt_rand(0, 10);
			$data['agent_total'] = $data['agent_num'] * mt_rand(5, 10);
			$data['promoter_total'] = $data['promoter_num'] * mt_rand(1, 5);
			$this->my_model->insert_entry($this->my_model->getTableName('_report_offers'), $data);
		}
	}
}
