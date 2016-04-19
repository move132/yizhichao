<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
 
	<div class="alert alert-danger" role="alert">
		支付宝当面付应用授权【商户授权给开发者第三方应用】
		<a href="javascript:;" class="btn-copy" data-clipboard-text="https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.hmXZrH&treeId=26&articleId=850&docType=4">支付接口</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:;" class="btn-copy" data-clipboard-text="https://doc.open.alipay.com/doc2/detail.htm?treeId=115&articleId=104110&docType=1">第三方应用授权</a>
	</div>
	 
	<form class="form-horizontal uio-form-box" id="app_auth_token_form" action="" onsubmit="javascript:return false;">
		<input type="hidden" name="action" value="app_auth_token">
		<div class="form-group">
			<label class="col-sm-2 control-label">授权地址：</label>
			<div class="col-sm-2" style="padding-top: 7px;">
				<a href="javascript:;" class="btn-copy" data-clipboard-text="https://openauth.alipay.com/oauth2/appToAppAuth.htm?app_id=<?php echo ALIPAY_THIRD_PARTY_APP_APPID;?>&redirect_uri=<?php echo urlencode(site_url('app/app_auth_token/authorization_code'));?>">引导授权</a>
			</div>

			<input type="submit" name="buttonSubmit" id="buttonSubmit" class="btn btn-success col-sm-2" value="刷新授权" />
		</div>			
	</form>
	 
 
<script type="text/javascript">
var storeLevelList = <?php echo isset($system['storeLevel']) ? $system['storeLevel'] : json_encode(array());?>;
$(function(){
	$("#buttonSubmit").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {'action': 'refresh_token'};
		postData(data, function(res){
			if(res.code == 0){
				showSuccess("操作成功");
			}else{
				showError(res.msg);
			}
		});
	});
});
</script>