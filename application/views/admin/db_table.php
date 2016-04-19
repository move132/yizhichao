<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:50px;">编号</th>
			<th>数据表</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $key=>$item){ ?>
		<tr>
			<td><?php echo $key + $offset + 1;?></td>
			<td><a class="buttonList" href="javascript:;"><?php echo $item;?></a></td>
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