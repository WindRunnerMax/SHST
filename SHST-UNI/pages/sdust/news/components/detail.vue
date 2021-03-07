<template>
    <view>

        <layout>
            <view class="y-center">
                <view class="y-center">
                    <image class="a-ml avatar-unit" :src="post.avatar_url"></image>
                    <view class="a-ml a-pl">{{post.nick_name}}</view>
                    <view class="a-ml a-pl a-color-blue">{{post.user_type | userFilter}}</view>
                </view>
            </view>
            <view class="a-color-grey a-fontsize-12 a-mt-6">{{post.create_time}}</view>
            <view class="a-lmt">
                <view class="">
                    <view class="a-lmt a-fontsize-13 a-color-grey tips-con">{{post.content}}</view>
                </view>
                <view class="a-flex-space-between">
                    <view
                        v-if="post.host"
                        v-for="(item, index) in 3"
                        :key="index"
                        class="thumbnail  a-lmt"
                        :class="{'a-lmr': item < 2}"
                        :style="{width: imgWidth + 'px', height: imgWidth + 'px'}"
                    >
                        <image
                            class="x-full y-full"
                            v-if="post.imgs[item]"
                            :src="post.host+'public/upload/'+post.imgs[item]"
                            lazy-load
                            mode="aspectFill"
                            @click="viewImage(post.host+'public/upload/'+post.imgs[item], fullPathArr)"
                        >
                        </image>
                    </view>
                </view>
                <view class="a-flex-space-between y-center a-mt-15">
                    <view class="y-center">
                        <view class="a-btn a-btn-blue a-btn-mini a-btn-cicle a-btn-blue-plain ">
                            <view class="y-center">
                                <view class="iconfont icon-huati1 a-mr tag-icon"></view>
                                <view >{{post.type | typeFilter}}</view>
                            </view>
                        </view>
                        <view v-if="!mine" class="a-btn a-btn-blue a-btn-mini a-btn-cicle a-btn-yellow-plain a-lml">
                            <button open-type="share" class="y-center">
                                <view class="iconfont icon-fenxiang a-mr tag-icon"></view>
                                <view >分享</view>
                            </button>
                        </view>
                    </view>
                    <view class="y-center a-color-grey">
                        <view class="y-center a-lmr" @click="parise">
                            <view class="iconfont icon-dianzan a-fontsize-16" :class="{'a-color-orange': post.praised}"></view>
                            <view class="a-ml">{{post.praise}}</view>
                        </view>
                        <view class="y-center a-lmr">
                            <view class="iconfont icon-chakan a-fontsize-16"></view>
                            <view class="a-ml">{{post.look_over}}</view>
                        </view>
                        <view class="y-center a-lmr">
                            <view class="iconfont icon-pinglun a-fontsize-16"></view>
                            <view class="a-ml a-fontsize-13 a-color-grey tips-con">{{post.review}}</view>
                        </view>
                    </view>
                </view>

            </view>
        </layout>

        <layout class="a-color-grey" v-if="mine">
            <slot></slot>
        </layout>

        <layout v-if="!mine" >
            <view class="y-center">
                <view class="iconfont icon-xiangqing a-fontsize-13 a-color-orange"></view>
                <view class="a-ml-6">请注意信息鉴别</view>
                <view class="a-link a-lml" @click="report(post.id, '帖子', post.content)">举报</view>
            </view>
        </layout>

        <layout class="a-color-grey">
            <view class="y-center">
                <view class="a-input-con-line a-flex-full">
                    <input v-model="reviewContent" placeholder="请输入留言"  class="x-full" />
                </view>
                <view class="a-btn a-btn-blue" @click="postReview(reviewContent)">写留言</view>
            </view>
            <view >
                <view v-for="(item, index) in review" :key="index" class="a-lmt" >
                    <view class="a-flex-space-between">
                        <view class="y-center">
                            <image class="a-ml avatar-unit" :src="item.avatar_url"></image>
                            <view class="a-ml a-pl">{{item.nick_name}}</view>
                            <view class="a-ml a-pl a-color-blue">{{item.user_type | userFilter}}</view>
                        </view>
                        <view class="y-center">
                            <view v-if="item.mine" class="a-link a-lml" @click="deleteReview(index)">删除</view>
                            <view class="a-link a-lml" @click="report(item.id, '评论', item.review)">举报</view>
                            <view class="a-lml"># {{item.series}}</view>
                        </view>
                    </view>
                    <view class="a-lmt a-lmb a-ml-6">{{item.review}}</view>
                    <view class="a-hr"></view>
                </view>
            </view>
        </layout>

    </view>
