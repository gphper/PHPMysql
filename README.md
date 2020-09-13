## PHPMysql

php 使用socket 直连mysql，并实现mysql协议

## 初衷

最近研究数据库中间件的时候，看到连接中间件的时候和链接数据库的操作没什么区别，然后就就想到中间件中肯定是实现了mysql协议。人开发者在连接的时候不会感觉到什么不习惯的地方，于是好奇心驱使我就想用php实现在mysql协议，使用TCP直接连接数据库进行操作，当然平时是要用的时候都是 PDO 扩展帮我们做好的，这个项目主要目的在于研究mysql协议，请勿用于线上正式环境

## 使用示例

```php
require "vendor/autoload.php";

use PHPMysql\DBHandle\DB;

//查询
$query = DB::connection("root","hengda","test")->query("select * from `order`");
//插入
DB::connection()->query("insert INTO `order` (order_sn) VALUES ('sasdas')");
//更新
DB::connection()->query("update `order` set order_sn = 'hello' where ord_id = 2");
//删除
DB::connection()->query("delete from `order` where ord_id = 26");
```

## 技术分享列表
* [php一步一步实现mysql协议(一)——抓包本地mysql通信](https://www.cnblogs.com/itsuibi/p/13654492.html)
* [php一步一步实现mysql协议(二) ——握手初始化](https://www.cnblogs.com/itsuibi/p/13660811.html)
* [php一步一步实现mysql协议(三) ——登录认证密码加密](https://www.cnblogs.com/itsuibi/p/13661538.html)
* [php一步一步实现mysql协议(四)——执行命令](https://www.cnblogs.com/itsuibi/p/13661555.html)

