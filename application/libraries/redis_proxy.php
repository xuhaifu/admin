<?php
class Redis_proxy
{

    private $redis;

    public function __construct($conf = null)
    {
        if(empty($conf) && ! is_array($conf)) {
            $CI = & get_instance();
            $CI->load->config('redis', TRUE);
            $conf = $CI->config->item('redis');
        }
        $this->redis = new Redis();
        if(! $this->redis->connect($conf['host'], $conf['port'])) {
            return false;
        }
        if(isset($conf['password']) && $conf['password'] != '') {
            if(! $this->redis->auth($conf['password'])) {
                return false;
            }
        }
    }
    
    /**
     * 设置值
     * 
     * @param string $key KEY名称
     * @param string|array $data  获取得到的数据
     * @param int $ttl 时间
     */
    public function set($key, $data, $ttl = 3600)
    {
        $data = json_encode($data, TRUE);
        return ($ttl) ? $this->redis->setex($key, $ttl, $data) : $this->redis->set($key, $data);
    }
    
    /**
     * 通过KEY获取数据
     * 
     * @param string $key KEY名称
     */
    public function get($key)
    {
        $result = $this->redis->get($key);
        return json_decode($result, TRUE);
    }
    
    /**
     * 删除一条数据
     * 
     * @param string $key KEY名称
     */
    public function delete($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * 设置过期时间
     */
    public function expire($key, $ttl)
    {
        return $this->redis->expire($key, $ttl);
    }
    /**
     * 清空数据
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }
    
    /**
     * 数据入队列
     * 
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value, $right = true)
    {
        $value = json_encode($value);
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }
    
    /**
     * 数据出队列
     * 
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key, $left = true)
    {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);
        return json_decode($val);
    }
    
    /**
     * 数据自增
     * 
     * @param string $key KEY名称
     */
    public function incr($key)
    {
        return $this->redis->incr($key);
    }
    
    /**
     * 数据自减
     * 
     * @param string $key KEY名称
     */
    public function decr($key)
    {
        return $this->redis->decr($key);
    }
    
    /**
     * key是否存在，存在返回ture
     * 
     * @param string $key KEY名称
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function sAdd($store_name, $value)
    {
        return $this->redis->sAdd($store_name, $value);
    }

    public function sRemove($store_name, $value)
    {
        return $this->redis->sRemove($store_name, $value);
    }

    public function sMembers($store_name)
    {
        return $this->redis->sMembers($store_name);
    }

    /**
     * 返回redis对象
     */
    public function redis()
    {
        return $this->redis;
    }
}