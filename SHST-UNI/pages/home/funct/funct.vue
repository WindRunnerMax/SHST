<template>
    <view>

        <layout title="学习" color="#FF6347" :inherit-color="true">
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

        <layout title="信息" color="#3CB371;" :inherit-color="true">
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
                <!-- #endif -->

                <!-- #ifdef MP-QQ -->
                <navigator class="icon" target="miniProgram" app-id="1110074303" hover-class="none" version="release">
                    <i class="iconfont icon-lubiao-xf "></i>
                    <view>迎新专版</view>
                </navigator>
                <!-- #endif -->

            </view>
        </layout>

        <layout title="科大" color="#9F8BEC;" :inherit-color="true">
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

        <layout title="拓展" color="#6495ED" :inherit-color="true">
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

        <layout v-if="adShow" :topSpace="true">
            <!-- #ifdef MP-WEIXIN -->
            <ad unit-id="adunit-b82100ae7bddf4ad" @error="adError" class="adapt"></ad>
            <!-- #endif -->
            <!-- #ifdef MP-QQ -->
            <ad unit-id="001b7e7e765436c6351d8a6d693437d2" @error="adError" class="adapt"></ad>
            <!-- #endif -->
        </layout>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    export default {
        data: function() {
            return {
                adShow: 1,
                now: formatDate()
            }
        },
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
            },
            adError: function() {
                this.adShow = 0;
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
