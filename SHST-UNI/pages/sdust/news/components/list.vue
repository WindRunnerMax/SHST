<template>
    <view>

        <view v-if="list.length">
            <view v-for="(item, index) in list" :key="item.id">
                <layout>
                    <view class="y-center a-flex-space-between">
                        <view class="y-center">
                            <image class="a-ml avatar-unit" :src="item.avatar_url"></image>
                            <view class="a-ml a-pl">{{item.nick_name}}</view>
                            <view class="a-ml a-pl a-color-blue">{{item.user_type | userFilter}}</view>
                        </view>
                    </view>
                    <view class="a-lmt" @click="$emit('jump', item.id)">
                        <view >
                            <view>
                                <view class="a-color-grey">{{item.title}}</view>
                                <view class="a-lmt a-lmt a-fontsize-13 a-color-grey tips-con">{{item.content}}</view>
                            </view>
                            <view v-if="item.host" class="thumbnail a-lmr a-lmt">
                                <image
                                    class="x-full y-full"
                                    :src="item.host+'public/upload/'+item.img_url[0]"
                                    lazy-load
                                    mode="aspectFill"
                                >
                                </image>
                            </view>
                        </view>

                        <view class="a-flex-space-between a-lmt a-pt">
                            <view class="a-btn a-btn-blue a-btn-mini a-btn-cicle a-btn-blue-plain ">
                                <view class="y-center">
                                    <view class="iconfont icon-huati1 a-mr"></view>
                                    <view >{{item.type | typeFilter}}</view>
                                </view>
                            </view>
                            <view class="y-center a-color-grey a-fontsize-13">
                                <view class="y-center a-lmr">
                                    <view class="iconfont icon-chakan a-fontsize-16"></view>
                                    <view class="a-ml">{{item.look_over}}</view>
                                </view>
                                <view class="y-center a-lmr">
                                    <view class="iconfont icon-dianzan a-fontsize-16"></view>
                                    <view class="a-ml">{{item.praise}}</view>
                                </view>
                                <view class="y-center a-lmr">
                                    <view class="iconfont icon-pinglun a-fontsize-16"></view>
                                    <view class="a-ml">{{item.review}}</view>
                                </view>
                            </view>
                        </view>

                        <view v-if="mine">
                            <view class="a-hr"></view>
                            <view class="a-flex-space-between a-lmt" >
                                <view>当前状态</view>
                                <view class="a-color-orange">{{item.status | statusFilter}}</view>
                            </view>
                            <view class="a-flex-space-between a-lmt a-lmb" >
                                <view>发布时间</view>
                                <view>{{item.create_time}}</view>
                            </view>
                            <view class="a-hr"></view>
                        </view>
                    </view>
                </layout>
            </view>
            <layout>
                <loading :loading="loadStatus" @click="this.$emit('loadNext', activeIndex, page+1)"></loading>
            </layout>
        </view>
        <layout v-else>
            <view class="y-center">
                <view class="a-dot a-background-grey"></view>
                <view>空空如也</view>
            </view>
        </layout>

    </view>
</template>

<script>
    import {share} from "@/vector/pub-fct.js";
    import loading from "@/components/loading/loading.vue";
    export default {
        name: "list",
        components: { loading },
        data: () => ({

        }),
        props: ["list", "page", "loadStatus", "activeIndex", "mine"],
        beforeCreate: function() {},
        created: function() {},
        filters: {
            statusFilter: status => {
                switch(status){
                    case 0: return "审核中";
                    case 1: return "审核通过";
                    case -1: return "审核拒绝";
                }
            },
            userFilter: (type) => {
                type = Number(type);
                switch(type){
                    case 1: return "开发者";
                    case 2: return "管理员";
                }
                return "";
            },
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
            }
        },
        computed: {},
        methods: {}
    }
</script>

<style lang="scss" scoped>
    .thumbnail{
        width: 120px;
        height: 120px;
        overflow: hidden;
        border-radius: 3px;
    }
    .avatar-unit{
        overflow: hidden;
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
    .iconfont{
        font-size: 12px;
    }
</style>
