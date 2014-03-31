

1. about: 
CSRF（Cross-site request forgery），中文名称：跨站请求伪造，也被称为：one click attack/session riding，缩写为：CSRF/XSRF

原理: 

浏览器多标签情况下 各个标签共享一个进程，可以互相获取存在浏览器的信息

如果用户先在工行网银登陆，工行服务器会在浏览器存储cookie, 如果在未退出工行网银的情况下
用户切换标签登录到一个不安全的网站，该网站便会有机会通过js的referer 拿到工行的地址和 其它一些信息 并伪造请求

示例1：
用户在工行付费连接
http://www.mybank.com/Transfer.php?toBankId=11&money=1000

然后访问危险网站，网站会根据js生成
<img src=http://www.mybank.com/Transfer.php?toBankId=11&money=1000>
当图片加载完后 用户的付费连接访问了2次，多付费1000

这种情况需要用post请求避免
