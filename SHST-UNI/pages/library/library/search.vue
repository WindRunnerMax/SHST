<template>
    <view>

        <layout title="图书检索">
            <view class="x-center y-center a-lmt a-mb">
                <input class="a-input" v-model="book"></input>
                <view class="a-btn a-btn-blue" @click="query">检索</view>
            </view>
        </layout>

        <view v-for="(item,index) in info" :key="index">
            <layout>
                <view class="a-flex-space-between" @click="nav('detail?id='+item.id)" >
                    <view class="left-info">
                        <view>{{item.infoList[0]}}</view>
                        <view>{{item.infoList[1]}}</view>
                        <view>{{item.infoList[2]}}</view>
                        <view>{{item.infoList[3]}}</view>
                    </view>
                    <view class="right-jump">
                        <view class="iconfont icon-arrow-right"></view>
                    </view>
                </view>
            </layout>
        </view>

        <layout v-if="show">
            <view class="operat">
                <view class="a-flex">
                    <view @click="pre" class="a-btn a-btn-blue">上一页</view>
                    <view @click="next" class="a-btn a-btn-blue">下一页</view>
                </view>
                <view>{{pageInfo}}</view>
            </view>
        </layout>

        <layout title="Tips:" v-if="!show">
            <view class="tips-con">
                <view>1.图书馆的服务器挺容易崩溃的，如果出现External Error，那一般是学校图书馆崩溃了</view>
                <view>2.学校图书馆外网访问好像会定时关闭，正常使用时间大约是在 7:00-22:00</view>
            </view>

        </layout>


    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    import {regMatch} from "@/modules/regex";
    export default {
        data: () => ({
                book: "",
                page: 1,
                show: false,
                pageInfo: "",
                info: []
        }),
        created: function() {
            uni.$app.onload(() => {
                var startTime = "07:00";
                var endTime = "22:30";
                var curTime = formatDate("hh:mm");
                if (startTime > curTime || curTime > endTime) uni.$app.toast("当前时间图书馆服务已关闭");
            })
        },
        methods: {
            query: async function(e) {
                var param = "?q=" + this.book;
                if (typeof(e) === "number") {
                    param += "&page=" + e;
                }
                var res = await uni.$app.request({
                    load: 2,
                    throttle: true,
                    url: uni.$app.data.url + "/lib/query" + param,
                })
                var bookList = [];
                var repx = /<li (onclick.*?>[\s\S]*?)<\/li>/g;
                var pageInfo = regMatch(/[0-9][\S]*页/g, res.data.info);
                regMatch(repx,res.data.info).forEach((value, index, array) => {
                    var listObject = {};
                    repx = /<em>(.*?)<\/em>/g;
                    listObject.infoList = regMatch(repx,value);
                    repx = /javascript:bookDetail\(['"]\/opac\/m\/book\/(.*)['"]\)/g;
                    listObject.id = regMatch(repx,value)[0];
                    bookList.push(listObject);
                })
                console.log(bookList);
                this.info = bookList;
                this.page = res.data.page;
                this.pageInfo = pageInfo[0];
                this.show = true;
            },
            pre: function() {
                var curPage = Number(this.page);
                if (curPage <= 1) return void 0;
                this.query(curPage - 1);
                this.$nextTick(() => uni.pageScrollTo({scrollTop: 0, duration: 0}));
            },
            next: function() {
                var curPage = Number(this.page);
                this.query(curPage + 1);
                this.$nextTick(() => uni.pageScrollTo({scrollTop: 0, duration: 0}));
            },
        }
    }
</script>

<style scoped>
    .a-input {
        align-self: center;
        border: 1px solid #eee;
        margin: 0;
        display: block;
        height: 23px;
        width: 150px;
        margin-right: 10px;
    }

    .left-info{
        line-height: 26px;
    }
    .left-info:first-line {
        font-size: 18px;
    }

    .right-jump {
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border-left: 1px solid #eee;
    }

    .operat {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
