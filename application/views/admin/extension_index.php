<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg" >
	<span class="label-title">姓名：</span>
	<input type="text"  class="form-control " id='name' placeholder="" value='<?php echo isset($select['name'])? $select['name'] :''; ?>' />
	<span class="label-title">联系电话：</span>
	<input type="text"  class="form-control " id='tel' placeholder="" value='<?php echo isset($select['tel'])? $select['tel'] :''; ?>' /> 
	<input type='hidden' id='parent_id' value='<?php echo isset($select['parent_id'])? $select['parent_id']:''; ?>' > 
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button>
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:80px;">姓名</th>
			<th >联系电话</th>
			<th >所在地区</th>
			<th >资金</th>
			<th >加入时间</th>
			<th style="width:15%;">推广</th>
			<th style="width:15%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-parent-id='<?php echo $item['parent_id']?>' data-id="<?php echo $item['id'];?>" data-phone="<?php echo $item['phone'];?>" data-name="<?php echo $item['name'];?>" >
			<td><?php echo $item['name'];?></td>
			<td><?php echo $item['phone'];?></td>
			<td>
				<?php echo getStrAddr($item['region_0'], $item['region_1'], $item['region_2'], $item['region_3'], $region);?>
				<br/>
				<?php echo $item['addr_info'];?>
			</td>
			<td><?php echo '可用：￥'.$item['money']."<br/>".'冻结：￥'.$item['frozen_money']."<br/>".'已提取：￥'.$item['finish_money'];?></td>
			<td><?php echo formatTime($item['atime'], 2);?></td>
			<td class="uio-code">
				<p class="label-txt">链接方式</p>
				<?php if($item['parent_id'] == 0){ ?>
				<button type="button" class="btn btn-link btn-sm btn-copy" data-clipboard-text="<?php echo $this->agent_promoter_model->getPromoterUrl($item['id']);?>">推广员</button>
				<?php } ?>
				<button type="button" class="btn btn-link btn-sm btn-copy" data-clipboard-text="<?php echo $this->agent_promoter_model->getStoreUrl($item['id']);?>">店铺</button>
				<p class="label-txt">二维码方式<span class ='repairScan'>【修复】</span></p>
				<?php if($item['parent_id'] == 0){ ?>
				<a href="<?php echo site_url(array($this->router->directory, $this->router->class, 'download?id='.$item['id'].'&parent_id='.$item['parent_id'].'&type=1'));?>" target="_blank">
					<button type="button" class="btn btn-link btn-sm">推广员</button>
					<img class="trans-fadeout" data-src="<?php 
						$file = getScanFileName(1, $item['id'], $item['parent_id']);
						echo base_url($file); ?>" />
				</a>
				<?php } ?>
				<a href="<?php echo site_url(array($this->router->directory, $this->router->class, 'download?id='.$item['id'].'&parent_id='.$item['parent_id'].'&type=2'));?>" target="_blank">
					<button type="button" class="btn btn-link btn-sm">店铺</button>
					<img class="trans-fadeout"  data-src="<?php 
						$file = getScanFileName(2, $item['id'], $item['parent_id']);
						echo base_url($file); ?>" />
				</a>
			</td>
			<td>
				<button type="button" class="buttonEdit btn btn-link btn-sm">编辑</button>
				<button type="button" class="buttonMoneyLog btn btn-link btn-sm">资金流</button>
				<?php if($item['parent_id'] == 0){ ?>
				<button type="button" class="buttonUser btn btn-link btn-sm" onclick="clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>', {'parent_id':<?php echo $item['id'];?>})">推广员</button>
				<?php } ?>
				<button type="button" class="buttonStatus btn btn-<?php echo $item['status'] == 1 ? 'danger' : 'success';?> btn-sm">
					<?php echo $item['status'] == 1 ? '禁用' : '开通';?>
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
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'status';
		data.id = $(this).parents('tr').attr('data-id');
		data.phone = $(this).parents('tr').attr('data-phone');
		data.name = $(this).parents('tr').attr('data-name');
		var e = $(this);
		data.status = e.hasClass('btn-success') ? 1 : 0;
		postData(data, function(res){
			if(res.code == 0){
				if(e.hasClass('btn-success')){//开通
					e.removeClass('btn-success').addClass('btn-danger').text('禁用');
				}else if(e.hasClass('btn-danger')){//禁用
					e.removeClass('btn-danger').addClass('btn-success').text('开通');
				}
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});

	//修复二维码功能
	$('.repairScan').click(function(){
 		var id = $(this).parents('tr').attr('data-id');
		var parent_id = $(this).parents('tr').attr('data-parent-id');
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'repairScan'));?>';
		var data = {};
		data.id = id;
		data.parent_id = parent_id;
		data.type = 2;
		postData(data, function(res){
			if(res.code == 0){
				showSuccess(res.msg);
				
			}else{
				showError(res.msg);
			}
		});
	})
	
	//查询模块
	select_data = {};
	select_data.name = $('#name').val();
	select_data.tel = $('#tel').val();
	select_data.parent_id = $('#parent_id').val(); 
	$('.select').click(function(){
		var  name = $('#name').val();
		var  tel = $('#tel').val();
		var data = {}; 
		data.select = {};
		data.select.name = name;
		data.select.parent_id = $('#parent_id').val();
		data.select.tel = tel
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {};
		data.select.name = '';
		data.select.tel = '';
		data.select.parent_id = $('#parent_id').val();
		setMainPage(data);
	});


	$(".uio-code a").hover(function(){ 
		var src=$(this).find("img").attr("data-src"); 
		if (!$(this).find("img").attr("src")) {
			$(this).find("img").attr("src",src);
		};
	});
});	
</script>