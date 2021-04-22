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
     * ǿ�ǽ���ϵͳ
     */
    ////////////////////////////////////////////////////////
    private String account = "";
    private String password = "";
    private String url = "http://jwgl.sdust.edu.cn/app.do";
    ////////////////////////////////////////////////////////

    /**
     * ע�⣺���ڴ���Json��Ҫ����Json����������ʱ������Json���ݣ����赱ǰѧ����2018-2019-2����ǰ�ܴ���18��
     * ����Json�������ֱ�ӵ���getCurrentTime()�����õ��ַ�����ת��ΪJson��ȡ����
     * ��ʵѧ���뵱ǰ�ܴ��ǿ������м���ģ������Լ��ٶ�ǿ�Ƿ�����������
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
            System.out.println("��¼ʧ��");
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
     * ��ں���
     * @param args
     */
    public static void main(String[] args) {
        MainSw Q = new MainSw();
        // Q.getStudentInfo().exec(); //��ȡѧ����Ϣ
        // Q.getCurrentTime().exec(); //��ȡѧ����Ϣ
        // Q.getTable().exec(); //��ǰ�ܴοα�
        // Q.getTable().setWeek("3").exec(); //ָ���ܴοα�
        // Q.getGrade().exec(); //��ѯȫ���ɼ�
        // Q.getGrade().setTerm("2018-2019-2").exec(); //ָ��ѧ�ڳɼ���ѯ
        // Q.getClassroom("0102").exec(); //�ս��Ҳ�ѯ "allday"��ȫ�� "am"������ "pm"������ "night"������ "0102":1.2�ڿս��� "0304":3.4�ڿս���
        // Q.getExamInfo().exec(); //��ȡ������Ϣ
    }

}
