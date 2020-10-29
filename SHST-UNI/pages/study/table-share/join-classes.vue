<template>
    <view>
        <layout>
            <view class="">
                <view class="a-input-con-line a-lmb">
                    <view class="a-label">课程名:</view>
                    <input class="a-input" v-model="className" placeholder="请输入(可选)" />
                </view>
                <view class="a-input-con-line a-lmb">
                    <view class="a-label">教师名:</view>
                    <input class="a-input" v-model="teacherName" placeholder="请输入(可选)" />
                </view>
                <view class="a-btn a-btn-blue x-full" @click="confirm()">确定</view>
            </view>
        </layout>

        <view v-if="show" class="class-con">
            <view v-for="(item,index) in computedClasses" :key="index">
                <layout>
                    <view class="a-flex-space-between">
                        <view>
                            <view class="classname">{{item.class_name}}</view>
                            <view class="a-lmt">{{item.teacher}}</view>
                            <view class="a-flex a-lmt">
                                <view >第{{item.classWeek}}周</view>
                                <view class="a-lml">{{item.week}}</view>
                                <view class="a-lml">第{{item.start}}</view>
                            </view>
                        </view>
                        <view class="y-center">
                            <view class="x-center right-con">
                                <view class="classroom">{{item.classroom}}</view>
                                <view class="a-lmt">{{item.date_start}}</view>
                            </view>
                        </view>

                    </view>

                </layout>
            </view>

            <layout>
                <loading :loading="loading" @click="loadClasses(page+1)" ></loading>
            </layout>

        </view>


        <layout>
            <view class="tips-con">
                <view>提示：</view>
                <view>1. 该数据为2020-2021-1学期数据，数据仅供参考。</view>
                <view>2. 数据为根据课程信息整理，某些课程信息收录不全。</view>
                <view>3. 当一门课同时有两位老师授课时，不作检索。</view>
            </view>
        </layout>

    </view>
</template>

<script>
    import util from "@/modules/datetime";
    import loading from "@/components/loading/loading.vue";
    import advertise from "@/components/advertise/advertise.vue";
    export default {
        components:{
            loading, advertise
        },
        data: function() {
            return {
                page: 1,
                className: "",
                teacherName: "",
                classes: [],
                show: false,
                loading: "loadmore"
            }
        },
        beforeCreate: function() {},
        created: function() {},
        filters: {},
        computed: {
            computedClasses: function(){
                var week = ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"];
                var startClass = ["01-02节","03-04节","05-06节","07-08节","09-10节"];
                return this.classes.map(v => {
                    v.week = week[v.day_of_week];
                    v.start = startClass[v.turn_index];
                    v.classWeek = ~~(util.dateDiff(uni.$app.data.curTermStart,
                        util.formatDate(undefined, new Date(v.date_start))) / 7) + 1;
                    return v;
                })
            }
        },
        methods: {
            confirm: function(){
                this.classes = [];
                this.page = 1;
                this.loadClasses(1);
            },
            loadClasses: function(page){
                uni.$app.throttle(500, async () => {
                    this.loading = "loading";
                    this.page = page;
                    if(!this.className && !this.teacherName) {
                        uni.$app.toast("请至少输入一个搜索项");
                        return void 0;
                    }
                    var data = {};
                    if(this.className) data["classname"] = this.className;
                    if(this.teacherName) data["teacher"] = this.teacherName;
                    var res = await uni.$app.request({
                        load: 2,
                        throttle: true,
                        url: uni.$app.data.url + `/sw/loadclasses/${page}`,
                        data: data
                    })
                    this.show = true;
                    this.classes = this.classes.concat(res.data.info);
                    this.$nextTick(() => {
                        if(res.data.info.length >= 5) this.adShow = true;
                    });
                    if(res.data.info.length < 10) this.loading = "nomore";
                    else this.loading = "loadmore";
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .a-label{
        font-size: 12px;
    }
    .class-con{
        color: #aaa;
    }
    .classname{
        color: #333;
        font-size: 15px;
    }
    .classroom{
        color: $a-blue;
        font-size: 18px;
    }
    .right-con{
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>
