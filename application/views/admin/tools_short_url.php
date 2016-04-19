<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active"  id="home">
		<form class="form-horizontal uio-form-box" id="short_url_form" action="" onsubmit="javascript:return false;">
			<input type="hidden" name="action" value="short_url">
			<div class="form-group">
				<label class="col-sm-2 control-label">地址URL：</label>
				<div class="col-sm-7">
					<textarea class="form-control" rows="6" placeholder="输入长地址URL" name="long_url"></textarea>
				</div>
				<div class="col-sm-3 tips-msg"></div>
			</div>
			<div class="form-group" id="short_url" style="display: none;">
				<label class="col-sm-2 control-label">短地址URL：</label>
				<div class="col-sm-7">
					<a href="javascript:;" class="btn-copy" data-clipboard-text=""></a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>
				<div class="col-sm-7">
					<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-3" value="转短地址" />
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
var storeLevelList = <?php echo isset($system['storeLevel']) ? $system['storeLevel'] : json_encode(array());?>;
$(function(){
	$("form#short_url_form").validate({ 
		errorPlacement: function(error, element){
			$(element).parent().siblings(".tips-msg").append(error);
	    },
		errorElement: 'label',
		submitHandler: function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
			var data = formatForm($("form#short_url_form").serializeArray());
			postData(data, function(res){
				if(res.code == 0){
					$("#short_url > div > a").attr('data-clipboard-text', res.data.url).text(res.data.url);
					$("#short_url").show();
				}else{
					showError(res.msg);
				}
			});
		},
		errorClass: 'invalid',
		rules: {
			long_url: {
				required: true,
				url: true
			}
		},
		messages: {
			long_url: {
				required: "<i class='icon-exclamation-sign'></i>长地址链接不能为空",
				url: "<i class='icon-exclamation-sign'></i>必须输入正确格式的网址"
			}
		}
	});
});
</script>