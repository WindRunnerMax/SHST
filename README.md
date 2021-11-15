# SHST

[![tips2](https://img.shields.io/badge/-API-%234C98F7.svg?style=for-the-badge&logo=monogram&logoColor=white)](https://github.com/WindrunnerMax/SW/tree/master) 
[![tips1](https://img.shields.io/badge/-更新日志-%234C98F7.svg?style=for-the-badge&logo=azure-pipelines&logoColor=white)](https://github.com/WindrunnerMax/SHST/blob/SDUST/ChangeLog.md) 
[![tips3](https://img.shields.io/badge/-山科小站-%234C98F7.svg?style=for-the-badge&logo=marketo&logoColor=white)](https://windrunner_max.gitee.io/imgpath/SHST/Static/SHST-WX.jpg) 

```
SHST/Python 目录下为Python版本的API
SHST/PHP 目录下为PHP版本的API
SHST/Java 目录下为Java版本的API 
SHST-SDUST/SHST-UNI 目录下为小程序项目[山科小站--小程序]，山东科技大学校园服务平台，已上线微信小程序与QQ小程序 
SHST-SDUST/SHST-PLUS 目录下为小程序项目[山科小站--plus]，山科小站的扩充版本，纯爬虫解析HTML版本，已上线微信小程序
SHST-SDUST/SHST-WEL 目录下为小程序项目[山科小站--迎新专版]，提供校内的相关信息，迎新专用，已上线微信小程序与QQ小程序   
SHST-SDUST/SHST-WEX 目录下为NVUE/WEEX项目[山科小站--APP]，采用原生渲染，作为UNIAPP纯NVUE/WEEX项目开发，已上架酷安应用市场
```


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

```php
/***
注意：
1. 由于强智版本不尽相同，返回的数据字段会有所差别，例如我们学校返回的是flag字段标记登陆成功，而有学校会返回success字段标记登陆成功
2. 数据接口全部抓取智校园App而来，可以使用Fiddler等抓包工具自行尝试抓包，注意安卓7及以上不会认同用户自定义证书，可以使用root将证书安装为系统证书或使用其他的辅助工具尝试抓包
3. 虽然数据接口由智校园App得来，但这并不意味着只有学校支持智校园才能使用数据接口，强智教务系统的接口一般是默认开放的，当然系统管理员可以手动关闭，而智校园的使用是需要强智公司授权的，也就是说虽然学校不能用智校园，但是完全有可能开放接口
4. 目前发现有的教务系统不能直接查询全部成绩，这个接口的使用请自行验证，按学期查询成绩的接口使用目前并未发现问题
5. 如果接口无法使用，可以尝试直接识别验证码爬取教务系统，验证码识别请看 https://github.com/WindrunnerMax/SWVerifyCode 此仓库，提供了使用 Python、PHP、Java、JavaScript 识别验证码的示例
6. 对于请求的URL，是直接使用protocol://hostname[:port]/app.do，并不是教师学生端的URL再拼接app.do，具体可以访问http://app.qzdatasoft.com:9876/qzkjapp/phone/provinceData查阅，此外有些使用ASPX的强智系统的开放接口为${学校教务系统}/app/app.ashx
***/
```

  
## 山科小站

![山科小站二维码](https://cdn.jsdelivr.net/gh/SHST-SDUST/SHST-UNI/vector/resources/exhibition/shst-wx.jpg)

### 配置相关 

```typescript
// global

declare namespace UniApp {
    interface Uni {
        $app: {
            toast: typeof import("./modules/toast").toast;
            extend: typeof import("./modules/copy").extend;
            data: import("./modules/global-data").Data;
            throttle: typeof import("./modules/operate-limit").throttle;
            eventBus: typeof import("./modules/event-bus").default;
            request: typeof import("./modules/request").request;
            ajax: typeof import("./modules/request").ajax;
            reInitApp: () => void;
            onload: <T extends Array<unknown>>(funct: (...args: T) => void, ...args: T) => void;
        };
    }
}
```


### 目录结构  

[关于UNIAPP重构以及类的封装文档](https://blog.touchczy.top/#/MiniProgram/%E5%B1%B1%E7%A7%91%E5%B0%8F%E7%AB%99%E5%B0%8F%E7%A8%8B%E5%BA%8F)

[关于UNIAPP迁移TS的相关说明](https://blog.touchczy.top/#/MiniProgram/uniapp%E5%B0%8F%E7%A8%8B%E5%BA%8F%E8%BF%81%E7%A7%BB%E5%88%B0TS)

```
SHST-UNI                              // 山科小站总目录
    ├── components                    // 组件封装
    │   ├── headslot.vue              // 带solt的标题布局
    │   ├── layout.vue                // 卡片式布局
    │   ├── list.vue                  // 展示用list布局
    │   ├── sentence.vue              // 每日一句封装
    │   └── weather.vue               // 天气封装
    ├── modules                       // 模块化封装
    │   ├── cookies.ts                // Cookies操作
    │   ├── copy.ts                   // 深浅拷贝
    │   ├── datetime.ts               // 时间日期操作
    │   ├── event-bus.ts              // 事件总线
    │   ├── global-data.ts            // 全局变量
    │   ├── loading.ts                // 加载提示
    │   ├── operate-limit.ts          // 防抖与节流
    │   ├── regex.ts                  // 正则匹配
    │   ├── request.ts                // 网络请求
    │   ├── toast.ts                  // 消息提示
    │   └── update.ts                 // 自动更新 
    ├── pages                         // 页面
    │   ├── Ext                       // 拓展组
    │   ├── Home                      // Tabbar、辅助组
    │   ├── Lib                       // 图书馆功能组
    │   ├── Sdust                     // 科大组
    │   ├── Study                     // 学习组
    │   └── User                      // 用户组
    ├── static                        // 静态资源
    │   ├── camptour                  // 校园导览静态资源
    │   └── img                       // 图标等静态资源
    ├── unpackage                     // 打包文件
    ├── utils                         // 辅助功能
    │   ├── amap-wx.js                // 高德地图SDK
    │   └── md5.js                    // MD5引入
    ├── vector                        // 部署封装
    │   ├── resources                 // 资源文件
    │   │   ├── camptour              // 校园导览配置文件
    │   │   ├── asse.mini.wxss        // 公共样式库
    │   │   └── iconfont.wxss         // 字体图标
    │   ├── dispose.ts                // 部署小程序
    │   └── pubFct.ts                 // 公有方法
    ├── App.vue                       // App全局样式以及监听
    ├── main.ts                       // 挂载App，Vue初始化入口文件
    ├── manifest.json                 // 配置Uniapp打包等信息
    ├── pages.json                    // 路由
    └── uni.scss                      // 内置的常用样式变量
```

### 小程序  
![山科小站宣传图](https://cdn.jsdelivr.net/gh/SHST-SDUST/SHST-UNI/vector/resources/exhibition/show.jpg)

