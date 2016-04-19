<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="uio-shop-box">
	<form class="form-horizontal" id='news_add' action="" onsubmit="javascript:return false;" >
		<?php if(isset($list)){?>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="id" value="<?php echo $list['id']?>" />
		<?php }else{ ?>
			<input type="hidden" name="action" value="insert" />
		<?php 	}?>
		
		<div class="form-group">
			<label for="" class="col-md-2 control-label">消息标题：</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" id="title" name='title' value='<?php echo isset($list['title']) ? $list['title'] : '';?>'  placeholder="消息标题" />
			</div>
			<div class="col-sm-4 tips-msg">
					 
				</div>
		</div>
		<div class="form-group">
			<label for="" class="col-md-2 control-label">消息内容：</label>
			<div class="col-md-10">
				<div id='summernote'><?php echo isset($list['content']) ? $list['content'] : ''; ?></div>
			</div>
			<div class="col-sm-4 tips-msg">
			</div>
		</div> 
		<div class="form-group">
			<label for="" class="col-md-2 control-label"></label>
			<div class="col-sm-5">
				<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="提交" />
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
$(function(){
	$('#summernote').summernote({
		lang: 'zh-CN',
		height: 240,            
	});
	$("form#news_add").validate({ 
		errorPlacement: function(error, element){
			if($(element).parent().siblings(".tips-msg").length){
				$(element).parent().siblings(".tips-msg").append(error);
			}else{
				$(element).parent().parent().parent().siblings(".tips-msg").append(error);
			}
	    },
		errorElement: 'label',
		submitHandler: function(){
			if ($('#summernote').summernote('isEmpty')) {
				showError('消息内容不能为空');	
				 return ;
			} 
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#news_add").serializeArray());
			var markupStr = $('#summernote').summernote('code');
				data.content = markupStr;
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
			title: {
				required: true,
			},
		},
		messages: {
			title: {
				required: "<i class='icon-exclamation-sign'></i>消息标题不能为空",
			},
		}
	});
});



</script>

































