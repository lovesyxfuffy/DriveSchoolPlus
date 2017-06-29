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
url前缀: /api/manage/account
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

```


#####  2.创建管理员
url: /api/manage/account/i/info

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
  "code": 200,
  "msg": []
}
```
