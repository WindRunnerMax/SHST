<template>
    <view>

        <list
            mine
            :list="list"
            :page="page"
            :loadStatus="loadStatus"
            :activeIndex="activeIndex"
            @load-next="loadNext"
            @jump="jump"
        ></list>

    </view>
</template>

<script>
    import {registerCheck} from "@/vector/pub-fct.js";
    import list from "../components/list.vue";
    export default {
        components: {list},
        data: () => ({
            activeIndex: 0,
            list: [],
            page: 1,
            loadStatus: "loadmore"
        }),
        beforeCreate: function() {},
        created: function() {
            uni.$app.onload(async () =>  this.loadNext(0, 1));
            uni.$app.eventBus.on("RefreshMyList", this.refresh);
        },
        beforeDestroy: function(){
            uni.$app.eventBus.off("RefreshMyList", this.refresh);
        },
        onPullDownRefresh: function(){
            this.refresh(0);
            uni.stopPullDownRefresh();
        },
        filters: {},
        computed: {},
        methods: {
            refresh: function(index){
                this.list = [];
                this.page = 1;
                this.loadNext(index, this.page);
            },
            loadNext: async function(type, page){
                this.loadStatus="loading";
                this.page = page;
                let res = await uni.$app.request({
                    load: 3,
                    url: `${uni.$app.data.url}/news/getMyNews/${page}`,
                })
                this.list = this.list.concat(res.data.list.map(v => {
                    v.img_url = v.img_url.split(",");
                    v.nick_name = res.data.user.nick_name;
                    v.avatar_url = res.data.user.avatar_url;
                    return v;
                }));
                if(res.data.list.length < 10) this.loadStatus="nomore";
                else this.loadStatus="loadmore";
            },
            jump: function(id){
                this.nav("./my-detail?id=" + id);
            }
        }
    }
</script>

<style lang="scss">
     page{
         padding: 10px;
         box-sizing: border-box;
         background-color: $a-background;
     }
</style>
