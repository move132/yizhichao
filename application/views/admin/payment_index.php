<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>

<div class="main_hd notpd">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="uio-tab-items active">
			<a href="#wechat" aria-controls="wechat" role="tab" data-toggle="tab">微信</a>
		</li>
		<li role="presentation" class="uio-tab-items">
			<a href="#alipay" aria-controls="alipay" role="tab" data-toggle="tab">支付宝</a>
		</li>
		<li role="presentation" class="uio-tab-items">
			<a href="#baidu" aria-controls="baidu" role="tab" data-toggle="tab">百度钱包</a>
		</li>
		<li role="presentation" class="uio-tab-items">
			<a href="#jingdong" aria-controls="jingdong" role="tab" data-toggle="tab">京东钱包</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active"  id="wechat">
		<form class="form-horizontal uio-form-box" id="wechat_form" action="" onsubmit="javascript:return false;">
			<input type="hidden" name="action" value="wechat">
			<input type="hidden" name="mode" value="1">
			<div class="form-group">
				<label  class="col-sm-2 control-label">使用状态：</label>
				<div class="col-sm-7">
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="1" <?php if(isset($payment['wechat']['is_open']) && $payment['wechat']['is_open'] == 1){echo 'checked="checked"';}?> />
							开启
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="0" <?php if(!isset($payment['wechat']['is_open']) || (isset($payment['wechat']['is_open']) && $payment['wechat']['is_open'] == 0)){echo 'checked="checked"';}?> />
							关闭
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">手续费：</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" placeholder="" name="fee" value="<?php echo isset($payment['wechat']['fee'] ) && !empty($payment['wechat']['fee']) ? $payment['wechat']['fee'] : 0;?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="微信应用号" name="app_id" value="<?php echo isset($payment['wechat']['config']['app_id'] ) && !empty($payment['wechat']['config']['app_id']) ? $payment['wechat']['config']['app_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="微信应用密钥" name="app_key" value="<?php echo isset($payment['wechat']['config']['app_key'] ) && !empty($payment['wechat']['config']['app_key']) ? $payment['wechat']['config']['app_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="微信商户号" name="mch_id" value="<?php echo isset($payment['wechat']['config']['mch_id'] ) && !empty($payment['wechat']['config']['mch_id']) ? $payment['wechat']['config']['mch_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="微信商户密钥" name="mch_key" value="<?php echo isset($payment['wechat']['config']['mch_key'] ) && !empty($payment['wechat']['config']['mch_key']) ? $payment['wechat']['config']['mch_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"></label>
				<div class="col-sm-7">
					<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
				</div>
			</div>
		</form>
	</div>
	<div role="tabpanel" class="tab-pane"  id="alipay">
		<form class="form-horizontal uio-form-box" id="alipay_form" action="" onsubmit="javascript:return false;">
			<input type="hidden" name="action" value="alipay">
			<input type="hidden" name="mode" value="2">
			<div class="form-group">
				<label  class="col-sm-2 control-label">使用状态：</label>
				<div class="col-sm-7">
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="1" <?php if(isset($payment['alipay']['is_open']) && $payment['alipay']['is_open'] == 1){echo 'checked="checked"';}?> />
							开启
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="0" <?php if(!isset($payment['alipay']['is_open']) || (isset($payment['alipay']['is_open']) && $payment['alipay']['is_open'] == 0)){echo 'checked="checked"';}?> />
							关闭
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">手续费：</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" placeholder="" name="fee" value="<?php echo isset($payment['alipay']['fee'] ) && !empty($payment['alipay']['fee']) ? $payment['alipay']['fee'] : 0;?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="支付宝应用号" name="app_id" value="<?php echo isset($payment['alipay']['config']['app_id'] ) && !empty($payment['alipay']['config']['app_id']) ? $payment['alipay']['config']['app_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<!-- <div class="form-group">
				<label  class="col-sm-2 control-label">应用密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="支付宝应用密钥" name="app_key" value="<?php echo isset($payment['alipay']['config']['app_key'] ) && !empty($payment['alipay']['config']['app_key']) ? $payment['alipay']['config']['app_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div> -->
			<div class="form-group">
				<label  class="col-sm-2 control-label">合作伙伴身份（PID）：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="支付宝合作伙伴身份（PID）" name="mch_id" value="<?php echo isset($payment['alipay']['config']['mch_id'] ) && !empty($payment['alipay']['config']['mch_id']) ? $payment['alipay']['config']['mch_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<!-- <div class="form-group">
				<label  class="col-sm-2 control-label">商户密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="支付宝商户密钥" name="mch_key" value="<?php echo isset($payment['alipay']['config']['mch_key'] ) && !empty($payment['alipay']['config']['mch_key']) ? $payment['alipay']['config']['mch_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div> -->
			<div class="form-group">
				<label class="col-md-2 control-label"></label>
				<div class="col-sm-7">
					<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
				</div>
			</div>
		</form>
	</div>	
	<div role="tabpanel" class="tab-pane"  id="baidu">
		<form class="form-horizontal uio-form-box" id="baidu_form" action="" onsubmit="javascript:return false;">
			<input type="hidden" name="action" value="baidu">
			<input type="hidden" name="mode" value="3">
			<div class="form-group">
				<label  class="col-sm-2 control-label">使用状态：</label>
				<div class="col-sm-7">
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="1" <?php if(isset($payment['baidu']['is_open']) && $payment['baidu']['is_open'] == 1){echo 'checked="checked"';}?> />
							开启
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="0" <?php if(!isset($payment['baidu']['is_open']) || (isset($payment['baidu']['is_open']) && $payment['baidu']['is_open'] == 0)){echo 'checked="checked"';}?> />
							关闭
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">手续费：</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" placeholder="" name="fee" value="<?php echo isset($payment['baidu']['fee'] ) && !empty($payment['baidu']['fee']) ? $payment['baidu']['fee'] : 0;?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="百度应用号" name="app_id" value="<?php echo isset($payment['baidu']['config']['app_id'] ) && !empty($payment['baidu']['config']['app_id']) ? $payment['baidu']['config']['app_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="百度应用密钥" name="app_key" value="<?php echo isset($payment['baidu']['config']['app_key'] ) && !empty($payment['baidu']['config']['app_key']) ? $payment['baidu']['config']['app_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="百度商户号" name="mch_id" value="<?php echo isset($payment['baidu']['config']['mch_id'] ) && !empty($payment['baidu']['config']['mch_id']) ? $payment['baidu']['config']['mch_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="百度商户密钥" name="mch_key" value="<?php echo isset($payment['baidu']['config']['mch_key'] ) && !empty($payment['baidu']['config']['mch_key']) ? $payment['baidu']['config']['mch_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"></label>
				<div class="col-sm-7">
					<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
				</div>
			</div>
		</form>
	</div>
	<div role="tabpanel" class="tab-pane"  id="jingdong">
		<form class="form-horizontal uio-form-box" id="jingdong_form" action="" onsubmit="javascript:return false;">
			<input type="hidden" name="action" value="jingdong">
			<input type="hidden" name="mode" value="4">
			<div class="form-group">
				<label  class="col-sm-2 control-label">使用状态：</label>
				<div class="col-sm-7">
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="1" <?php if(isset($payment['jingdong']['is_open']) && $payment['jingdong']['is_open'] == 1){echo 'checked="checked"';}?> />
							开启
						</label>
					</div>
					<div class="radio-inline">
						<label>
							<input type="radio" name="is_open" value="0" <?php if(!isset($payment['jingdong']['is_open']) || (isset($payment['jingdong']['is_open']) && $payment['jingdong']['is_open'] == 0)){echo 'checked="checked"';}?> />
							关闭
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">手续费：</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" placeholder="" name="fee" value="<?php echo isset($payment['jingdong']['fee'] ) && !empty($payment['jingdong']['fee']) ? $payment['jingdong']['fee'] : 0;?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="京东应用号" name="app_id" value="<?php echo isset($payment['jingdong']['config']['app_id'] ) && !empty($payment['jingdong']['config']['app_id']) ? $payment['jingdong']['config']['app_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">应用密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="京东应用密钥" name="app_key" value="<?php echo isset($payment['jingdong']['config']['app_key'] ) && !empty($payment['jingdong']['config']['app_key']) ? $payment['jingdong']['config']['app_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户号：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="京东商户号" name="mch_id" value="<?php echo isset($payment['jingdong']['config']['mch_id'] ) && !empty($payment['jingdong']['config']['mch_id']) ? $payment['jingdong']['config']['mch_id'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label  class="col-sm-2 control-label">商户密钥：</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" placeholder="京东商户密钥" name="mch_key" value="<?php echo isset($payment['jingdong']['config']['mch_key'] ) && !empty($payment['jingdong']['config']['mch_key']) ? $payment['jingdong']['config']['mch_key'] : '';?>" />
				</div>
				<div class="col-sm-4 tips-msg"></div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label"></label>
				<div class="col-sm-7">
					<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("form#alipay_form").validate({
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#alipay_form").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					showSuccess(res.msg);
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			app_id: {
				required: true,
			},
			app_key: {
				required: true,
			},
			mch_id: {
				required: true,
			},
			mch_key: {
				required: true,
			}
		},
		messages: {		
			app_id: {
				required: "<i class='icon-exclamation-sign'></i>应用号不能为空",
			},
			app_key: {
				required: "<i class='icon-exclamation-sign'></i>应用密钥不能为空",
			},
			mch_id: {
				required: "<i class='icon-exclamation-sign'></i>商户号不能为空",
			},
			mch_key: {
				required: "<i class='icon-exclamation-sign'></i>商户密钥不能为空",
			}
		}
	});
	$("form#wechat_form").validate({
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#wechat_form").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					showSuccess(res.msg);
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			app_id: {
				required: true,
			},
			app_key: {
				required: true,
			},
			mch_id: {
				required: true,
			},
			mch_key: {
				required: true,
			}
		},
		messages: {		
			app_id: {
				required: "<i class='icon-exclamation-sign'></i>应用号不能为空",
			},
			app_key: {
				required: "<i class='icon-exclamation-sign'></i>应用密钥不能为空",
			},
			mch_id: {
				required: "<i class='icon-exclamation-sign'></i>商户号不能为空",
			},
			mch_key: {
				required: "<i class='icon-exclamation-sign'></i>商户密钥不能为空",
			}
		}
	});
	$("form#baidu_form").validate({
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#baidu_form").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					showSuccess(res.msg);
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			app_id: {
				required: true,
			},
			app_key: {
				required: true,
			},
			mch_id: {
				required: true,
			},
			mch_key: {
				required: true,
			}
		},
		messages: {		
			app_id: {
				required: "<i class='icon-exclamation-sign'></i>应用号不能为空",
			},
			app_key: {
				required: "<i class='icon-exclamation-sign'></i>应用密钥不能为空",
			},
			mch_id: {
				required: "<i class='icon-exclamation-sign'></i>商户号不能为空",
			},
			mch_key: {
				required: "<i class='icon-exclamation-sign'></i>商户密钥不能为空",
			}
		}
	});
	$("form#jingdong_form").validate({
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#jingdong_form").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					showSuccess(res.msg);
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			app_id: {
				required: true,
			},
			app_key: {
				required: true,
			},
			mch_id: {
				required: true,
			},
			mch_key: {
				required: true,
			}
		},
		messages: {		
			app_id: {
				required: "<i class='icon-exclamation-sign'></i>应用号不能为空",
			},
			app_key: {
				required: "<i class='icon-exclamation-sign'></i>应用密钥不能为空",
			},
			mch_id: {
				required: "<i class='icon-exclamation-sign'></i>商户号不能为空",
			},
			mch_key: {
				required: "<i class='icon-exclamation-sign'></i>商户密钥不能为空",
			}
		}
	});
});
</script>