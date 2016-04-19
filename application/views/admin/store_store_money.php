<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>变动后资金</th>
			<th>变动后冻结资金</th>
			<th>变动后记账资金</th>
			<th>变动金额</th>
			<th>操作时间</th>
			<th>备注</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr>
			<td><?php echo $item['money'];?></td>
			<td><?php echo $item['frozen_money'];?></td>
			<td><?php echo $item['bill_money'];?></td>
			<td><?php echo $item['diffmoney'];?></td>
			<td>
				<?php echo mdate("%Y/%m/%d %H:%i", $item['atime']);?>
			</td>
			<td><?php echo $item['remark'];?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php echo $pagination;?>
<script>
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	//查询模块
	select_data = {};
	select_data.name = $('#name').val();
	$('.select').click(function(){
		var  name = $('#name').val();
		var data = {}; 
		data.select = {}; 
		data.select.name = name;
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {}; 
		data.select.name = '';
		setMainPage(data);
	})
	
})
</script>