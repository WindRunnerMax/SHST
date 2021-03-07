<template>
    <view>
        <view class="y-center top-container">
            <view class="option">
                <scroll-view scroll-x class="x-full" >
                    <view class="a-flex">
                        <view
                            class="tab-unit"
                            v-for="item in tabs"
                            :key="item.index"
                            @click="activeIndex !== item.index && switchTab(item.index)"
                        >
                            <view class="tab-line"></view>
                            <view class="tab-font" :class="{'tab-active-font': activeIndex === item.index}">{{item.name}}</view>
                            <view class="tab-line" :class="{'tab-active': activeIndex === item.index}"></view>
                        </view>
                    </view>
                </scroll-view>
                <view class="mask"></view>
            </view>
            <view class="y-center mine a-pl" @click="checkNav('/pages/sdust/news/mine/my-list')">
                <open-data class="avatar a-ml" type="userAvatarUrl"></open-data>
                <view class="a-ml">我的</view>
            </view>
        </view>

        <view class="new-post-container x-center y-center" @click="checkNav('/pages/sdust/news/new-post/new-post')">
            <view class="iconfont icon-jia"></view>
        </view>

        <view class="container">
            <layout v-show="overhead">
                <view class="tips-con">顶置: {{overhead}}</view>
            </layout>

            <list
                :list="list"
                :page="page"
                :loadStatus="loadStatus"
                :activeIndex="activeIndex"
                @load-next="loadNext"
                @jump="jump"
            ></list>

        </view>

    </view>
</template>

<script>
    import {registerCheck} from "@/vector/pub-fct.js";
    import list from "./components/list.vue";
    export default {
        components: {list},
        data: () => ({
            tabs: [{name: "全部", index: 0},
                   {name: "失物", index: 1},
                   {name: "招领", index: 2},
                   {name: "表白", index: 3},
                   {name: "二手", index: 4},
                   {name: "拼车", "index": 5},
                   {name: "其他", index: 6},
            ],
            overhead: "",
            activeIndex: 0,
            list: [],
            page: 1,
            loadStatus: "loadmore"
        }),
        beforeCreate: function() {},
        created: function() {
            uni.$app.onload(async () => {
                this.loadNext(this.activeIndex, 1);
                let res = await uni.$app.request({
                    url: `${uni.$app.data.url}/news/getOverhead`,
                })
                this.overhead = res.data.info;
            })
        },
        onPullDownRefresh: function(){
            this.switchTab(this.activeIndex);
            setTimeout(() => uni.stopPullDownRefresh(), 1000);
        },
        filters: {},
        computed: {},
        methods: {
            switchTab: function(index){
                this.activeIndex = index;
                this.list = [];
                this.page = 1;
                this.loadNext(index, this.page);
            },
            loadNext: async function(type, page){
                this.loadStatus="loading";
                this.page = page;
                let res = await uni.$app.request({
                    load: 3,
                    url: `${uni.$app.data.url}/news/getNews/${type}/${page}`,
                })
                this.list = this.list.concat(res.data.list.map(v => {
                    v.img_url = v.img_url.split(",");
                    return v;
                }));
                if(res.data.list.length < 10) this.loadStatus="nomore";
                else this.loadStatus="loadmore";
            },
            checkNav: function(...args){
                registerCheck(() => this.nav(...args));
            },
            jump: function(id){
                this.nav("/pages/sdust/news/post-detail/post-detail?id=" + id);
            }
        }
    }
</script>

<style lang="scss">
    page{
        padding: 0;
    }
    .top-container{
        background-color: $a-white;
        border-bottom: 1px solid #eee;
    }
    .container{
        padding: 10px;
        box-sizing: border-box;
    }
    .mask{
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 1px;
        background-color: #eee;
        opacity: 0.9;
        box-shadow: -1px 0 6px 1px #888888;
    }
    .option{
        overflow: hidden;
        position: relative;
        width: calc(100% - 75px);
    }
    .avatar{
        overflow: hidden;
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
    .tab-unit{
        white-space: nowrap;
    }
    .mine{
        width: 65px;
        white-space: nowrap;
    }
    .tab-line{
        height: 3px;
    }
    .tab-active{
        background-color: $a-blue;
    }
    .tab-font{
        padding: 10px 15px;
    }
    .tab-active-font{
        color: $a-blue;
    }
    .new-post-container{
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: $a-blue;
        color: $a-white;
        position: fixed;
        top: 86%;
        right: 30px;
    }
    .new-post-container > view{
        font-size: 20px;
    }

</style>
