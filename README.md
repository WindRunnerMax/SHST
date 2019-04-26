# web
```
SW/Web 目录下基于ThinkPHP制作的WEB项目，提供课表查询，空教室查询，成绩查询(实测智校园APP出问题无法进入时，本项目正常使用)  
```
更新日志：https://github.com/WindrunnerMax/SW/blob/master/ChangeLog.md
```
嵙同学部署到服务器即用，外校同学若使用需改动:
SW/app/index/controller/Sw.php  
SW/app/funct/controller/Sw.php  
中private $url = "http://jwgl.sdust.edu.cn/app.do";  
更改为private $url = "${学校教务管理系统网站}/app.do";
```

![show](https://raw.githubusercontent.com/WindrunnerMax/SW/master/Web/public/show1.jpg)
![show](https://raw.githubusercontent.com/WindrunnerMax/SW/master/Web/public/show2.jpg)

----
  
# python脚本
```
SW/Python 目录下有python爬虫脚本，功能较全
```
```
使用此脚本有以下几处需要作改动:  
account = "" #账号  
password = "" #密码  
url = "http://jwgl.sdust.edu.cn/app.do" # ${学校教务管理系统网站}/app.do  
```

**源码末尾取消注释即可获取函数返回的值，json.loads()即可得到json格式数据**  
* Q.getStudentIdInfo() #获取学号信息
* Q.getCurrentTime() #获取学年信息
* Q.getKbcxAzc() #当前周次课表
* Q.getKbcxAzc(3) #指定周次课表
* Q.getKxJscx("0102") #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
* Q.getCjcx("2018-2019-1") #成绩查询 #无参数查询全部成绩
* Q.getKscx() #获取考试信息
----  
> 可以手机自行抓取强智APP获取更多接口  
> 手机抓包:Packet Capture，电脑抓包推:Fiddler

