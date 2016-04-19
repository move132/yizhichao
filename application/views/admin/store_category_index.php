<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:50px;">编号</th>
			<th>类目名称</th>
			<th style="width:15%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id="<?php echo $class_parent_id.'-'.$item['class_id'];?>" data-level="<?php echo $item['class_type'];?>">
			<td><?php echo $item['class_id'];?></td>
			<td><?php echo $item['class_name'];?></td>
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
		if($(this).parents('tr').attr('data-level') > 1){
			return false;
		}
		var data = {};
		data.class_parent_id =  $(this).parents('tr').attr('data-id');
		clickPage(require_page_url, data);
	});	
});
</script>