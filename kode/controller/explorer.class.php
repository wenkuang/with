<?php 
/*
* @link http://www.kalcaddle.com/
* @author warlee | e-mail:kalcaddle@qq.com
* @copyright warlee 2014.(Shanghai)Co.,Ltd
* @license http://kalcaddle.com/tools/licenses/license.txt
*/

class explorer extends Controller{
    /**
     * 构造函数
     */
    public $path;
    public function __construct(){
        parent::__construct();
        $this->tpl = TEMPLATE.'explorer/';
        if (isset($this->in['path'])) {
            $this->path = _DIR($this->in['path']);
        }
    }

    public function index(){
        if($this->in['path'] !=''){
            $dir = $_GET['path'];
        }else if(isset($_SESSION['this_path'])){
            $dir = $_SESSION['this_path'];
        }else{
            $dir = '/';//首次进入系统,不带参数
            if ($GLOBALS['is_root']) $dir = WEB_ROOT;
        }        
        $dir = rtrim($dir,'/').'/';
        $is_frame = false;//是否以iframe方式打开
        if ($this->in['type'] == 'iframe') $is_frame = true;//
        $upload_max = get_post_max();   
        $this->assign('upload_max',$upload_max);                
        $this->assign('is_frame',$is_frame);
        $this->assign('dir',$dir);
        $this->display('index.php');
    }

    public function pathInfo(){
        $path = $this->path;
        $type=$this->in['type'];
        if ($type=="folder"){
            $data = path_info($path,$this->L['time_type_info']);
        }else{
            $data = file_info($path,$this->L['time_type_info']);
        }
        show_json($data);
    }
    public function pathInfoMuti(){
        $info_list = json_decode($this->in['list'],true);
        foreach ($info_list as &$val) {          
            $val['path'] = _DIR($val['path']);
        }
        $data = path_info_muti($info_list);
        show_json($data);
    }      
    public function pathRname(){
        if (!is_writable($this->path)) {
            show_json($this->L['no_permission'],false);
        }
        $rname_to=_DIR($this->in['rname_to']);
        if (file_exists($rname_to)) {
            show_json($this->L['name_isexists'],false);
        }
        rename($this->path,$rname_to);
        show_json($this->L['rname_success']);
    }
    public function pathList(){
        load_class('history');
        session_start();//re start
        $session=$_SESSION['history'];
        $user_path = $this->in['path'];
        
        if (is_array($session)){
            $hi=new history($session);
            if ($user_path==""){
                $user_path=$hi->getFirst();
            }else {
                $hi->add($user_path); 
                $_SESSION['history']=$hi->getHistory();
            }
        }else {
            $hi=new history(array(),20);
            if ($user_path=="")  $user_path='/';
            $hi->add($user_path);
            $_SESSION['history']=$hi->getHistory();
        }
        $_SESSION['this_path']=$user_path;
        $list=$this->path($this->path);
        $list['history_status']= array('back'=>$hi->isback(),'next'=>$hi->isnext());
        show_json($list);
    }
    public function search(){
        if (!isset($this->in['search'])) show_json($this->L['please_inpute_search_words'],false);
        $is_content = false;
        $is_case    = false;
        $ext        = '';
        if (isset($this->in['is_content'])) $is_content = true;
        if (isset($this->in['is_case'])) $is_case = true;
        if (isset($this->in['ext'])) $ext= str_replace(' ','',$this->in['ext']);
        $list = path_search(
            $this->path,
            iconv_system($this->in['search']),
            $is_content,$ext,$is_case);
        _DIR_OUT($list);
        show_json($list);
    }
    public function treeList(){//树结构
        $app = $this->in['app'];//是否获取文件 传folder|file
        if ($this->in['type']=='init') $this->_tree_init($app);
        if ($this->in['this_path']){
            $path=_DIR($this->in['this_path']);
        }else{
            $path=_DIR($this->in['path']).$this->in['name'];
        }
        //if (!is_readable($path)) show_json($path,false);

        $list_file = ($app == 'editor'?true:false);//编辑器内列出文件
        $list=$this->path($path,$list_file,true);
        function sort_by_key($a, $b){
            if ($a['name'] == $b['name']) return 0;
            return ($a['name'] > $b['name']) ? 1 : -1;
        }
        usort($list['folderlist'], "sort_by_key");
        usort($list['filelist'], "sort_by_key");
        if ($app == 'editor') {
            $res = array_merge($list['folderlist'],$list['filelist']);
            show_json($res,true);
        }else{
            show_json($list['folderlist'],true);
        }
    }
    private function _tree_init($app){
        $check_file = ($app == 'editor'?true:false);
        $favData=new fileCache($this->config['user_fav_file']);
        $fav_list = $favData->get();
        $fav = array();
        foreach($fav_list as $key => $val){
            $fav[] = array(
                'ext'       => 'folder',
                'name'      => $val['name'],
                'this_path' => $val['path'],
                'iconSkin'  => "fav",
                'type'      => 'folder',
                'isParent'  => path_haschildren(_DIR($val['path']),$check_file)
            );
        }
        $tree_path = WEB_ROOT;
        if (!$GLOBALS['is_root']) {
            $tree_path = '/';
        }
        if ($check_file) {//编辑器
            $list=$this->path(_DIR($tree_path),true,true);
            $res = array_merge($list['folderlist'],$list['filelist']);
            $tree_data = array(
                array('name'=>$this->L['fav'],'ext'=>'__fav__','iconSkin'=>"fav",'open'=>true,'children'=>$fav),
                array('name'=>$this->L['root_path'],'ext'=>'__root__','children'=>$res,'iconSkin'=>"my",'open'=>true,'this_path'=> $tree_path,'isParent'=>true)
            );
        }else{//文件管理器
            $lib_array =  array(
                array('name'=>$this->L['desktop'],'ext'=>'_null_','iconSkin'=>"my",'this_path'=> MYHOME.'desktop/','isParent'=>true),
                array('name'=>$this->L['my_document'],'ext'=>'_null_','iconSkin'=>"doc",'this_path'=> MYHOME.'doc/','isParent'=>true),
                array('name'=>$this->L['my_picture'],'ext'=>'_null_','iconSkin'=>"pic",'this_path'=> MYHOME.'image/','isParent'=>true),           
                array('name'=>$this->L['my_music'],'ext'=>'_null_','iconSkin'=>"music",'this_path'=> MYHOME.'music/','isParent'=>true),
                array('name'=>$this->L['my_movie'],'ext'=>'_null_','iconSkin'=>"movie",'this_path'=> MYHOME.'movie/','isParent'=>true), 
                array('name'=>$this->L['my_download'],'ext'=>'_null_','iconSkin'=>"download",'this_path'=> MYHOME.'download/','isParent'=>true) 
            );
            $tree_data = array(
                array('name'=>$this->L['fav'],'ext'=>'__fav__','iconSkin'=>"fav",'open'=>true,'children'=>$fav),
                array('name'=>$this->L['lib'],'ext'=>'__lib__','iconSkin'=>"lib",'open'=>true,'children'=>$lib_array),
                array('name'=>$this->L['root_path'],'ext'=>'__root__','iconSkin'=>"my",'open'=>true,'this_path'=> $tree_path,'isParent'=>true)
            );
        }
        show_json($tree_data);
    }

