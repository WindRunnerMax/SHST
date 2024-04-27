# SHST

<p>
<a href="https://github.com/WindrunnerMax/SHST">GitHub</a>
<span>｜</span>
<a href="https://cdn.jsdelivr.net/gh/SHST-SDUST/SHST-UNI/src/vector/resources/exhibition/shst-wx.jpg">Online</a>
<span>｜</span>
<a href="https://juejin.cn/post/7341805821527113747#heading-1">BLOG</a>
</p>

山科小站，山东科技大学校园服务平台，提供教务系统、图书馆、校园导航等服务，拥有比较丰富的生态产品：

<table>
<thead>

<tr>
<th width="150px" >项目地址</th>
<th>简介</th>
</tr>

</thead>
<tbody>

<tr>
<td><a href="./Python/">SHST/Python</a></td>
<td><code>Python</code>版本的<code>API</code>。</td>
</tr>

<tr>
<td><a href="./PHP/">SHST/PHP</a></td>
<td><code>PHP</code>版本的<code>API</code>。</td>
</tr>

<tr>
<td><a href="./Java/">SHST/Java</a></td>
<td><code>Java</code>版本的<code>API</code>。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-UNI">SHST-UNI</a></td>
<td>山科小站小程序版本，山东科技大学校园服务平台，已上线微信小程序与<code>QQ</code>小程序。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-PLUS">SHST-PLUS</a></td>
<td>山科小站小程序<code>Plus</code>版本，作为小程序<code>API</code>的补充，纯爬虫解析<code>HTML</code>版本，已上线微信小程序。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-UNI-NEXT">SHST-NEXT</a></td>
<td>山科小站小程序<code>Next</code>版本，提供山科小站与小站<code>Plus</code>组合最新版本，已上线微信小程序。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-WEL">SHST-WEL</a></td>
<td>山科小站迎新专版小程序，提供校内的相关信息迎新专用，已上线微信小程序与<code>QQ</code>小程序。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-WEX">SHST-WEX</a></td>
<td>山科小站<code>APP</code>版本，提供<code>NVUE/WEEX</code>版本的原生渲染<code>APP</code>，已上架酷安应用市场。</td>
</tr>

<tr>
<td><a href="https://github.com/SHST-SDUST/SHST-ULTRA">SHST-ULTRA</a></td>
<td>山科小站小程序<code>Ultra</code>版本，纯小程序端请求与解析数据，无需服务器中转，已上线微信小程序。</td>
</tr>

</tbody>
</table>



## API 

### Python

```python
# Python/SW.py

# 配置信息
account = ""                              # 账号
password = ""                             # 密码
url = "http://jwgl.sdust.edu.cn/app.do"   # ${学校教务系统}/app.do

# 代码末尾注释的方法 取消注释测试执行
Q.get_student_info()                      # 获取学生信息
Q.get_current_time()                      # 获取学年信息
Q.get_class_info()                        # 当前周次课表
Q.get_class_info(3)                       # 指定周次课表
Q.get_classroom_info("0102")              # 空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
Q.get_grade_info("2018-2019-1")           # 成绩查询 # 无参数查询全部成绩
Q.get_exam_info()                         # 获取考试信息
```

### PHP
```php
// PHP/Main.php

// 配置信息
$accountSW = "";                            // 账号
$passwordSW = "";                           // 密码
$urlSW = "http://jwgl.sdust.edu.cn/app.do"; // ${学校教务系统}/app.do

// 代码末尾注释的方法 取消注释测试执行
$Q -> getStudentInfo();                     // 获取学生信息
$Q -> getCurrentTime();                     // 获取学年信息
$Q -> getTable();                           // 当前周次课表
$Q -> getTable(3);                          // 指定周次课表
$Q -> getGrade("2018-2019-2");              // 成绩查询 // 无参数查询全部成绩
$Q -> getClassroom("0102");                 // 空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
$Q -> getExam();                            // 获取考试信息
```

### Java
```java
// Java/MainSw.java

// 配置信息
private String account = "";                            // 账号
private String password = "";                           // 密码
private String url = "http://jwgl.sdust.edu.cn/app.do"; // ${学校教务系统}/app.do

// 代码末尾注释的方法 取消注释测试执行
Q.getStudentInfo().exec();                              // 获取学生信息
Q.getCurrentTime().exec();                              // 获取学年信息
Q.getTable().exec();                                    // 当前周次课表
Q.getTable().setWeek("3").exec();                       // 指定周次课表
Q.getGrade().exec();                                    // 查询全部成绩
Q.getGrade().setTerm("2018-2019-2").exec();             // 查询指定学期成绩
Q.getClassroom("0102").exec();                          // 空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
Q.getExamInfo().exec();                                 // 获取考试信息
```

### Notice

1. 由于强智版本不尽相同，返回的数据字段会有所差别，例如我们学校返回的是`flag`字段标记登陆成功，而有学校会返回`success`字段标记登录成功。
2. 数据接口全部抓取智校园`App`而来，可以使用`Fiddler`等抓包工具自行尝试抓包，注意安卓`7`及以上不会认同用户自定义证书，可以使用`root`将证书安装为系统证书或使用其他的辅助工具尝试抓包。
3. 虽然数据接口由智校园`App`得来，但这并不意味着只有学校支持智校园才能使用数据接口，强智教务系统的接口一般是默认开放的，当然系统管理员可以手动关闭，而智校园的使用是需要强智公司授权的，也就是说虽然学校不能用智校园，但是完全有可能开放接口。
4. 目前发现有的教务系统不能直接查询全部成绩，这个接口的使用请自行验证，按学期查询成绩的接口使用目前并未发现问题。
5. 如果接口无法使用，可以尝试直接识别验证码爬取教务系统，验证码识别请参阅`https://github.com/WindrunnerMax/SWVerifyCode`仓库，提供了使用`Python、PHP、Java、JavaScript`识别验证码的示例
6. 对于请求的`URL`，是直接使用`protocol://hostname[:port]/app.do`，并不是教师学生端的`URL`再拼接`app.do`，具体可以访问`http://app.qzdatasoft.com:9876/qzkjapp/phone/provinceData`查阅，此外有些使用`ASPX`的强智系统的开放接口为`${URL}/app/app.ashx`。

  
## 山科小站

![山科小站宣传图](https://cdn.jsdelivr.net/gh/SHST-SDUST/SHST-UNI/src/vector/resources/exhibition/show.jpg)

