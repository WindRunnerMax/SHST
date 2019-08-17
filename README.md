# SW

```
SW/Python 目录下为Python版本的API
SW/PHP 目录下为PHP版本的API
SW/Java 目录下为Java版本的API
```

----
  
## Python

**配置文件 SW/Python/SW.py**
* account 账号
* password 密码
* url {$强智系统URL}/app.do

**源码末尾取消注释即可获取相应方法返回的值，json.loads()即可得到json格式数据**  
* Q.getStudentInfo() #获取学号信息
* Q.getCurrentTime() #获取学年信息
* Q.getKbcxAzc() #当前周次课表
* Q.getKbcxAzc(3) #指定周次课表
* Q.getKxJscx("0102") #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
* Q.getCjcx("2018-2019-1") #成绩查询 #无参数查询全部成绩
* Q.getKscx() #获取考试信息

----
  
## PHP

**配置文件 SW/PHP/Main.php**
* accountSW 账号
* passwordSW 密码
* urlSW {$强智系统URL}/app.do
* 源码末尾取消注释即可获取相应方法返回的值
----
  
## Java

**配置文件 SW/Java/MainSw.java**
* account 账号
* password 密码
* url {$强智系统URL}/app.do
* 注意导入工程时更改包名
* 源码末尾取消注释即可获取相应方法返回的值

----

