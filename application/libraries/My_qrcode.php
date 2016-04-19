<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * php生成二维码类
 *
 */

class My_qrcode {
	
	public function __construct()
	{
		include_once(APPPATH.'libraries/phpqrcode.php');
	}
	
	/**
	 * 生成文件二维码
	 * @ $text 二维码地址
	 * @ $outfile 是否输出文件，输出文件则传入文件名
	 * @ $level 容错率0-3
	 * @ $size 图片大小
	 * @ $margin 二维码边距
	 */
	public function png($text, $outfile = false, $level = 3, $size = 9, $margin = 4)
	{
		return QRcode::png($text, $outfile, $level, $size, $margin);
	}
}