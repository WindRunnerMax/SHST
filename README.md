# SHST

```
SHST/Python 目录下为Python版本的API
SHST/PHP 目录下为PHP版本的API
SHST/Java 目录下为Java版本的API 
```

## Python

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

## PHP
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

## Java
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

## Notice

```php
/***
注意：
1. 由于强智版本不尽相同，返回的数据字段会有所差别，例如我们学校返回的是flag字段标记登陆成功，而有学校会返回success字段标记登陆成功
2. 数据接口全部抓取智校园App而来，可以使用Fiddler等抓包工具自行尝试抓包，注意安卓7及以上不会认同用户自定义证书，可以使用root将证书安装为系统证书或使用其他的辅助工具尝试抓包
3. 虽然数据接口由智校园App得来，但这并不意味着只有学校支持智校园才能使用数据接口，强智教务系统的接口一般是默认开放的，当然系统管理员可以手动关闭，而智校园的使用是需要强智公司授权的，也就是说虽然学校不能用智校园，但是完全有可能开放接口
4. 目前发现有的教务系统不能直接查询全部成绩，这个接口的使用请自行验证，按学期查询成绩的接口使用目前并未发现问题
5. 如果接口无法使用，可以尝试直接识别验证码爬取教务系统，验证码识别请看 https://github.com/WindrunnerMax/SWVerifyCode 此仓库，提供了使用 Python、PHP、Java、JavaScript 识别验证码的示例
***/
```
