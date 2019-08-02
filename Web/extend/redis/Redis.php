<?php
namespace redis;
use \Redis as RedisBase;
// +-----------------------------------------------------
// | Redis扩展类
// +-----------------------------------------------------
// | author: xcjiu
// +-----------------------------------------------------
// | github: https://github.com/xcjiu/PHP-Redis
// +-----------------------------------------------------
class Redis
{
    private static $redis = null;
    private static $expire = 3600; //默认存储时间（秒）
    private static $host = '127.0.0.1';
    private static $port = '6379';
    private static $password = '';
    private static $db = 0;
    private static $timeout = 3;
    /**
     * 初始化Redis连接
     * 所有配置参数在实例化Redis类时加入参数即可
     */
    public function __construct($config=[])
    {
        if($config && is_array($config)){
            self::config($config);
        }
        if(self::$redis==null){
            self::$redis = new RedisBase();
        }
        self::$redis->connect(self::$host,self::$port,self::$timeout) or die('Redis 连接失败！');
        if(!empty(self::$password)){
            self::$redis->auth(self::$password); //如果有设置密码，则需要连接密码
        }
        if((int)self::$db){
            self::$redis->select(self::$db); //选择缓存库
        }
    }
    
    //构造函数可能不起作用，则用这个初始化类 Redis::_initialize($config=[])
    public static function _initialize($config=[])
    {
        self::initRedis($config);
    }

    //都不好用主动调用
    public static function initRedis($config=[])
    {
        if($config && is_array($config)){
            self::config($config);
        }
        if(self::$redis==null){
            self::$redis = new RedisBase();
        }
        self::$redis->connect(self::$host,self::$port,self::$timeout) or die('Redis 连接失败！');
        if(!empty(self::$password)){
            self::$redis->auth(self::$password); //如果有设置密码，则需要连接密码
        }
        if((int)self::$db){
            self::$redis->select(self::$db); //选择缓存库
        }
    }

    /**
     * 加载配置参数
     * @param  array  $config 配置数组
     */
    private static function config(array $config=[])
    {
        self::$host = isset($config['host']) ? $config['host'] : '127.0.0.1'; 
        self::$port = isset($config['port']) ? $config['port'] : '6379'; 
        self::$password = isset($config['password']) ? $config['password'] : ''; 
        self::$db = isset($config['db']) ? $config['db'] : ''; 
        self::$expire = isset($config['expire']) ? $config['expire'] : 3600; 
        self::$timeout = isset($config['timeout']) ? $config['timeout'] : 10; 
    }

    /**
     * 切换到指定的数据库, 数据库索引号用数字值指定
     * @param  int $db 存储库
     * @return 
     */
    public static function selectdb($db)
    {
        self::$redis->select((int)$db);
    }

    /**
     * 创建当前数据库的备份(该命令将在 redis 安装目录中创建dump.rdb文件)
     * @return bool 成功true否则false (如果需要恢复数据，只需将备份文件 (dump.rdb) 移动到 redis 安装目录并启动服务即可)
     */
    public static function savedb()
    {
        return self::$redis->save();
    }

