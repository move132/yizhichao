<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="uio-shop-box">
	<form class="form-horizontal" id="admin_add" action="" onsubmit="javascript:return false;">
		<input type="hidden" name="action" value="insert" />
		<div class="form-group">
			<label for="account" class="col-md-2 control-label">登录帐号：</label>
			<div class="col-sm-10" style="padding-left:0;">
				<div class="col-sm-8">
					<input type="text" class="form-control"  name="account" placeholder="登录帐号" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div> 
		<div class="form-group">
			<label for="password" class="col-md-2 control-label">登录密码：</label>		 
			<div class="col-sm-10" style="padding-left:0;">
				<div class="col-sm-8">
					<input type="password" class="form-control" id="password"  name="password" placeholder="登录密码" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="endpassword" class="col-md-2 control-label">确认密码：</label> 
			<div class="col-sm-10" style="padding-left:0;">
				<div class="col-sm-8" >
					<input type="password" class="form-control" name="endpassword" placeholder="确认密码" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">员工名称：</label>
			<div class="col-sm-10" style="padding-left:0;">
				<div class="col-sm-8">
					<input type="text" class="form-control"  name="name" placeholder="员工名称" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-md-2 control-label">员工邮箱：</label>
			<div class="col-sm-10" style="padding-left:0;">
				<div class="col-sm-8">
					<input type="text" class="form-control"  name="email" placeholder="员工邮箱" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div> 
		<div class="form-group">
			<label for="inputEmail3" class="col-md-2 control-label"></label>
			<div class="col-sm-5">
				<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" /> 
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		$("form#admin_add").validate({ 
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
			var data = formatForm($("form#admin_add").serializeArray());
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
			account: {
				required: true,
			},
			password: {
				required: true,
				minlength: 6
			}, 
			endpassword: {
				required: true,
				equalTo: "#password"
			},
			name: {
				required: true,
			}
		},
		messages: {
			account: {
				required: "<i class='icon-exclamation-sign'></i>登录帐号不能为空",
			},
			password: {
				required: "<i class='icon-exclamation-sign'></i>登录密码不能为空",
				minlength:"<i class='icon-exclamation-sign'></i>登录密码不能少于6位"
			}, 
			endpassword: {
				required: "<i class='icon-exclamation-sign'></i>确认密码不能为空",
				equalTo:"<i class='icon-exclamation-sign'></i>2次密码不一致"
			},
			name: {
				required: "<i class='icon-exclamation-sign'></i>员工名称不能为空",
			}
		}
	});
	});
</script>