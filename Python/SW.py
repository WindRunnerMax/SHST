#!/usr/bin/python 
# -*- coding: utf-8 -*-

import time
import requests
import re
import os
import json
import datetime

# 强智教务管理系统
########################################
account = ""
password = ""
url = "http://jwgl.sdust.edu.cn/app.do" 
########################################


class SW(object):
    """docstring for SW"""
    def __init__(self, account, password,url):
        super(SW, self).__init__()
        self.url = url
        self.account = account
        self.password = password
        self.session = self.login()
    
    HEADERS = {
    "User-Agent":"Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )",
    "Referer": "http://www.baidu.com",
    "accept-encoding": "gzip, deflate, br",
    "accept-language": "zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2",
    "cache-control": "max-age=0"
    }

    def login(self):
        params={
            "method" : "authUser",
            "xh" : self.account,
            "pwd" : self.password
        }
        session = requests.Session()
        req = session.get(self.url, params=params,timeout = 5,headers = self.HEADERS)
        s = json.loads(req.text)
        print(s['msg'])
        if s['flag'] != "1" :
            exit(0)
        self.HEADERS['token'] = s['token']
        # print(s['token'])
        # print(self.HEADERS)
        return session

    

    def get_handle(self,params):
        req = self.session.get(self.url ,params = params ,timeout = 5 ,headers=self.HEADERS)
        return req

    def get_student_info(self):
        params = {
            "method" : "getUserInfo",
            "xh" : self.account
        }
        req = self.get_handle(params)
        print(req.text)
        pass
    
    def get_current_time(self):
        params = {
            "method" : "getCurrentTime",
            "currDate" : datetime.datetime.now().strftime('%Y-%m-%d')
        }
        req = self.get_handle(params)
        print(req.text)
        return req.text

    def get_class_info(self,zc = -1):
        s = json.loads(self.get_current_time())
        params={
            "method" : "getKbcxAzc",
            "xnxqid" : s['xnxqh'],
            "zc" : s['zc'] if zc == -1 else zc,
            "xh" : self.account
        }
        req = self.get_handle(params)
        print(req.text)
        pass

    def get_classroom_info(self,idleTime = "allday"):
        params={
            "method" : "getKxJscx",
            "time" : datetime.datetime.now().strftime('%Y-%m-%d'),
            "idleTime" : idleTime
        }
        req = self.get_handle(params)
        print(req.text)

    def get_grade_info(self,sy = ""):
        params={
            "method" : "getCjcx",
            "xh" : self.account,
            "xnxqid" : sy
        }
        req = self.get_handle(params)
        print("全部成绩" if sy == "" else sy)
        if req.text != "null" :
            s = json.loads(req.text)
            # print(s)
            for x in s:
                print("%s   %d   %s" % (str(x['zcj']),x['xf'],x['kcmc']))
            print("绩点：" + str(self.get_gp(s)))
        else :
            print("空")

    def get_exam_info(self):
        params={
            "method" : "getKscx",
            "xh" : self.account,
        }
        req = self.get_handle(params)
        print(req.text)

    def get_gp(self,data):
        GP = 0.0
        credit = 0.0
        for x in data:
            credit += x['xf']
            if  x['zcj'] == "优":
                GP += (4.5 * x['xf'])
            elif x['zcj'] == "良":
                GP += (3.5 * x['xf'])
            elif x['zcj'] == "中":
                GP += (2.5 * x['xf'])
            elif x['zcj'] == "及格":
                GP += (1.5 * x['xf'])
            elif x['zcj'] == "不及格":
                GP += 0 
            else :
                if float(x['zcj']) >= 60:
                   GP += (((float(x['zcj'])-60)/10+1) * x['xf'])
        return GP/credit


if __name__ == '__main__':
    Q = SW(account,password,url)
    # Q.get_student_info() #获取学生信息
    # Q.get_current_time() #获取学年信息
    # Q.get_class_info() #当前周次课表
    # Q.get_class_info(3) #指定周次课表
    # Q.get_classroom_info("0102") #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
    # Q.get_grade_info("2018-2019-1") #成绩查询 #无参数查询全部成绩
    # Q.get_exam_info() #获取考试信息


