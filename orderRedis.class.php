<?php
class OrderRedis {

    private $redis;
    private $host;  //redis ip
    private $port;  //redis 端口

    public function __construct($host,$port){
        $this->host=$host;
        $this->port=$port;
        //连接redis
        if(class_exists('Redis')){
            $this->redis = new \Redis();
            if($this->redis->connect($this->host, $this->port)){
                $this->connect=true;
            }
        }else{
            exit('redis扩展不存在');
        }
    }

    /**
     * @method 盘口-添加订单
     *
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @param  $order_id    订单编号
     * @param  $price       价格
     * @return int
     */
    public function newOrderBook($market, $type, $side, $order_id, $price){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        return $this->redis->zAdd($key,$price,$order_id) ;
    }

    /**
     * @method 盘口-数据列表(price降序)
     *
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @return array
     */
    public function getOrderBooks($market, $type='limit', $side){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        $order_book_range=$this->redis->zRevRange($key,0,-1,true);
        return $order_book_range;
    }

    /**
     * @method 盘口-查找指定价格区间的盘
     * 
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @param  $fromPrice   最小价格（不包含）
     * @param  $toPrice     最大价格（包含）
     * @return int
     */
    public function getPriceArea($market,$type='limit',$side,$fromPrice,$toPrice){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        return $this->redis->zRangeByScore($key, $fromPrice, $toPrice, ['withscores' => TRUE]);
    }

    /**
     * @method 盘口-根据订单编号查找价格
     * 
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @param  $order_id
     * @return float
     */
    public function getOrderBook($market,$type='limit',$side,$order_id){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        return $this->redis->zScore($key,$order_id);
    }

    /**
     * @method 盘口-指定订单编号的价格加减运算
     * 
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @param  $order_id    订单编号
     * @param  $price       要增加（正数）或 减少（负数）的价格数量
     * @return float        修改后的价格
     */
    public function operaOrderBook($market,$type='limit',$side,$order_id,$price){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        return $this->redis->zIncrBy($key,$price,$order_id);
    }

    /**
     * @method  盘口-删除指定订单
     * 
     * @param  $market      交易市场（交易对）
     * @param  $side        [买单bid,卖单ask]
     * @param  $type        订单类型[limit,market]
     * @param  $order_id    订单编号
     */
    public function removeOrderBook($market,$type='limit', $side,$order_id){
        $key='orderbook:'.$market .':'.$type. ':' .$side;
        return  $this->redis->zDelete($key, $order_id);
    }


    /**
     * @method 订单-新增
     * 
     * @param $order_id 订单编号
     * @param $data     订单的内容键值对，包括[order_id,user_id,market,price,quantity,side,type]
     * @return bool    
     */
    public function newOrder($order_id,$data){
        if(!is_array($data)){
            return [];
        }
        $data['status']=0;//订单状态
        $data['match_id']='';//与本单撮合成功的订单id,逗号分隔
        $resOrder=$this->redis->hMSet($order_id,$data);
        $resOrderBook=$this->newOrderBook($data['market'], $data['type'], $data['side'], $order_id, $data['price']);//新建订单同时插入盘口
        return $resOrder;
    }

    /**
     * @method 订单-查找指定编号订单指定字段值
     * 
     * @param $order_id 订单编号
     * @param $field     要查询的订单字段名，可选[order_id,user_id,market,price,quantity,side,type]
     * @return array    字段值
     */
    public function getOrderField($order_id,$field){
        return $this->redis->hMGet($order_id,[$field])[$field];
    }

    /**
     * @method 订单-查找指定编号订单
     * 
     * @param $order_id 订单编号
     * @param $data     要查询的订单字段数组，可选[order_id,user_id,market,price,quantity,side,type]
     * @return array    新增的订单信息
     */
    public function getOrder($order_id){
        return $this->redis->hGetAll($order_id);
    }

    /**
     * @method 订单-指定字段值的加减运算
     * 
     * @param $order_id 订单编号
     * @param $field    字段名
     * @param $num      运算数量(正数+，负数-)
     * @return float    运算后的结果
     */
    public function operaOrder($order_id,$field,$num){
        return $this->redis->hIncrByFloat($order_id,$field,round($num,4));
    }

    /**
     * @method 订单-修改指定订单字段值
     * 
     * @param $order_id 订单编号
     * @param $field    字段名
     * @param $value    修改后的值
     * @return bool
     */
    public function updateOrder($order_id,$field,$value){
        return $this->redis->hMSet($order_id,[$field=>$value]);
    }


    /**
     * @method 订单-删除多个订单
     * 
     * @param $orderArray   要删除的订单id数组
     */
    public function removeOrders($orderArray){
        return $this->redis->delete($orderArray);
    }


    /**
     * @method 订单-删除指定编号的订单
     * 
     * @param $orderArray   要删除的订单id数组
     * @return bool
     */
    public function removeOrder($order_id){
        return $this->redis->del($order_id);
    }

    /**
     * @method 查寻Redis所有key
     * 
     * @return array    keys数组
     */
    public function getKeys(){
        return $this->redis->keys('*');
    }

    /**
     * @method 清空Redis所有数据
     * 
     * @return int  清空的数量
     */
    public function cleanAll(){
        return $this->redis->flushdb();
    }

    //订单列表
    public function listOrder(){
        $prefix='A10';//订单编号前缀
        $keys=$this->getKeys();
        $list=[];
        foreach ($keys as $key => $value) {
            if (substr($value, 0, strlen($prefix)) == $prefix) {
                $list[]=$this->getOrder($value);
            }
        }
        return $list;
    }
}