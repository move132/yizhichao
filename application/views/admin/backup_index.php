<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>文件名</th>
			<th>大小</th>
			<th>日期</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-name="<?php echo $item['name'];?>">
			<td><?php echo $item['name'];?></td>
			<td><?php echo byte_format($item['size'], 2);?></td>
			<td><?php echo formatTime($item['date'], 2);?></td>
			<td>
				<button type="button" class="buttonList btn btn-link btn-sm">
					<a href="<?php echo site_url(array($this->router->directory, $this->router->class, 'download')).'?filename='.$item['name'];?>">下载</a>
				</button>
				<button type="button" class="buttonStatus btn btn-danger btn-sm">删除</button>
			</td>
		</tr>
		<?php } ?>	 
	</tbody>
</table>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'unlink';
		data.filename = $(this).parents('tr').attr('data-name');
		var t = $(this);
		postData(data, function(res){
			if(res.code == 0){
				t.parents('tr').remove();
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});	
});
</script>