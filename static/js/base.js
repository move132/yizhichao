var load_index;
function my_ajax(data, type, dataType, callback){
	//console.log('my_ajax', data);
	if(typeof(data) == "undefined"){
		data = {};
	}
	$.ajax({
		beforeSend: function(xhr){
			load_index = layer.load(2);
		},
		complete: function(xhr, status){
			layer.close(load_index);
			//console.log('complete', arguments);
		},
		data: data,
		dataType: typeof(dataType) == "undefined" ? 'json' : dataType,//xml html script json jsonp text
		error: function(xhr, error, e){
			//console.log('error', arguments);
			if(xhr.status == 405){
				layer.msg(xhr.responseText, {time: 0,shade:0.5});
			}else{
				layer.msg("请求异常，状态码【"+xhr.status+"】，描述："+xhr.statusText, {time:3000,shade:0.5});
			}
		},
		success: function(res){
			//console.log('success', arguments);
			
			callback(res);
		},
		timeout: 10000,
		type: type,
		url: require_url
	});
}
function getData(data, dataType, callback){
	if(typeof(data) == "undefined"){
		data = {};
	}
	data.data_num = getCookie('data_num');
	my_ajax(data, 'GET', dataType, callback);
}
function postData(data, callback, dataType){
	my_ajax(data, 'POST', dataType, callback);
}
function getHtmlData(data, callback){
	getData(data, 'html', callback);
}
function setMainPage(data){//更新主内容区
	if(typeof(data) != "undefined"){
		setCookie('require_data', data);
	}
	getHtmlData(data, function(res){
		$("#mainPage").html(res);
	});
}
function clickMenu(url, data_num){//左侧菜单事件
	if(data_num == -1){
		$(".menu_title,.menu_item").removeClass('selected');
	}
	setCookie('require_url', url);	
	setCookie('data_num', data_num);
	setCookie('require_data', '');
	setCookie('select_data', '');
	require_url = url;
	setMainPage({});
}
function clickPage(url, data){//主页面区事件
	if(! url.length){
		return false;
	}
	
	setCookie('require_url', url);
	if(typeof(data) != "undefined"){
		if(typeof(data) == "string"){
			data = JSON.parse(data);
		}
		
		setCookie('require_data', JSON.stringify(data));
	}
	require_url = url;
	setMainPage(data);
}
function reflush(url, data){
	//console.log(url, data);
	if(! url.length){
		return false;
	}
	require_url = url;
	postData(JSON.parse(data), function(res){
		if(res.code == 0){
			showSuccess(res.msg);
		}else{
			showError(res.msg);
		}
	});
}
function initPage(){//初始化页面
	var data_num;	
	if(data_num = getCookie('data_num')){
		$('dl > [data-num="'+data_num+'"]').addClass('selected');
	}else{
		setCookie('data_num', 0);
	}
	var data = {};
	
	var require_data;
	if(require_data = getCookie('require_data')){
		data = JSON.parse(require_data);
	}
	var select_data;
	if(select_data = getCookie('select_data')){
		data.select = JSON.parse(select_data);
	}
	
	setMainPage(data);
}
function formatForm(formData){
	var data = {};
	var name,value;
	$.each(formData, function(i, item){
		name = item.name;
		value = item.value;
		data[name] = value;
	});
	return data;
}
function showSuccess(msg){
	layer.msg(msg, {time:1000});
}
function showError(msg){
	layer.msg(msg, {time:2000});
}
/*
 * 	 弹框方法
*	 @title 弹框的标题
*	 @url   框内链接的内容
*/
function showOpen(title,url,area){ 
	area=area||['650px',"50%"]; 
	layer.open({
		type:2,
		title:title,
		shade:[0.5, '#393D49'],
		area: area,
		maxmin: false,
		content: [url,'no']
	})
}
function setCookie(key, data){
	var val = '';
	if(typeof(data) != "string"){
		val = JSON.stringify(data);
	}else{
		val = data;
	}
	$.cookie(key, val, {path: '/'});
}
function getCookie(key){
	return $.cookie(key);
}






