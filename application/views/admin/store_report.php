<?php echo getListHeader($listHeader['location'], $listHeader['actions']);?>
 
<div class="mark-list">
	<div  class="uio_btom">
		<div class="col-md-2 sele sel_1">
			<select id='select_type' class="form-control">
				<option value='week'  <?php echo $type == 'week'?'selected':'';?>>按周统计</option>
				<option value='month'  <?php echo $type =='month'?'selected':'';?>>按月统计</option>
				<option value='year'  <?php echo $type == 'year'?'selected':'';?>>按年统计</option>
				<option value='day'  <?php echo $type == 'day'?'selected':'';?>>按日统计</option>
			</select>
		</div>
		<div class="col-md-2 sele sel0"  style="<?php if($type=='day'){echo 'display:none;';}?>">
			<select id='select_year'  class="form-control">
				<?php foreach ($year_arr as $y_v){?>
					<option value='<?php echo $y_v?>'  <?php echo $now_year == $y_v?'selected':'';?>  ><?php echo $y_v?></option>
				<?php }?>
			</select>
		</div>
		<div class="col-md-2 sele sel1" style="<?php if($type=='day'||$type=='year'){echo 'display:none;';}?>">
			<select id='select_month'   class="form-control">
				<?php foreach ($month_arr as $m_v){?>
					<option value='<?php echo $m_v?>' <?php echo $now_month == $m_v?'selected':'';?> ><?php echo $m_v?></option>
				<?php }?>
			</select>
		</div>
		<div class="col-md-4 sele sel2" style="<?php if($type!='week'){echo 'display:none;';}?>">
			<select id='select_week'    class="form-control">
				<?php foreach ($week_arr as $k=>$v){?>
	                  		<option value="<?php echo $v['key'];?>" <?php echo $now_week == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
	              <?php } ?>
			</select>
		</div>

		<div class="col-md-4 panel_search_form forms_q sele sel3 "  id='select_day' style="<?php if($type!='day'){echo 'display:none;';}?>">
			<input  value="<?php echo isset($start_time)?$start_time :'';?>"  type="text" id="date_timepicker_start" class="form-control" placeholder="开始时间"/>
			<span  class="lable" >-</span>
			<input value="<?php echo isset($end_time) ? $end_time :'';?>"  type="text" id="date_timepicker_end"  class="form-control" placeholder="结束时间"/>
		</div>
		<button class="btn btn-default" id='select_all'>查询</button>
		<button class="btn btn-default" id='reset'>重置</button>
	</div>
	 
	<div id="ECharts" style="width: 100%;height:350px;"></div>
	<div class="panel panel-default"> 
		<table class="table">
			<thead>
				<tr>
					<th>时间</th>
					<th class="tr">新增店铺数</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($item)){foreach ($item as $v){?>
					<tr>
						<td><?php echo get_date($v['day']);?></td>
						<td class="tr"><?php echo $v['num']?></td>
					</tr>
				<?php }}?>
				<?php if(isset($items)){foreach ($items as $k=>$v){  ?>
					<tr>
						<td><?php echo $month_str_arr[$k-1];?></td>
						<td class="tr"><?php echo $v?></td>
					</tr>
				
				<?php }}?>
			</tbody>
		</table>
	</div>

</div>
 
<script type="text/javascript">
	// 基于准备好的dom，初始化echarts实例
	var myChart = echarts.init(document.getElementById('ECharts'));
	// 指定图表的配置项和数据
    var option = {
    	    title : {
    	        text: '趋势图',
    	        subtext: '<?php echo $subtext?>'
    	    },
    	    tooltip : {
    	    },
    	    legend: {
    	        data:['入驻统计']
    	    },
    	    toolbox: {
     	        show : true,
    	        feature : {
    	            saveAsImage : {show: true}
    	        }
    	    },
    	    calculable : true,
    	    xAxis : [
    	        {
    	            type : 'category',
    	            boundaryGap : false,
    	            data : [<?php echo $xAxis;?>]
    	        }
    	    ],
    	    yAxis : [
    	        {
    	            type : 'value',
    	            axisLabel : {
    	                formatter: '{value}'
    	            }
    	        }
    	    ],
    	    series : [
    	        {
    	            name:'入驻统计',
    	            type:'line',
    	            data:[<?php echo $yAxis?>],
    	            lineStyle: {
	                	normal: { 
		                    color: 'orange', 
	                    }
	                }, 
    	            markLine : {
    	                data : [
    	                    {
    	                    	type : 'average', 
    	                    	name : '平均值',
                            }
    	                ]
    	            }
    	        }
    	    ]
    	};
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

    $(function(){
        //时间插件
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

		//月联动周
		$('#select_month').change(function(){
			var select_year = $('#select_year').val();
			var select_month = $('#select_month').val();
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'monthToweek'));?>';
			var data = {};
			data.year = select_year;
			data.month = select_month;
			postData(data, function(res){
				if(res.code == 0){
					$('#select_week').empty()
					$('#select_week').append(res.data)
				}else{
					showError(res.msg);
				}
			});
		})
		
		//单击查询事件
		select_data = {};
		select_data.year = $('#select_year').val();
		select_data.month = $('#select_month').val();
		select_data.week = $('#select_week').val();
		select_data.type = $('#select_type').val();
		select_data.start_time = $('#date_timepicker_start').val();
		select_data.end_time = $('#date_timepicker_end').val();
		$('#select_all').click(function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'report'));?>';
			var select_year = $('#select_year').val();
			var select_month = $('#select_month').val();
			var select_week = $('#select_week').val();
			var select_type = $('#select_type').val();
			var select_start_time = $('#date_timepicker_start').val();
			var select_end_time = $('#date_timepicker_end').val();
			var data = {};
			data.select = {};
			data.select.year = select_year;
			data.select.month = select_month;
			data.select.week = select_week;
			data.select.type = select_type;
			data.select.start_time = select_start_time;
			data.select.end_time = select_end_time;
			setMainPage(data);
		})
		//重置事件
		$('#reset').click(function(){
			require_url = '<?php echo site_url(array($this->router->directory, $this->router->class, 'report'));?>';
			var select_type = $('#select_type').val();
			var data = {};
			data.select = {};
			data.select.year = '';
			data.select.month = '';
			data.select.week = '';
			data.select.type = select_type;
			data.select.start_time = '';
			data.select.end_time = '';
			setMainPage(data);
		})
			
		//收索类型事件
		$('#select_type').change(function(){
			var select_type = $('#select_type').val();
			
			if(select_type == 'year'){ 
				$(".sele").hide();
				$(".sel_1,.sel0").show();
			}else if(select_type == 'month'){
				 
				$(".sele").hide();
				$(".sel_1,.sel0,.sel1").show(); 
			}else if(select_type == 'day'){
				 
				$(".sele").hide();
				$(".sel_1,.sel3").show(); 
			}else{ 
				$(".sele").hide();
				$(".sel_1,.sel0,.sel1,.sel2").show(); 
			}
		})
		
    });
</script>