    public function historyBack(){
        load_class('history');
        session_start();//re start
        $session=$_SESSION['history'];
        if (is_array($session)){
            $hi=new history($session);
            $path=$hi->goback();            
            $_SESSION['history']=$hi->getHistory();
            $folderlist=$this->path(_DIR($path));
            $_SESSION['this_path']=$path;
            show_json(array(
                'history_status'=>array('back'=>$hi->isback(),'next'=>$hi->isnext()),
                'thispath'=>$path,
                'list'=>$folderlist
            ));
        }
    }
    public function historyNext(){
        load_class('history');
        session_start();//re start
        $session=$_SESSION['history'];
        if (is_array($session)){
            $hi=new history($session);
            $path=$hi->gonext();            
            $_SESSION['history']=$hi->getHistory();
            $folderlist=$this->path(_DIR($path));
            $_SESSION['this_path']=$path;
            show_json(array(
                'history_status'=>array('back'=>$hi->isback(),'next'=>$hi->isnext()),
                'thispath'=>$path,
                'list'=>$folderlist
            ));
        }
    }
    public function pathDelete(){
        $list = json_decode($this->in['list'],true);
        $success = 0;
        $error   = 0;
        foreach ($list as $val) {
            $path_full = _DIR($val['path']);
            if ($val['type'] == 'folder') {
                if(del_dir($path_full)) $success ++;
                else $error++;
            }else{
                if(del_file($path_full)) $success++;
                else $error++;
            }
        }
        if (count($list) == 1) {
            if ($success) show_json($this->L['remove_success']);
            else show_json($this->L['remove_fali'],false);
        }else{
            $code = $error==0?true:false;
            show_json($this->L['remove_success'].$success.'success,'.$error.'error',$code);
        }       
    }
    public function mkfile(){
        $new= rtrim($this->path,'/');
        if(touch($new)){
            show_json($this->L['create_success']);
        }else{
            show_json($this->L['create_error'],false);
        }
    }
    public function mkdir(){
        $new = rtrim($this->path,'/');
        if(mkdir($new,0777)){
            show_json($this->L['create_success']);
        }else{
            show_json($this->L['create_error']);
        }
    }
    public function pathCopy(){
        session_start();//re start
        $copy_list = json_decode($this->in['list'],true);
        $list_num = count($copy_list);
        for ($i=0; $i < $list_num; $i++) { 
            $copy_list[$i]['path'] =$copy_list[$i]['path'];
        }
        $_SESSION['path_copy']= json_encode($copy_list);            
        $_SESSION['path_copy_type']='copy';
        show_json($this->L['copy_success']);
    }
    public function pathCute(){
        session_start();//re start
        $cute_list = json_decode($this->in['list'],true);
        $list_num = count($cute_list);
        for ($i=0; $i < $list_num; $i++) { 
            $cute_list[$i]['path'] = $cute_list[$i]['path'];
        }
        $_SESSION['path_copy']= json_encode($cute_list);            
        $_SESSION['path_copy_type']='cute';
        show_json($this->L['cute_success']);
    }
    public function pathCuteDrag(){
        $clipboard = json_decode($this->in['list'],true);
        $path_past=$this->path;
        if (!is_writable($path_past)) show_json($this->L['no_permission'],false);         
        foreach ($clipboard as $val) {
            $path_copy = _DIR($val['path']);
            $filename  = get_path_this($path_copy);
            if ($clipboard[$i]['type'] == 'folder') {
                @rename($path_copy,$path_past.$filename.'/');
            }else{
                @rename($path_copy,$path_past.$filename);
            }
        }
        show_json($this->L['success']);
    }      
    public function clipboard(){
        $clipboard = json_decode($_SESSION['path_copy'],true);
        $msg = '';
        if (count($clipboard) == 0){
            $msg = '<div style="padding:20px;">null!</div>';
        }else{          
            $msg='<div style="height:200px;overflow:auto;padding:10px;width:400px"><b>'.$this->L['clipboard_state']
                .($_SESSION['path_copy_type']=='cute'?$this->L['cute']:$this->L['copy']).'</b><br/>';
            $len = 40;
            foreach ($clipboard as $val) {
                $val['path'] = rawurldecode($val['path']);
                $path=(strlen($val['path'])<$len)?$val['path']:'...'.substr($val['path'],-$len);
                $msg.= '<br/>'.$val['type'].' :  '.$path;
            }            
            $msg.="</div>";
        }
        show_json($msg);
    }
    public function pathPast(){
        session_start();//re start
        $info = '';$data = array();
        $clipboard = json_decode($_SESSION['path_copy'],true);
		if (count($clipboard) == 0){
            show_json($data,false,$this->L['clipboard_null']);
        }
        $copy_type = $_SESSION['path_copy_type'];
        $path_past=$this->path;
        if (!is_writable($path_past)) show_json($data,false,$this->L['no_permission_write']);
        
        $list_num = count($clipboard);
        if ($list_num == 0) {
            show_json($data,false,$this->L['clipboard_null']);
        }
        for ($i=0; $i < $list_num; $i++) {
            $path_copy = _DIR($clipboard[$i]['path']);  
            $filename  = get_path_this($path_copy);
            $filename_out  = iconv_app($filename);

            if (!file_exists($path_copy) && !is_dir($path_copy)){
                $info .=$path_copy."<li>{$filename_out}'.$this->L['copy_not_exists'].'</li>";
                continue;
            }
            if ($clipboard[$i]['type'] == 'folder'){
                if ($path_copy == substr($path_past,0,strlen($path_copy))){
                    $info .="<li style='color:#f33;'>{$filename_out}'.$this->L['current_has_parent'].'</li>";
                    continue;
                }
            }       
            if ($copy_type == 'copy') {
                if ($clipboard[$i]['type'] == 'folder') {
                    copy_dir($path_copy,$path_past.$filename);
                }else{
                    copy($path_copy,$path_past.$filename);
                }
                
            }else{
                if ($cute_list[$i]['type'] == 'folder') {
                    rename($path_copy,$path_past.$filename.'/');
                }else{
                    rename($path_copy,$path_past.$filename);
                }                
            }
            $data[] = iconv_app($filename);
        }
        if ($copy_type == 'copy') {
            $info=$this->L['past_success'].$info;
        }else{
            $_SESSION['path_copy'] = json_encode(array());
            $_SESSION['path_copy_type'] = '';
            $info=$this->L['cute_past_success'].$info;
        }
        show_json($data,true,$info);
    }
    public function fileDownload(){
        file_download($this->path);
    }
    public function zip(){
        load_class('zip');
        ini_set('memory_limit', '2028M');//2G;
        $zip_list = json_decode($this->in['list'],true);
        $list_num = count($zip_list);
        for ($i=0; $i < $list_num; $i++) { 
            $zip_list[$i]['path'] = _DIR($zip_list[$i]['path']);
        }
        $basic_path =get_path_father($zip_list[0]['path']);     
        if ($list_num == 1) {
            $path_this_name=get_path_this($zip_list[0]['path']);
            $zipname = $basic_path.$path_this_name.'.zip';
        }else{
            $zipname = $basic_path.'temp_'.substr(md5(time()),5,3).'.zip';
        }
        $len = 25;
        $zipname_app = iconv_system($zipname);
        $zipname_app = strlen($zipname_app)>$len?'...'.substr($zipname_app, -$len):$zipname_app;
        $z = new zip($zipname, $basic_path);
        if (!$z -> fp){
            show_json("{$zipname}".$this->L['no_permission_write'],false);
        }else {
            for ($i=0; $i < $list_num; $i++) {
                $z -> addFileList($zip_list[$i]['path']);
            }
            $z -> zipAll() or show_json($this->L['zip_null'],false);
            $info = $this->L['zip_success']." $z->file_count files.<br/>".
                    $this->L['size']."：{$z->sizeFormat(filesize($zipname))}";
            show_json($info);
        }
    }
    public function unzip(){
        load_class('zip');
        ini_set('memory_limit', '2028M');//2G;
        $path=$this->path;
        $name = get_path_this($path);
        $name = substr($name,0,strrpos($name,'.'));
        $path_father_name=get_path_father($path);
        $unzip_to = $path_father_name.$path_this_name;
        if (isset($this->in['path_to'])) {//解压到指定位置
            $unzip_to = _DIR($this->in['path_to']);
        }
        $z = new unZip;
        if ($z->Extract($path,$unzip_to) ==-1){
            show_json($this->L['not_zip'],false);
        }else {
            show_json($this->L['unzip_success']);
            clearstatcache();
        }
    }
    public function image(){
        if (filesize($this->path) <= 1024*10) {//小于10k 不再生成缩略图
            file_proxy_out($this->path);
        }
        load_class('imageThumb');
        $image= $this->path;
        $image_md5  = md5($image);
        $image_thum = $this->config['pic_thumb'].$image_md5.'.png';
        if (!is_dir($this->config['pic_thumb'])){
            mkdir($this->config['pic_thumb'],0777);
        }
        if (!file_exists($image_thum)){//如果拼装成的url不存在则没有生成过
            if ($_SESSION['this_path']==$this->config['pic_thumb']){//当前目录则不生成缩略图
                $image_thum=$this->path;
            }else {
                $cm=new CreatMiniature();
                $cm->SetVar($image,'file');
                //$cm->Prorate($image_thum,72,64);//生成等比例缩略图
                $cm->BackFill($image_thum,72,64,true);//等比例缩略图，空白处填填充透明色
            }
        }
        if (!file_exists($image_thum) || filesize($image_thum)<100){//缩略图生成失败则用默认图标
            $image_thum=STATIC_PATH.'images/image.png';
        }
        //输出
        file_proxy_out($image_thum);
    }

