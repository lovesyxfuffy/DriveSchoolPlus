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
        "stuName":"|max:30",
        "stuIdCard":"|max:18",
        "stuTelephone":"",
        "stuPermit":"",
        "stuQq":"|numeric",
        "fieldId":"|numeric",
        "classId":"|numeric",
        "type":"",
        "stuCost":"|numeric",
        "agentId":"required",
        "reduction":"required",
        "inviter_id":"",
  
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

#####  3.获取订单统计情况 按日期（在页面上面）
url: /api/manage/

method: post

request: 
```json
{
  "createTime":"2017-08-20"
}
```

response:
```json
{
    "code": "200",
    "msg": {
        "OrderAllNumber": 8,
        "OrderCanceledNumber": 3,
        "OrderToConfirmedNumber": 2,
        "OrderConfirmedNumber": 2,
        "OrderAllMoney": "11000",
        "OrderOnlineMoney": "2000",
        "OrderOfflineMoney": "3000"
    }
}
```
#####  4.更改订单的状态 （订单一共有四个状态  默认1 已提交状态  2已经取消  3已经支付完成  4已经确认）
url: /api/manage/order

method: post

request: 
```json
{
  "orderStatus":"3",  
  "orderId":"1,2,4,5"  （传递一个或多个订单的ID,如果是多个的话，用,隔开）
}
```

response:
```json
{
    "code": "200",
    "msg": {
        "OrderAllNumber": 8,
        "OrderCanceledNumber": 3,
        "OrderToConfirmedNumber": 2,
        "OrderConfirmedNumber": 2,
        "OrderAllMoney": "11000",
        "OrderOnlineMoney": "2000",
        "OrderOfflineMoney": "3000"
    }
}
```


<br/>
<br/>
学员管理部分

#####  1. 增加学员
url: /api/manage/student/one

method: post

request: 
```json
{
        "headImg"  :"aqweqw.jpg",
        "name"     :"zhaoshuai",
        "age"      :"1996",
        "sex"      :"男",
        "idCard"   :"371502199602295336",
        "telephone":"17865169626",
        "permit"   :"无",
        "qq"       :"982599220"
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```

#####  2. 批量增加学员
url: /api/manage/student/batch

method: post

（excel文件的字段还需和甲方商定好）
request: 
```json
{
     "schedule":"2",
     "stuExcel" : 一个cxcel 文件
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```


#####  3. 获取学员信息
url: /api/manage/student/info

method: get

//参数在这里 page 和 rows 是必须的 
其他的可以再添加  比如说 根据名字查询或者电话查询 
request: 
```json
{
     "page":"1",
     "rows" : "10",
  
     "name()" : "赵帅",
     "teltephone()" : "17865169626",
     "idCard" : "1313131321231",
}
```

response:
```json
{
    "code": "200",
    "msg": {
        "current_page": 1,
        "data": [
            {
                "id": 100000000,
                "headImg": "UxOJPovtuRPVxQD8M5YmtReOot5aWZS38ZpXV2TU.jpeg",
                "name": "赵二狗",
                "sex": "男",
                "age": 21,
                "idCard": "371502119602295316",
                "telephone": "17865169626",
                "qq": 98253222,
                "permit": "wu",
                "schedule": 4,
                "fieldId": 1,
                "createTime": "2017-07-12 11:41:02",
                "status": 1
            },
            {
                "id": 100000005,
                "headImg": null,
                "name": "赵红帅",
                "sex": "男",
                "age": 21,
                "idCard": "371502199602294976",
                "telephone": "17865169626",
                "qq": 982599220,
                "permit": "自带",
                "schedule": 19,
                "fieldId": null,
                "createTime": "2017-07-12 11:41:02",
                "status": 1
            },
            {
                "id": 100000006,
                "headImg": null,
                "name": "赵红",
                "sex": "男",
                "age": 21,
                "idCard": "371502199602294976",
                "telephone": "17865169626",
                "qq": 982599220,
                "permit": "自带",
                "schedule": 19,
                "fieldId": null,
                "createTime": "2017-07-12 11:41:02",
                "status": 1
            }
        ],
        "from": 1,
        "last_page": 2,
        "next_page_url": "http://localhost/api/manage/student/info?page=2",
        "path": "http://localhost/api/manage/student/info",
        "per_page": "3",
        "prev_page_url": null,
        "to": 3,
        "total": 5
    }
}
```

