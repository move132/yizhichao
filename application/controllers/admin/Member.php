<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{
		// set_time_limit(0);$this->test_member_script();exit();
		$this->load->helper(array('my', 'template'));
		$this->resData['listHeader']['location'][] = array('name' => '用户列表', 'url' => '');

		$this->load->model('member_model');

		$where = array();
 		$like = array();
		$select_data = $this->input->get('select');
		if($select_data){
			$select_name = trim($select_data['name']);
			$like['name'] = $select_name;
			$this->resData['select']['name'] = $select_name;
		}
		$data = $this->member_model->getTableList($where, $this->per_page, $this->offset, $like);
		$this->resData['list'] = $data['list'];
		
		$this->resData['pagination'] = $this->pagination(
			site_url(array($this->router->directory, $this->router->class, $this->router->method)),
			$data['total']
		);
		$this->load->view($this->getTemplateFile(), $this->resData);
	}

	private function test_member_script()
	{
		for($i = 0; $i < 10000; $i++){
			$data = array(
				'mode' => mt_rand(1, 2),
				'uuid' => makeStorePID(),
				'name' => 'robot_'.makeStorePID(),
				'sex' => mt_rand(0, 2),
				'header' => '',
				'num' => mt_rand(1, 20),
				'first_time' => mt_rand(microtime('-365day'), microtime('-1day'))
			);
			$data['money'] = $data['num'] * mt_rand(1, 1000);
			$data['last_time'] = $data['first_time'] + $data['num'] * 24 * 3600;

			$this->my_model->insert_entry($this->my_model->getTableName('_member'), $data);
		}
	}

	//用户分析
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
		$data_array = array();
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$xAxis_arr_str = '';
		$wheres['year'] = $search_year;
		$data = $this->my_model->get_all('report_member',$wheres);
		$yAxis_arr_num = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,);
		$yAxis_arr_total = $yAxis_arr_num;
		if($data){
			foreach ($data as $v_y){
				if($v_y['mode'] == 1){
					$yAxis_arr_num[intval($v_y['month'])] = $yAxis_arr_num[intval($v_y['month'])]+$v_y['num'];
				}else{
					$yAxis_arr_total[intval($v_y['month'])] = $yAxis_arr_total[intval($v_y['month'])]+$v_y['num'];
				}
				@$data_array[intval($v_y['month'])]['wechat'] = $yAxis_arr_num[intval($v_y['month'])];
				@$data_array[intval($v_y['month'])]['apliy'] = $yAxis_arr_total[intval($v_y['month'])];
			}
			$res = implode("','", $yAxis_arr_num);
			$yAxis_arr_str_num = "'".$res."'";
			$res = implode("','", $yAxis_arr_total);
			$yAxis_arr_str_total = "'".$res."'";
		}
		
		$month_str_arr = array('一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');
		$this->resData['month_str_arr'] = $month_str_arr;
		$this->resData['items'] = $data_array;
		$this->resData['subtext'] = '按年统计';
		$this->resData['xAxis'] = getSystemMonth();
		$this->resData['yAxis_wechat'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_apliy'] =  $yAxis_arr_str_total;
	}
	
	private function _month($search_month,$search_year)
	{
		$where = array();
		$data_array = array();
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$xAxis_arr_str = '';
		$month_num = days_in_month($search_month,$search_year);//返回某月的天数
		for($i = 1;$i<=$month_num;$i++){
			$month_array[$i] = $i;
		}
		$xAxis_str = implode("','", $month_array);
		$xAxis = "'".$xAxis_str."'";
		$where = array('year' => $search_year,'month'=>$search_month);
		$data = $this->my_model->get_all('report_member',$where);
		$yAxis_arr_num = array();
		$yAxis_arr_total = array();
		$data_array = array();
		if($data){
			foreach ($data as $v_m){
				if($v_m['mode'] == 1){
					@$yAxis_arr_num[intval(substr($v_m['day'], 6,2))] += $v_m['num'];
				}else{
					@$yAxis_arr_total[intval(substr($v_m['day'], 6,2))] += $v_m['num'];
				}
				@$data_array[intval(substr($v_m['day'], 6,2))]['wechat'] = $yAxis_arr_num[intval(substr($v_m['day'], 6,2))] ;
				@$data_array[intval(substr($v_m['day'], 6,2))]['apliy'] = $yAxis_arr_total[intval(substr($v_m['day'], 6,2))] ;
				@$data_array[intval(substr($v_m['day'], 6,2))]['day'] = $v_m['day'];
			}
		}
		$yAxis_arr = elements($month_array,$yAxis_arr_num,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_num = "'".$res."'";
		$yAxis_arr = elements($month_array,$yAxis_arr_total,0);
		$res = implode("','", $yAxis_arr);
		$yAxis_arr_str_total = "'".$res."'";
		$this->resData['item'] = $data_array;
		$this->resData['subtext'] = '按月统计';
		$this->resData['xAxis'] = $xAxis;
		$this->resData['yAxis_wechat'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_apliy'] =  $yAxis_arr_str_total;
	}
	
	private function _week($week_time_arr)
	{
		$where = array();
		$data_array = array();
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$xAxis_arr_str = '';
		$start_day = str_replace('-', '', $week_time_arr[0]);
		$where = array(
				'day >= ' => $start_day,
				'day <= ' => str_replace('-', '', $week_time_arr[1]),
		);
		$data = $this->my_model->get_all('report_member',$where);
		$yAxis_arr_wechat = array(1 => 0 , 2 => 0 , 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
		$yAxis_arr_apliy = $yAxis_arr_wechat;
		$data_array = array();
		if($data){
			foreach ($data as $v){
				if($v['mode'] == 1){
					$yAxis_arr_wechat[getWeek($v['day'])] += $v['num'];
				}else{
					$yAxis_arr_apliy[getWeek($v['day'])] += $v['num'];
				}
				$data_array[getWeek($v['day'])]['wechat'] = $yAxis_arr_wechat[getWeek($v['day'])] ;
				$data_array[getWeek($v['day'])]['apliy'] = $yAxis_arr_apliy[getWeek($v['day'])] ;
				$data_array[getWeek($v['day'])]['day'] = $v['day'];
			}
		}
		$this->resData['item'] = $data_array;
		$res = implode("','", $yAxis_arr_wechat);
		$yAxis_arr_str_wechat = "'".$res."'";
		$res = implode("','", $yAxis_arr_apliy);
		$yAxis_arr_str_apliy = "'".$res."'";
		//整理图表所需数据
		$legend = '';
		$this->resData['subtext'] = '按周统计';
		$this->resData['xAxis'] = getSystemWeekArr();
		$this->resData['yAxis_wechat'] =  $yAxis_arr_str_wechat;
		$this->resData['yAxis_apliy'] =  $yAxis_arr_str_apliy;
	}
	
	private function _day($select_data)
	{
		$where = array();
		$data_array = array();
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$xAxis_arr_str = '';
		$step = 13*24*3600;//跨年
		if(!$select_data['start_time'] && !$select_data['end_time']){//都没设置  查找当前时间前14天条数据
			$sday = str_replace('-', '', date('Y-m-d',now()));
			$eday = str_replace('-', '', date('Y-m-d',now()-$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_member',$where);  
		}elseif(!$select_data['start_time']){//开始时间没有设置   查找结束时间前14天条数据
			$eday = str_replace('/', '', $select_data['end_time']);
			$sday = str_replace('-','',date('Y-m-d',strtotime($eday)-$step)); 
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_member',$where);
		}elseif(!$select_data['end_time']){//结束时间没有设置  查找开始时间--当前时间的所有数据
			$sday = str_replace('/', '', $select_data['start_time']);
			$eday = str_replace('-', '', date('Y-m-d',strtotime($sday)+$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_member',$where);
		}else{//查找开始时间---结束时间的数据
			$where_day_start = str_replace('/', '', $select_data['start_time']);
			$where_day_end = str_replace('/', '', $select_data['end_time']);
			$where = array('day >=' => $where_day_start,'day <=' => $where_day_end);
			$data_day = $this->my_model->get_all('report_member',$where);
		}
		$data_array = array();
		if($data_day){
			foreach ($data_day as $v){
				if($v['mode'] == 1){
					$xAxis_arr[$v['day']] = substr($v['day'],4,2).'-'.substr($v['day'],6,2);
					@$yAxis_arr_num[$v['day']] += $v['num'];
				}else{
					@$yAxis_arr_total[$v['day']] += $v['num'];
				}
				@$data_array[$v['day']]['wechat'] = $yAxis_arr_num[$v['day']];
				@$data_array[$v['day']]['apliy'] = $yAxis_arr_total[$v['day']];
				@$data_array[$v['day']]['day'] = $v['day'];
			}
			ksort($data_array);
			ksort($yAxis_arr_num);
			ksort($yAxis_arr_total);
			ksort($xAxis_arr);
			$y_res = implode("','", $yAxis_arr_num);
			$yAxis_arr_str_num = "'".$y_res."'";
			$y_res = implode("','", $yAxis_arr_total);
			$yAxis_arr_str_total = "'".$y_res."'";
			$x_res = implode("','", $xAxis_arr);
			$xAxis_arr_str = "'".$x_res."'";
		}
		//整理图表所需数据
		$this->resData['item'] = $data_array;
		$this->resData['subtext'] = '按日统计';
		$this->resData['xAxis'] = $xAxis_arr_str;
		$this->resData['yAxis_wechat'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_apliy'] =  $yAxis_arr_str_total;
	}
	
	
	
	
	private function test_data_script()
	{
		$aDate = date_range('2015-11-1', mdate("%Y-%m-%d"), true, 'Ymd');
		foreach($aDate as $day){
			for($mode = 1; $mode < 3; $mode++){
				$data = array(
					'year' => mdate('%Y', strtotime($day)),
					'month' => mdate('%m', strtotime($day)),
					'day' => $day,
					'num' => mt_rand(1, 1024),
					'mode' => $mode
				);
				$this->my_model->insert_entry($this->my_model->getTableName('_report_member'), $data);
			}
		}
	}
}
