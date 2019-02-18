### 主机信息

socket IP：127.0.0.1

socket 端口：2046

redis IP：127.0.0.1

redis 端口：6379



### 撮合交易

发送数据json格式

```
{
    "type":"order",		//数据类型	
    "code":1,			//状态码
    "data":{			//订单的详细内容
        "type":"limit",	//订单类型 限价单limit,市价单market
        "side":"ask",	//买单bid,卖单ask
        "quantity":"5",	//订单数量
        "price":"101",	//价格
        "market":"BTC/USDT",	//交易市场
        "user_id":1,			//用户id
        "order_id":"A100002"	//订单编号
    }
}
```

返回json数据

```
{
    "code":1,	//状态码
    "type":"order",	//数据类型
    "data":{
        "updateArr":[	//需要更新的订单
            {
                "order_id":"A100003",	//订单编号
                "quantity":"3.88",		//需要扣除的数量
                "sellout":0				//是否已售完 1.已售完 0.未售完
            },
            {
                "order_id":"A100002",
                "quantity":"5",
                "sellout":1
            },
            {
                "order_id":"A100001",
                "quantity":"5",
                "sellout":1
            }
        ],
        "matchArr":[	//撮合成功的订单
            {
                "sell_id":"1",		//卖家id
                "buy_id":15,		//买家id
                "price":"102",		//成交价格
                "quantity":"3.88",	//成交数量
                "side":"bid",		//成交类型 bid:买入 ask：卖出
                "market":"BTC/USDT", //交易市场
                "sell_order":"A100003",	//卖单id
                "buy_order":"A100015"	//买单id
            },
            {
                "sell_id":"1",
                "buy_id":15,
                "price":"103",
                "quantity":"5",
                "side":"bid",
                "market":"BTC/USDT",
                "sell_order":"A100002",
                "buy_order":"A100015"
            },
            {
                "sell_id":"1",
                "buy_id":15,
                "price":"104",
                "quantity":"5",
                "side":"bid",
                "market":"BTC/USDT",
                "sell_order":"A100001",
                "buy_order":"A100015"
            }
        ],
        "newArr":[		//新插入盘口的订单
            {
                "order_id":"A100015",	//订单id
                "type":"limit",			//订单类型
                "side":"bid",			//订单方向 bid：买单 ask:卖单
                "market":"BTC/USDT",	//交易市场
                "quantity":6.12,		//数量
                "price":"104",			//价格
                "user_id":15			//用户id
            }
        ]
    }
}
```



### 盘口数据

发送数据格式

```
{
    "type":"handicap",		 //数据类型	
    "code":1,				//状态码
    "data":{				//订单的详细内容
        "type":"limit",			//订单类型 限价单limit,市价单market
        "market":"BTC/USDT"		//交易市场
    }
}
```

返回数据

```
{
    "code":1,			//状态码
    "type":"handicap",	//数据类型	
    "data":{
        "bid":[			//买盘
            {
                "price":104,	//数量
                "quantity":20,	//价格
                "total":2080	//总价
            },
            {
                "price":102,
                "quantity":6.12,
                "total":624.24
            },
            {
                "price":101,
                "quantity":2.4156,
                "total":243.9756
            }
        ],
        "ask":[		//卖盘
            {
                "price":990,
                "quantity":2.4156,
                "total":2391.444
            },
            {
                "price":980,
                "quantity":6.12,
                "total":5997.6
            }
        ]
    }
}
```



### 清空所有数据

发送数据格式

```
{
    "type":"empty",	//数据类型	
    "code":1,
    "data":""
}
```

返回数据

```
{
    "code":1,
    "type":"empty",
    "data":"empty all data success"
}
```



### 取消订单

发送数据格式

```
{
    "type":"cancel",	//数据类型	
    "code":1,			//状态码
    "data":{			//订单的详细内容
        "type":"limit",	//订单类型 限价单limit,市价单market
        "side":"ask",	//买单bid,卖单ask
        "market":"BTC/USDT",	//交易市场
        "order_id":"A100002"	//要删除的订单编号
    }
}
```

返回数据

```
{
    "code":1,
    "type":"cancel",
    "data":"success"
}
```

