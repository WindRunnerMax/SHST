<template>
    <div>

        <layout>
            <div class="notice">欢迎：{{account}} {{name}}</div>
        </layout>
        <layout title="自定义课表配色">
            <div class="y-CenterCon notice">
                <div>自定义配色教程</div>
                <a href="https://mp.weixin.qq.com/s/9L3kFI0jdHajnPm83jRbwA">点我查看教程</a>
            </div>
            <div class="y-CenterCon notice">
                <div>配色在线测试功能</div>
                <a href="http://windrunner_max.gitee.io/imgpath/SHST/Page/ColorScheme.html">点我进入测试</a>
            </div>
            <el-input placeholder="请输入配色方案" v-model="colorList" size="small" clearable></el-input>
            <div class="y-CenterCon" style="justify-content: flex-end;margin-top: 10px;">
                <el-button type="primary" size="small" @click="example(0)">暗色</el-button>
                <el-button type="primary" size="small" @click="example(1)">亮色</el-button>
                <el-button type="primary" size="small" @click="setColorList">提交</el-button>
            </div>
        </layout>

    </div>

</template>

<script>
    export default {
        data() {
            return {
                account: "",
                name: "",
                colorList: ""
            }
        },
        created: async function() {
            this.params = this.$route.params;
            var res = await $app.request({
               url: `${$app.globalData.url}mp/getCustomInfo/${this.params.t}/${this.params.u}/${this.params.s}`,
            })
            if(res.data.status === -1) {
                $app.toast(res.data.msg);
            } else if(res.data.status === 1) {
                this.account = res.data.data.account;
                this.name = res.data.data.name;
                this.colorList = res.data.data.color_list.replace(/\[|\]|"/g,"")
            }
        },
        methods: {
            example: function(type){
                this.colorList = type === 0 ? "#EAA78C,#F9CD82,#9ADEAD,#9CB6E9,#E49D9B,#97D7D7,#ABA0CA,#9F8BEC,#ACA4D5,#6495ED,#7BCDA5,#76B4EF,#E1C38F,#F6C46A,#B19ED1,#F09B98,#87CECB,#D1A495,#89D196" : "#FE9E9F,#93BAFF,#D999F9,#81C784,#FFC107,#FFA477";
            },
            setColorList: async function(){
                if(!this.colorList) {
                    $app.toast("配色方案最少1种配色");
                    return false;
                }
                var str = this.colorList.replace(/\s+/g,"");
                var arr = str.split(",");
                if(arr.length > 20){
                    $app.toast("配色方案最多20种配色");
                    return false;
                } 
                var colorCheck = arr.find(v => !v.match(/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/));
                if(colorCheck){
                    $app.toast(`配色方案 ${colorCheck} 有误`);
                    return false;
                }
                var res = await $app.request({
                   url: `${$app.globalData.url}mp/setTableColor/${this.params.t}/${this.params.u}/${this.params.s}`,
                   method: "POST",
                   data:{
                       colorList: str
                   }
                })
                if(res.data.status === -1) $app.toast(res.data.msg);
                else if(res.data.status === 1) $app.toast("设置成功","success");
            }
        }
    }
</script>

<style>
    .notice > a{
        margin-left: 5px;
        color: var(--color-blue);
    }
    .notice{
        margin: 5px;
    }
</style>
