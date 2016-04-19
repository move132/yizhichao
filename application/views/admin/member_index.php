<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg" >
	<span class="label-title">会员姓名：</span>
	<input type="text"  class="form-control " id='name' placeholder="" value='<?php echo isset($select['name'])? $select['name'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button> 
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>性别</th>
			<th>会员</th>
			<th>消费次数</th>
			<th>消费总金额</th>
			<th>最近消费</th>
			<th width="20%">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr>
			<td><?php echo strSex($item['sex']); ?></td>
			<td>
				<div class="pic-thumb" style="float: left;">
					<img src="<?php echo $item['header']?$item['header']:base_url('static/image/lib/seller_defaul.png') ?>" width="32" height="32">
				</div>
				<dl class="goods-name" style="padding-left: 40px;">
					<dt><?php echo strAccountSource($item['mode']);?></dt>
					<dt>
						<?php echo $item['name']?>
					</dt>
					<dd style="text-align: left;">首次消费：<?php echo formatTime($item['first_time']);?></dd>
				</dl>
			</td>
			<td><?php echo $item['num'];?></td>
			<td><?php echo $item['money'];?></td>
			<td><?php echo $item['last_time'] ? formatTime($item['last_time'], 2) : '首次';?></td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">订单</button>
				<button type="button" class="buttonEdit btn btn-link btn-sm">店铺</button>
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