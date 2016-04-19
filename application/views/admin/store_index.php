<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>

<div class="panel_search_form bg" >
	<span class="label-title">商户号：</span>	 
	<input type='text' value='<?php echo isset($select['select_pid']) ? $select['select_pid'] :'' ; ?>' name='pid' class="form-control select_pid" />
	<span class="label-title">店铺名称：</span> 
	<input type='text' value='<?php echo isset($select['select_name']) ? $select['select_name'] :'' ; ?>' name='name' class="form-control select_name" />
	<button class="btn btn-default select" >查询</button>
	<button class='btn btn-default reset' >重置</button>
</div>

<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:80px;">商户号</th>
			<th >店铺名称</th>
			<th >所在地区</th>
			<th >店铺等级</th>
			<th >访问时间</th>
			<th  style="width:15%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id="<?php echo $item['id'];?>" data-phone="<?php echo $item['tel'];?>">
			<td><?php echo $item['pid'];?></td>
			<td><?php echo $item['name'];?></td>
			<td>
				<?php echo getStrAddr($item['region_0'], $item['region_1'], $item['region_2'], $item['region_3'], $region);?>
				<br/>
				<?php echo $item['addr_info'];?>
			</td>
			<td>
				<?php if($system['storeLevel']){ ?>
				<?php $aStoreLevel = json_decode($system['storeLevel'], true);echo isset($aStoreLevel[$item['level']]) ? $aStoreLevel[$item['level']] : '未知' ?>				
				<?php }else{ ?>
				未设置
				<?php } ?>
			</td>
			<td><?php echo $item['last_login_time'] ? date("Y-m-d H:i", $item['last_login_time']) : '未登录';?></td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">详情</button>
				<button type="button" class="buttonViews btn btn-link btn-sm"  
				onclick ="clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'store_money'));?>',
				 {'sid':<?php echo $item['id'];?>,'store_name':'<?php echo $item['name']; ?>'})"   >账本</button>
				<button type="button" class="buttonViews btn btn-link btn-sm"
				onclick ="clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'store_seller'));?>',
				 {'sid':<?php echo $item['id'];?>,'store_name':'<?php echo $item['name']; ?>'})">店员</button>
				<button type="button" class="buttonStatus btn btn-<?php echo $item['status'] == 1 ? 'danger' : 'success';?> btn-sm">
					<?php echo $item['status'] == 1 ? '停运' : '启用';?>
				</button>
			</td>
		</tr>
		<?php } ?>	 
	</tbody>
</table>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	$(".buttonView").click(function(){
		var id = $(this).parents('tr').attr('data-id');
		var url = "<?php echo site_url(array($this->router->directory, $this->router->class, 'view?id='));?>"+id;
		showOpen(title='商铺详情',url,["500px","600px"]);
	});
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'status';
		data.id = $(this).parents('tr').attr('data-id');
		data.phone = $(this).parents('tr').attr('data-phone');
		var e = $(this);
		data.status = e.hasClass('btn-success') ? 1 : 0;
		postData(data, function(res){
			if(res.code == 0){
				if(e.hasClass('btn-success')){//启用
					e.removeClass('btn-success').addClass('btn-danger').text('停运');
				}else if(e.hasClass('btn-danger')){//停运
					e.removeClass('btn-danger').addClass('btn-success').text('启用');
				}
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});	
	
	//查询模块
	select_data = {};
	select_data.pid = $('.select_pid').val();
	select_data.name = $('.select_name').val();
	$('.select').click(function(){
		var select_pid = $('.select_pid').val();
		var select_name = $('.select_name').val();
		var data = {};
		data.select = {};
		data.select.pid = select_pid;
		data.select.name = select_name;
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {};
		data.select.pid = '';
		data.select.name = '';
		setMainPage(data);
	})

	


	
});	
</script>