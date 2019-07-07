package com.czy.sw;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.czy.sw.Http;

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
	 * 引入Json包后可以直接调用GetCurrentTime()方法得到字符串再转化为Json获取数据
	 * 其实学期与当前周次是可以自行计算的，还可以减少对强智服务器的请求，详情可以看一下SW/Web/app/auxiliary/Conf.php类
	 */
	////////////////////////////////////////////////////////
	private String curWeek = "18";
	private String curTerm = "2018-2019-2";
	////////////////////////////////////////////////////////
	
	private Map<String, String> headers = new HashMap<String, String>();
	
	private Map<String, String> GetHashMap() {
		return new HashMap<String, String>();
	}
	
	public MainSw() {
		Map<String, String> param = GetHashMap();
		param.put("method", "authUser");
		param.put("xh", this.account);
		param.put("pwd", this.password);
		String reqResult = Http.httpRequest(this.url, param, "GET", this.headers);
		System.out.println(reqResult);
		String[] reqResultArr  = reqResult.split(",");
		if(reqResultArr[0].charAt(9) == '0') {
			System.out.println("登录失败");
			System.exit(0);
		}else {
			this.headers.put("token", reqResultArr[2].substring(9, reqResultArr[2].length()-1));
		}
	}
	
	private String GetHandle(Map<String, String> param) {
		return Http.httpRequest(this.url, param, "GET", this.headers);
	}
	
	public String GetStudentIdInfo() {
		Map<String, String> param = GetHashMap();
		param.put("method", "getStudentIdInfo");
		param.put("xh", this.account);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetCurrentTime() {
		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		Map<String, String> param = GetHashMap();
		param.put("method", "getCurrentTime");
		param.put("currDate", df.format(new Date()));
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetTable() {
		Map<String, String> param = GetHashMap();
		param.put("method", "getKbcxAzc");
		param.put("xh", this.account);
		param.put("xnxqid", this.curTerm);
		param.put("zc", this.curWeek);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetTable(String week) {
		Map<String, String> param = GetHashMap();
		param.put("method", "getKbcxAzc");
		param.put("xh", this.account);
		param.put("xnxqid", this.curTerm);
		param.put("zc", week);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetGrade() {
		Map<String, String> param = GetHashMap();
		param.put("method", "getCjcx");
		param.put("xh", this.account);
		param.put("xnxqid", "");
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetGrade(String term) {
		Map<String, String> param = GetHashMap();
		param.put("method", "getCjcx");
		param.put("xh", this.account);
		param.put("xnxqid", term);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	public String GetClassroom(String idleTime) {
		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		Map<String, String> param = GetHashMap();
		param.put("method", "getKxJscx");
		param.put("time", df.format(new Date()));
		param.put("idleTime", idleTime);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}

	public String GetExam() {
		Map<String, String> param = GetHashMap();
		param.put("method", "getKscx");
		param.put("xh", this.account);
		String req = this.GetHandle(param);
		System.out.println(req);
		return req;
	}
	
	/**
	 * 入口函数
	 * @param args
	 */
	public static void main(String[] args) {
		MainSw Q = new MainSw();
//		Q.GetStudentIdInfo(); //获取学号信息
//		Q.GetCurrentTime(); //获取学年信息
//		Q.GetTable(); //当前周次课表
//		Q.GetTable("3"); //指定周次课表
//		Q.GetGrade(); //查询全部成绩
//		Q.GetGrade("2018-2019-2"); //指定学期成绩查询
//		Q.GetClassroom("0102"); //空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
//		Q.GetExam(); //获取考试信息
	}

}
