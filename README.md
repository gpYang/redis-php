## Redis 驱动器

使用魔法函数重载，可调用 `php_redis` 扩展内的所有函数。

## 使用方法

静态调用

```php
$conf = [
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'password' => '',
    'database' => 2,
];
\sys\Redis::config($conf);
var_dump(\sys\Redis::hGetAll('user:1'));
```

动态调用

```php
$conf = [
    'host'     => '127.0.0.1',
    'port'     => 6379,
    'password' => '',
    'database' => 2,
];
$redis = new \sys\Redis;
$redis->config($conf);
var_dump($redis->hGetAll('user:1'));
```
