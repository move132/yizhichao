<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
<div class="panel_search_form bg" >
	<span class="label-title">作者：</span>
	<input type="text"  class="form-control account" id='account' placeholder="" value='<?php echo isset($select['select_account'])? $select['select_account'] :''; ?>' />
	<span class="label-title">操作时间：</span>
	<input type="text" id="date_timepicker_start"   class="form-control" placeholder="开始时间" value='<?php echo isset($select['select_time_start'])? $select['select_time_start'] :''; ?>' />
	<span  class="lable" >-</span>
	<input type="text" id="date_timepicker_end"     class="form-control" placeholder="结束时间" value='<?php echo isset($select['select_time_end'])? $select['select_time_end'] :''; ?>' />
	<button class="btn btn-default select">查询</button>
	<button class="btn btn-default reset">重置</button> 
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="width:50px;">编号</th>
			<th style="width:10%;">作者</th>
			<th style="width:20%;">操作时间</th>
			<th >备注内容</th>
			<th  style="width:15%;">操作动作</th>
		</tr>
	</thead>
	<tbody>
		<?php  $i = 1;foreach($list as $item){ ?>
		<tr>
			<td><?php echo $i++;?></td>
			<td><?php echo $item['account'];?></td>
			<td><?php echo date('Y-m-d H:i:s',$item['atime']);?></td>
			<td><?php echo $item['remark'];?></td>
			<td><?php echo $item['action'];?></td>
		</tr>
		<?php } ?>	 
	</tbody>
</table>
<?php echo $pagination;?>
<script type="text/javascript">
require_page_url = '<?php echo site_url(array($this->router->directory, $this->router->class, $this->router->method));?>';
$(function(){
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
	select_data.account = $('#account').val();
	select_data.time_end = $('#date_timepicker_end').val();
	select_data.time_start = $('#date_timepicker_start').val();
	$('.select').click(function(){
		var time_start = $('#date_timepicker_start').val();
		var time_end = $('#date_timepicker_end').val();
		var  account = $('#account').val();
		var data = {};
		data.select = {}; 
		data.select.time_start = time_start;
		data.select.time_end = time_end;
		data.select.account = account;
		setMainPage(data);
	})
	$('.reset').click(function(){
		var data = {};
		data.select = {}; 
		data.select.time_start = '';
		data.select.time_end = '';
		data.select.account = '';
		setMainPage(data);
	})




})
</script>