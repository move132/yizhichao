<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg" >
	<span class="label-title">员工姓名：</span>
	<input type="text"  class="form-control " id='name' placeholder="" value='<?php echo isset($select['name'])? $select['name'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button>
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>员工姓名</th>
			<th>登录账号</th>
			<th>添加时间</th>
			<th>登录次数</th>
			<th>上次登陆</th>
			<th>状态</th>
			<th width="20%">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id='<?php echo $item['id']  ?>' data-name='<?php echo $item['name']?>'  >
			<td><?php echo $item['name'];?></td>
			<td><?php echo $item['account'];?></td>
			<td><?php echo mdate("%Y/%m/%d %H:%i", $item['atime']);?></td>
			<td><?php echo $item['num'];?></td>
			<td><?php echo $item['last_time'] ? mdate("%Y/%m/%d %H:%i", $item['last_time']) : '首次';?></td>
			<td>
				<?php if($item['disable']){?>
				<span class="text-danger">禁用</span>
				<?php }else{ ?>
				<span class="text-success">正常</span>
				<?php } ?>
			</td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">详情</button>
				<button type="button" class="buttonEdit btn btn-success btn-sm">重置</button>
				<button type="button" class="buttonStatus btn btn-<?php echo $item['disable'] == 0 ? 'danger' : 'success';?> btn-sm">
					<?php echo $item['disable'] == 0 ? '停用' : '启用';?>
				</button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php echo $pagination;?>
<script>
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	//详情
	$('.buttonView').click(function(){
		var id = $(this).parents('tr').attr('data-id');
		var url = "<?php echo site_url(array($this->router->directory, $this->router->class, 'view?id='));?>"+id;
		showOpen(title='管理员详情',url,["500px","600px"]);
	})
	//重置密码
	$(".buttonEdit").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'update';
		data.id = $(this).parents('tr').attr('data-id');
		data.name = $(this).parents('tr').attr('data-name');
		postData(data, function(res){
			if(res.code == 0){
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});
	//操作消息状态
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'status';
		data.id = $(this).parents('tr').attr('data-id');
		data.name = $(this).parents('tr').attr('data-name');
		var e = $(this);
		data.status = e.hasClass('btn-success') ? 0 : 1;
		postData(data, function(res){
			if(res.code == 0){
				if(e.hasClass('btn-success')){//启用
					e.removeClass('btn-success').addClass('btn-danger').text('停用');
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