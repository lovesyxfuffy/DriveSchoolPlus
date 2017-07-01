驾校后台接口文档
===================
***
备注:返回内容有如下统一的数据格式:
``` javascript
{
    code:200
    msg:{}
}
```
code有如下几种情况
```
200:成功
500或其他:错误
```
数据一律从msg属性中获取
***
<br/>
<br/>
管理员部分

-------
#####  1.管理员账号登录
url: /api/manage/account/i/auth

method: post

request: 
```json
{
  "account":"",
  "password":""
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```


#####  2.创建管理员
url: /api/manage/account/i/info

method: post

request: 
```json
{
  "account":17865169626,
  "password":123123,
  "name":"zhaoshuai"
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```

#####  3.获取管理员自己的信息
url: /api/manage/account/i/info

method: get

request: 
```json
{需要在登录成功之后}
```
response:
```json
{
    "code": "200",
    "msg": {
        "id": 1,
        "name": "zhaoshuai",
        "account": "qweqwe",
        "role_id": null
    }
}
```

<br/>
<br/>
订单部分

#####  1.提交订单
url: /api/manage/order

method: post

request: 
```json
{
        "stuName":"required|filled|max:30",
        "stuIdCard":"required|filled|max:18",
        "stuTelephone":"required|filled",
        "stuPermit":"required|filled",
        "stuQq":"required|filled|numeric",
        "fieldId":"required|filled|numeric",
        "classId":"required|filled|numeric",
        "type":"required|filled",
        "stuCost":"required|filled|numeric",
        "agentId":"required",
        "reduction":"required",
        "inviter_id":"required|filled",
  
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```
#####  2.管理员获取订单
url: /api/manage/order

method: get

request: 
```json
{
        "page":"1",
        "rows":"10",
     
        //下面是可选的 
        "stuName":"赵红帅",
        "payedWay":"1",
        "fieldId":"1",
        "stuTelephone":"17865169626",
        "stuIdCard":"371502119602295316"
  
```

response:
```json
{
    "code": "200",
    "msg": {
        "total": 6,
        "per_page": "3",
        "current_page": 1,
        "last_page": 2,
        "next_page_url": "http://localhost/api/manage/order?page=2",
        "prev_page_url": null,
        "from": 1,
        "to": 3,
        "data": [
            {
                "orderId": 7,
                "stuName": "赵红帅",
                "stuIdCard": "371502119602295316",
                "stuTelephone": "17865169626",
                "stuPermit": "有",
                "stuQq": "982599220",
                "fieldName": null,
                "className": null,
                "orderType": 1,
                "stuCost": 2000,
                "agentName": null,
                "orderReduction": 0,
                "orderCreateTime": "2017-07-01 11:08:25",
                "orderStatus": 1,
                "allAmount": null,
                "payedAmount": null,
                "payedWay": null,
                "payedCreateTime": null,
                "payedStatus": null
            },
            {
                "orderId": 6,
                "stuName": "张三",
                "stuIdCard": "371502119602295316",
                "stuTelephone": "17865169999",
                "stuPermit": "无",
                "stuQq": "9829220",
                "fieldName": null,
                "className": null,
                "orderType": 1,
                "stuCost": 3000,
                "agentName": null,
                "orderReduction": 0,
                "orderCreateTime": "2017-07-01 09:36:49",
                "orderStatus": 1,
                "allAmount": null,
                "payedAmount": null,
                "payedWay": null,
                "payedCreateTime": null,
                "payedStatus": null
            },
            {
                "orderId": 5,
                "stuName": "张三",
                "stuIdCard": "371502119602295316",
                "stuTelephone": "17865169999",
                "stuPermit": "无",
                "stuQq": "9829220",
                "fieldName": null,
                "className": null,
                "orderType": 1,
                "stuCost": 3000,
                "agentName": null,
                "orderReduction": 0,
                "orderCreateTime": "2017-07-01 09:31:13",
                "orderStatus": 1,
                "allAmount": 3000,
                "payedAmount": 150,
                "payedWay": "2",
                "payedCreateTime": "2017-07-01 19:04:05",
                "payedStatus": 1
            }
        ]
    }
}
```
