package com.sw;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.Map;
import java.util.Map.Entry;

/**
 * @author Czy
 * @time Jul 6, 2019
 * @detail Http������
 */

public class Http {

    public static String httpRequest(String url, Map<String, String> param, String method, Map<String, String> headers) {
        String paramDipose = pramHandle(param);
        if (method.equalsIgnoreCase("GET")) {
            return doGet(url + paramDipose, headers);
        } else {
            return doPost(url, paramDipose, headers);
        }
    }

    private static String pramHandle(Map<String, String> params) {
        StringBuilder urlParam = new StringBuilder("?");
        ;
        for (Entry<String, String> param : params.entrySet()) {
            urlParam.append(param.getKey() + "=" + param.getValue() + "&");
        }
        return urlParam.toString();
    }

    private static String doGet(String httpurl, Map<String, String> headers) {
        HttpURLConnection connection = null;
        String result = null;// ���ؽ���ַ���
        try {
            // ����Զ��url���Ӷ���
            URL url = new URL(httpurl);
            // ͨ��Զ��url���Ӷ����һ�����ӣ�ǿת��httpURLConnection��
            connection = (HttpURLConnection) url.openConnection();
            // �������ӷ�ʽ��get
            connection.setRequestMethod("GET");
            // �������������������ĳ�ʱʱ�䣺15000����
            connection.setConnectTimeout(15000);
            // ���ö�ȡԶ�̷��ص�����ʱ�䣺60000����
            connection.setReadTimeout(60000);
            for (Entry<String, String> header : headers.entrySet()) {
                connection.setRequestProperty(header.getKey(), header.getValue());
            }
            // ��������
            connection.connect();
            // ͨ��connection���ӣ���ȡ������
            if (connection.getResponseCode() == 200) result = getResult(connection);
        } catch (Exception e) {
            System.out.println(e.toString());
        }
        return result;
    }

    private static String doPost(String httpUrl, String param, Map<String, String> headers) {
        HttpURLConnection connection = null;
        OutputStream os = null;
        String result = null;
        try {
            URL url = new URL(httpUrl);
            connection = (HttpURLConnection) url.openConnection();
            connection.setRequestMethod("POST");
            connection.setConnectTimeout(15000);
            connection.setReadTimeout(60000);
            connection.setDoOutput(true);
            connection.setDoInput(true);
            for (Entry<String, String> header : headers.entrySet()) {
                connection.setRequestProperty(header.getKey(), header.getValue());
            }
            connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
            os = connection.getOutputStream();
            os.write(param.getBytes());
            if (connection.getResponseCode() == 200) result = getResult(connection);
        } catch (Exception e) {
            System.out.println(e.toString());
        } finally {
            if (null != os) {
                try {
                    os.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return result;
    }

    private static String getResult(HttpURLConnection connection){
        InputStream is = null;
        BufferedReader br = null;
        StringBuilder sb = new StringBuilder();
        try {
            is = connection.getInputStream();
            br = new BufferedReader(new InputStreamReader(is, StandardCharsets.UTF_8));

            String temp = "";
            while ((temp = br.readLine()) != null) {
                sb.append(temp);
            }
        }catch (Exception e){
            e.printStackTrace();
        }finally {
            closeConn(br,is,connection);

        }
        return sb.toString();
    }

    private static void closeConn(BufferedReader br,InputStream is,HttpURLConnection connection){
            if (null != br) {
                try {
                    br.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            if (null != is) {
                try {
                    is.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            if(connection!=null){
                connection.disconnect();

            }
    }

}



