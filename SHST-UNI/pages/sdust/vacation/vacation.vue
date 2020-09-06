<template>
    <view>
        
        <list title="节假日安排"></list>
        <view v-for="(item,index) in data" :key="index">
            <layout>
                <view class="fline">
                    <view class="a-dot" v-bind:style="{background: colorList[index % colorList.length]}"></view>
                    <view>{{item.name}}</view>
                    <view>{{item.v_time}}</view>
                </view>
                <view class="sline">{{item.info}}</view>
            </layout>
        </view>
        
    </view>
</template>

<script>
    export default {
        data: function() {
            return {
                show: 0,
                data: [],
                colorList: uni.$app.data.colorList
            }
        },
        created: function() {
            uni.$app.onload(async () => {
                var res = await uni.$app.request({
                    load: 2,
                    throttle: true,
                    url:uni.$app.data.url + "/ext/vacation",
                })
                this.data = res.data.info;
                this.show = 1;
            })
        },
        methods: {
            
        }
    }
</script>

<style>
    .fline{
        display: flex;
        align-items: center;
    }
    .fline view{
        margin: 5px;
        font-size: 14px;
    }
    .sline{
        margin: 1px 23px;
    }
</style>
