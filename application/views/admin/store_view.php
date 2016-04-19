
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static/css/lib/base.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static/css/lib/bootstrap.min.css');?>" />
<div class="  uio-view" >
	<dl class="dl-horizontal" >
		<dt>商户号：</dt>
		<dd><?php echo $info['pid']?></dd>
		<dt>店铺名称：</dt>
		<dd><?php echo $info['name']?></dd>
		<dt>所在地区：</dt>
		<dd><?php echo getStrAddr($info['region_0'], $info['region_1'], $info['region_2'], $info['region_3'], $region);?></dd>
		<dt>详细地址：</dt>
		<dd><?php echo $info['addr_info'];?></dd>
		<dt>联系人：</dt>
		<dd><?php echo $info['person'];?></dd>
		<dt>联系电话：</dt>
		<dd><?php echo $info['tel'];?></dd>
		<dt>加入时间：</dt>
		<dd><?php echo mdate("%Y/%m/%d %H:%m:%s", $info['atime']);?></dd>
		<dt>最后一次登录时间：</dt>
		<dd><?php echo mdate("%Y/%m/%d %H:%m:%s", $info['last_login_time']);?></dd>
		<dt>店铺类型：</dt>
		<dd>
		<?php echo $store_class[$info['class_id']]?>
		</dd>
		<dt>经营类目：</dt>
		<dd>
		 <?php echo getStrAddr(0, $info['category_id_1'], $info['category_id_2'], $info['category_id_3'], $store_category)?>
		</dd>
		
		<dt>店铺等级：</dt>
		<dd>
		<?php if($system['storeLevel']){ ?>
		<?php $aStoreLevel = json_decode($system['storeLevel'], true);echo isset($aStoreLevel[$info['level']]) ? $aStoreLevel[$info['level']] : '未知' ?>				
		<?php }else{ ?>
		未设置
		<?php } ?>
		</dd>
		<dt>可提现资金：</dt>
		<dd><?php echo $info['money'];?></dd>
		<dt>冻结资金：</dt>
		<dd><?php echo $info['frozen_money'];?></dd>
		<dt>已提现资金：</dt>
		<dd><?php echo $info['finish_money'];?></dd>
		<dt>记账资金：</dt>
		<dd><?php echo $info['bill_money'];?></dd>
		<dt>身份证：</dt>
		<dd><?php echo $info['idc_number'];?></dd>
		<dt>银行账户：</dt>
		<dd><?php echo isset($bank[$info['bank_id']]) ? $bank[$info['bank_id']]['name'] : '未知';?></dd>
		<dt>银行卡号：</dt>
		<dd><?php echo $info['card_id'];?></dd>
	</dl>
</div> 























