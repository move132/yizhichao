<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 生成店铺编号(两位随机 + 从2016-01-01 00:00:00 到现在的秒数+微秒)
 * 长度 =2位 + 10位 + 3位  = 15位
 */
function makeStorePID()
{
	return mt_rand(10,99) . sprintf('%010d',time() - 1451577600) . sprintf('%03d', (float) microtime() * 1000);
}

/**
 * 生成支付单编号(两位随机 + 从2016-01-01 00:00:00 到现在的秒数+微秒+店铺ID%1000)
 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
 */
function makePaySn($store_id)
{
	return mt_rand(10,99) . sprintf('%010d',time() - 1451577600) . sprintf('%03d', (float) microtime() * 1000) . sprintf('%03d', (int) $store_id % 1000);
}

//取得今日开始时间
function getTodayStartTime()
{
	return strtotime(date("Y-m-d"));
}

//取指定日期开始时间
function getDayStartTime($day)
{
	return strtotime(date("Y-m-d", strtotime("${day} days")));
}

function getStrAddr($region_0, $region_1, $region_2, $region_3, $region = array())
{
	$arr = array();
	if(isset($region[$region_0][$region_1])){
		array_push($arr, $region[$region_0][$region_1]);
	}
	if(isset($region[$region_1][$region_2])){
		array_push($arr, $region[$region_1][$region_2]);
	}
	if(isset($region[$region_2][$region_3])){
		array_push($arr, $region[$region_2][$region_3]);
	}
	return implode('/', $arr);
}

//短信验证码
function smsCode($len = 4)
{
	return random_string('numeric', $len);
}

//注册初始密码明文
function getRegisterPassword($len = 6)
{
	return smsCode($len);	
}

//格式化输出时间
function formatTime($time, $style = 1)
{
	switch($style){
		case 1:
			$sTime = mdate("%Y-%m-%d %H:%i:%s", $time);
			break;
		case 2:
			$sTime = str_replace(' ', '<br/>', mdate("%Y-%m-%d %H:%i:%s", $time));
			break;
		case 3:
			$sTime = mdate("%Y%m%d%H%i%s", $time);
			break;
		default :
			$sTime = $time;
			break;
	}

	return $sTime;
}

//支付签名随机串
function getNonceStr()
{
	return md5(microtime());
}

//支付签名时间戳
function getTimeStamp(){
	return time();
}

//性别
function strSex($sex)
{
	return $sex == 0 ? '保密' : ($sex == 1 ? '男' : ($sex == 2 ? '女' : '未知'));
}

//账号来源
function strAccountSource($mode)
{	
	switch($mode){
		case 1:
			$str = '微信';
			break;
		case 2:
			$str = '支付宝';
			break;
		default :
			$str = '未知';
			break;
	}
	return $str;
}

//店员级别
function strSeller($shopowner)
{
	if($shopowner == '1'){
		return '店长';
	}else{
		return '店员';
	}
}

/**
 * 扩展file_get_contents函数
 * @param  [type]  $url     通信地址
 * @param  string  $method  数据传输方式：[POST, GET]
 * @param  string  $body    传输内容
 * @param  string  $type    传输格式
 * @param  integer $timeout 超时
 * @return [json]           [description]
 */
function file_get_contents_hsy($url, $method = 'GET', $body = '', $type = 'text/html', $timeout = 3)
{
	$opts = array(
			'http' => array(
					'header' => 'Content-Type: '.$type.'; charset=utf-8',
					'method' => $method,
					'timeout' => $timeout
			)
	);
	if($method == 'POST'){
		$opts['http']['header'] = 'Content-Type: '.$type.'; encoding=utf-8';
		$opts['http']['content'] = $body;
		$opts['http']['Content-Length'] = strlen($body);
	}
	$ctx = stream_context_create($opts);
	return @file_get_contents($url, 0, $ctx);
}

/**
 * 将xml转化成数组
 * @param unknown $data_xml  xml字符串
 * @return array
 */
function xmlToarray($data_xml){
	libxml_disable_entity_loader(true);
	return json_decode(json_encode(simplexml_load_string($data_xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

function isWeiXin(){
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	return strpos($agent, 'micromessenger') ? true : false;
}

//获取头像
function avatar($header, $type = 1){
	$avatar = '';
	switch($type){
		case 1://管理员
		case 2://代理/推广员
			if(empty($header)){
				$avatar = base_url('static/image/lib/avatar.png');
			}else{
				$avatar = base_url('uploads/'.$header);
			}
			break;
	}
	return $avatar;
}

/**
 * 返回前六位，最后4位的银行卡号 如：456321****1254
 * @$card_id  银行卡号
 */
function getCadeId($card_id){
	$str_num = strlen($card_id);
	//银行卡前六位
	$bank_6 = substr($card_id, 0,5);
	//银行卡后四位
	$bank_4 = substr($card_id, $str_num-4);
	return $bank_6.'****'.$bank_4;
}

/**
 * 获取二维码图片地址
 * @$type  1:代理、推广员二维码 , 2:店铺
 * @$id    店铺  or 代理、推广员ID
 * @$parent_id  默认0 推荐入驻人（代理、推广员ID）
 */
function getScanFileName($type, $id, $parent_id = 0, $dir = ''){
	if($type == 1){
		$file_name = 'qrcode_'.$id.'_'.$parent_id.'_promoter.png';
	}elseif($type == 2){
		$file_name = 'qrcode_'.$id.'_'.$parent_id.'_store.png';
	}
	if(empty($dir)){
		$dir = PHPQRCODE_AGENT_PROMOTER;
	}
	return $dir.$file_name;
}


