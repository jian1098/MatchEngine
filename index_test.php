<?php
require_once('orderBook.class.php');
// require_once('socketServer.php');

$orderbook=new OrderBook('127.0.0.1','6379');
$demoOrder1=[
	'order_id' => 'A100001',
	'user_id' => '1',
	'market' => 'BTC/USDT',
	'price' => '104',
	'quantity' => '5',
	'side' => 'ask',
	'type' => 'limit'
];
$demoOrder2=[
	'order_id' => 'A100002',
	'user_id' => '1',
	'market' => 'BTC/USDT',
	'price' => '103',
	'quantity' => '5',
	'side' => 'ask',
	'type' => 'limit'
];
$demoOrder3=[
	'order_id' => 'A100003',
	'user_id' => '1',
	'market' => 'BTC/USDT',
	'price' => '102',
	'quantity' => '5',
	'side' => 'ask',
	'type' => 'limit'
];
$demoOrder4=[
	'order_id' => 'A100004',
	'user_id' => '1',
	'market' => 'BTC/USDT',
	'price' => '101',
	'quantity' => '5',
	'side' => 'ask',
	'type' => 'limit'
];


$demoOrder11=[
	'order_id' 	=> 'A100011',
	'user_id' 	=> '11',
	'market' 	=> 'BTC/USDT',
	'price'		=> '102',
	'quantity' 	=> '1.1111',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];
$demoOrder12=[
	'order_id' 	=> 'A100012',
	'user_id' 	=> '12',
	'market' 	=> 'BTC/USDT',
	'price'		=> '102',
	'quantity' 	=> '0.0089',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];
$demoOrder13=[
	'order_id' 	=> 'A100013',
	'user_id' 	=> '13',
	'market' 	=> 'BTC/USDT',
	'price'		=> '102',
	'quantity' 	=> '5',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];
$demoOrder14=[
	'order_id' 	=> 'A100014',
	'user_id' 	=> '14',
	'market' 	=> 'BTC/USDT',
	'price'		=> '101',
	'quantity' 	=> '2.4156',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];
$demoOrder15=[
	'order_id' 	=> 'A100015',
	'user_id' 	=> '15',
	'market' 	=> 'BTC/USDT',
	'price'		=> '104',
	'quantity' 	=> '20',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];

$demoOrder5=[
	'order_id' => 'A100005',
	'user_id' => '5',
	'market' => 'BTC/USDT',
	'price' => '99',
	'quantity' => '5',
	'side' => 'bid',
	'type' => 'limit'
];
$demoOrder6=[
	'order_id' => 'A100006',
	'user_id' => '6',
	'market' => 'BTC/USDT',
	'price' => '98',
	'quantity' => '5',
	'side' => 'bid',
	'type' => 'limit'
];
$demoOrder7=[
	'order_id' => 'A100007',
	'user_id' => '7',
	'market' => 'BTC/USDT',
	'price' => '97',
	'quantity' => '5',
	'side' => 'bid',
	'type' => 'limit'
];
$demoOrder8=[
	'order_id' => 'A100008',
	'user_id' => '1',
	'market' => 'BTC/USDT',
	'price' => '96',
	'quantity' => '5',
	'side' => 'bid',
	'type' => 'limit'
];

$demoOrder16=[
	'order_id' 	=> 'A100016',
	'user_id' 	=> '16',
	'market' 	=> 'BTC/USDT',
	'price'		=> '98',
	'quantity' 	=> '1.1111',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];
$demoOrder17=[
	'order_id' 	=> 'A100017',
	'user_id' 	=> '17',
	'market' 	=> 'BTC/USDT',
	'price'		=> '98',
	'quantity' 	=> '0.0089',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];
$demoOrder18=[
	'order_id' 	=> 'A100018',
	'user_id' 	=> '18',
	'market' 	=> 'BTC/USDT',
	'price'		=> '98',
	'quantity' 	=> '5',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];
$demoOrder19=[
	'order_id' 	=> 'A100019',
	'user_id' 	=> '19',
	'market' 	=> 'BTC/USDT',
	'price'		=> '99',
	'quantity' 	=> '2.4156',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];
$demoOrder20=[
	'order_id' 	=> 'A100020',
	'user_id' 	=> '20',
	'market' 	=> 'BTC/USDT',
	'price'		=> '96',
	'quantity' 	=> '20',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];


$demoOrder100=[
	'order_id' 	=> 'A100100',
	'user_id' 	=> '20',
	'market' 	=> 'BTC/USDT',
	'price'		=> '7',
	'quantity' 	=> '1.223',
	'side' 		=> 'bid',
	'type' 		=> 'limit'
];
$demoOrder101=[
	'order_id' 	=> 'A100101',
	'user_id' 	=> '20',
	'market' 	=> 'BTC/USDT',
	'price'		=> '7',
	'quantity' 	=> '1.22',
	'side' 		=> 'ask',
	'type' 		=> 'limit'
];
echo '<pre>';
//卖盘测试
// $info=$orderbook->processOrder($demoOrder1);
// $info=$orderbook->processOrder($demoOrder2);
// $info=$orderbook->processOrder($demoOrder3);
// $info=$orderbook->processOrder($demoOrder4);

//买盘测试
// $info=$orderbook->processOrder($demoOrder5);
// $info=$orderbook->processOrder($demoOrder6);
// $info=$orderbook->processOrder($demoOrder7);
// $info=$orderbook->processOrder($demoOrder8);

//买单测试
// $info=$orderbook->processOrder($demoOrder11);
// $info=$orderbook->processOrder($demoOrder12);
// $info=$orderbook->processOrder($demoOrder13);
// $info=$orderbook->processOrder($demoOrder14);
// $info=$orderbook->processOrder($demoOrder15);


//卖单测试
// $info=$orderbook->processOrder($demoOrder16);
// $info=$orderbook->processOrder($demoOrder17);
// $info=$orderbook->processOrder($demoOrder18);
// $info=$orderbook->processOrder($demoOrder19);
// $info=$orderbook->processOrder($demoOrder20);
// $info=$orderbook->processOrder($demoOrder100);
$info=$orderbook->processOrder($demoOrder101);
print_r($info);
echo '</pre>';