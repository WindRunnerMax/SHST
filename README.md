# SW
```
SW/Python 目录下为Python版本的API
SW/PHP 目录下为PHP版本的API
SW/Java 目录下为Java版本的API
SW/Web 目录下基于ThinkPHP制作的WEB项目[科大]，提供课表查询，空教室查询，成绩查询，图书馆书籍检索，图书馆借阅查询，常用链接分享等功能  
SW/WXSA 目录下为微信小程序项目[山科小站]，提供课表查询，空教室查询，成绩查询，图书馆书籍检索，图书馆借阅查询，常用链接分享，校历，地图等功能  
```

[![tips](https://img.shields.io/badge/-更新日志-%234C98F7.svg?style=for-the-badge&logo=azure-pipelines&logoColor=White)](https://github.com/WindrunnerMax/SW/blob/master/ChangeLog.md) 
[![tips2](https://img.shields.io/badge/-通用版本-%234C98F7.svg?style=for-the-badge&logo=monogram&logoColor=White)](https://github.com/WindrunnerMax/SW/tree/master) 
[![tips3](https://img.shields.io/badge/-科大-%234C98F7.svg?style=for-the-badge&logo=sitepoint&logoColor=White)](https://github.com/WindrunnerMax/SW/blob/SDUST/Web/public/show1.jpg) 
[![tips4](https://img.shields.io/badge/-山科小站-%234C98F7.svg?style=for-the-badge&logo=marketo&logoColor=White)](https://github.com/WindrunnerMax/SW/blob/SDUST/Web/public/show2.jpg) 

----
  
## Python

**配置文件 SW/Python/SW.py**
* account 账号
* password 密码
* url {$强智系统URL}/app.do

**源码末尾取消注释即可获取相应方法返回的值，json.loads()即可得到json格式数据**  
* Q.getStudentIdInfo() #获取学号信息
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
## Web  

**配置文件 SW/Web/app/auxiliary/Conf.php**
* getCtx() 返回路径(用于配置引入静态文件等)
* getCurTerm() 获取当前学期
* getCurTermStart() 本学期开学日期
* getUrl() {$强智系统URL}/app.do
* getHeader() 获取请求头
* getTips() 公告信息
* getColorList() 颜色方案
* getNewTips() 版本更新状态用于发布公告

----
## Wechat-App  

**配置文件 SW/WXSA/app.js**
* userFlag 用户登录状态
* url 后台请求登录路径
* header 请求头信息(不建议改动)
* openid OPENID信息
* colorList 颜色方案
* version 版本号
* curTerm 当前学期
* curTermStart 开学日期
* extend() 深拷贝与浅拷贝，无需改动
* toast() 弹窗提示
* ajax() 网络请求封装

**配置文件 SW/Web/app/funct/controller/User.php**
* $appid 小程序申请的APPID
* $appSecret 小程序申请的APPSECRET

----  
![show](https://github.com/WindrunnerMax/SW/blob/SDUST/Web/public/show1.jpg)
![show](https://github.com/WindrunnerMax/SW/blob/SDUST/Web/public/show2.jpg)

