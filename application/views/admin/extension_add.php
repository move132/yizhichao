<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<?php $account_info = $this->aSession['account_type'] == 1 ? '代理人' : '推广员';?>
<form class="form-horizontal" id="agent_add" action="" onsubmit="javascript:return false;">
	<input type="hidden" name="action" value="insert" />
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $account_info.'信息';?></div>
		<div class="uio-shop-box margin20">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">姓名：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8">
						<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $account_info.'姓名';?>" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="phone" class="col-md-2 control-label">登录手机号：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8" >
						<input type="text" class="form-control" id="phone" name="phone" placeholder="<?php echo $account_info.'手机号';?>" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="idc_number" class="col-md-2 control-label">身份证：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8">
						<input type="text" class="form-control" id="idc_number" name="idc_number" value='' placeholder="身份证" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-md-2 control-label">所在地区：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8">
						<div class="col-sm-12 nopad">
							<div class="col-sm-4 nopad">
								<select class="form-control" id="region_1" name="region_1">
									<option value="">-请选择-</option>
								</select>
							</div>
							<div class="col-sm-4 nopad">
								<select class="form-control" id="region_2" name="region_2">
									<option value="">-请选择-</option>
								</select>
							</div>
							<div class="col-sm-4 nopad">
								<select class="form-control" id="region_3" name="region_3">
									<option value="">-请选择-</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="addr_info" class="col-md-2 control-label">详情地址：</label>

				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8">
						<input type="text" class="form-control" id="addr_info" name="addr_info" placeholder="详情地址" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<?php if($this->aSession['account_type'] == 1){ ?>
			<div class="form-group">
				<label for="promoter_fee" class="col-md-2 control-label">授权分佣：</label>

				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-2">
						<input type="text" class="form-control" id="promoter_fee" name="promoter_fee" placeholder="" value="0" />
					</div>
					<div class="col-sm-4">
						<label class="control-label" style="width: 100%">实际分佣 = 授权分佣 - 下线分佣</label>
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<?php } ?>
			<div class="form-group">
				<label for="store_fee" class="col-md-2 control-label">店铺分佣：</label>

				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-2">
						<input type="text" class="form-control" id="store_fee" name="store_fee" placeholder="" value="0" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">提现银行信息</div>
		<div class="uio-shop-box margin20">
			<div class="form-group">
				<label for="card_account" class="col-md-2 control-label">开户姓名：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8">
						<input type="text" class="form-control" id="card_account" name="card_account" placeholder="提现绑定银行开户姓名" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="card_id" class="col-md-2 control-label">银行卡号：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-8" >
						<input type="text" class="form-control" id="card_id" name="card_id" placeholder="提现绑定银行卡号" />
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="bank_id" class="col-md-2 control-label">开户银行：</label>
				<div class="col-sm-10" style="padding-left:0;">
					<div class="col-sm-4">
						<select class="form-control" id="bank_id" name="bank_id">
						<?php foreach($bank as $item){ ?>
						<option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
						<?php } ?>
						</select>
					</div>
					<div class="col-sm-4 tips-msg"></div>
				</div>
			</div>			
		</div>
	</div>
	<div class="col-sm-5 col-md-offset-1">
		<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
	</div>
</form>

<script type="text/javascript">
$(function(){
	function init_region_2(i_region_2){
		if(i_region_2){
			var s_region_3 = '';
			for(var i in regions[i_region_2]){
				s_region_3 += '<option value="'+i+'">'+regions[i_region_2][i]+'</option>'
			}
			$("#region_3").html(s_region_3);
		}else{

		}
	}

	var s_region_1 = '';
	for(var i in regions[1]){
		s_region_1 += '<option value="'+i+'">'+regions[1][i]+'</option>'
	}
	$("#region_1").append(s_region_1);

	$("#region_1").change(function(){
		var i_region_1 = parseInt($(this).val());
		if(i_region_1){
			var s_region_2 = '';
			var default_id = 0
			for(var i in regions[i_region_1]){
				if(!default_id){
					default_id = i;
				}
				s_region_2 += '<option value="'+i+'">'+regions[i_region_1][i]+'</option>'
			}
			$("#region_2").html(s_region_2);
			init_region_2(default_id);
		}else{

		}
	});

	$("#region_2").change(function(){
		var i_region_2 = parseInt($(this).val());
		init_region_2(i_region_2);
	});

	$("form#agent_add").validate({ 
		errorPlacement: function(error, element){
			if($(element).parent().siblings(".tips-msg").length){
				$(element).parent().siblings(".tips-msg").append(error);
			}else{
				$(element).parent().parent().parent().siblings(".tips-msg").append(error);
			}

	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#agent_add").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					layer.confirm(
						res.msg,
						{
							icon:1,
							btn: ['继续添加', '查看列表']
						}, function(index){
							layer.close(index);
							clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'add'));?>');
						}, function(index){
							layer.close(index);
							clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'index'));?>');
						}
					);
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			name: {
				required: true,
			},
			phone: {
				required: true,
				digits: true,
				remote: {
					url: '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>',
					type: 'post',
					data: {action: 'checkPhone', phone: function(){
						return $("#phone").val();
					}}
				}
			},
			idc_number: {
				required: true,
				digits: true,
			},
			region_3: {
				required: true
			}, 
			addr_info: {
				required: true,
			},
			promoter_fee: {
				required: true,
				range: [0, 1]
			},
			store_fee: {
				required: true,
				range: [0, 1]
			},
			card_account: {
				required: true,
			},
			card_id: {
				required: true,
			}
		},
		messages: {
			name: {
				required: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>姓名不能为空",
			},
			phone: {
				required: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>手机号不能为空",
				digits: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>手机号格式错误",
				remote: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>手机号已被注册",
			},		
			idc_number: {
				required: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>身份证不能为空",
				digits: "<i class='icon-exclamation-sign'></i><?php echo $account_info;?>身份证格式错误",
			},
			region_3: {
				required: "<i class='icon-exclamation-sign'></i>所在地区不能为空",
			}, 
			addr_info: {
				required: "<i class='icon-exclamation-sign'></i>详细地址不能为空",
			},
			promoter_fee: {
				required: "<i class='icon-exclamation-sign'></i>授权分佣不能为空",
				range: "<i class='icon-exclamation-sign'></i>分佣限定在{0}到{1}之间",
			},
			store_fee: {
				required: "<i class='icon-exclamation-sign'></i>店铺分佣不能为空",
				range: "<i class='icon-exclamation-sign'></i>分佣限定在{0}到{1}之间",
			},
			card_account: {
				required: "<i class='icon-exclamation-sign'></i>开户姓名不能为空",
			},
			card_id: {
				required: "<i class='icon-exclamation-sign'></i>银行卡号不能为空",
			}
		}
	});
});


</script>