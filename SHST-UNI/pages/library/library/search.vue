<template>
    <view>

        <layout title="图书检索">
            <view class="x-center y-center a-lmt a-mb">
                <input class="a-input a-lmr" v-model="book"></input>
                <view class="a-btn a-btn-blue" @click="query(1)">检索</view>
            </view>
        </layout>

        <view v-for="(item,index) in info" :key="index">
            <layout>
                <view class="a-flex-space-between" @click="viewDetail(index, 'detail?id='+item.id)" >
                    <view class="y-center a-overflow-hidden a-flex-full">
                        <view class="img-cotainer a-lmr a-flex-none">
                            <image
                                class="img-cotainer x-center y-center"
                                :src="item.img"
                            ></image>
                        </view>
                        <view class="book-info text-ellipsis">
                            <view class="a-fontsize-16 text-ellipsis">{{item.infoList[0]}}</view>
                            <view class="a-color-grey text-ellipsis" >{{item.infoList[1]}}</view>
                            <view class="a-color-grey text-ellipsis">{{item.infoList[2]}}</view>
                            <view class="a-color-grey text-ellipsis">{{item.infoList[3]}}</view>
                        </view>
                    </view>
                    <view class="x-center y-center a-flex-none a-ml-10 a-mr-10">
                        <view class="iconfont icon-arrow-right a-flex-none a-fontsize-18 a-color-grey"></view>
                    </view>
                </view>
            </layout>
        </view>

        <layout v-if="show">
            <view class="a-flex-space-between y-center">
                <view class="y-center">
                    <view @click="pre" class="a-btn a-btn-blue">上一页</view>
                    <view @click="next" class="a-btn a-btn-blue">下一页</view>
                </view>
                <view class="a-color-grey">{{pageInfo}}</view>
            </view>
        </layout>

        <layout title="Tips:" v-if="!show">
            <view class="tips-con">
                <view>1.图书馆的服务器挺容易崩溃的，如果出现External Error，那一般是学校图书馆崩溃了</view>
                <view>2.学校图书馆外网访问会定时关闭，正常使用时间大约是在 7:00-22:00</view>
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
                info: [],
        }),
        created: function() {
            uni.$app.onload(() => {
                let startTime = "07:00";
                let endTime = "22:30";
                let curTime = formatDate("hh:mm");
                if (startTime > curTime || curTime > endTime) uni.$app.toast("当前时间图书馆服务已关闭");
            })
        },
        methods: {
            query: async function(page) {
                if(!this.book) {
                    uni.$app.toast("请输入书籍信息");
                    return void 0;
                }
                let unImg = "https://img3.doubanio.com/f/shire/5522dd1f5b742d1e1394a17f44d590646b63871d/pics/book-default-lpic.gif";
                let param = "?q=" + this.book.replace(/\s/g, "") + "&page=" + page;
                let res = await uni.$app.request({
                    load: 2,
                    throttle: true,
                    url: uni.$app.data.url + "/lib/query" + param,
                })
                let isbnStr = "";
                let bookList = [];
                let pageInfo = regMatch(/[0-9][\S]*页/g, res.data.info);
                regMatch(/<li (onclick.*?>[\s\S]*?)<\/li>/g, res.data.info).forEach((value, index, array) => {
                    let listObject = {};
                    listObject.infoList = regMatch(/<em>(.*?)<\/em>/g,value);
                    listObject.id = regMatch(/javascript:bookDetail\(['"]\/opac\/m\/book\/(.*)['"]\)/g, value)[0];
                    let isbn = regMatch(/isbn="(.*?)"/g, value)[0];
                    listObject.isbn = isbn ? String(/\d+/.exec(isbn.replace(/-/g, ""))) : "";
                    listObject.img = unImg;
                    if(listObject.isbn) isbnStr = isbnStr + "," + listObject.isbn;
                    bookList.push(listObject);
                })
                console.log(bookList);
                this.info = bookList;
                this.page = res.data.page;
                this.pageInfo = pageInfo[0];
                this.show = true;
                res = await uni.$app.request({
                    load: 0,
                    url: uni.$app.data.url + "/lib/cover",
                    method: "POST",
                    data: { isbn: isbnStr }
                })
                if(!res.data.info) return void 0;
                const covers = JSON.parse(res.data.info);
                covers.result.forEach(img => {
                    bookList.forEach(book => {
                        if(book.isbn === img.isbn) book.img = img.coverlink;
                    })
                })
                this.bookList = bookList;
                console.log(bookList);
            },
            pre: function() {
                let curPage = Number(this.page);
                if (curPage <= 1) return void 0;
                this.query(curPage - 1);
                this.$nextTick(() => uni.pageScrollTo({scrollTop: 0, duration: 0}));
            },
            next: function() {
                let curPage = Number(this.page);
                this.query(curPage + 1);
                this.$nextTick(() => uni.pageScrollTo({scrollTop: 0, duration: 0}));
            },
            viewDetail: function(index, url){
                uni.$app.data.tmp.book = this.info[index];
                this.nav(url);
            }
        }
    }
</script>

<style scoped>
    .a-input{
        width: 160px;
        flex: none;
    }
    .book-info{
        line-height: 26px;
    }
    .img-cotainer{
        width: 70px;
        height: 90px;
        padding: 5px;
        overflow: hidden;
    }
</style>
