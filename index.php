<?php
/**
 *  PHP Socket Server
 * */
require_once('orderBook.class.php');
//撮合引擎初始化,参数是redis的ip和端口
$orderbook=new OrderBook('127.0.0.1','6379'); 

// //确保客户端连接时不会超时
// error_reporting(0);                                                                                                                          
// set_time_limit(0);
// ini_set('serialize_precision',14); //防止php7.1以上浮点数json_encode精度会出问题

// //设置socket服务端地址与端口
// $address = '127.0.0.1'; //服务端ip
// $port = 2046;           //服务端端口

// //创建socket：AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6 ， SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM
// $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed : ".socket_strerror(socket_last_error()). "<br>");
// //阻塞模式
// socket_set_block($sock) or die("socket_set_block() failed : ".socket_strerror(socket_last_error()) ."<br>");
//  //绑定到socket端口
// $result = socket_bind($sock, $address, $port) or die("socket_bind() failed : ". socket_strerror(socket_last_error()) . "<br>");
// //开始监听
// $result = socket_listen($sock, 4) or die("socket_listen() failed : ". socket_strerror(socket_last_error()) . "<br>");

// //转json数据
// function toJson($code,$type,$data){
//     $arr=[
//         'code'  => $code,
//         'type'  => $type,
//         'data'  => $data
//     ];
//     return json_encode($arr);
// }

// do {
//     //它接收连接请求并调用一个子链接socket来处理客户端和服务器间的信息
//     $msgsock = socket_accept($sock) or die("sock_accept() failed : ". socket_strerror(socket_last_error()) . "<br>");
 
//     //读取客户端数据
//     $json = socket_read($msgsock, 8192);
//     if (!$json) {
//         $resp=toJson(0,'order','please input data!');
//         socket_write($msgsock, $resp, strlen($resp)) or die("socket_write() failed : ". socket_strerror(socket_last_error()). "<br>");
//     }
//     $order=json_decode($json,true); //解析接收数据

//     switch ($order['type']){
//     case 'order':   //订单处理
//         $resp=toJson(1,'order',$orderbook->processOrder($order['data']));
//         break;  
//     case 'handicap'://盘口数据
//         $resp=toJson(1,'handicap',$orderbook->getHandicap($order['data']['type'],$order['data']['market']));
//         break;    
//     case 'empty':   //清空数据
//         $resp=toJson(1,'empty',$orderbook->emptys());
//         break;
//     case 'cancel':  //取消订单
//         $resp=toJson(1,'cancel',$orderbook->removeOrder($order['data']['market'],$order['data']['type'],$order['data']['side'],$order['data']['order_id']));
//         break;
//     default:
//         $resp=toJson(0,'order','error data!');
//     }
 
//     //数据传输，向客户端写入返回结果
//     $msg = $resp."\n";//没有\n会返回失败
//     socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed : ". socket_strerror(socket_last_error()). "<br>");
//     //输出返回到客户端时，父/子socket都应通过socket_close来终止
//     socket_close($msgsock);
// }while(1);
 
// socket_close($sock);


//-------------------------------------------------------------------------------------------------------
$order_id=rand(1000000,9999999);
$price=rand(50,100);
$quantity=rand(10,100)/10;
$option=['ask','bid'];
$side=$option[$price%2];
// echo $side.'<br>';
$demoOrder1=[
    'order_id'  => $order_id,
    'user_id'   => $_POST['user_id'],
    'market'    => $_POST['market'],
    'price'     => $price,
    'quantity'  => $quantity,
    'side'      => $side,
    'type'      => 'limit'
];

// $demoOrder1=[
//     'order_id'  => '6127749',
//     'user_id'   => 1,
//     'market'    => 'BTC/USDT',
//     'price'     => 55,
//     'quantity'  => 7.6,
//     'side'      => 'bid',
//     'type'      => 'limit'
// ];

// $demoOrder2=[
//     'order_id'  => '7280107',
//     'user_id'   => 1,
//     'market'    => 'BTC/USDT',
//     'price'     => 61,
//     'quantity'  => 9.4,
//     'side'      => 'bid',
//     'type'      => 'limit'
// ];

// $demoOrder3=[
//     'order_id'  => '8519040',
//     'user_id'   => 1,
//     'market'    => 'BTC/USDT',
//     'price'     => 73,
//     'quantity'  => 3.1,
//     'side'      => 'bid',
//     'type'      => 'limit'
// ];


// $demoOrder4=[
//     'order_id'  => '3802493',
//     'user_id'   => 1,
//     'market'    => 'BTC/USDT',
//     'price'     => 62,
//     'quantity'  => 6.6,
//     'side'      => 'ask',
//     'type'      => 'limit'
// ];

// $demoOrder5=[
//     'order_id'  => '1389746',
//     'user_id'   => 1,
//     'market'    => 'BTC/USDT',
//     'price'     => 54,
//     'quantity'  => 3.8,
//     'side'      => 'ask',
//     'type'      => 'limit'
// ];

// $res=$orderbook->processOrder($demoOrder1);
// $res=$orderbook->processOrder($demoOrder2);
// $res=$orderbook->processOrder($demoOrder3);
$res=$orderbook->processOrder($demoOrder1);
var_dump($res);