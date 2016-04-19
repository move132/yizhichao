<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg">
	<span class="label-title">标题：</span>
	<input type="text"  class="form-control account" id='news_title' placeholder="" value='<?php echo isset($select['title'])? $select['title'] :''; ?>' />
	<span class="label-title">添加时间：</span>
	<input type="text" id="date_timepicker_start"   class="form-control" placeholder="开始时间" value='<?php echo isset($select['select_time_start'])? $select['select_time_start'] :''; ?>' />
	<span  class="lable" >-</span>
	<input type="text" id="date_timepicker_end"     class="form-control" placeholder="结束时间" value='<?php echo isset($select['select_time_end'])? $select['select_time_end'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button> 
</div>


<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:80px;">阅读量</th>
			<th style="width:10%;">作者</th>
			<th style="width:20%;">添加时间</th>
			<th >标题</th>
			<th  style="width:20%;">操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $item){ ?>
		<tr data-id="<?php echo $item['id'];?>">
			<td><?php echo $item['views'];?></td>
			<td><?php echo $item['account'];?></td>
			<td><?php echo date('Y-m-d H:i:s',$item['atime']);?></td>
			<td><?php echo $item['title'];?></td>
			<td>
				<button type="button" class="buttonView btn btn-link btn-sm">详情</button>
				<button type="button" class="buttonEdit btn btn-link btn-sm">编辑</button>
				<button type="button" class="buttonStatus btn btn-<?php echo $item['is_close'] == 0 ? 'danger' : 'success';?> btn-sm">
					<?php echo $item['is_close'] == 0 ? '删除' : '恢复';?>
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
	//编辑
	$(".buttonEdit").click(function(){
		var id = $(this).parents('tr').attr('data-id');
		clickPage('<?php echo site_url(array($this->router->directory, $this->router->class, 'add?id='));?>'+id)
	});
	//详情
	$(".buttonView").click(function(){
		var id = $(this).parents('tr').attr('data-id');
		var url = "<?php echo site_url(array($this->router->directory, $this->router->class, 'view?id='));?>"+id;
		showOpen(title='消息详情',url)
	});
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
	select_data.news_title = $('#news_title').val(); 
	$('.select').click(function(){
		var time_start = $('#date_timepicker_start').val();
		var time_end = $('#date_timepicker_end').val();
		var news_title = $('#news_title').val();
		var data = {}; 
		data.select = {};
		data.select.time_start = time_start;
		data.select.time_end = time_end;
		data.select.news_title = news_title;
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {};
		data.select.time_start = '';
		data.select.time_end = '';
		data.select.news_title = '';
		setMainPage(data);
	})

	
});	
</script>