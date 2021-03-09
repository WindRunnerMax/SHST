<template>
    <view>

        <layout title="图书信息">
            <view class="y-center">
                <view class="img-cotainer a-lmr a-flex-none">
                    <image
                        class="img-cotainer x-center y-center"
                        :src="book.img"
                    ></image>
                </view>
                <view class="tips-con">
                    <view class="a-fontsize-16">{{book.name}}</view>
                    <view class="a-color-grey">{{book.detail[0]}}</view>
                    <view class="a-color-grey">{{book.detail[1]}}</view>
                    <view class="a-color-grey">{{book.detail[2]}}</view>
                </view>
            </view>
        </layout>

        <layout title="馆藏信息">
            <view class="tips-con">
                <view v-for="(item,index) in book.stroage" :key="index">
                    <view v-if="index % 5 === 0 && index !== 0" class="a-gap-15"></view>
                    <view>{{item}}</view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    import {regMatch} from "@/modules/regex";
    export default {
        data: () => ({
            book: {
                name: "",
                img: "",
                detail: [],
                stroage: []
            }
        }),
        onLoad: async function(option) {
            this.book.img = uni.$app.data.tmp.book.img;
            uni.$app.data.tmp.book = null;
            uni.$app.onload(async ()=>{
                let res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/lib/detail",
                    throttle: true,
                    data: {
                        id: option.id
                    },
                })
                this.book.name = regMatch(/<h2>(.*?)<\/h2>/g,res.data.info)[0];
                let detail = [];
                regMatch(/<tr><td>([\S]*?)<\/?td><\/tr>/g,res.data.info).forEach(value => detail.push(value));
                let liBookInfo = regMatch(/<ul data-role="listview">[\s\S]*?<\/ul>/g,res.data.info)[0];
                let stroage = regMatch(/<p.*?>(.*?)<\/p>/g, liBookInfo);
                this.book.detail = detail;
                this.book.stroage = stroage;
            })
        },
        methods: {

        }
    }
</script>

<style scoped>
    .img-cotainer{
        width: 70px;
        height: 90px;
        padding: 5px;
        overflow: hidden;
    }
</style>
