# RedisDriver.php

Redis驱动器，统一配置信息与数据存储规则，将原生函数封装为更易于使用的类，引入表的概念，让使用者更容易理解数据的存储结构。

### 字符串 (String)

```php
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redisDriver = new RedisDriver($config);
// 保存
$redisDriver->setStr('captcha', '125864', 60);
// 查询
$captcha = $redisDriver->getStr('captcha');
// 删除
$redisDriver->delStr('captcha');
```
### 哈希表 (Hash)

```php
$redisDriver = new RedisDriver();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redisDriver->connect($config);
$data = array('uid' => 1008, 'name' => '小明');
// 保存
$redisDriver->setHash('session_id', $data, 7200);
// 返回所有字段
$array = $redisDriver->getHash('session_id');
// 返回一个字段
$array = $redisDriver->getHash('session_id', 'name');
// 返回多个字段
$array = $redisDriver->getHash('session_id', array('uid', 'name'));
// 删除一个字段
$redisDriver->delHash('session_id', 'name');
// 删除
$redisDriver->delHash('session_id');
```

## 表格 (Table)

```php
$redisDriver = new RedisDriver();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redisDriver->connect($config);
$data = array('uid' => 1008, 'name' => '小明', 'sex' => 1);
// 保存
$redisDriver->setTableRow('users', '1008', $data);
// 查询
$array = $redisDriver->getTableRow('users', '1008');
// 删除
$redisDriver->delTableRow('users', '1008');
```

## 列表 (List)

```php
$redisDriver = new RedisDriver();
$config = array(
	'ip' => '127.0.0.1',
	'port' => '6379',
	'passwd' => '123456',
);
$redisDriver->connect($config);
$data = array('uid' => 1008, 'name' => '小明', 'sex' => 1);
// 入列
$redisDriver->pushList('queue_userinfo', '1008', $data);
// 出列 (无数据时等待60秒)
$array = $redisDriver->pullList('queue_userinfo', 60);
// 列表长度
$size = $redisDriver->getListSize('queue_userinfo');
// 删除
$redisDriver->delList('queue_userinfo');
```
