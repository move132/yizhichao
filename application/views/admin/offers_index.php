<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="main_hd notpd">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="uio-tab-items <?php if($select['offers_type'] == 'store'){ ?>active<?php }?>">
			<a href="#store" aria-controls="store" role="tab" data-toggle="tab">店铺</a>
		</li>
		<li role="presentation" class="uio-tab-items <?php if($select['offers_type'] == 'agent'){ ?>active<?php }?>">
			<a href="#agent" aria-controls="agent" role="tab" data-toggle="tab">代理/推广员</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<?php if($select['offers_type'] == 'store'){ ?>
	<div role="tabpanel" class="tab-pane active"  id="store">
		<div class="panel_search_form bg" >
			<span class="label-title">店铺名称：</span>
			<input type="text"  class="form-control account" id='keyword' placeholder="" value='<?php echo isset($select['keyword'])? $select['keyword'] :''; ?>' />
			<span class="label-title"  >申请时间：</span>
			<input type="text" id="date_timepicker_start"   class="form-control" placeholder="开始时间" value='<?php echo isset($select['time_start'])? $select['time_start'] :''; ?>' />
			<span  class="lable" >-</span>
			<input type="text" id="date_timepicker_end"     class="form-control" placeholder="结束时间" value='<?php echo isset($select['time_end'])? $select['time_end'] :''; ?>' />
			<button class="btn btn-default select">查询</button>
			<button class="btn btn-default reset">重置</button>
			<div class="pull-right">
				<a href="javascript:;" class="btn btn-link">上传表格</a>
				<a href="<?php echo site_url('admin/offers/excel').'?offers_type='.$select['offers_type'].'&offers_status=export';?>" target="_blank" class="btn btn-link">下载表格</a>
			</div> 
		</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width:10%;">店铺</th>
					<th style="width:100px;">账户余额</th>
					<th  style="width:160px;">提现</th>
					<th style="width:110px;">历史冻结</th>
					<th  style="width:110px;">成功提现</th>
					<th style="width:150px;">申请时间</th>
					<th style="width:150px;">平台处理</th>
					<th  style="width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $item){ ?>
				<tr data-id="<?php echo $item['id'];?>">
					<td>
						<dl>
							<dt><?php echo $item['name'];?></dt>
							<dl><?php echo $item['tid'];?></dl>
						</dl>
					</td>
					<td><?php echo $item['beforemoney'];?></td>
					<td><?php echo $item['money'];?></td>
					<td><?php echo $item['frozen_money'];?></td>
					<td><?php echo $item['finish_money'];?></td>
					<td><?php echo formatTime($item['atime'], 2);?></td>
					<td><?php echo formatTime($item['stime'], 2);?></td>
					<td>
						<?php if($item['state'] == 1){ ?>
						<button type="button" class="buttonStatus yes btn btn-link btn-sm">同意</button>
						<button type="button" class="buttonStatus no btn btn-link btn-sm">拒绝</button>
						<?php }elseif($item['state'] == 2){ ?>
						<span>第三方转账中</span>
						<?php }elseif($item['state'] == 3){ ?>
						<span>平台拒绝</span>
						<?php }elseif($item['state'] == 4){ ?>
						<span>转账成功</span>
						<?php }elseif($item['state'] == 5){ ?>
						<span>转账失败</span>
						<?php }else{ ?>
						<span>未知状态</span>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>	 
			</tbody>
		</table>
	</div>
	<?php }elseif($select['offers_type'] == 'agent'){ ?>
	<div role="tabpanel" class="tab-pane active"  id="agent">
		<div class="panel_search_form bg" >
			<span class="label-title">代理/推广员：</span>
			<input type="text"  class="form-control account" id='keyword' placeholder="" value='<?php echo isset($select['keyword'])? $select['keyword'] :''; ?>' />
			<span class="label-title"  >申请时间：</span>
			<input type="text" id="date_timepicker_start"   class="form-control" placeholder="开始时间" value='<?php echo isset($select['time_start'])? $select['time_start'] :''; ?>' />
			<span  class="lable" >-</span>
			<input type="text" id="date_timepicker_end"     class="form-control" placeholder="结束时间" value='<?php echo isset($select['time_end'])? $select['time_end'] :''; ?>' />
			<button class="btn btn-default select">查询</button>
			<button class="btn btn-default reset">重置</button>
			<div class="pull-right">
				<a href="javascript:;" class="btn btn-link">上传表格</a>
				<a href="<?php echo site_url('admin/offers/excel').'?offers_type='.$select['offers_type'].'&offers_status=export';?>" target="_blank" class="btn btn-link">下载表格</a>
			</div> 
		</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width:10%;">代理/推广员</th>
					<th style="width:100px;">账户余额</th>
					<th  style="width:160px;">提现</th>
					<th style="width:110px;">历史冻结</th>
					<th  style="width:110px;">成功提现</th>
					<th style="width:150px;">申请时间</th>
					<th style="width:150px;">平台处理</th>
					<th  style="width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $item){ ?>
				<tr data-id="<?php echo $item['id'];?>">
					<td>
						<dl>
							<dt><?php echo $item['name'];?></dt>
							<dl><?php echo $item['tid'];?></dl>
						</dl>
					</td>
					<td><?php echo $item['beforemoney'];?></td>
					<td><?php echo $item['money'];?></td>
					<td><?php echo $item['frozen_money'];?></td>
					<td><?php echo $item['finish_money'];?></td>
					<td><?php echo formatTime($item['atime'], 2);?></td>
					<td><?php echo formatTime($item['stime'], 2);?></td>
					<td>
						<?php if($item['state'] == 1){ ?>
						<button type="button" class="buttonStatus yes btn btn-link btn-sm">同意</button>
						<button type="button" class="buttonStatus no btn btn-link btn-sm">拒绝</button>
						<?php }elseif($item['state'] == 2){ ?>
						<span>第三方转账中</span>
						<?php }elseif($item['state'] == 3){ ?>
						<span>平台拒绝</span>
						<?php }elseif($item['state'] == 4){ ?>
						<span>转账成功</span>
						<?php }elseif($item['state'] == 5){ ?>
						<span>转账失败</span>
						<?php }else{ ?>
						<span>未知状态</span>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>	 
			</tbody>
		</table>
	</div>
	<?php } ?>
</div>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){	
	//时间查询
	$('#date_timepicker_start').datetimepicker({
	  	format:'Y/m/d',
	  	timepicker:false,
	  	onShow:function( ct ){
		   	this.setOptions({
		    	maxDate:$('#date_timepicker_end').val() ? $('#date_timepicker_end').val() : false
		   	})
		  }
	});
	$('#date_timepicker_end').datetimepicker({
		format:'Y/m/d',
		timepicker:false,
		onShow:function( ct ){
			this.setOptions({
			    minDate:$('#date_timepicker_start').val() ? $('#date_timepicker_start').val() : false
			})
		}
	});

	$("ul.nav-tabs > li > a").click(function(){
		var data = {};
		data.select = {};
		data.select.offers_type = $(this).attr('aria-controls');
		setCookie('select_data', data.select);
		setMainPage(data);		
	});
	
	//查询模块	
	$('.select').click(function(){
		var data = {}; 
		data.select = {};
		data.select.offers_type = "<?php echo $select['offers_type'];?>";
		data.select.time_start = $('#date_timepicker_start').val();
		data.select.time_end = $('#date_timepicker_end').val();
		data.select.keyword = $('#keyword').val();
		setCookie('select_data', data.select);
		setMainPage(data);
	});

	//重置模块
	$('.reset').click(function(){
		var data = {};
		data.select = {};
		data.select.offers_type = "<?php echo $select['offers_type'];?>";
		setCookie('select_data', data.select);
		setMainPage(data);
	});

	//审核操作
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'state';
		data.id = $(this).parents('tr').attr('data-id');
		var e = $(this);
		data.status = e.hasClass('yes') ? 'yes' : 'no';

		postData(data, function(res){
			if(res.code == 0){
				
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});

});	
</script>