    /**
     * 存储一个键值
     * @param  string or int $key 键名
     * @param  mix $value 键值，支持数组、对象
     * @param  int $expire 过期时间(秒)
     * @return bool 返回布尔值
     */
    public static function set($key, $value, $expire='')
    {
        if(is_int($key) || is_string($key)){
            //如果是int类型的数字就不要序列化，否则用自增自减功能会失败，
            //如果不序列化，set()方法只能保存字符串和数字类型,
            //如果不序列化，浮点型数字会有失误，如13.6保存，获取时是13.59999999999
            $value = is_int($value) ? $value : serialize($value);
            $expire = (int)$expire ? $expire : self::$expire;
            if(self::$redis->set($key, $value) && self::$redis->expire($key, $expire)){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 设置过期时间
     * @param  string or int $key 键名
     * @param  int $expire 过期时间(秒)
     * @return bool 返回布尔值  [如果成功返回true,如果键不存在或已过期则返回false]
     */
    public static function expire($key, $expire=0)
    {
        $expire = (int)$expire ? $expire : self::$expire;
        if(self::$redis->expire($key, $expire)){
            return true;
        }
        return false;
    }

    /**
     * 获取键值
     * @param string or int $key 键名
     * @return mix 返回值
     */
    public static function get($key)
    {
        $value = self::$redis->get($key);
        if(is_object($value)){
            return $value;
        }
        return is_numeric($value) ? $value : unserialize($value);
    }

    /**
     * 删除一个键值
     * @param  string or int $key 键名
     * @return int 成功返回1 ，失败或不存在键返回0
     */
    public static function del($key)
    {
        return self::$redis->del($key);
    } 

    /**
     * 截取字符串,支持汉字
     * @param  string or int $key 键名
     * @param  int $start 起始位，从0开始
     * @param  int $end   截取长度
     * @return string   返回字符串,如果键不存在或值不是字符串类型则返回false
     */
    public static function substr($key,$start,$end=0)
    {   
        $value = self::get($key);
        if($value && is_string($value)){
           if($end){
                return mb_substr($value,$start,$end);
            }
            return mb_substr($value,$start); 
        }
        return false;
    }

    /**
     * 设置指定 key 的值，并返回 key 的旧值
     * @param  string or int  $key 键名
     * @param  mix  $value 要指定的健值，支持数组
     * @param  int $expire 过期时间，如果不填则用全局配置
     * @return mix 返回旧值，如果旧值不存在则返回false,并新创建key的键值
     */
    public static function replace($key, $value, $expire=0)
    {
        $value = self::$redis->getSet($key, $value);
        $expire = (int)$expire ? $expire : self::$expire;
        self::$redis->expire($key, $expire);
        return is_numeric($value) ? $value : unserialize($value);
    }

    /**
     * 同时设置一个或多个键值对。（支持数组值）
     * @param  array  $arr [要设置的键值对数组]
     * @return bool 返回布尔值，成功true否则false
     */
    public static function mset($arr)
    {
        if($arr && is_array($arr)){
            foreach ($arr as &$value) {
               $value = is_int($value) ? $value : serialize($value);
            }
            if(self::$redis->mset($arr)){
               return true; 
            }
           return false; 
        }
        return false;
    }

    /**
     * 返回所有(一个或多个)给定 key 的值
     * 可传入一个或多个键名参数，键名字符串类型，如 $values = $redis::mget('one','two','three', ...);
     * @return 返回包含所有指定键值数组，如果不存在则返回false
     */
    public static function mget()
    {
        $keys = func_get_args();
        if($keys){
            $values = self::$redis->mget($keys);
            if($values){
                foreach ($values as &$value) {
                    $value = is_numeric($value) ? $value : unserialize($value);
                }
                return $values;
            }
        }
        return false;
    }

    /**
     * 查询剩余过期时间（秒）
     * @param  string or int $key  键名
     * @return int 返回剩余的时间，如果已过期则返回负数
     */
    public static function expiretime($key) 
    {
        return self::$redis->ttl($key);
    }

    /**
     * 指定的 key 不存在时，为 key 设置指定的值(SET if Not eXists)
     * @param  string or int $key  键名
     * @param  mix  $value 要指定的健值，支持数组
     * @param  int $expire 过期时间，如果不填则用全局配置
     * @return bool  设置成功返回true 否则false
     */
    public static function setnx($key, $value, $expire=0)
    {
        $value = is_int($value) ? $value : serialize($value);
        $res = self::$redis->setnx($key, $value);
        if($res){
            $expire = (int)$expire ? $expire : self::$expire;
            self::$redis->expire($key, $expire);
        }
        return $res;
    }

    /**
     * 返回对应键值的长度
     * @param  string or int $key  键名
     * @return int  返回字符串的长度，如果键值是数组则返回数组元素的个数，如果键值不存在则返回0
     */
    public static function valuelen($key)
    {
        $value = self::get($key);
        $lenth = 0;
        if($value){
            if(is_array($value)){
                $lenth = count($value);
            }else{
               $lenth = strlen($value);
           }
        }
        return $lenth;
    }

    /**
     * 将 key 中储存的数字值自增
     * @param  string or int $key  键名
     * @param  int $int 自增量，如果不填则默认是自增量为 1
     * @return int  返回自增后的值，如果键不存在则新创建一个值为0，并在此基础上自增，返回自增后的数值.如果键值不是可转换的整数，则返回false
     */
    public static function inc($key, $int=0)
    {
        if((int)$int){
            return self::$redis->incrby($key,$int);
        }else{
            return self::$redis->incr($key);
        }
    }

    /**
     * 将 key 中储存的数字值自减
     * @param  string or int $key  键名
     * @param  int $int 自减量，如果不填则默认是自减量为 1
     * @return int  返回自减后的值，如果键不存在则新创建一个值为0，并在此基础上自减，返回自减后的数值.如果键值不是可转换的整数，则返回false
     */
    public static function dec($key, $int=0)
    {
        if((int)$int){
            return self::$redis->decrby($key,$int);
        }else{
            return self::$redis->decr($key);
        }
    }

    /**
     * 为指定的 key 追加值
     * @param  string or int $key  键名
     * @param  mix  $value 要指定的健值，支持数组
     * @param  bool $pos 要追加的位置，默认false为追加至末尾，true则追加到开头
     * @param  int $expire 过期时间，如果不填则用全局配置
     * @return bool  设置成功返回true 否则false,支付向字符串或者数组追加内容，向字符串追加时加入的值必须为字符串类型，如果健不存在则创建新的键值对
     */
    public static function append($key, $value, $pos=false, $expire=0)
    {
        $cache = self::get($key);
        if($cache){
            if(is_array($cache)){
                if($pos===true){
                    $value = array_unshift($cache, $value);
                }else{
                   $value = array_push($cache, $value); 
               }
            }else{
                if(!is_string($value)){
                    return false;
                }
                if($pos===true){
                    $value .= $cache;
                }else{
                    $value = $cache . $value;
                }
            }
        }
        return self::set($key, $value, $expire);
    }
 

    // +--------------------------------------------------
    // | 以上方法均为字符串常用方法，并且把数组也兼容了
    // | 以下为哈希表处理方法
    // +--------------------------------------------------
    

    /**
     * 为哈希表中的字段赋值 
     * @param  string  $table  哈希表名
     * @param  string  $column 字段名
     * @param  string|array  $value  字段值
     * @param  int $expire 过期时间, 如果不填则不设置过期时间
     * @return int  如果成功返回 1，否则返回 0.当字段值已存在时覆盖旧值并且返回 0  
     */
    public static function hset($table, $column, $value, $expire=0)
    {
        $value = is_array($value) ? json_encode($value) : $value;
        $res = self::$redis->hset($table, $column, $value);
        if((int)$expire){
            self::$redis->expire($table, $expire);
        }        
       return $res;
    }

    /**
     * 获取哈希表字段值
     * @param  string $table  表名
     * @param  string $column 字段名
     * @return mix  返回字段值，如果字段值是数组保存的返回json格式字符串，转换成数组json_encode($value),如果字段不存在返回false;
     */
    public static function hget($table, $column)
    {
        return self::$redis->hget($table, $column);
    }

    /**
     * 删除哈希表 key 中的一个或多个指定字段，不存在的字段将被忽略
     * @param  string $table  表名
     * @param  string $column 字段名
     * @return int  返回被成功删除字段的数量，不包括被忽略的字段,(删除哈希表用self::del($table))
     */
    public static function hdel($table, $columns)
    {
        $columns = func_get_args();
        $table = $columns[0];
        $count = count($columns);
        $num = 0;
        for ($i=1; $i < $count; $i++) { 
            $num += self::$redis->hdel($table, $columns[$i]);
        }
        return $num;
    }

    /**
     * 查看哈希表的指定字段是否存在
     * @param  string $table  表名
     * @param  string $column 字段名
     * @return bool  存在返回true,否则false
     */
    public static function hexists($table, $column)
    {
        if((int)self::$redis->hexists($table, $column)){
            return true;
        }
        return false;
    }

    /**
     * 返回哈希表中，所有的字段和值
     * @param  string $table 表名
     * @return array   返回键值对数组
     */
    public static function hgetall($table)
    {
        return self::$redis->hgetall($table);
    }

    /**
     * 为哈希表中的字段值加上指定增量值(支持整数和浮点数)
     * @param  string $table  表名
     * @param  string $column 字段名
     * @param  int $num  增量值，默认1, 也可以是负数值,相当于对指定字段进行减法操作
     * @return int|float|bool  返回计算后的字段值,如果字段值不是数字值则返回false,如果哈希表不存在或字段不存在返回false
     */
    public static function hinc($table, $column, $num=1)
    {
        $value = self::hget($table, $column);
        if(is_numeric($value)){ //数字类型，包括整数和浮点数
            $value += $num;
            self::hset($table, $column, $value);
            return $value;
        }else{
            return false;
        }
    }

    /**
     * 获取哈希表中的所有字段
     * @param  string $table  表名
     * @return array  返回包含所有字段的数组
     */
    public static function hkeys($table)
    {
        return self::$redis->hkeys($table);
    }

    /**
     * 返回哈希表所有域(field)的值
     * @param  string $table  表名
     * @return array  返回包含所有字段值的数组,数字索引
     */
    public static function hvals($table)
    {
        return self::$redis->hvals($table);
    }

    /**
     * 获取哈希表中字段的数量
     * @param  string $table  表名
     * @return int 如果哈希表不存在则返回0
     */
    public static function hlen($table)
    {
        return self::$redis->hlen($table);
    }

    /**
     * 获取哈希表中，一个或多个给定字段的值
     * @param  string $table  表名
     * @param  string $columns 字段名
     * @return array  返回键值对数组，如果字段不存在则字段值为null, 如果哈希表不存在返回空数组
     */
    public static function hmget($table, $columns)
    {
        $data = self::hgetall($table);
        $result = [];
        if($data){
            $columns = func_get_args();
            unset($columns[0]);
            foreach ($columns as $value) {
                $result[$value] = isset($data[$value]) ? $data[$value] : null;
            }
        }
        return $result;
    }

    /**
     * 同时将多个 field-value (字段-值)对设置到哈希表中
     * @param  string $table  表名
     * @param  array $data  要添加的键值对
     * @param  int $expire  过期时间，不填则不设置过期时间
     * @return bool 成功返回true,否则false
     */
    public static function hmset($table, array $data, $expire=0)
    {
        $result = self::$redis->hmset($table, $data);
        if((int)$expire){
            self::expire($table, $expire);  
        }
        return $result;
    }

    /**
     * 为哈希表中不存在的的字段赋值
     * @param  string  $table  哈希表名
     * @param  string  $column 字段名
     * @param  string|array  $value  字段值
     * @param  int $expire 过期时间, 如果不填则不设置过期时间
     * @return bool  如果成功返回true，否则返回 false.
     */
    public static function hsetnx($table, $column, $value, $expire=0)
    {
        if(is_array($value)){
            $value = json_encode($value);
        }
        $result = self::$redis->hsetnx($table, $column, $value);
        if((int)$expire){
            self::expire($table, $expire);  
        }
        return $result;
    }



    // +--------------------------------------------------
    // | 以上方法均为哈希表常用方法
    // | 以下为列表处理方法
    // +--------------------------------------------------


    /**
     * 将一个或多个值插入到列表头部（值可重复）或列表尾部。如果列表不存在，则创建新列表并插入值
     * @param  string  $list  列表名
     * @param  string|array  $value  要插入的值,如果是多个值请放入数组传入
     * @param  string $pop 要插入的位置，默认first头部,last表示尾部
     * @param  int $expire 过期时间, 如果不填则不设置过期时间
     * @return int  返回列表的长度
     */
    public static function lpush($list, $value, $pop='first', $expire=0)
    {
        if(is_array($value)){
            foreach ($value as $v) {
                $result = ($pop=='last') ? self::$redis->rpush($list,$v) : self::$redis->lpush($list, $v);
            }
        }else{
            $result = ($pop=='last') ? self::$redis->rpush($list,$value) : self::$redis->lpush($list, $value);
        }
        if((int)$expire){
            self::expire($list, $expire);  
        }
        return $result;
    }

    /**
     * 通过索引获取列表中的元素
     * @param  string  $list  列表名
     * @param  int  $index  索引位置，从0开始计,默认0表示第一个元素，-1表示最后一个元素索引
     * @return string  返回指定索引位置的元素
     */
    public static function lindex($list, $index=0)
    {
        return self::$redis->lindex($list, $index);
    }

    /**
     * 通过索引来设置元素的值
     * @param  string $list  列表名
     * @param  string $value 元素值
     * @param  int $index 索引值
     * @return bool  成功返回true,否则false.当索引参数超出范围，或列表不存在返回false。
     */
    public static function lset($list, $index, $value)
    {
        return self::$redis->lset($list, $index, $value);
    }

    /**
     * 返回列表中指定区间内的元素
     * @param  string  $list  列表名
     * @param  int  $start  起始位置，从0开始计,默认0
     * @param  int  $end 结束位置，-1表示最后一个元素，默认-1
     * @return array  返回列表元素数组
     */
    public static function lrange($list, $start=0, $end=-1)
    {
        return self::$redis->lrange($list, $start, $end);
    }

    /**
     * 返回列表的长度
     * @param  string $list 列表名
     * @return int  列表长度
     */
    public static function llen($list)
    {
        return self::$redis->llen($list);
    }

    /**
     * 移出并获取列表的第一个元素或最后一个元素
     * @param  string $list 列表名
     * @param  string $pop 移出并获取的位置，默认first为第一个元素
     * @return string|bool  列表第一个元素或最后一个元素,如果列表不存在则返回false
     */
    public static function lpop($list, $pop='first')
    {
        if($pop=='last'){
            return self::$redis->rpop($list);
        }
        return self::$redis->lpop($list);
    }

    /**
     * 从列表中弹出最后一个值，将弹出的元素插入到另外一个列表开头并返回这个元素
     * @param  string $list1  要弹出元素的列表名
     * @param  string $list2  要接收元素的列表名
     * @return string|bool  返回被弹出的元素,如果其中有一个列表不存在则返回false
     */
    public static function lpoppush($list1,$list2)
    {
        if(self::lrange($list1) && self::lrange($list2)){
            return self::$redis->brpoplpush($list1, $list2, 500);
        }
        return false;
    }

    /**
     * 用于在指定的列表元素前或者后插入元素。如果元素有重复则选择第一个出现的。当指定元素不存在于列表中时，不执行任何操作
     * @param  string $list   列表名
     * @param  string $element 指定的元素
     * @param  string $value   要插入的元素
     * @param  string $pop     要插入的位置，before前,after后。默认before
     * @return int  返回列表的长度。 如果没有找到指定元素 ，返回 -1 。 如果列表不存在或为空列表，返回 0 。
     */
    public static function linsert($list, $element, $value, $pop='before')
    {
        return self::$redis->linsert($list, $pop, $element, $value);
    }

    /**
     * 移除列表中指定的元素
     * @param  string  $list  列表名
     * @param  string  $element  指定的元素
     * @param  int $count  要删除的个数，0表示删除所有指定元素，负整数表示从表尾搜索, 默认0
     * @return int  被移除元素的数量。 列表不存在时返回 0 
     */
    public static function lrem($list, $element, $count=0)
    {
        return self::$redis->lrem($list, $count, $element);
    }

    /**
     * 让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除
     * @param  string $list  列表名
     * @param  int $start 起始位置，从0开始
     * @param  int $stop  结束位置，负数表示倒数第n个
     * @return bool  成功返回true否则false
     */
    public static function ltrim($list, $start, $stop)
    {
        return self::$redis->ltrim($list, $start, $stop);
    }



    // +--------------------------------------------------
    // | 以上方法均为列表常用方法
    // | 以下为集合处理方法(集合分为有序和无序集合)
    // +--------------------------------------------------
    


    /**
     * 将一个或多个元素加入到无序集合中，已经存在于集合的元素将被忽略.如果集合不存在，则创建一个只包含添加的元素作成员的集合。
     * @param  string $set  集合名称
     * @param  string|array $value 元素值（唯一）,如果要加入多个元素请传入多个元素的数组
     * @return int  返回被添加元素的数量.如果$set不是集合类型时返回0
     */
    public static function sadd($set, $value)
    {
        $num = 0;
        if(is_array($value)){
            foreach ($value as $key =>$v) {
                $num += self::$redis->sadd($set, $v);
            }
        }else{
            $num += self::$redis->sadd($set, $value);
        }
        return $num;
    }

    /**
     * 返回无序集合中的所有的成员
     * @param  string $set 集合名称
     * @return  array  返回包含所有成员的数组
     */
    public static function smembers($set)
    {
        return self::$redis->smembers($set);
    }

    /**
     * 获取集合中元素的数量。
     * @param  string $set 集合名称
     * @return int  返回集合的成员数量
     */
    public static function scard($set)
    {
        return self::$redis->scard($set);
    }

    /**
     * 移除并返回集合中的一个随机元素
     * @param  string $set 集合名称
     * @return string|bool  返回移除的元素,如果集合为空则返回false
     */
    public static function spop($set)
    {
        return self::$redis->spop($set);
    }

    /**
     * 移除集合中的一个或多个成员元素，不存在的成员元素会被忽略
     * @param  string $set 集合名称
     * @param  string|array $member 要移除的元素，如果要移除多个请传入多个元素的数组
     * @return int  返回被移除元素的个数
     */
    public static function srem($set, $member)
    {
        $num = 0;
        if(is_array($member)){
            foreach ($member as $value) {
                $num += self::$redis->srem($set, $value);
            }
        }else{
            $num += self::$redis->srem($set, $member);
        }
        return $num;
    }

    /**
     * 返回集合中的一个或多个随机元素
     * @param  string $set   集合名称
     * @param  int $count 要返回的元素个数，0表示返回单个元素，大于等于集合基数则返回整个元素数组。默认0
     * @return string|array   返回随机元素，如果是返回多个则为数组返回
     */
    public static function srand($set, $count=0)
    {
        return ((int)$count==0) ? self::$redis->srandmember($set) : self::$redis->srandmember($set, $count);
    }

    /**
     * 返回给定集合之间的差集(集合1对于集合2的差集)。不存在的集合将视为空集
     * @param  string $set1 集合1名称
     * @param  string $set2 集合2名称
     * @return array  返回差集（即筛选存在集合1中但不存在于集合2中的元素）
     */
    public static function sdiff($set1, $set2)
    {
        return self::$redis->sdiff($set1, $set2);
    }

    /**
     * 将给定集合set1和set2之间的差集存储在指定的set集合中。如果指定的集合已存在，则会被覆盖。
     * @param  string $set  指定存储的集合
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return int  返回指定存储集合元素的数量
     */
    public static function sdiffstore($set, $set1, $set2)
    {
        return self::$redis->sdiffstore($set, $set1, $set2);
    }

    /**
     * 返回set1集合和set2集合的交集（即筛选同时存在集合1和集合2中的元素）
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return array  返回包含交集元素的数组
     */
    public static function sinter($set1, $set2)
    {
        return self::$redis->sinter($set1, $set2);
    }

    /**
     * 将给定集合set1和set2之间的交集存储在指定的set集合中。如果指定的集合已存在，则会被覆盖。
     * @param  string $set  指定存储的集合
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return int  返回指定存储集合元素的数量
     */
    public static function sinterstore($set, $set1, $set2)
    {
        return self::$redis->sinterstore($set, $set1, $set2);
    }

    /**
     * 判断成员元素是否是集合的成员
     * @param  string $set   集合名称
     * @param  string $member 要判断的元素
     * @return bool 如果成员元素是集合的成员返回true,否则false
     */
    public static function sismember($set, $member)
    {
        return self::$redis->sismember($set, $member);
    }

    /**
     * 将元素从集合1中移动到集合2中
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @param  string $member 要移动的元素成员
     * @return bool  成功返回true,否则false
     */
    public static function smove($set1, $set2, $member)
    {
        return self::$redis->smove($set1, $set2, $member);
    }

    /**
     * 返回集合1和集合2的并集(即两个集合合并后去重的结果)。不存在的集合被视为空集。
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return array  返回并集数组
     */
    public static function sunion($set1, $set2)
    {
        return self::$redis->sunion($set1, $set2);
    }

    /**
     * 将给定集合set1和set2之间的并集存储在指定的set集合中。如果指定的集合已存在，则会被覆盖。
     * @param  string $set  指定存储的集合
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return int  返回指定存储集合元素的数量
     */
    public static function sunionstore($set, $set1, $set2)
    {
        return self::$redis->sunionstore($set, $set1, $set2);
    }


    /* ----------------------以下有序集合----------------------- */

    /**
     * 将一个或多个成员元素及其分数值加入到有序集当中，如果成员已存在则更新它的分数值，如果集合不存在则创建
     * @param  string $set    集合名称
     * @param  array $arr  元素（唯一）与其分数值（分数值可以是整数值或双精度浮点数）组成的数组
     * @return int  返回添加成功的成员数量
     */
    public static function zadd($set, $arr)
    {
        $num = 0;
        if($arr && is_array($arr)){
            foreach ($arr as $key => $value) {
                if(is_numeric($value)){
                    $num += self::$redis->zadd($set, $value, $key);
                }
            }
        }
        return $num;
    }

    /**
     * 返回有序集中，指定区间内的成员。默认返回所有成员(默认升序排列)
     * @param  string  $set   集合名称
     * @param  int  $start 起始位置，从0开始计，默认0
     * @param  int $stop  结束位置，-1表示最后一位，默认-1
     * @param  bool $desc 排序，false默认升序，true为倒序
     * @return array  返回有序集合中指定区间内的成员数组（默认返回所有）
     */
    public static function zrange($set, $start=0, $stop=-1, $desc=false)
    {
        if($desc===true){
            return self::$redis->ZREVRANGE($set, $start, $stop, 'WITHSCORES');
        }
        return self::$redis->zrange($set, $start, $stop, 'WITHSCORES');
    }

    /**
     * 返回有序集合中成员数量
     * @param  string $set 集合名称
     * @return int  返回成员的数量
     */
    public static function zcard($set)
    {
        return self::$redis->zcard($set);
    }

    /**
     * 计算有序集合中指定分数区间的成员数量
     * @param  string $set 集合名称
     * @param  int|float $min 最小分数值
     * @param  int|float $max 最大分数值
     * @return int  返回指定区间的成员数量
     */
    public static function zcount($set, $min, $max)
    {
        return self::$redis->zcount($set, $min, $max);
    }

    /**
     * 对有序集合中指定成员的分数加上增量
     * @param  string $set 集合名称
     * @param  string  $member 指定元素
     * @param  int $num   增量值，负数表示进行减法运算，默认1
     * @return float  返回运算后的分数值（浮点型）
     */
    public static function zinc($set, $member, $num=1)
    {
        return self::$redis->zincrby($set, $num, $member);
    }

    /**
     * 计算set1和set2有序集的交集，并将该交集(结果集)储存到新集合set中。
     * @param  string $set  指定存储的集合名称
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return int  返回保存到目标结果集的的成员数量
     */
    public static function zinterstore($set, $set1, $set2)
    {
        return self::$redis->zinterstore($set, 2, $set1, $set2);
    }


    /**
     * 计算set1和set2有序集的并集，并将该并集(结果集)储存到新集合set中。
     * @param  string $set  指定存储的集合名称
     * @param  string $set1 集合1
     * @param  string $set2 集合2
     * @return int  返回保存到目标结果集的的成员数量
     */
    public static function zunionstore($set, $set1, $set2)
    {
        return self::$redis->zunionstore($set, 2, $set1, $set2);
    }

    /**
     * 返回有序集合中指定分数区间的成员列表。有序集成员按分数值递增(从小到大)次序排列
     * @param  string $set   集合名称
     * @param  string $min  最小分数值字符串表示，'1'表示>=1，(1'表示>1
     * @param  string $max  最大分数值字符串表示,'100'表示<=1，(100'表示<100
     * @param  bool $withscores  返回的数组是否包含分数值，默认true, false不包含
     * @return array   返回指定区间的成员，默认是元素=>分数值的键值对数组。如果只要返回包含元素的数组请设置$withscores=false
     */
    public static function zrangebyscore($set, $min, $max, $withscores=true)
    {
        if($withscores===true){
            return self::$redis->zrangebyscore($set, $min, $max, ['withscores'=>'-inf']);
        }
        return self::$redis->zrangebyscore($set, $min, $max);
    }

    /**
     * 返回有序集中指定成员的排名。其中有序集成员按分数值递增(从小到大)顺序排列。
     * @param  string $set    集合名称
     * @param  string $member 成员名称（元素）
     * @return int|bool   返回 member 的排名, 如果member不存在返回false
     */
    public static function zrank($set, $member)
    {
        return self::$redis->zrank($set, $member);
    }

    /**
     * 移除有序集中的一个或多个成员，不存在的成员将被忽略。
     * @param  string $set     有序集合名称
     * @param  string|array $members 要移除的成员，如果要移除多个请传入多个成员的数组
     * @return int   返回被移除的成员数量，不存在的成员将被忽略
     */
    public static function zrem($set, $members)
    {
        $num = 0;
        if(is_array($members)){
            foreach ($members as $value) {
                $num += self::$redis->zrem($set, $value);
            }
        }else{
            $num += self::$redis->zrem($set, $members);
        }
        return $num;
    }

    /**
     * 移除有序集中，指定分数（score）区间内的所有成员。
     * @param  string $set   集合名称
     * @param  int $min 最小分数值
     * @param  int $max 最大分数值
     * @return int  返回被移除的成员数量
     */
    public static function zrembyscore($set, $min, $max)
    {
        return self::$redis->zremrangebyscore($set, $min, $max);
    }

    /**
     * 移除有序集中，指定排名(rank)区间内的所有成员（这个排名数字越大排名越高，最低排名0开始）
     * @param  string $set   集合名称
     * @param  int $min 最小排名，从0开始计
     * @param  int $max 最大排名
     * @return int  返回被移除的成员数量，如移除排名为倒数5名的成员：$redis::zremrank($set,0,4);
     */
    public static function zrembyrank($set, $min, $max)
    {
        return self::$redis->zremrangebyrank($set, $min, $max);
    }

    /**
     * 返回有序集中，成员的分数值。
     * @param  string $set    集合名称
     * @param  string $member 成员
     * @return float|bool   返回分数值(浮点型)，如果成员不存在返回false
     */
    public static function zscore($set, $member)
    {
        return self::$redis->zscore($set, $member);
    }

    /**
     * 开启事务
     */
    public static function transation()
    {
        self::$redis->multi();
    }

    /**
     * 提交事务
     */
    public static function commit()
    {
        self::$redis->exec();
    }

    /**
     * 取消事务
     */
    public static function discard()
    {
        self::$redis->discard();
    }




    





    public static function myself()
    {
        return self::$redis;
    }
}