    // 远程下载
    public function serverDownload() {
        $url = rawurldecode($this->in['url']);
        $save_path = _DIR($this->in['save_path']);
        $save_path = $save_path.'download_'.rand(100,999).'.tmp';
        $result = file_download_this($url,$save_path);
        if ($result == 1){
            show_json($this->L['download_success'],true,$save_path);
        }else{
            if ($result == -1){
                show_json($this->L['download_error_create'],false);
            }else{
                show_json($this->L['download_error_exists'],false);
            }
        }
    }

    // 远程下载
    public function fileProxy() {
        if (!$GLOBALS['is_root']) show_json($this->L['no_permission'],false);
        file_proxy_out($this->path);
    }    

    /**
     * 上传,html5拖拽  flash 多文件
     */
    public function fileUpload(){
        $save_path = $this->path;
        if ($save_path == '') show_json($this->L['upload_error_big'],false);
        upload('file',$save_path);
    }

    //获取文件列表&哦exe文件json解析
    private function path($dir,$list_file=true,$check_children=false){
        $list = path_list($dir,$list_file,$check_children);
        foreach ($list['filelist'] as $key => $val) {
            if ($val['ext'] == 'oexe') {
                $path = iconv_system($val['path']).'/'.iconv_system($val['name']);
                $json = json_decode(file_get_contents($path),true);
                if(is_array($json)) $list['filelist'][$key] = array_merge($val,$json);                
            }
        }
        _DIR_OUT($list);
        return $list;
    }
}