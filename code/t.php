<script src=" http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3168919025" type="text/javascript" charset="utf-8"></script>

<div id="wb_connect_btn"></div>

<script>
  	WB2.anyWhere(function(W){
		W.widget.connectButton({
			id: "wb_connect_btn",	
			type:'3,2',
			callback : {
				login:function(o){	//登录后的回调函数
					alert("login: "+o.screen_name)	
				},
				logout:function(){	//退出后的回调函数
					alert('logout');
				}
			}
		});
	});
  
  
  
</script>