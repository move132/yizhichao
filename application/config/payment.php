<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['payment'] = array (
  'alipay' => 
  array (
    'code' => 'alipay',
    'is_open' => '1',
    'fee' => '0.003',
    'mode' => '2',
    'config' => 
    array (
      'app_id' => '2015092400320805',
      'app_key' => NULL,
      'mch_id' => '2088221249153821',
      'mch_key' => NULL,
    ),
  ),
  'wechat' => 
  array (
    'code' => 'wechat',
    'is_open' => '1',
    'fee' => '0.006',
    'mode' => '1',
    'config' => 
    array (
      'app_id' => 'wx309a06e520927179',
      'app_key' => '5c6b5ee80c09dd8251b5e271f359b534',
      'mch_id' => '1283447401',
      'mch_key' => '14937c3a0f1ba660e03c6e4b4ee34944',
    ),
  ),
);