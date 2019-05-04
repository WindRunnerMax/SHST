# web
```
SW/Web 目录下基于ThinkPHP制作的WEB项目，提供课表查询，空教室查询，成绩查询，图书馆书籍检索，图书馆借阅查询，常用链接分享 
```
更新日志：https://github.com/WindrunnerMax/SW/blob/master/ChangeLog.md

![show](https://github.com/WindrunnerMax/SW/blob/SDUST/Web/public/show1.jpg)

----
  
# python脚本
```
SW/Python 目录下有python爬虫脚本，功能较全
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
```
可以手机自行抓取强智APP获取更多接口  
手机抓包:Packet Capture，电脑抓包:Fiddler
```
