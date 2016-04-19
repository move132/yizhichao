<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg">
	<span class="label-title">标题：</span>
	<input type="text"  class="form-control account" id='keyword' placeholder="" value='<?php echo isset($select['title'])? $select['title'] :''; ?>' />
	<span class="label-title">反馈时间：</span>
	<input type="text" id="date_timepicker_start"   class="form-control" placeholder="开始时间" value='<?php echo isset($select['select_time_start'])? $select['select_time_start'] :''; ?>' />
	<span  class="lable" >-</span>
	<input type="text" id="date_timepicker_end"     class="form-control" placeholder="结束时间" value='<?php echo isset($select['select_time_end'])? $select['select_time_end'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button> 
</div>


<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:80px;">收银员ID</th>
			<th style="width:100px;">代理/推广员</th>
			<th style="width:120px;">反馈时间</th>
			<th >详情</th>
			<th  style="width:15%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id="<?php echo $item['id'];?>">
			<td><?php echo $item['seller_id'];?></td>
			<td><?php echo $item['agent_id'];?></td>
			<td><?php echo formatTime($item['atime'], 2);?></td>
			<td><?php echo $item['content'];?></td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">已读</button>
				<button type="button" class="buttonView btn btn-link btn-sm">回复</button>
			</td>
		</tr>
		<?php } ?>	 
	</tbody>
</table>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
	//操作消息状态
	$(".buttonStatus").click(function(){
		require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'done'));?>';
		var data = {};
		data.action = 'status';
		data.id = $(this).parents('tr').attr('data-id');
		var e = $(this);
		data.status = e.hasClass('btn-success') ? 0 : 1;
		postData(data, function(res){
			if(res.code == 0){
				if(e.hasClass('btn-success')){//启用
					e.removeClass('btn-success').addClass('btn-danger').text('删除');
				}else if(e.hasClass('btn-danger')){//停运
					e.removeClass('btn-danger').addClass('btn-success').text('恢复');
				}
				showSuccess(res.msg);
			}else{
				showError(res.msg);
			}
		});
	});	
	//时间查询
	$('#date_timepicker_start').datetimepicker({
		lang:'ch',
	  	format:'Y/m/d',
	  	timepicker:false,
	  	onShow:function( ct ){
		   	this.setOptions({
		    	maxDate:$('#date_timepicker_end').val()?$('#date_timepicker_end').val():false
		   	})
		  }
	});
	$('#date_timepicker_end').datetimepicker({
	 	lang:'ch',
		format:'Y/m/d',
		timepicker:false,
		onShow:function( ct ){
			this.setOptions({
			    minDate:$('#date_timepicker_start').val()?$('#date_timepicker_start').val():false
			})
		}
	});
	$.datetimepicker.setLocale("ch")
	//查询模块
	select_data = {};
	select_data.time_start = $('#date_timepicker_start').val();
	select_data.time_end = $('#date_timepicker_end').val();
	select_data.keyword = $('#keyword').val(); 
	$('.select').click(function(){
		var time_start = $('#date_timepicker_start').val();
		var time_end = $('#date_timepicker_end').val();
		var keyword = $('#keyword').val();
		var data = {}; 
		data.select = {};
		data.select.time_start = time_start;
		data.select.time_end = time_end;
		data.select.keyword = keyword;
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {};
		data.select.time_start = '';
		data.select.time_end = '';
		data.select.keyword = '';
		setMainPage(data);
	})

	
});	
</script>