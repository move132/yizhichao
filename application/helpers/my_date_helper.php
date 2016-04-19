<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获得系统年份数组
 */
function getSystemYearArr($year = ''){
	$step = 5;//跨年
	$max = mdate("%Y");

	if(empty($year) || $year > $max){
		$year = $max;
	}
	$year_arr = array();
	if($year + $step > $max){//从当前年倒序
		for($i = $step * 2; $i > -1; $i--){
			$Y = $max - $i;
			$year_arr[$Y] = $Y;
		}
	}else{//左右各5年
		$year_arr[$year] = $year;
		for($i = 0; $i < $step; $i++){
			$left = $year - ($i + 1);
			$right = $year + ($i + 1);

			$year_arr[$left] = $left;
			$year_arr[$right] = $right;
		}
		ksort($year_arr);
	}
	// $year_arr = array('2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');
	return $year_arr;
}

/**
 * 未使用
 * 获取某月的最后一天的时间搓
 * @ $month int
 * @ $year 空值使用今年
 */
function getMonthLastDay($month, $year = ''){
	$days = days_in_month($month, $year);
	if(empty($year)){
		$year = mdate("%Y");
	}

	return mktime(23, 59, 59, $month, $days, $year);
}

// 获取本周的开始时间和结束时间【按中国习惯周一到周日】
function getWeek_SdateAndEdate(){
	$today = date('N', now())-1;
	$return_arr['sdate'] = date('Y-m-d', strtotime('-'.$today.' days'));
	$return_arr['edate'] = date('Y-m-d', strtotime('+'. (6 -$today).'days'));
	return implode('|',$return_arr);
}

/**
 * 获得系统月份数组
 */
function getSystemMonthArr(){
	$month_arr = array('1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12');
	return $month_arr;
}

/**
 * 获得系统周数组
 */
function getSystemWeekArr(){
	return "'周一','周二','周三','周四','周五','周六','周日'";
}

/**
 * 
 */
function getSystemMonth(){	
 	return "'一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'"; 	
}

/**
 * 未用到
 * 处理搜索时间
 */
 function dealwithSearchTime($search_arr){
	//初始化时间
	//天
	if(!$search_arr['search_time']){
		$search_arr['search_time'] = date('Y-m-d', time()- 86400);
	}
	$search_arr['day']['search_time'] = strtotime($search_arr['search_time']);//搜索的时间

	//周
	if(!$search_arr['searchweek_year']){
		$search_arr['searchweek_year'] = date('Y', time());
	}
	if(!$search_arr['searchweek_month']){
		$search_arr['searchweek_month'] = date('m', time());
	}
	if(!$search_arr['searchweek_week']){
		$search_arr['searchweek_week'] =  implode('|', getWeek_SdateAndEdate(time()));
	}
	$weekcurrent_year = $search_arr['searchweek_year'];
	$weekcurrent_month = $search_arr['searchweek_month'];
	$weekcurrent_week = $search_arr['searchweek_week'];
	$search_arr['week']['current_year'] = $weekcurrent_year;
	$search_arr['week']['current_month'] = $weekcurrent_month;
	$search_arr['week']['current_week'] = $weekcurrent_week;

	//月
	if(!$search_arr['searchmonth_year']){
		$search_arr['searchmonth_year'] = date('Y', time());
	}
	if(!$search_arr['searchmonth_month']){
		$search_arr['searchmonth_month'] = date('m', time());
	}
	$monthcurrent_year = $search_arr['searchmonth_year'];
	$monthcurrent_month = $search_arr['searchmonth_month'];
	$search_arr['month']['current_year'] = $monthcurrent_year;
	$search_arr['month']['current_month'] = $monthcurrent_month;
	return $search_arr;
}

/**
 * 获得系统某月的周数组，第一周不足的需要补足,最后一周补足将会补足
 * @$current_year 年
 * @$current_month 月
 */
function getMonthWeekArr($current_year, $current_month){
	//该月第一天
	$firstday = strtotime($current_year.'-'.$current_month.'-01');
	//该月的第一周有几天
	$firstweekday = (7 - date('N',$firstday) +1);
	//计算该月第一个周一的时间
	$starttime = $firstday-3600*24*(7-$firstweekday);
	//该月的最后一天
	$lastday = strtotime($current_year.'-'.$current_month.'-01'." +1 month -1 day");
	//该月的最后一周有几天
	$lastweekday = date('N',$lastday);
	//该月的最后一个周末的时间
	$endtime = $lastday+3600*24*(7-$lastweekday);
	$week_arr = array();
	for ($i=$starttime; $i<$endtime; $i= $i+3600*24*7){
		$week_arr[] = array('key'=>date('Y-m-d',$i).'|'.date('Y-m-d',$i+3600*24*6), 'val'=>date('Y-m-d',$i).'~'.date('Y-m-d',$i+3600*24*6));
	}
	return $week_arr;
}

/**
 * 返回 日期对应的周数
 * @$today   如  20160324 
 * return  1 到  7 
 */
function getWeek($today){
	return date('N', strtotime($today));
}

function get_date($today){
	return $today;
}