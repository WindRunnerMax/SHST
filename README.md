# web
**基于ThinkPHP制作的WEB项目，提供课表查询，空教室查询，成绩查询（实测智校园APP出问题无法进入时，本项目正常使用）**  
**嵙同学部署到服务器即用，外校同学若使用需改动：**
----
> SW/app/index/controller/Sw.php<br>
> SW/app/funct/controller/Sw.php<br>
> 中private $url = "http://jwgl.sdust.edu.cn/app.do"; <br>
更改为private $url = "${学校教务管理系统网站}/app.do";<br>
----
![show](https://raw.githubusercontent.com/WindrunnerMax/SW/master/public/show1.jpg)
![show](https://raw.githubusercontent.com/WindrunnerMax/SW/master/public/show2.jpg)
  
  ----
  
# python脚本
**SW/python目录下有python爬虫脚本，功能较全**  
**使用此脚本有以下几处需要作改动:**  
----
> account = "" #账号 <br>
> password = "" #密码 <br>
> url = "http://jwgl.sdust.edu.cn/app.do" # ${学校教务管理系统网站}/app.do<br>
----
**源码末尾取消注释即可获取函数返回的值，json.loads()即可得到json格式数据**  
* Q.getStudentIdInfo() #获取学号信息<br>
* Q.getCurrentTime() #获取学年信息<br>
* Q.getKbcxAzc() #当前周次课表<br>
* Q.getKbcxAzc(3) #指定周次课表<br>
* Q.getKxJscx("0102") #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室<br>
* Q.getCjcx("2018-2019-1") #成绩查询 #无参数查询全部成绩<br>
* Q.getKscx() #获取考试信息<br>
----
   
> 可以手机自行抓包获取更多接口 （推荐软件Packet Capture)<br>  
