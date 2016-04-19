<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:50px;">编号</th>
			<th>银行名称</th>
			<th style="width:15%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id="">
			<td><?php echo $item['id'];?></td>
			<td><?php echo $item['name'];?></td>
			<td>
				<button type="button" class="buttonList btn btn-link btn-sm">详情</button>				
			</td>
		</tr>
		<?php } ?>	 
	</tbody>
</table>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	$(".buttonList").click(function(){
		return false;
		var data = {};
		clickPage(require_page_url, data);
	});	
});
</script>