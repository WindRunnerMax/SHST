<template>
    <div>

        <layout>
            <div class='x-CenterCon'>
                <img style="width: 230px;height: 80px;margin: 20px 0 30px 0;" src="https://windrunner_max.gitee.io/imgpath/SHST/Static/SDUST.jpg" />
            </div>
            <el-form :model="loginData" :rules="rules" ref="form" label-width="60px" status-icon label-position='left'>
                <el-form-item label="账号" prop="account" >
                    <el-input placeholder="请输入" v-model="loginData.account" size="medium" clearable></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password" style="text-align: center;">
                    <el-input placeholder="请输入" v-model="loginData.password" size="medium" show-password></el-input>
                </el-form-item>

                <div class="x-CenterCon" style="margin-top: 10px;">
                    <el-button type="primary" size="medium" @click="submitForm('form')" style="width: 100%;">绑定账号</el-button>
                </div>
            </el-form>
                <div class="y-CenterCon" style="justify-content: space-between;padding-bottom: 10px;">
                    <div class='tips'>请输入强智系统账号密码</div>
                    <div style='margin:10px 0 0 3px;font-size:13px;color:red;'>{{status}}</div>
                </div>
        </layout>
        
        <layout>
            <div class="notice">
                <div>1. 一个账号只能绑定一个学号，不允许切换登陆</div>
                <div>2. 首先必须在山科小站微信小程序、QQ小程序、App其中任意一个客户端登陆过账号才可以在此处绑定账号</div>
                <div>3. 出现 “此学号已被其他账号绑定” 的提示说明此学号已被其他的微信绑定，如果不是您个人所为，密码可能已被泄露，请尽快修改密码并联系我解绑账号关联，QQ群：722942376</div>
            </div>
        </layout>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                loginData:{
                    account: "",
                    password: "",
                },
                rules: {
                    account: [
                        {required: true,message: '请输入账号',trigger: 'blur'},
                        {min: 12,max: 12,message: '学号为12位',trigger: 'blur'}
                    ],
                    password: [
                        {required: true,message: '请输入密码',trigger: 'blur'},
                        {min: 6,message: '密码不应该小于6位',trigger: 'blur'}
                    ]
                },
                status: ""
            }
        },
        methods: {
            submitForm: function(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.login();
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            login:async function(){
                var params = this.$route.params;
                console.log(params);
                var res = await $app.request({
                    url: `${$app.globalData.url}auth/mp/${params.t}/${params.u}/${params.s}`,
                    method: "POST",
                    data:{
                        "account" : this.loginData.account,
                        "password": encodeURI(this.loginData.password)
                    }
                })
                if(res.data.status === -1) $app.toast(res.data.msg);
                else if(res.data.status === 1) this.$router.push({ name: 'custom', params: params});
            }
        }
    }
</script>

<style scoped>
    body{
        background-color: #fff;
    }
    .tips{
        margin-top: 10px;
        color: var(--color-blue);
    }
    
</style>
