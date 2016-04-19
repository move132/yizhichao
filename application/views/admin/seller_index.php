<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg" >
	<span class="label-title">店员：</span>
	<input type="text"  class="form-control " id='name' placeholder="" value='<?php echo isset($select['name'])? $select['name'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button> 
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>性别</th>
			<th>店员</th>
			<th>所属店铺</th>
			<th>激活状态</th>
			<th>激活时间</th>
			<th>上次登录</th>
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
				<dl class="goods-name" style="padding-left: 40px;  ">
					<dt><?php echo strSeller($item['shopowner'])?></dt>
					<dt>积分：<?php echo $item['points']?></dt>
					<dt>
						昵称：<?php echo $item['nickname']?>
					</dt>
					<dd style="text-align: left;">邀请时间：<?php echo formatTime($item['atime']);?></dd>
				</dl>
			</td>
			<td><?php echo $item['name'];?></td>
			<td><?php echo $item['status']==1 ? '已激活' : '未激活';?></td>
			<td><?php echo $item['stime'] ? formatTime($item['stime'], 2) : '未激活';?></td>
			<td><?php echo $item['ltime'] ? formatTime($item['ltime'], 2) : '未激活';?></td>			
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