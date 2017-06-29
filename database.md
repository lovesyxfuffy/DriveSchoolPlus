## 数据库设计


### 驾校订单部分

- - -

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
| create_time      | datetime |创建时间 |
| school_id      | Integer | |
| status     | Integer | |

4.student

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
| id      | Integer | |
| head_img      | varchar | 头像地址|
| name      | varchar | |
| age      | Integer | |
| sex      | varchar | |
| idCard     | varchar | |
| telephone      | varchar | |
| qq      | varchar | |
| schedule      | Integer |进度|
| status      | Integer ||

5.trainer

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
| id      | Integer | |
| name      | varchar | |
| head_img      | varchar | |
| sex      | varchar | |
| age      | varchar | |
| telephone      | varchar | |
| weChat      | varchar | 微信号|
| licence      | varchar |驾照 |
| class_id      | Integer | 当前教授的班型|
| school_id      | Integer |所属驾校id |
|appointment_status|Integer|预约状态|
|status|Integer||

6.student_lesson(学生-教练关联表)

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|trainer_id|Integer||
|student_id|Integer||
|field_id|Integer||
|schedule|Integer|该学生当前处于哪个科目|
|class_id|Integer||
|evaluate|TEXT|学生对教练的评价|
|evaluate_level|Integer|评价星级|

7.class(班型)

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|content|TEXT|班型描述|
|school_id|Integer|所属驾校id|
|rule_number|Integer|班型规则|
|cost|Integer|班型花费|
|cost_content|TEXT|费用明细|1

8.agent

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|name|varchar||
|telephone|varchar||
|bank_number|varchar|银行账户|
|bank_info|varchar|开户银行信息|
|percentage|Integer|分红数目|
|reduction|Integer|能提供的优惠金额|
|inviter_id|Integer|上级代理人id|

9.percentage

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|agent_id|Integer||
|amount|Integer||
|rule_id|Integer|分红规则id|
|create_time|datetime||
|status|Integer||

10.percentage_rule(分红规则)

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|Cycle|Integer|结算周期，单位天|
|level|Integer|结算上溯等级|

11.finance_record(财务记录)

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|account_id|Integer||
|record_content|varchar||
|record_type|Integer|操作类型|
|recode_time|datetime||

12.field

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|name|var||
|content|TEXT||
|student_count|Integer||
|position_x|Double|经纬度-经度|
|position_y|Double|经纬度-纬度|
|position|varchar|位置描述|

- - -

### 后台管理权限部分

13.account

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|name|varchar||
|account|varchar||
|password|varchar||
|role_id|Integer||

14.role

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|name|varchar||
|role_authority|Integer||
|role_database_authority||

15.menu

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|name|varchar||
|database_name|varchar||
|level|Integer||
|father_menu_id|Integer||

16.database_authority

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|database_name|varchar||
|database_level|Integer||

17.role_menu

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|role_id|Integer||
|menu_id|Integer||
|authority|Integer||

18.school_settings(内容包括所有需要设置内容)

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|school_id|Integer||
|banner|varchar|banner图地址|
|school_content|TEXT|驾校介绍|
|school_acceptance|TEXT|驾校服务承诺
|school_notice|TEXT|学车须知|
|text_notice|TEXT|测考锦囊|
- - -

### 线上测考部分

19.questions

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|belongs|Integer|所属章节（科目x）|
|domain|varchar|所属领域|
|right_answer|Integer|正确答案(option)id|
|content|TEXT|题目描述|
|type|Integer|单选多选|

20.options

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|question_id|Integer||
|question_order|Integer|ABCD(1,2,3,4保存)|
|content|varchar||

21.student_question

| column        | type          | comment        |  
| ------------- |:-------------:|:-------------:|
|id|Integer||
|student_id|Integer||
|question_id|Integer||
|type|Integer|收藏OR错题|






























