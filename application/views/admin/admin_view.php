
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static/css/lib/base.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('static/css/lib/bootstrap.min.css');?>" />
<div class="  uio-view" >
	<dl class="dl-horizontal" >
	  <dt>姓名：</dt>
	  <dd><?php echo $list['name']?></dd>
	  <dt>账号：</dt>
	  <dd><?php echo $list['account']?></dd>
	  <dt>邮箱：</dt>
	  <dd><?php echo $list['email']?></dd>
	  <dt>添加时间：</dt>
	  <dd><?php echo mdate("%Y/%m/%d %H:%i", $list['atime']);?></dd>
	  <dt>登录次数：</dt>
	  <dd><?php echo $list['num']?></dd>
	  <dt>上次登陆：</dt>
	  <dd><?php echo mdate("%Y/%m/%d %H:%i", $list['last_time']);?></dd>
	  <dt>状态：</dt>
	  <dd><?php echo $list['disable']==1?'禁用':'正常' ;   ?></dd>
	
	</dl>
</div> 























