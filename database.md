##数据库设计

驾校订单部分

1.driver_school

| column        | type          | comment          |  
| ------------- |:-------------:|:-------------:|
| id      | Integer | |
| name      | varchar      ||
| account | varchar     ||
| password | varchar     ||
| role_id | Integer     ||
| status | Integer     ||

2.order

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
| id      | Integer | |
| stu_name      | varchar ||
| stu_idCard      | varchar ||
| stu_telephone     | varchar ||
| stu_permit      | varchar |暂住证|
| stu_qq      | varchar ||
| field_id      | Integer |场地id|
| class_id      | varchar |班型id|
| type      | Integer |报名类型|
| stu_cost      | Integer |报名费用|
| agent_id      | Integer |代理人id|
| reduction      | Integer |优惠金额|
| payed_id      | Integer |支付信息id|
| inviter_id      | Integer |邀请人id|
| create_time      | datetime |创建时间|

3.payed

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
| id      | Integer | |
| order_id      | Integer |订单id |
| amount      | Integer |总额 |
| payed      | Integer | 已支付金额|
| create_time      | 创建时间 | |
| school_id      | Integer | |
| status     | Integer | |












