<?php
require_once('orderRedis.class.php');

class OrderBook {
	private $orderRedis;
	private $precision=4;	//数量的小数位数

	public function __construct($host,$port){
        $this->orderRedis=new OrderRedis($host,$port);
    }

	/**
    * @method 订单处理
    * 
    * @param $order 订单数据[order_id,user_id,market,price,quantity,side,type]
    * @return bool    
    */
	public function processOrder($order){
		if(!$this->limitDataCheck($order)){//数据校验
			return 'Invalid order';
		}
		if($order['type']=='limit'){//限价单
			$info=$this->processLimitOrder($order);
		}else{//市价单
			$info=$this->processMarketOrder($order);
		}
		return $info;
	}

	/**
    * @method 限价订单处理
    * 
    * @param $order 订单数据[order_id,user_id,market,price,quantity,side,type,status,match_id]
    * @return bool    
    */
	private function processLimitOrder($order){
		$updateArr=[];	//数量发送变化的订单
		$matchArr=[];	//撮合成功的订单
		$newArr=[];		//新增的订单

		echo 'process order：'.$order['order_id'].'<br>';
		if($order['side']=='ask'){	//处理卖单
			//查找买盘是价格大于本价格的订单,价格降序排列
			$orderArea=$this->orderRedis->getPriceArea($order['market'],$order['type'],'bid',$order['price'],9999999);//array(2) { ["A100004"]=> float(101) ["A100003"]=> float(102) }

			if(count($orderArea)>0){	//有能撮合的订单->撮合
				$orderArea=array_reverse($orderArea,true);//数组反转
				foreach ($orderArea as $key => $value) {
					$orderInfo=$this->orderRedis->getOrder($key); //根据orderid查找订单详情
					if(round($order['quantity'],$this->precision)<=round($orderInfo['quantity'],$this->precision)){	//本单可售数量充足 
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'quantity',round($orderInfo['quantity']-$order['quantity'],$this->precision));//修改本单可售数量
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'match_id',$orderInfo['match_id'].$order['order_id'].',');//修改本单撮合的id
						$sellout=0;
						if ($operaRes&&round($order['quantity'],$this->precision)==round($orderInfo['quantity'],$this->precision)) {//若可售数量为0，从买盘撤单
							$removeRes=$this->orderRedis->removeOrderBook($orderInfo['market'],'limit','bid',$orderInfo['order_id']);
							$sellout=1;
						}

						//数量发送变化的订单
						$updateOrder=[
							'order_id'	=>	$key,
							'quantity'	=>	round($order['quantity'],$this->precision),
							'sellout'	=>	$sellout
						];
						$updateArr[]=$updateOrder;
						//撮合成功的订单
						$matchOrder=[
							'sell_id'	=>	$order['user_id'],	//卖家id
							'buy_id'	=>	$orderInfo['user_id'],//买家id
							'price'		=>	$orderInfo['price'],
							'quantity'	=>	round($order['quantity'],$this->precision),
							'side'		=>	'ask',	//卖出
							'market'	=>	$order['market'],
							'sell_order'=>	$order['order_id'],//卖单id
							'buy_order'	=>	$orderInfo['order_id'],//买单id
						];
						$matchArr[]=$matchOrder;
						$order['quantity']=0;//数量清0
						break;
					}else{	//本单可售数量不足
						$order['quantity']=round($order['quantity']-$orderInfo['quantity'],$this->precision);//修改传入订单数量
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'quantity',0);//修改本单可售数量为0
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'match_id',$orderInfo['match_id'].$order['order_id'].',');//修改本单撮合的id
						$removeRes=$this->orderRedis->removeOrderBook($orderInfo['market'],'limit','bid',$orderInfo['order_id']);//将从买盘撤单
						//数量发送变化的订单
						$updateOrder=[
							'order_id'	=>	$key,
							'quantity'	=>	round($orderInfo['quantity'],$this->precision),
							'sellout'	=>	1
						];
						$updateArr[]=$updateOrder;
						//撮合成功的订单
						$matchOrder=[
							'sell_id'	=>	$order['user_id'],
							'buy_id'	=>	$orderInfo['user_id'],
							'price'		=>	$orderInfo['price'],
							'quantity'	=>	round($orderInfo['quantity'],$this->precision),
							'side'		=>	'ask',	//卖出
							'market'	=>	$order['market'],
							'sell_order'=>	$order['order_id'],	//卖单id
							'buy_order'	=>	$orderInfo['order_id'],	//买单id
						];
						$matchArr[]=$matchOrder;
						continue;	//继续撮合直到传入订单完成或没有撮合的买单
					}
				}

				if ($order['quantity']>0) { //如果传入订单还有未撮合的数量->剩下的数量直接生成卖盘单
					$newOrder=$this->orderRedis->newOrder($order['order_id'],$order);
					//新生成订单
					$newOrder=[
						'order_id'	=>	$order['order_id'],
						'type'		=>	$order['type'],
						'side'		=>	$order['side'],
						'market'	=>	$order['market'],
						'quantity'	=>	round($order['quantity'],$this->precision),
						'price'		=>	$order['price'],
						'user_id'	=>	$order['user_id']
					];
					$newArr[]=$newOrder;
				}
			}else{	//没有可以撮合的订单->直接生成卖盘单
				$newOrder=$this->orderRedis->newOrder($order['order_id'],$order);
				//新生成订单
				$newOrder=[
					'order_id'	=>	$order['order_id'],
					'type'		=>	$order['type'],
					'side'		=>	$order['side'],
					'market'	=>	$order['market'],
					'quantity'	=>	round($order['quantity'],$this->precision),
					'price'		=>	$order['price'],
					'user_id'	=>	$order['user_id']
				];
				$newArr[]=$newOrder;
			}
		}else{	//处理买单
			//查找卖盘是价格小于等于本价格的订单,价格升序排列
			$orderArea=$this->orderRedis->getPriceArea($order['market'],$order['type'],'ask',0,$order['price']);//array(2) { ["A100004"]=> float(101) ["A100003"]=> float(102) }
			if(count($orderArea)>0){	//有能撮合的订单->撮合
				foreach ($orderArea as $key => $value) {
					$orderInfo=$this->orderRedis->getOrder($key); //根据orderid查找订单详情
					if(round($order['quantity'],$this->precision)<=round($orderInfo['quantity'],$this->precision)){	//本单可售数量充足 
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'quantity',round($orderInfo['quantity']-$order['quantity'],$this->precision));//修改本单可售数量
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'match_id',$orderInfo['match_id'].$order['order_id'].',');//修改本单撮合的id
						$sellout=0;
						if ($operaRes&&round($order['quantity'],$this->precision)==round($orderInfo['quantity'],$this->precision)) {//若可售数量为0，从卖盘撤单
							$removeRes=$this->orderRedis->removeOrderBook($orderInfo['market'],'limit','ask',$orderInfo['order_id']);
							$sellout=1;
						}
						//数量发送变化的订单
						$updateOrder=[
							'order_id'	=>	$key,
							'quantity'	=>	round($order['quantity'],$this->precision),
							'sellout'	=>	$sellout
						];
						$updateArr[]=$updateOrder;
						//撮合成功的订单
						$matchOrder=[
							'sell_id'	=>	$orderInfo['user_id'],
							'buy_id'	=>	$order['user_id'],
							'price'		=>	$orderInfo['price'],
							'quantity'	=>	round($order['quantity'],$this->precision),
							'side'		=>	'bid',	//买入
							'market'	=>	$order['market'],
							'sell_order'=>	$orderInfo['order_id'],//卖单id
							'buy_order'	=>	$order['order_id'],//买单id
						];
						$matchArr[]=$matchOrder;
						$order['quantity']=0;//数量清0
						break;
					}else{	//本单可售数量不足
						$order['quantity']=round($order['quantity']-$orderInfo['quantity'],$this->precision);//修改传入订单数量
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'quantity',0);//修改本单可售数量为0
						$operaRes=$this->orderRedis->updateOrder($orderInfo['order_id'],'match_id',$orderInfo['match_id'].$order['order_id'].',');//修改本单撮合的id
						$removeRes=$this->orderRedis->removeOrderBook($orderInfo['market'],'limit','ask',$orderInfo['order_id']);//将从卖盘撤单

						//数量发送变化的订单
						$updateOrder=[
							'order_id'	=>	$key,
							'quantity'	=>	round($orderInfo['quantity'],$this->precision),
							'sellout'	=>	1
						];
						$updateArr[]=$updateOrder;
						//撮合成功的订单
						$matchOrder=[
							'sell_id'	=>	$orderInfo['user_id'],
							'buy_id'	=>	$order['user_id'],
							'price'		=>	$orderInfo['price'],
							'quantity'	=>	round($orderInfo['quantity'],$this->precision),
							'side'		=>	'bid',	//买入
							'market'	=>	$order['market'],
							'sell_order'=>	$orderInfo['order_id'],//卖单id
							'buy_order'	=>	$order['order_id'],//买单id
						];
						$matchArr[]=$matchOrder;
						continue;	//继续撮合直到传入订单完成或没有撮合的卖单
					}
				}

				if ($order['quantity']!=0) { //如果传入订单还有未撮合的数量->剩下的数量直接生成买盘单
					$newOrder=$this->orderRedis->newOrder($order['order_id'],$order);
					//新生成订单
					$newOrder=[
						'order_id'	=>	$order['order_id'],
						'type'		=>	$order['type'],
						'side'		=>	$order['side'],
						'market'	=>	$order['market'],
						'quantity'	=>	round($order['quantity'],$this->precision),
						'price'		=>	$order['price'],
						'user_id'	=>	$order['user_id']
					];
					$newArr[]=$newOrder;
				}
			}else{	//没有可以撮合的订单->直接生成买盘单
				echo 'no match<br>';
				$newOrder=$this->orderRedis->newOrder($order['order_id'],$order);
				//新生成订单
				$newOrder=[
					'order_id'	=>	$order['order_id'],
					'type'		=>	$order['type'],
					'side'		=>	$order['side'],
					'market'	=>	$order['market'],
					'quantity'	=>	round($order['quantity'],$this->precision),
					'price'		=>	$order['price'],
					'user_id'	=>	$order['user_id']
				];
				$newArr[]=$newOrder;
			}
		}
		$data=['updateArr'=>$updateArr,'matchArr'=>$matchArr,'newArr'=>$newArr];
		return $data;
	}

	/**
    * @method 市价订单处理
    * 
    * @param $order 订单数据[order_id,user_id,market,price,quantity,side,type]
    * @return bool    
    */
	private function processMarketOrder($order){
		$info='Comming Soon!';
		return $info;
	}

	//限价订单数据校验
	private function limitDataCheck($order){
		if(!isset($order['user_id'])||!isset($order['quantity'])||!isset($order['price'])||!isset($order['order_id'])||!isset($order['side'])||!isset($order['type'])){
			return 0;
		}
		if($order['price']<=0||$order['quantity']<=0||!in_array($order['type'], ['limit','market'])||!in_array($order['side'], ['ask','bid'])){
			return 0;
		}
		return 1;
	}

	/**
    * @method 盘口数据
    * 
    * @param $type 		订单类型
    * @param $market 	交易市场
    * @return bool    
    */
	public function getHandicap($type,$market){
		$handicap=['bid'=>[],'ask'=>[]];
		$bidArr=[];
		$askArr=[];
		$bidlist=$this->orderRedis->getOrderBooks($market, $type, 'bid');
		foreach ($bidlist as $key => $value) {
			if(!isset($bidArr["$value"])){
				$bidArr["$value"]=0;
			}
			$order=$this->orderRedis->getOrder($key);
			$bidArr["$value"]+=$order['quantity'];
		}
		foreach ($bidArr as $key => $value) {
			$handicap['bid'][]=['price'=>$key,'quantity'=>$value,'total'=>$key*$value];
		}

		$asklist=$this->orderRedis->getOrderBooks($market, $type, 'ask');
		foreach ($asklist as $key => $value) {
			if(!isset($askArr["$value"])){
				$askArr["$value"]=0;
			}
			$order=$this->orderRedis->getOrder($key);
			$askArr["$value"]+=$order['quantity'];
		}
		foreach ($askArr as $key => $value) {
			$handicap['ask'][]=['price'=>$key,'quantity'=>$value,'total'=>$key*$value];
		}
		return $handicap;
	}

	//清空Reids所有数据
	public function emptys(){
		$info=$this->orderRedis->cleanAll();
		if ($info) {
			return 'empty all data success';
		}else{
			return 'empty fail';
		}
	}

	/**
    * @method 取消订单
    * 
    * @param  $market      交易市场（交易对）
    * @param  $side        [买单bid,卖单ask]
    * @param  $type        订单类型[limit,market]
    * @param  $order_id    订单编号
    * @return bool    
    */
	public function removeOrder($market,$type='limit', $side,$order_id){
		$info=$this->orderRedis->removeOrderBook($market,$type, $side,$order_id);//从盘口删除
		$info=$this->orderRedis->removeOrder($order_id);//从订单列表删除
		if ($info) {
			return 'success';
		}else{
			return 'fail';
		}
	}
}