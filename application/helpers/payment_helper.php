<?php
/**
 * 支付接口所需要的一些方法
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RSA签名
 * @param $data 待签名数据
 * @param $private_key_path 商户私钥文件路径
 * return 签名结果
 */
function rsaSign($data, $private_key_path) 
{
	$priKey = file_get_contents($private_key_path);
	$res = openssl_get_privatekey($priKey);
	openssl_sign($data, $sign, $res);
	openssl_free_key($res);
	//base64编码
	$sign = base64_encode($sign);
	return $sign;
}

/**
 * RSA验签
 * @param $data 待签名数据
 * @param $ali_public_key_path 支付宝的公钥文件路径
 * @param $sign 要校对的的签名结果
 * return 验证结果
 */
function rsaVerify($data, $ali_public_key_path, $sign, $is_file = true)  
{
	if($is_file){
		$pubKey = file_get_contents($ali_public_key_path);
	}else{
		$pubKey = $ali_public_key_path;
	}

	$res = openssl_get_publickey($pubKey);
	$result = (bool)openssl_verify($data, base64_decode($sign), $res);
	openssl_free_key($res);
	return $result;
}

/**
 * RSA解密
 * @param $content 需要解密的内容，密文
 * @param $private_key_path 商户私钥文件路径
 * return 解密后内容，明文
 */
function rsaDecrypt($content, $private_key_path) 
{
	$priKey = file_get_contents($private_key_path);
	$res = openssl_get_privatekey($priKey);
	//用base64将内容还原成二进制
	$content = base64_decode($content);
	//把需要解密的内容，按128位拆开解密
	$result  = '';
	for($i = 0; $i < strlen($content)/128; $i++  ) {
		$data = substr($content, $i * 128, 128);
		openssl_private_decrypt($data, $decrypt, $res);
		$result .= $decrypt;
	}
	openssl_free_key($res);
	return $result;
}

/**
 * 格式化签名参数
 * @param unknown $param  array
 * @return string
 */
function arrayToKSortstring($param)
{
	ksort($param);
	$str = '';
	foreach($param as $key=>$val){
		if(empty($val)){
			continue;
		}
		$str .= $key . '=' . $val . '&';
	}
	$str = trim($str, '&');
	return $str;
}

//微信签名算法key设置路径：微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
function getWechatSign($mch_key, $param = array())
{
	return strtoupper(md5(arrayToKSortstring($param).'&key='.$mch_key));
}

/**
 * 数组转化成xml
 * @param unknown $param 数组类型
 * @return string
 */
function arrayToxml($param)
{
	$xml = '<xml>';
	foreach($param as $key=>$value){
		if(empty($value)){
			continue;
		}
		$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
	}
	$xml .= '</xml>';
	return $xml;
}

//返回第三方微信数据格式化
function formatWeChatXML($param = array(), $tag = true){
	$xml = '';
	if($tag){
		$xml .= '<xml>';
	}
	foreach($param as $key=>$val){
		if(is_array($val)){
			$tmp = '';
			foreach($val as $k=>$v){
				if(is_array($v)){
					$tmp2 = '';
					foreach($v as $i=>$r){
						if(! is_numeric($r)){
							$r = '<![CDATA['.$r.']]>';
						}

						$tmp2 .= '<'.$i.'>'.$r.'</'.$i.'>';
					}
					$v = $tmp2;
				}else{
					if(! is_numeric($v)){
						$v = '<![CDATA['.$v.']]>';
					}
				}
				
				$tmp .= '<'.$k.'>'.$v.'</'.$k.'>';
			}
			$val = $tmp;
		}else{
			if(! is_numeric($val) && $key != 'Articles'){
				$val = '<![CDATA['.$val.']]>';
			}
		}
		
		$xml .= '<'.$key.'>'.$val.'</'.$key.'>';
	}
	if($tag){
		$xml .= '</xml>';
	}
	
	return $xml;
}

//JS-SDK权限验证的签名
function getWechatJSSignature($param){
	return sha1(arrayToKSortstring($param));
}