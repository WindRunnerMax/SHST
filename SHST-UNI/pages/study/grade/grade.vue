<template>
    <view>

        <layout title="查成绩">
            <view class="select-con">
                <view>请选择学期</view>
                <picker @change="bindPickerChange" :value="index" :range="yearArr" class="a-link" range-key="show">
                    <view>{{yearArr[index].show}}</view>
                </picker>
            </view>
        </layout>

        <view v-if="show">
            <headslot :title="showSelect">
                <view class="y-center" style="flex-wrap: wrap; font-size: 13px;">
                    <view class="y-center over-unit">
                        <view class="a-dot" style="background:#6495ED;"></view>
                        <view>学分:{{point}}</view>
                    </view>
                    <view class="y-center over-unit">
                        <view class="a-dot" style="background:#ACA4D5;"></view>
                        <view>绩点:{{pointN}}</view>
                    </view>
                    <view class="y-center over-unit">
                        <view class="a-dot" style="background:#EAA78C;"></view>
                        <view>加权:{{pointW}}</view>
                    </view>
                </view>
            </headslot>
            <view class="a-lmt"></view>
            <view v-for="(item,index) in grade" :key="index">
                <layout>
                    <view class="unit adapt">
                        <view class="info-left">
                            <view class="c-name">{{item.kcmc}}</view>
                            <view style="color:#aaa;">{{item.kclbmc}}</view>
                            <view style="color:#aaa;">{{item.ksxzmc}}</view>
                        </view>
                        <view class="info-right">
                            <view class="cgrade">{{item.zcj}}</view>
                            <view style="color:#aaa;margin-top: 3px;">{{item.xf}}学分</view>
                        </view>
                    </view>
                </layout>
            </view>

            <layout v-if="ad">
                <!-- #ifdef MP-WEIXIN -->
                <ad unit-id="adunit-31c347091893cf0c" class="adapt" @error="adError"></ad>
                <!-- #endif -->
                <!-- #ifdef MP-QQ -->
                <ad unit-id="e40bef6dbe8ecaf7104fe126bfc34e56" class="adapt" @error="adError"></ad>
                <!-- #endif -->
            </layout>

        </view>


    </view>
</template>

<script>
    import headslot from "@/components/headslot.vue";
    export default {
        components: {
            headslot
        },
        data: function() {
            return {
                index: 1,
                yearArr: [{}, {show: "请稍后",value: ""}],
                point: 0,
                pointN: 0,
                pointW: 0,
                show: false,
                grade: 0,
                ad: true,
                showSelect: ""
            }
        },
        created: function() {
            // 处理学期
            uni.$app.onload(() => {
                var year = parseInt(uni.$app.data.curTerm.split("-")[1]);
                var yearArr = [{ show: "全部学期", value: "" }];
                for (var i = 1; i <= 4; ++i) {
                    let firstTerm = (year - i) + "-" + (year - i + 1) + "-2";
                    let secondTerm = (year - i) + "-" + (year - i + 1) + "-1";
                    if (firstTerm <= uni.$app.data.curTerm) {
                        yearArr.push({ show: firstTerm, value: firstTerm })
                    }
                    if (secondTerm <= uni.$app.data.curTerm) {
                        yearArr.push({ show: secondTerm, value: secondTerm })
                    }
                }
                this.yearArr = yearArr;
                this.initGrade();
            })
        },
        methods: {
            bindPickerChange: function(e) {
                console.log(this.yearArr[e.detail.value].value);
                var stuYear = this.yearArr[e.detail.value].value;
                var query = (stuYear === "" ? "" : "/" + stuYear);
                this.showSelect = this.yearArr[e.detail.value].show;
                this.index = e.detail.value
                this.getGradeRemote(query);
            },
            initGrade:function() {
                var stuYear = this.showSelect = uni.$app.data.curTerm;
                var query = (stuYear === "" ? "" : "/" + stuYear);
                this.getGradeRemote(query);
            },
            getGradeRemote: async function(query) {
                var res = await uni.$app.request({
                    load: 2,
                    throttle: true,
                    url: uni.$app.data.url + "sw/grade" + query,
                })
                if(!res.data.data) {
                    uni.$app.toast("加载失败，请重试");
                    return void 0;
                }
                if (res.data.data[0]) {
                    var info = res.data.data;
                    var point = 0;
                    var pointN = 0;
                    var pointW = 0;
                    var n = 0;
                    info.forEach(function(value) {
                        if (value.kclbmc !== "公选") {
                            n++;
                            point += value.xf;
                            if (value.zcj === "优") {
                                pointN += 4.5;
                                pointW += (4.5 * value.xf);
                            } else if (value.zcj === "良") {
                                pointN += 3.5;
                                pointW += (3.5 * value.xf);
                            } else if (value.zcj === "中") {
                                pointN += 2.5;
                                pointW += (2.5 * value.xf);
                            } else if (value.zcj === "及格") {
                                pointN += 1.5;
                                pointW += (1.5 * value.xf);
                            } else if (value.zcj === "不及格") {} else {
                                var s = parseInt(value.zcj);
                                if (s >= 60) {
                                    pointN += ((s - 50) / 10);
                                    pointW += (((s - 50) / 10) * value.xf);
                                }
                            }
                        }
                    })
                    this.point = point;
                    this.pointN = (pointN / n).toFixed(2);
                    this.pointW = (pointW / point).toFixed(2);
                }
                let defaultValue = {kclbmc: "暂无",kcmc: this.showSelect+"学期暂无成绩",ksxzmc: "暂无成绩",xf: 0,zcj: "100"}
                this.grade = !res.data.data[0] ? [defaultValue] : res.data.data;
                this.ad = !res.data.data[0] ? false : true;
                this.show = true;
            },
            adError: function(e) {
                this.ad = false;
            }
        }
    }
</script>

<style scoped>
    .select-con {
        display: flex;
        justify-content: space-between;
        padding: 15px 0 7px 0;
    }

    .over-unit {
        margin: 0 3px;
    }
    
    .unit {
        padding: 3px 0 ;
        display: flex;
        justify-content: space-between;
    }

    .c-name {
        font-size: 14px;
    }

    .info-right {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .info-left {
        display: flex;
        flex-direction: column;
        line-height: 21px;
    }

    .cgrade {
        font-size: 20px;
        color: #569FD1;
    }
</style>
