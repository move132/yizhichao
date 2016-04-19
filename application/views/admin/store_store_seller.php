<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>编号</th>
			<th>店员</th>
		
			<th>上次登录</th>
			<th>激活状态</th>
			<th width="20%">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr>
			<td><?php echo $item['id'] ?></td>
			<td>
				<div class="pic-thumb" style="float: left;">
					<img src="<?php echo $item['header']?$item['header']:base_url('static/image/lib/seller_defaul.png') ?>" width="32" height="32">
				</div>
				<dl class="goods-name" style="padding-left: 40px;">
					<dt>
						<?php echo $item['nickname']?>						</dt>
					<dd style="text-align: left;">邀请时间：<?php echo mdate("%Y/%m/%d %H:%i", $item['atime']);?></dd>
				</dl>
			</td>
		
			<td><?php echo $item['ltime'] ? mdate("%Y/%m/%d %H:%i", $item['ltime']) : '重未登录';?></td>
			<td><?php echo $item['status']==1 ? '已激活' : '未激活';?></td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">详情</button>
				<button type="button" class="buttonEdit btn btn-link btn-sm">编辑</button>
			</td>
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