<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MY_Admin_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	//初始化页面
	public function index()
	{
		
	}

	//支付分析
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
				$this->_month($search_month,$search_year);
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
	public function monthToweek(){
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
		$where = array();
		$data_array = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$start_day = str_replace('-', '', $week_time_arr[0]);
		$where = array(
				'day >= ' => $start_day,
				'day <= ' => str_replace('-', '', $week_time_arr[1]),
		);
		$data = $this->my_model->get_all('report_pay',$where);
		$yAxis_arr_num = array(1 => 0 , 2 => 0 , 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
		$yAxis_arr_total = array(1 => 0 , 2 => 0 , 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
		if($data){
			foreach ($data as $v){
				$yAxis_arr_num[getWeek($v['day'])] = $v['num']+$yAxis_arr_num[getWeek($v['day'])];
				$yAxis_arr_total[getWeek($v['day'])] = $v['total']+$yAxis_arr_total[getWeek($v['day'])];
				$data_array[getWeek($v['day'])]['num'] = $yAxis_arr_num[getWeek($v['day'])] ;
				$data_array[getWeek($v['day'])]['total'] = $yAxis_arr_total[getWeek($v['day'])] ;
				$data_array[getWeek($v['day'])]['day'] = $v['day'];
			}
			$res = implode("','", $yAxis_arr_num);
			$yAxis_arr_str_num = "'".$res."'";
			$res = implode("','", $yAxis_arr_total);
			$yAxis_arr_str_total = "'".$res."'";
		}
		$this->resData['item'] = $data_array;
		//整理图表所需数据
		$this->resData['subtext'] = '按周统计';
		$this->resData['xAxis'] = getSystemWeekArr();
		$this->resData['yAxis_num'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_total'] =  $yAxis_arr_str_total;
	}
	
	private function _month($search_month,$search_year)
	{
		$where = array();
		$data_array = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$month_num = days_in_month($search_month,$search_year);//返回某月的天数
		for($i = 1;$i<=$month_num;$i++){
			$month_array[$i] = $i;
		}
		$xAxis_str = implode("','", $month_array);
		$xAxis = "'".$xAxis_str."'";
		$where = array('year' => $search_year,'month'=>$search_month);
		$data = $this->my_model->get_all('report_pay',$where);
		$yAxis_arr_num = array();
		$yAxis_arr_total = array();
		if($data){
			foreach ($data as $v_m){
				@$yAxis_arr_num[intval(substr($v_m['day'], 6,2))] += $v_m['num'];
				@$yAxis_arr_total[intval(substr($v_m['day'], 6,2))] += $v_m['total'];
				$data_array[intval(substr($v_m['day'], 6,2))]['num'] = $yAxis_arr_num[intval(substr($v_m['day'], 6,2))] ;
				$data_array[intval(substr($v_m['day'], 6,2))]['total'] = $yAxis_arr_total[intval(substr($v_m['day'], 6,2))] ;
				$data_array[intval(substr($v_m['day'], 6,2))]['day'] = $v_m['day'];
			}
			$yAxis_arr = elements($month_array,$yAxis_arr_num,0);
			$res = implode("','", $yAxis_arr);
			$yAxis_arr_str_num = "'".$res."'";
			$yAxis_arr = elements($month_array,$yAxis_arr_total,0);
			$res = implode("','", $yAxis_arr);
			$yAxis_arr_str_total = "'".$res."'";
		}
		$this->resData['item'] = $data_array;
		$this->resData['subtext'] = '按月统计';
		$this->resData['xAxis'] = $xAxis;
		$this->resData['yAxis_num'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_total'] =  $yAxis_arr_str_total;
	}
	
	private function _year($search_year)
	{
		$where = array();
		$data_array = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$wheres['year'] = $search_year;
		$data = $this->my_model->get_all('report_pay',$wheres);
		$yAxis_arr_num = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,);
		$yAxis_arr_total = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,);
		if($data){
			foreach ($data as $v_y){
				$yAxis_arr_num[intval($v_y['month'])] = $yAxis_arr_num[intval($v_y['month'])]+$v_y['num'];
				$yAxis_arr_total[intval($v_y['month'])] = $yAxis_arr_total[intval($v_y['month'])]+$v_y['total'];
				$data_array[intval($v_y['month'])]['num'] = $yAxis_arr_num[intval($v_y['month'])];
				$data_array[intval($v_y['month'])]['total'] = $yAxis_arr_total[intval($v_y['month'])];
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
		$this->resData['xAxis'] = "'一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'";
		$this->resData['yAxis_num'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_total'] =  $yAxis_arr_str_total;
	}
	
	private function _day($select_data)
	{
		$where = array();
		$data_array = array();
		$xAxis_arr_str = '';
		$yAxis_arr_str_num = '';
		$yAxis_arr_str_total = '';
		$step = 13*24*3600;//跨年
		if(!$select_data['start_time'] && !$select_data['end_time']){//都没设置  查找当前时间前14天条数据
			$sday = str_replace('-', '', date('Y-m-d',now()));
			$eday = str_replace('-', '', date('Y-m-d',now()-$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_pay',$where);  
		}elseif(!$select_data['start_time']){//开始时间没有设置   查找结束时间前14天条数据
			$eday = str_replace('/', '', $select_data['end_time']);
			$sday = str_replace('-','',date('Y-m-d',strtotime($eday)-$step));  
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_pay',$where);
		}elseif(!$select_data['end_time']){//结束时间没有设置  查找开始时间--当前时间的所有数据
			$sday = str_replace('/', '', $select_data['start_time']);
			$eday = str_replace('-', '', date('Y-m-d',strtotime($sday)+$step));
			$where = array('day >= ' => $sday,'day <= ' => $eday);
			$data_day = $this->my_model->get_all('report_pay',$where);
		}else{//查找开始时间---结束时间的数据
			$where_day_start = str_replace('/', '', $select_data['start_time']);
			$where_day_end = str_replace('/', '', $select_data['end_time']);
			$where = array('day >=' => $where_day_start,'day <=' => $where_day_end);
			$data_day = $this->my_model->get_all('report_pay',$where);
		}
		if($data_day){
			foreach ($data_day as $v){
				$xAxis_arr[$v['day']] = substr($v['day'],4,2).'-'.substr($v['day'],6,2);
				@$yAxis_arr_num[$v['day']] += $v['num'];
				@$yAxis_arr_total[$v['day']] += $v['total'];
				$data_array[$v['day']]['num'] = $yAxis_arr_num[$v['day']];
				$data_array[$v['day']]['total'] = $yAxis_arr_total[$v['day']];
				$data_array[$v['day']]['day'] = $v['day'];
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
		$this->resData['yAxis_num'] =  $yAxis_arr_str_num;
		$this->resData['yAxis_total'] =  $yAxis_arr_str_total;
	}
	
	
	private function test_data_script()
	{
		$aDate = date_range('2015-11-1', mdate("%Y-%m-%d"), true, 'Ymd');
		foreach($aDate as $day){
			$data = array(
				'year' => mdate('%Y', strtotime($day)),
				'month' => mdate('%m', strtotime($day)),
				'day' => $day,
				'sid' => mt_rand(1, 100),
				'num' => mt_rand(1, 1024)
			);
			$data['total'] = $data['num'] * mt_rand(1, 10);
			$this->my_model->insert_entry($this->my_model->getTableName('_report_pay'), $data);
		}
	}
}