#####  4. 修改学员信息
url: /api/manage/student/info

method: put

（除了 id 是必须的 其他都可以选  之后的班型  场地 教练等）
（schedule 是需要批量选择的）
request: 
```json
{
        "id"       :"2",
        
        "headImg"  :"aqweqw.jpg",
        "name"     :"zhaoshuai",
        "age"      :"1996",
        "sex"      :"男",
        "idCard"   :"371502199602295336",
        "telephone":"17865169626",
        "permit"   :"无",
        "qq"       :"982599220"
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```

#####  5. 上传头像
url: /api/manage/student/img

method: post

request: 
```json
{   
        "headImg"  : 图片,
}
```

response:
```json
{
    "code": "200",
    "msg": "1iMlGYZEjjMn1K9hq6dhS5KRRKsdQsVaQ9O74C4b.jpeg"
}
```


#####  6. 获取头像
url: /api/manage/student/img

method: get

request: 
```json
{   
        "headImg"  : "1iMlGYZEjjMn1K9hq6dhS5KRRKsdQsVaQ9O74C4b.jpeg"
}
```

response:
```json
返回一个图片资源
```


#####  7. 修改学员进度
url: /api/manage/student/schedule

method: put

request: 
```json
{   
     "id"  : "100000005,100000006,100000007",
     "schedule" : "2"
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```



#####  8. 导出信息
url: /api/manage/student/export

method: get

传入参数进行导出

request: 
```json
{   
     "startTime"  :"2017-01-01",
     "endTime"    :"2017-02-01",
     "id"         :"100000005,100000006,100000007"
}
```

response:
```json
excel文件
```


<br/>
<br/>
教练管理部分

#####  1. 增加教练
url: /api/manage/trainer/one

method: post

request: 
```json
{
        "headImg"  :"aqweqw.jpg",
        "name"     :"zhaoshuai",
        "age"      :"1996",
        "sex"      :"男",
        "idCard"   :"371502199602295336",
        "telephone":"17865169626",
        "teachYear":"1999",
        "weixin"   :"982599220"
        "carNumber":"鲁P88888" 
}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```



#####  2. 获取教练信息
url: /api/manage/trainer/info

method: get

//参数在这里 page 和 rows 是必须的 
其他的可以再添加  比如说 根据名字查询或者电话查询 
request: 
```json
{
     "page":"1",
     "rows" : "10",
  
     "name()" : "赵帅",
     "teltephone()" : "17865169626",
     "idCard" : "1313131321231",
}
```

response:
```json
{
    "code": "200",
    "msg": {
        "current_page": 1,
        "data": [
            {
                "id": 300000000,
                "headImg": "1321",
                "name": "王二",
                "sex": "女",
                "age": 51,
                "teachYear": 18,
                "carNumber": "京PQ2112",
                "idCard": "371502196602235663",
                "telephone": "123123123",
                "weixin": "123",
                "classId": 0,
                "schoolId": 0,
                "appointmentStatus": 0,
                "status": 1
            },
            {
                "id": 1,
                "headImg": "gRPdVoQnQMc5r1dlLdzkkthkb5hHbGvGPCnslESk.jpeg",
                "name": "李二牛",
                "sex": "女",
                "age": 51,
                "teachYear": 18,
                "carNumber": "京PQ2112",
                "idCard": "371502196602235663",
                "telephone": "123123123",
                "weixin": "123",
                "classId": 0,
                "schoolId": 0,
                "appointmentStatus": 0,
                "status": 1
            }
        ],
        "from": 1,
        "last_page": 1,
        "next_page_url": null,
        "path": "http://localhost/api/manage/trainer/info",
        "per_page": "23",
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}

```

