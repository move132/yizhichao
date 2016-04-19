<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="uio-shop-box">
	<form class="form-horizontal" id="store_add" action="" onsubmit="javascript:return false;">
		<input type="hidden" name="action" value="insert" />
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">店铺名称：</label>
			<div class="col-sm-10">
				<div class="col-sm-8">
					<input type="text" class="form-control" id="name" name="name" placeholder="店铺名称" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword3" class="col-md-2 control-label">所在地区：</label>
			<div class="col-sm-10"> 
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
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="addr_info" class="col-md-2 control-label">详情地址：</label>
		 
			<div class="col-sm-10">
				<div class="col-sm-8">
					<input type="text" class="form-control" id="addr_info" name="addr_info" placeholder="详情地址" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="person" class="col-md-2 control-label">联系人：</label> 
			<div class="col-sm-10">
				<div class="col-sm-8">
					<input type="text" class="form-control" id="person" name="person" placeholder="联系人" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="tel" class="col-md-2 control-label">联系电话：</label> 
			<div class="col-sm-10">
				<div class="col-sm-8"><input type="text" class="form-control" id="tel" name="tel" placeholder="联系电话" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div> 
		<div class="form-group">
			<label for="inputEmail3" class="col-md-2 control-label"></label>
			<div class="col-sm-5">
				<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
				<!-- <button type="button" id="buttonSubmit" class="btn btn-success col-sm-3">提交</button> -->
			</div>
		</div>
	</form>
</div>
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

	$("form#store_add").validate({ 
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
			var data = formatForm($("form#store_add").serializeArray());
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
			region_3: {
				required: true
			}, 
			addr_info: {
				required: true,
			},
			person: {
				required: true,
			},
			tel: {
				required: true,
			}
		},
		messages: {
			name: {
				required: "<i class='icon-exclamation-sign'></i>不能为空",
			},
			region_3: {
				required: "<i class='icon-exclamation-sign'></i>不能为空",
			}, 
			addr_info: {
				required: "<i class='icon-exclamation-sign'></i>不能为空",
			},
			person: {
				required: "<i class='icon-exclamation-sign'></i>不能为空",
			},
			tel: {
				required: "<i class='icon-exclamation-sign'></i>不能为空",
			}
		}
	});
});
</script>