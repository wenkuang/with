http://www.ibm.com/developerworks/cn/web/1102_niugang_csrf/

1. about: 
CSRF（Cross-site request forgery），中文名称：跨站请求伪造，也被称为：one click attack/session riding，缩写为：CSRF/XSRF

原理: 

浏览器多标签情况下 各个标签共享一个进程，可以互相获取存在浏览器的信息

如果用户先在工行网银登陆，工行服务器会在浏览器存储cookie, 如果在未退出工行网银的情况下
用户切换标签登录到一个不安全的网站，该网站便会有机会通过js的referer 拿到工行的地址和 其它一些信息 并伪造请求

示例1：
#用户在工行付费连接
http://www.mybank.com/Transfer.php?toBankId=11&money=1000

然后访问危险网站，网站会根据document.referer生成
<img src=http://www.mybank.com/Transfer.php?toBankId=11&money=1000>
当图片加载完后 用户的付费连接访问了2次，多付费1000

这种情况需要用post请求避免，而且后台必须要用$_POST而不是$_REQUEST
#http://www.cnblogs.com/hyddd/archive/2009/04/09/1432744.html
#危险网站新代码，使用form发送post请求
<html>
　　<head>
　　　　<script type="text/javascript">
　　　　　　function steal()
　　　　　　{
          　　　　 iframe = document.frames["steal"];
　　     　　      iframe.document.Submit("transfer");
　　　　　　}
　　　　</script>
　　</head>

　　<body onload="steal()">
　　　　<iframe name="steal" display="none">
　　　　　　<form method="POST" name="transfer"　action="http://www.myBank.com/Transfer.php">
　　　　　　　　<input type="hidden" name="toBankId" value="11">
　　　　　　　　<input type="hidden" name="money" value="1000">
　　　　　　</form>
　　　　</iframe>
　　</body>
</html>


2. 防御

在表单中添加后台脚本生成的随机数，然后再后台与cookie验证，这个随机数危险网站很难生成相同的，会杜绝掉99%
　<?php
　　　　//构造加密的Cookie信息
　　　　$value = “DefenseSCRF”;
　　　　setcookie(”cookie”, $value, time()+3600);
　　?>
　　
　　　　<?php
　　　　$hash = md5($_COOKIE['cookie']);
　　?>
　　<form method=”POST” action=”transfer.php”>
　　　　<input type=”text” name=”toBankId”>
　　　　<input type=”text” name=”money”>
　　　　<input type=”hidden” name=”hash” value=”<?=$hash;?>”>
　　　　<input type=”submit” name=”submit” value=”Submit”>
　　</form>
      <?php
　　      if(isset($_POST['check'])) {
     　　      $hash = md5($_COOKIE['cookie']);
          　　 if($_POST['check'] == $hash) {
               　　 doJob();
　　           } else {
　　　　　　　　//...
          　　 }
　　      } else {
　　　　　　//...
　　      }
      ?>
      
      
      




