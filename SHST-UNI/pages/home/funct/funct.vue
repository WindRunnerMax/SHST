<template>
    <view>

        <layout title="学习" color="#FF6347" inherit-color>
            <view class="y-center">
                <view class="icon" @click="jump('/pages/study/time-table/time-table', 1)">
                    <i class="iconfont icon-kebiao"></i>
                    <view>查课表</view>
                </view>
                <view class="icon"  @click="jump('/pages/study/classroom/classroom', 1)">
                    <i class="iconfont icon-classroom"></i>
                    <view>查教室</view>
                </view>
                <view class="icon" @click="jump('/pages/study/grade/grade', 1)">
                    <i class="iconfont icon-grade"></i>
                    <view>查成绩</view>
                </view>
                <view class="icon" @click="jump('/pages/study/table-share/table-share', 1)">
                    <i class="iconfont icon-fly"></i>
                    <view>共享课表</view>
                </view>
            </view>
        </layout>

        <layout title="信息" color="#3CB371;" inherit-color>
            <view class="y-center">
                <view class="icon" @click="jump('/pages/library/library/search', 0)">
                    <i class="iconfont icon-lib"></i>
                    <view>图书检索</view>
                </view>
                <view class="icon" @click="jump('/pages/library/borrow/borrow', 1)">
                    <i class="iconfont icon-borrow"></i>
                    <view>借阅查询</view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <navigator class="icon" target="miniProgram" app-id="wxa53da62a53aaddea" hover-class="none" version="release">
                    <i class="iconfont icon-lubiao-xf "></i>
                    <view>迎新专版</view>
                </navigator>
                <navigator class="icon" target="miniProgram" app-id="wx3e1205c6aa103080" hover-class="none" version="release">
                    <i class="iconfont icon-shujia "></i>
                    <view>二手教材</view>
                </navigator>
                <!-- #endif -->

                <!-- #ifdef MP-QQ -->
                <navigator class="icon" target="miniProgram" app-id="1110074303" hover-class="none" version="release">
                    <i class="iconfont icon-lubiao-xf "></i>
                    <view>迎新专版</view>
                </navigator>
                <!-- #endif -->

            </view>
        </layout>

        <layout title="科大" color="#9F8BEC;" inherit-color>
            <view class="y-center">
                <view class="icon" @click="jump('/pages/sdust/map/map', 0)">
                    <i class="iconfont icon-map"></i>
                    <view>嵙地图</view>
                </view>
                <view class="icon" @click="jump('/pages/sdust/calendar/calendar', 0)">
                    <i class="iconfont icon-calendar"></i>
                    <view>校历</view>
                </view>
                <view class="icon" @click="jump('/pages/sdust/vacation/vacation', 0)">
                    <i class="iconfont icon-vacation"></i>
                    <view>放假安排</view>
                </view>
                <view class="icon" @click="jump('/pages/sdust/camptour/index', 0)">
                    <i class="iconfont icon-nav"></i>
                    <view>校园导览</view>
                </view>
            </view>
        </layout>

        <layout title="拓展" color="#6495ED" inherit-color>
            <view class="y-center">
                <view class="icon" @click="jump('/pages/ext/link/link', 0)">
                    <i class="iconfont icon-link"></i>
                    <view>分享链接</view>
                </view>
                <!-- #ifndef MP-WEIXIN -->
                <view class="icon" @click="jump('/pages/ext/event/event', 0)">
                    <i class="iconfont icon-schedule"></i>
                    <view>待办管理</view>
                </view>
                <!-- #endif -->
                <view class="icon" @click="jump('/pages/ext/exam/exam', 1)">
                    <i class="iconfont icon-biji-copy"></i>
                    <view>考试安排</view>
                </view>
                <view class="icon" @click="jump('/pages/ext/card/card', 1)">
                    <i class="iconfont icon-xuehao"></i>
                    <view>校园卡</view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <navigator class="icon" target="miniProgram" app-id="wx219195cad731454f" hover-class="none" version="release">
                    <i class="iconfont icon-ku"></i>
                    <view>资料分享</view>
                </navigator>
                <!-- #endif -->
            </view>
        </layout>

        <layout v-if="adShow && adSelect !== -1" top-space>
            <!-- #ifdef MP-WEIXIN -->
            <advertise :ad-select="adSelect" @error="adShow = false"></advertise>
            <!-- #endif -->
            <!-- #ifdef MP-QQ -->
            <advertise :ad-select="0" @error="adShow = false"></advertise>
            <!-- #endif -->
        </layout>

        <layout title="数据" color="#FF6347" inherit-color>
            <view class="y-center">
                <view class="icon" @click="jump('/pages/study/classroom/search-classes', 1)">
                    <i class="iconfont icon-kebiao1"></i>
                    <view>教室课表</view>
                </view>
                <view class="icon"  @click="jump('/pages/study/table-share/join-classes', 1)">
                    <i class="iconfont icon-tubiao-"></i>
                    <view>蹭课查询</view>
                </view>
                <!-- <view class="icon" @click="jump('/pages/user/reward/reward', 0)">
                    <i class="iconfont icon-zanshang"></i>
                    <view>赞赏</view>
                </view> -->
                <view class="icon" @click="jump('/pages/sdust/notice/notice', 0)">
                    <i class="iconfont icon-gonggao1"></i>
                    <view>校内公告</view>
                </view>
                <button open-type="feedback" class="icon" style="color: inherit;" hover-class="none">
                    <i class="iconfont icon-bianji"></i>
                    <view>意见反馈</view>
                </button>
            </view>
        </layout>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    import advertise from "@/components/advertise/advertise.vue";
    export default {
        components:{
            advertise
        },
        data: () => ({
            adShow: true,
            now: formatDate(),
            adSelect: uni.$app.data.initData.adSelect
        }),
        methods: {
            jump: async function(path, check) {
                if (check === 1 && uni.$app.data.userFlag !== 1) {
                    var [err, choice] = await uni.showModal({
                        title: "提示",
                        content: "该功能需要绑定强智教务系统，是否前去绑定",
                    })
                    if (choice.confirm) {
                        uni.navigateTo({url: "/pages/home/auxiliary/login?status=E"})
                    }
                    return void 0;
                }
                uni.navigateTo({url: path});
            }
        }
    }
</script>

<style>
    .icon {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 5px 0;
    }

    .icon view {
        color: #000000;
    }

    .iconfont {
        font-size: 27px;
        color: inherit !important;
        margin: 10px 0;
    }
</style>
