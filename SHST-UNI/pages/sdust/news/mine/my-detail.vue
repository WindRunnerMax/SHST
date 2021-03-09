<template>
    <view>

        <detail
            mine
            :post="post"
            :review="review"
            @save-post="savePost"
            @save-review="saveReview"
        >
            <view class="a-flex-space-between a-lmt">
                <view>当前状态</view>
                <view class="status">{{post.status | statusFilter}}</view>
            </view>
            <view class="a-flex-space-between a-lmt">
                <view>审核时间</view>
                <view>{{post.audit_time || "未审核"}}</view>
            </view>
            <view class="a-flex-space-between a-lmt">
                <view>审核信息</view>
                <view>{{post.message}}</view>
            </view>
            <view class="a-hr"></view>
            <view class="y-center a-lmt a-flex-space-between">
                <view></view>
                <view class="y-center ">
                    <view class="a-btn a-btn-blue " @click="edit(post.id)">编辑内容</view>
                    <view class="a-btn a-btn-orange" @click="deleteUnit(post.id)">删除帖子</view>
                </view>
            </view>
        </detail>

    </view>
</template>

<script>
    import detail from "../components/detail.vue";
    import {propsCopy} from "@/modules/copy.js";
    export default {
        components: {detail},
        data: () => ({
            post: {
                id: 0,
                content: "",
                host: "",
                imgs: [],
                type: "",
                create_time: "",
                message: "",
                audit_time: ""
            },
            review: []
        }),
        beforeCreate: function() {
            this.refreshList = false;
        },
        beforeDestroy:function(){
            if(this.refreshList) uni.$app.eventBus.commit("RefreshMyList");
        },
        onLoad: function(option) {
            if(option.id) {
                uni.$app.onload(async () => {
                    const res = await uni.$app.request({
                        load: 3,
                        url: `${uni.$app.data.url}/news/getMyDetail/${option.id}`,
                    })
                    this.post = res.data.detail;
                    this.post.imgs = res.data.detail.img_url ? this.post.imgs = res.data.detail.img_url.split(",") : [];
                    this.post.id = Number(option.id);
                    this.post.praised = res.data.praised;
                    this.review = res.data.review;
                })
            }
        },
        filters: {
            statusFilter: status => {
                switch(status){
                    case 0: return "审核中";
                    case 1: return "审核通过";
                    case -1: return "审核拒绝";
                }
            }
        },
        computed: {},
        methods: {
            edit: function(id){
                uni.$app.data.tmp.post = propsCopy({}, this.post, "id", "content", "type");
                uni.$app.data.tmp.post.imgs = this.post.imgs.map(v => this.post.host+"public/upload/"+v);
                uni.$app.data.tmp.post.paths = this.post.imgs.concat([]);
                this.nav("/pages/sdust/news/new-post/new-post", "redirect");
            },
            deleteUnit: function(){
                uni.$app.throttle(1000, async () => {
                    let [err,choice] = await uni.showModal({
                        title: "提示",
                        content: "确定要删除吗？",
                    })
                    if(choice.confirm) {
                        let res = await uni.$app.request({
                            load: 3,
                            method: "POST",
                            url: `${uni.$app.data.url}/news/deletePost`,
                            data: {id: this.post.id}
                        })
                        this.refreshList = true;
                        this.nav(null, "back");
                    }
                })
            },
            savePost: function(post){
                this.post = post;
            },
            saveReview: function(review){
                this.review = review;
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
    .status{
        color: $a-orange;
    }
</style>
