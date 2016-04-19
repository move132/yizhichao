<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="uio-shop-box">
	<form class="form-horizontal" id="area_add" action="" onsubmit="javascript:return false;">
		<input type="hidden" name="action" value="insert" />
		<input type="hidden" name="class_parent_id" value="<?php echo $class_parent_id;?>" />
		<div class="form-group">
			<label for="class_name" class="col-md-2 control-label">地区名称：</label>
			<div class="col-sm-10">
				<div class="col-sm-8">
					<input type="text" class="form-control" id="class_name" name="class_name" placeholder="地区名称：" />
				</div>
				<div class="col-sm-4 tips-msg">
					 
				</div>
			</div>
		</div>		
		<div class="form-group">
			<label for="inputEmail3" class="col-md-2 control-label"></label>
			<div class="col-sm-5" style="margin-left:15px;">
				<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
$(function(){
	$("form#area_add").validate({ 
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#area_add").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					layer.confirm(
						res.msg,
						{
							icon:1,
							btn: ['继续添加', '查看列表']
						}, function(index){
							layer.close(index);
							clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'add'));?>', {class_parent_id: $("input[name='class_parent_id']").val()});
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
			class_name: {
				required: true,
			}
		},
		messages: {
			class_name: {
				required: "<i class='icon-exclamation-sign'></i>地区名称不能为空",
			}
		}
	});
});
</script>