<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $L['title'];?></title>
	<link rel="Shortcut Icon" href="<?php echo STATIC_PATH;?>/images/favicon.ico">
	<link href="<?php echo STATIC_PATH;?>js/lib/picasa/style/style.css" rel="stylesheet"/>
	<link href="<?php echo STATIC_PATH;?>js/lib/webuploader/webuploader.css" rel="stylesheet"/>
	<link href="<?php echo STATIC_PATH;?>style/bootstrap.css" rel="stylesheet"/>	    
	<link href="<?php echo STATIC_PATH;?>style/font-awesome/style.css" rel="stylesheet"/>
	
	<link href="<?php echo STATIC_PATH;?>style/skin/<?php echo $config['user']['theme'];?>app_explorer.css" rel="stylesheet" id='link_css_list'/>
	
</head>

<?php if($is_frame){?>
<style>.topbar{display: none;}.frame-header{top:0;}.frame-main{top:50px;}</style>
<?php } ?>

<body onselectstart="return false" style="overflow:hidden;" oncontextmenu="return core.contextmenu();">
	<?php include(TEMPLATE.'common/navbar.html');?>
	<div class="frame-header">
		<div class="header-content">
			<div class="header-left">
				<div class="btn-group btn-group-sm">
					<button class="btn btn-default" id='history_back' title='<?php echo $L['history_back'];?>' type="button">
						<i class="font-icon icon-arrow-left"></i>
					</button>
					<button class="btn btn-default" id='history_next' title='<?php echo $L['history_next'];?>' type="button">
						<i class="font-icon icon-arrow-right"></i>
					</button>
					<button class="btn btn-default" id='refresh' title='<?php echo $L['refresh_all'];?>' type="button">
						<i class="font-icon icon-refresh"></i>
					</button>
				</div>
			</div><!-- /header left -->
			
			<div class='header-middle'>
				<a href="javascript:void(0);" id='home' class="home button left"  title='<?php echo $L['root_path'];?>'>
					<i class="font-icon icon-home"></i>
				</a>
				<div id='yarnball' title="<?php echo $L['address_in_edit'];?>"></div>
				<div id='yarnball_input'><input type="text" name="path" value="" class="path" id="path"/></div>
				<a href="javascript:void(0);" id='fav' class="middle button" title='<?php echo $L['add_to_fav'];?>'>
					<i class="font-icon icon-star"></i>
				</a>				
				<a href="javascript:void(0);" id='up' class="right button" title='<?php echo $L['go_up'];?>'>
					<i class="font-icon icon-circle-arrow-up"></i>
				</a>
			</div><!-- /header-middle end-->		
			<div class='header-right'>
				<input type="text" name="seach"/>
				<a href="javascript:void(0);" id='search' class="right button" title='<?php echo $L['search'];?>'>
					<i class="font-icon icon-search"></i>
				</a>
			</div>
		</div>
	</div><!-- / header end -->

	<div class="frame-main">
		<div class='frame-left'>
			<ul id="folderList" class="ztree"></ul>
		</div><!-- / frame-left end-->
		<div class='frame-resize'></div>
		<div class='frame-right'>
			<div class="frame-right-main">
				<div class="tools">
					<div class="tools-left">
						<div class="btn-group btn-group-sm">
					        <button id='newfolder' class="btn btn-default" type="button">
					        	<i class="font-icon icon-folder-close-alt"></i><?php echo $L['newfolder'];?>
					        </button>
					        <button id='newfile' class="btn btn-default" type="button">
					        	<i class="font-icon icon-file-alt"></i><?php echo $L['newfile'];?>
					        </button>
					        <button id='upload' class="btn btn-default" type="button">
					        	<i class="font-icon icon-cloud-upload"></i><?php echo $L['upload'];?>
					        </button>
						</div>
						<span class='msg'><?php echo $L['path_loading'];?></span>
					</div>
					<div class="tools-right">
						<div class="btn-group btn-group-sm">
						  <button id='set_icon' title="<?php echo $L['list_icon'];?>" type="button" class="btn btn-default">
						  	<i class="font-icon icon-th"></i>
						  </button>
						  <button id='set_list' title="<?php echo $L['list_list'];?>" type="button" class="btn btn-default">
						  	<i class="font-icon icon-list"></i>
						  </button>
						  <div class="btn-group btn-group-sm">
						    <button id="set_theme" title="<?php echo $L['setting_theme'];?>" type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
						      <i class="font-icon icon-dashboard"></i>&nbsp;&nbsp;<span class="caret"></span>						      
						    </button>
						    <ul class="dropdown-menu pull-right">
							    <?php 
									$tpl="<li class='list {this}' theme='{0}'><a href='javascript:void(0);'>{1}</a></li>\n";
									echo getTplList(',',':',$config['setting_all']['themeall'],$tpl,$config['user']['theme'],'this');
								?>
						    </ul>
						  </div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div><!-- end tools -->
				<div id='list_type_list'></div><!-- list type 列表排序方式 -->
				<div class='bodymain html5_drag_upload_box'>
					<div class="fileContiner"></div>
				</div><!-- html5拖拽上传list -->
			</div>
		</div><!-- / frame-right end-->
	</div><!-- / frame-main end-->
<script src="<?php echo STATIC_PATH;?>js/lib/seajs/sea.js"></script>
<script type="text/javascript">
    var LNG = <?php echo json_encode($L);?>;
    var AUTH = <?php echo json_encode($GLOBALS['auth']);?>;
	var G = {
		is_root 	: <?php echo  $GLOBALS['is_root'];?>,
		web_root 	: "<?php echo $GLOBALS['web_root'];?>",
		web_host 	: "<?php echo HOST;?>",
		static_path : "<?php echo STATIC_PATH;?>",
		basic_path  : "<?php echo BASIC_PATH;?>",
		upload_max  : "<?php echo $upload_max;?>",
		version 	: "<?php echo KOD_VERSION;?>",
		app_host 	: "<?php echo APPHOST;?>",

		this_path	: "<?php echo $dir;?>",//当前绝对路径
		myhome   	: "<?php echo MYHOME;?>",//当前绝对路径	

		json_data	: "",//用于存储每次获取列表后的json数据值。
		list_type	: "<?php echo $config['user']['list_type'];?>",		//文件列表显示方式 list/icon
		sort_field 	: "<?php echo $config['user']['list_sort_field'];?>", //列表排序依照的字段  
		sort_order 	: "<?php echo $config['user']['list_sort_order'];?>",	//列表排序升序or降序
		musictheme	: "<?php echo $config['user']['musictheme'];?>",
		movietheme	: "<?php echo $config['user']['movietheme'];?>"	
	};
	seajs.config({
		base: "<?php echo STATIC_PATH;?>js/",
		preload: ["lib/jquery-1.8.0.min"]
	});
	seajs.use("app/src/explorer/main");
</script>
</body>
</html>
