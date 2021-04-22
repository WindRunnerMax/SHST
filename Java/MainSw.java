package com.sw;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * @author Czy
 * @time Jul 6, 2019
 * @detail *
 */

public class MainSw {

    /**
     * 强智教务系统
     */
    ////////////////////////////////////////////////////////
    private String account = "";
    private String password = "";
    private String url = "http://jwgl.sdust.edu.cn/app.do";
    ////////////////////////////////////////////////////////

    /**
     * 注意：由于处理Json需要引入Json包，所以暂时不处理Json数据，假设当前学期是2018-2019-2，当前周次是18周
     * 引入Json包后可以直接调用getCurrentTime()方法得到字符串再转化为Json获取数据
     * 其实学期与当前周次是可以自行计算的，还可以减少对强智服务器的请求
     */
    ////////////////////////////////////////////////////////
    private String curWeek = "18";
    private String curTerm = "2018-2019-2";
    ////////////////////////////////////////////////////////

    private Map<String, String> params = new HashMap<>();
    private Map<String, String> headers = new HashMap<>();


    public MainSw() {
        this.params.put("method", "authUser");
        this.params.put("xh", this.account);
        this.params.put("pwd", this.password);
        String reqResult = Http.httpRequest(this.url, this.params, "GET", this.headers);
        System.out.println(reqResult);
        String[] reqResultArr  = reqResult.split(",");
        if(reqResultArr[0].charAt(9) == '0') {
            System.out.println("登录失败");
            System.exit(0);
        }else {
            this.headers.put("token", reqResultArr[2].substring(9, reqResultArr[2].length()-1));
        }
    }


    public MainSw getStudentInfo() {
        this.params.put("method", "getUserInfo");
        this.params.put("xh", this.account);
        return this;
    }

    public MainSw getCurrentTime() {
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        this.params.put("method", "getCurrentTime");
        this.params.put("currDate", df.format(new Date()));
        return this;
    }

    public MainSw getTable() {
        this.params.put("method", "getKbcxAzc");
        this.params.put("xh", this.account);
        this.params.put("xnxqid", this.curTerm);
        this.params.put("zc", this.curWeek);
        return this;
    }

    public MainSw setWeek(String week) {
        this.params.put("zc", week);
        return this;
    }

    public MainSw getGrade() {
        this.params.put("method", "getCjcx");
        this.params.put("xh", this.account);
        this.params.put("xnxqid", "");
        return this;
    }

    public MainSw setTerm(String term) {
        this.params.put("xnxqid", term);
        return this;
    }

    public MainSw getClassroom(String idleTime) {
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        this.params.put("method", "getKxJscx");
        this.params.put("time", df.format(new Date()));
        this.params.put("idleTime", idleTime);
        return this;
    }

    public MainSw getExamInfo() {
        this.params.put("method", "getKscx");
        this.params.put("xh", this.account);
        return this;
    }

    public String exec(){
        String result = Http.httpRequest(this.url, this.params, "GET", this.headers);
        this.params.clear();
        System.out.println(result);
        return result;
    }

    /**
     * 入口函数
     * @param args
     */
    public static void main(String[] args) {
        MainSw Q = new MainSw();
        // Q.getStudentInfo().exec(); //获取学生信息
        // Q.getCurrentTime().exec(); //获取学年信息
        // Q.getTable().exec(); //当前周次课表
        // Q.getTable().setWeek("3").exec(); //指定周次课表
        // Q.getGrade().exec(); //查询全部成绩
        // Q.getGrade().setTerm("2018-2019-2").exec(); //指定学期成绩查询
        // Q.getClassroom("0102").exec(); //空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
        // Q.getExamInfo().exec(); //获取考试信息
    }

}
