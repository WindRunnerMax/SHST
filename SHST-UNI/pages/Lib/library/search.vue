<template>
    <view>

        <layout title="图书检索">
            <view style='display:flex;justify-content: center;margin: 10px 0 6px 0;'>
                <input class='a-input' @input="bookInput" :value="book"></input>
                <view class='a-btn a-btn-blue' @tap='query'>检索</view>
            </view>
        </layout>

        <view v-for="(item,index) in info" :key="index">
            <layout>
                <view style='display:flex;justify-content: space-between;' @tap='jump' :data-id="item.id">
                    <view class='leftInfo'>
                        <view>{{item.infoList[0]}}</view>
                        <view>{{item.infoList[1]}}</view>
                        <view>{{item.infoList[2]}}</view>
                        <view>{{item.infoList[3]}}</view>
                    </view>
                    <view class='rightJump'>
                        <view class='iconfont icon-arrow-right'></view>
                    </view>
                </view>
            </layout>
        </view>

        <layout v-if="show">
            <view class='operat'>
                <view style='display:flex;'>
                    <view @tap='pre' class='a-btn a-btn-blue'>上一页</view>
                    <view @tap='next' class='a-btn a-btn-blue'>下一页</view>
                </view>
                <view>{{pageInfo}}</view>
            </view>
        </layout>

        <layout title="Tips:" v-if="!show">
            <view class="card">
                <view>1.图书馆的服务器挺容易崩溃的，如果出现PARSE ERROR，那一般是学校图书馆崩溃了</view>
                <view>2.学校图书馆外网访问好像会定时关闭，正常使用时间大约是在 7:00-22:00</view>
            </view>

        </layout>


    </view>
</template>

<script>
    const app = getApp();
    import {formatDate} from "@/modules/datetime";
    import {regMatch} from "@/modules/regex";
    export default {
        data() {
            return {
                book: "",
                page: 1,
                show: 0,
                pageInfo: "",
                info: []
            }
        },
        onLoad: function(options) {
            var startTime = "07:00";
            var endTime = "22:30";
            var curTime = formatDate("hh:mm");
            if (startTime > curTime || curTime > endTime) {
                app.toast("当前时间图书馆服务已关闭");
                return false;
            }
        },
        methods: {
            bookInput: function(e) {
                this.book = e.detail.value
            },
            query: async function(e) {
                var param = "?q=" + this.book;
                if (typeof(e) === "number") {
                    param += "&page=" + e;
                }
                var res = await app.request({
                    load: 2,
                    url: app.globalData.url + "lib/query" + param,
                })
                try {
                    if (res.data.Message === "Yes") {
                        var bookList = [];
                        var repx = /<li (onclick.*?>[\s\S]*?)<\/li>/g;
                        var pageInfo = res.data.info.match(/[0-9][\S]*页/);
                        regMatch(repx,res.data.info).forEach((value, index, array) => {
                            var listObject = {};
                            repx = /<em>(.*?)<\/em>/g;
                            listObject.infoList = regMatch(repx,value);
                            repx = /javascript:bookDetail\('\/opac\/m\/book\/(.*)'\)/g;
                            listObject.id = regMatch(repx,value)[0];
                            bookList.push(listObject);
                        })
                        console.log(bookList)
                        this.info = bookList
                        this.page = res.data.page
                        this.pageInfo = pageInfo[0]
                        this.show = 1
                    } else {
                        app.toast("External Error");
                    }
                } catch (e) {
                    console.log(e);
                    app.toast("Internal Error");
                }


            },
            pre() {
                var curPage = parseInt(this.page);
                if (curPage <= 0) return;
                this.query(curPage - 1);
            },
            next() {
                var curPage = parseInt(this.page);
                this.query(curPage + 1);
            },
            jump(e) {
                if (!e.currentTarget.dataset.id) {
                    app.toast("出错了");
                    return;
                }
                uni.navigateTo({
                    url: "libDetail?id=" + e.currentTarget.dataset.id
                })
            }
        }
    }
</script>

<style>
    .a-input {
        align-self: center;
        border: 1px solid #eee;
        margin: 0;
        display: block;
        height: 23px;
        width: 150px;
        margin-right: 10px;
    }
    
    .leftInfo{
        line-height: 26px;
    }
    .leftInfo:first-line {
        font-size: 18px;
    }

    .info {
        margin-top: 3px;
    }

    .rightJump {
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

    .card {
        line-height: 27px;
    }
</style>
