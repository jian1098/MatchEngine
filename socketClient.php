<?php
/**
 *  php socket client
 * */

error_reporting(0);                                                                                                                          
set_time_limit(0);

// 连接服务端
$host = '127.0.0.1';
$port = 2046;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket.<br>");
$connection = socket_connect($socket, $host, $port) or die("Could not connect server.<br>");

// 向服务端发送数据
// $data='{"type":"order","code":1,"data":{"type":"limit","side":"bid","quantity":"20","price":"104","market":"BTC/USDT","user_id":15,"order_id":"A100015"}}';//订单处理
// $data='{"type":"handicap","code":1,"data":{"type":"limit","market":"BTC/USDT"}}';//盘口数据
$data='{"type":"empty","code":1,"data":""}';//清空数据
// $data='{"type":"cancel","code":1,"data":{"type":"limit","market":"BTC/USDT","side":"ask","order_id":"A100004"}}';//取消订单

socket_write($socket, $data) or die("Write failed.<br>");

// 输出服务端返回数据
while($buff = socket_read($socket, 8192, PHP_NORMAL_READ)) {
    echo "Response was : ". $buff;
}
 
// 关闭连接
socket_close($socket);