#####  3. 修改教练信息
url: /api/manage/trainer/info

method: put

（除了 id 是必须的 其他都可以选  之后的班型  场地 教练等）
（schedule 是需要批量选择的）
request: 
```json
{
        "id"       :"2",
        
         "headImg"  :"aqweqw.jpg",
         "name"     :"zhaoshuai",
         "age"      :"1996",
         "sex"      :"男",
         "idCard"   :"371502199602295336",
         "telephone":"17865169626",
         "teachYear":"1999",
         "weixin"   :"982599220"
         "carNumber":"鲁P88888" 

}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```

#####  4. 上传头像
url: /api/manage/trainer/img

method: post

request: 
```json
{   
        "headImg"  : 图片,
}
```

response:
```json
{
    "code": "200",
    "msg": "1iMlGYZEjjMn1K9hq6dhS5KRRKsdQsVaQ9O74C4b.jpeg"
}
```


#####  5. 获取头像
url: /api/manage/trainer/img

method: get

request: 
```json
{   
        "headImg"  : "1iMlGYZEjjMn1K9hq6dhS5KRRKsdQsVaQ9O74C4b.jpeg"
}
```

response:
```json
返回一个图片资源
```


<br/>
<br/>
代理管理部分

#####  1.创建一个代理

url: /api/manage/agent/one

method: post

request: 
```json
{
        "name"     :"zhaoshuai",
        "age"      :"1996",
        "sex"      :"男",
        "idCard"   :"371502199602295336",
        "telephone":"17865169626",
        "bankCardNumber" :"6216611100002899022",
        "bankInfo"     :"中国工商银行"

}
```

response:
```json
{
    "code": "200",
    "msg": "OK"
}
```



#####  2. 获取代理信息
url: /api/manage/agent/info

method: get

//参数在这里 page 和 rows 是必须的 
其他的可以再添加  比如说 根据名字查询或者电话查询 
request: 
```json
{
     "page":"1",
     "rows" : "10",
  
     "name()" : "赵帅",
     "teltephone()" : "17865169626",
     "idCard" : "1313131321231",
}
```

response:
```json
{
    "code": "200",
    "msg": {
        "current_page": 1,
        "data": [
            {
                "id": 300000000,
                "headImg": "1321",
                "name": "王二",
                "sex": "女",
                "age": 51,
                "teachYear": 18,
                "carNumber": "京PQ2112",
                "idCard": "371502196602235663",
                "telephone": "123123123",
                "weixin": "123",
                "classId": 0,
                "schoolId": 0,
                "appointmentStatus": 0,
                "status": 1
            },
            {
                "id": 1,
                "headImg": "gRPdVoQnQMc5r1dlLdzkkthkb5hHbGvGPCnslESk.jpeg",
                "name": "李二牛",
                "sex": "女",
                "age": 51,
                "teachYear": 18,
                "carNumber": "京PQ2112",
                "idCard": "371502196602235663",
                "telephone": "123123123",
                "weixin": "123",
                "classId": 0,
                "schoolId": 0,
                "appointmentStatus": 0,
                "status": 1
            }
        ],
        "from": 1,
        "last_page": 1,
        "next_page_url": null,
        "path": "http://localhost/api/manage/trainer/info",
        "per_page": "23",
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}

```

#####  3. 修改代理信息
url: /api/manage/agent/info

method: put

（除了 id 是必须的 其他都可以选  之后的班型  场地 教练等）
（schedule 是需要批量选择的）
request: 
```json
{
        "id"       :"2",
        
         "name"     :"zhaoshuai",
         "age"      :"1996",
         "sex"      :"男",
         "idCard"   :"371502199602295336",
         "telephone":"17865169626",
         "bankCardNumber" :"6216611100002899022",
         "bankInfo"     :"中国工商银行"

}
```