</template>

<script>
    export default {
        name: "detail",
        components: {},
        data: () => ({
            screen: {
                width: 0,
                height: 0
            },
            reviewContent: ""
        }),
        props: ["post", "mine", "review"],
        beforeCreate: function() {},
        created: function() {
            uni.getSystemInfo({
               success: res => {
                 //获取屏幕窗口高度和宽度
                 this.screen.height = res.windowHeight;
                 this.screen.width = res.windowWidth;
               },
             })
        },
        filters: {
            typeFilter: (type) => {
                type = Number(type);
                switch(type){
                    case 0: return "全部";
                    case 1: return "失物";
                    case 2: return "招领";
                    case 3: return "表白";
                    case 4: return "二手";
                    case 5: return "拼车";
                    case 6: return "其他";
                }
            },
            userFilter: (type) => {
                type = Number(type);
                switch(type){
                    case 1: return "开发者";
                    case 2: return "管理员";
                }
                return "";
            }
        },
        computed: {
            imgWidth: ($vm) => ($vm.screen.width < 100) ?  90 : ($vm.screen.width - 40) / 3,
            fullPathArr: ($vm) => $vm.post.imgs.map(v => $vm.post.host+"public/upload/"+v)
        },
        methods: {
            parise: function(){
                uni.$app.throttle(1000, async () => {
                    if(this.post.praised === null) return void 0;
                    const operate = this.post.praised ? "deletePraise" : "praise";
                    const res = await uni.$app.request({
                        url: `${uni.$app.data.url}/news/${operate}`,
                        load: 3,
                        method: "POST",
                        data: {id: this.post.id}
                    })
                    const copyPost = {...this.post};
                    if(operate === "praise"){
                        ++copyPost.praise;
                        copyPost.praised = true;
                    }else{
                        --copyPost.praise;
                        copyPost.praised = false;
                    }
                    this.$emit("save-post", copyPost);
                })
            },
            postReview: function(content){
                if(!uni.$app.data.userFlag){
                    uni.$app.toast("请先登录");
                    return void 0;
                }
                content = content.trim();
                uni.$app.throttle(1000, async () => {
                    if(!content){
                        uni.$app.toast("请输入内容");
                        return void 0;
                    }
                    if(content.length >= 100){
                        uni.$app.toast("评论内容不能大于100字");
                        return void 0;
                    }
                    const res = await uni.$app.request({
                        url: `${uni.$app.data.url}/news/review`,
                        load: 3,
                        method: "POST",
                        data: {
                            id: this.post.id,
                            comment: content
                        }
                    })
                    const copyReview = [].concat(this.review);
                    copyReview.unshift({
                        mine: true,
                        nick_name: res.data.info.nick_name,
                        avatar_url: res.data.info.avatar_url,
                        review: this.reviewContent,
                        series: res.data.info.series,
                    });
                    this.reviewContent = "";
                    const copyPost = {...this.post};
                    ++copyPost.review;
                    this.$emit("save-post", copyPost);
                    this.$emit("save-review", copyReview);
                });
            },
            deleteReview: function(index){
                uni.$app.throttle(1000, async () => {
                    const [err,choice] = await uni.showModal({
                        title: "提示",
                        content: "确定要删除吗？",
                    })
                    if (choice.confirm) {
                        const res = await uni.$app.request({
                            url: `${uni.$app.data.url}/news/deleteReview`,
                            load: 3,
                            method: "POST",
                            data: { id: this.post.id }
                        })
                        const copyReview = [].concat(this.review);
                        copyReview.splice(index, 1);
                        console.log(copyReview)
                        const copyPost = {...this.post};
                        --copyPost.review;
                        this.$emit("save-post", copyPost);
                        this.$emit("save-review", copyReview);
                    }
                });
            },
            report: async function(id, type, content){
                uni.$app.throttle(1000, async () => {
                    const [err,choice] = await uni.showModal({
                        title: "提示",
                        content: "确定要举报不当信息吗？",
                    })
                    if (choice.confirm) {
                        await uni.$app.request({
                            url: `${uni.$app.data.url}/news/report`,
                            load: 3,
                            method: "POST",
                            data: { id, type, content }
                        })
                        uni.$app.toast("举报成功，请等待管理员处理，感谢反馈");
                    }
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .thumbnail{
        width: 90px;
        height: 90px;
        overflow: hidden;
        border-radius: 3px;
    }
    .avatar-unit{
        overflow: hidden;
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
    .tag-icon{
        font-size: 12px;
    }
</style>
