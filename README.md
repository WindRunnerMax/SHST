# SW
强智教务管理系统免验证码查询<br>
参考：<a href="https://github.com/TLingC/GDUF-QZAPI">https://github.com/TLingC/GDUF-QZAPI</a><br>
可以手机自行抓包获取更多接口 （推荐软件Packet Capture)<br>
<br>
使用此脚本有以下几处需要作改动：<br>
##########################################################################<br>
account = "" #账号 <br>
password = "" #密码 <br>
url = "http://jwgl.sdust.edu.cn/app.do" # $学校教务管理系统网站/app.do<br>
##########################################################################<br>
# 源码末尾取消注释即可获取函数返回的值，json.loads()即可得到json格式数据<br>
# Q.getStudentIdInfo() #获取学号信息<br>
# Q.getCurrentTime() #获取学年信息<br>
# Q.getKbcxAzc() #当前周次课表<br>
# Q.getKbcxAzc(3) #指定周次课表<br>
# Q.getKxJscx("0102") #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室<br>
# Q.getCjcx("2018-2019-1") #成绩查询 #无参数查询全部成绩<br>
# Q.getKscx() #获取考试信息<br>
###########################################################################<br>
