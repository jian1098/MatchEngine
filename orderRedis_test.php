<?php
require_once('orderRedis.class.php');

$orderRedis = new orderRedis('127.0.0.1',6379);
echo '<pre>';
// $info=$orderRedis->newOrderBook('BTC/USDT', 'limit' ,'ask','A100001','104');
// $info=$orderRedis->newOrderBook('BTC/USDT', 'limit' ,'ask','A100002','103');
// $info=$orderRedis->newOrderBook('BTC/USDT', 'limit' ,'ask','A100003','102');
// $info=$orderRedis->newOrderBook('BTC/USDT', 'limit' ,'ask','A100004','101');
// $info=$orderRedis->getOrderBooks('BTC/USDT', 'limit' ,'ask');
// $info=$orderRedis->getOrderBook('BTC/USDT', 'limit' ,'ask','a1004');
// echo '计算前：';
// print_r($info);
// $info=$orderRedis->operaOrderBook('BTC/USDT', 'limit' ,'ask','a1004','1.444');
// echo '计算后：';
// print_r($info);
// $info=$orderRedis->countOrderPrice('BTC/USDT', 'limit' ,'ask','1.03','1.13');
// $info=$orderRedis->removeOrderBook('BTC/USDT', 'limit' ,'ask','a1004');
// print_r($info);
// $info=$orderRedis->getOrderBooks('BTC/USDT', 'limit' ,'ask');
// print_r($info);

//清空数据
// $info=$orderRedis->getKeys();
// print_r($info);
// $info=$orderRedis->cleanAll();
// $info=$orderRedis->getKeys();
// print_r($info);

// $demoOrder=[
// 	'order_id' => 'O100001',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '2.15',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];
// $info=$orderRedis->newOrder($demoOrder['order_id'],$demoOrder);
// $demoOrder2=[
// 	'order_id' => 'O100002',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '2.15',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];
// $info=$orderRedis->newOrder($demoOrder2['order_id'],$demoOrder);
// $info=$orderRedis->getOrder('O100001');
// echo '计算前：';
// print_r($info);
// $info=$orderRedis->operaOrder('O100001','quantity','0.12');
// print_r($info);
// echo '计算后：';
// $info=$orderRedis->getOrder('O100001');
// print_r($info);

// $info=$orderRedis->getOrder('O100001');
// print_r($info);
// $info=$orderRedis->removeOrder('O100001');
// print_r($info);
// $info=$orderRedis->getOrder('O100001');
// print_r($info);

// $info=$orderRedis->getOrderIds();
// print_r($info);
// $info=$orderRedis->removeOrder('O100001');
// print_r($info);
// $info=$orderRedis->getOrderIds();
// print_r($info);


// $demoOrder1=[
// 	'order_id' => 'A100001',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '104',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];
// $demoOrder2=[
// 	'order_id' => 'A100002',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '103',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];
// $demoOrder3=[
// 	'order_id' => 'A100003',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '102',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];
// $demoOrder4=[
// 	'order_id' => 'A100004',
// 	'user_id' => '1',
// 	'market' => 'BTC/USDT',
// 	'price' => '101',
// 	'quantity' => '5',
// 	'side' => 'ask',
// 	'type' => 'limit'
// ];

// $info=$orderRedis->newOrder($demoOrder1['order_id'],$demoOrder1);
// $info=$orderRedis->newOrder($demoOrder2['order_id'],$demoOrder2);
// $info=$orderRedis->newOrder($demoOrder3['order_id'],$demoOrder3);
// $info=$orderRedis->newOrder($demoOrder4['order_id'],$demoOrder4);
// // var_dump($info);
// $info=$orderRedis->getOrderBooks('BTC/USDT','limit','ask');
// var_dump($info);


// $info=$orderRedis->getOrder('A100003');
// print_r($info);
// $info=$orderRedis->updateOrder('A100004','quantity','5');
// print_r($info);
// $info=$orderRedis->getOrder('A100003');
// print_r($info);
// 盘口列表
echo '<br>卖盘：<br>';
$info=$orderRedis->getOrderBooks('BTC/USDT', 'limit', 'ask');
print_r($info);
echo '<br>买盘：<br>';
$info=$orderRedis->getOrderBooks('BTC/USDT', 'limit', 'bid');
print_r($info);


// 订单列表
echo '<br>订单列表：<br>';
$info=$orderRedis->listOrder();
print_r($info);

echo '</pre>';