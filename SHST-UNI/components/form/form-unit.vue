<template>
    <view >
        <view class="form-unit">
            <view v-if="row" class="y-center">
                <view :style="{width: width ? width + 'px': 'unset'}">{{label}}</view>
                <slot></slot>
            </view>
            <view v-if="!row">
                <view >{{label}}</view>
                <slot></slot>
            </view>
            <view v-if="show" class="err">{{msg}}</view>
        </view>
    </view>
</template>

<script>
    export default {
        components: {},
        props: {
            row: {
                type: Boolean,
                default: false
            },
            rule: {
                required: true,
                type: String,
            },
            label: {
                type: String,
                default: ""
            },
            width: {
                type: [Number, String],
                default: 0
            }
        },
        inject: ["info"],
        data: () => ({

        }),
        beforeCreate: function() {},
        filters: {},
        computed: {
            show: function(){
                return this.rule && this.info[this.rule] && this.info[this.rule].show;
            },
            msg: function(){
                return this.rule && this.info[this.rule] && this.info[this.rule].msg;
            }
        },
        methods: {}
    }
</script>

<style lang="scss" scoped>
    .form-unit{
        padding: 10px;
        border-bottom: 1px solid #EEEEEE;
    }
    .y-center{
        display: flex;
        align-items: center;
    }
    .err{
        color: red;
        margin-top: 10px;
    }
</style>
