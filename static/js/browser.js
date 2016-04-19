(function(window){
	var u = {};

	u.callBackFun = function(res){
		if(xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200){
			console.log(res);
	        var response = xmlHttpRequest.response;
	        var res = JSON.parse(response);
	        //alert(JSON.stringify(res.data));
	    }
	};
	//XmlHttpRequest对象
	u.createXmlHttpRequest = function(){
		if(window.ActiveXObject){ //如果是IE浏览器
	        return new ActiveXObject("Microsoft.XMLHTTP");
	    }else if(window.XMLHttpRequest){ //非IE浏览器
	        return new XMLHttpRequest();
	    }
	};
	u.post = function(url, data, callBack){
		if(typeof(data) == "object"){
			var s = '';
			for(var key in data){
				s += key + '=' + data[key] + '&';
			}
			data = s;
		}
		if(typeof(callBack) == "undefined"){
			callBack = u.callBackFun;
		}
		xmlHttpRequest = u.createXmlHttpRequest();
		xmlHttpRequest.onreadystatechange = callBack;
		xmlHttpRequest.open("POST", url, true);
		xmlHttpRequest.withCredentials  = true;
		xmlHttpRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlHttpRequest.send(data);
	};

	window.browser = u;
})(window);