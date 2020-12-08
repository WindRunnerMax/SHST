<template>
    <view>

        <layout title="添加课程" v-if="edit !== -2">
            <view class="a-flex">
                <view class="a-input-con-line a-mb y-center a-mt a-flex-full">
                    <view >课程名:</view>
                    <input class="a-input" v-model="unit.className" placeholder="必填" />
                </view>
                <view class="a-input-con-line a-mb y-center a-flex-full">
                    <view >教室:</view>
                    <input class="a-input" v-model="unit.classroom" placeholder="必填" />
                </view>
            </view>
            <view class="a-flex">
                <view class="a-input-con-line a-mb y-center a-flex-full">
                    <view >教师名:</view>
                    <input class="a-input" v-model="unit.teacherName" placeholder="选填" />
                </view>
                <view class="a-input-con-line a-mb y-center a-flex-full">
                    <picker class="x-full"
                        @change="dayPickerChange"
                        :value="unit.day.index"
                        :range="unit.day.range">
                        <view class="a-flex-space-between y-center">
                            <view>日期:</view>
                            <view class="y-center">
                                <view>周 {{unit.day.range[unit.day.index]}}</view>
                            </view>
                            <view></view>
                        </view>
                    </picker>
                </view>
            </view>
            <view class="a-flex">
                <view class="a-input-con-line a-mb a-flex-full">
                    <picker class="x-full"
                        mode="multiSelector"
                        @change="weekPickerChange"
                        :value="unit.week.index"
                        :range="unit.week.range">
                        <view class="a-flex-space-between y-center">
                            <view>周次:</view>
                            <view class="y-center">
                                <view>第 {{unit.week.range[0][unit.week.index[0]]}}</view>
                                <view class="a-lml a-lmr">-</view>
                                <view>{{unit.week.range[1][unit.week.index[1]]}} 周</view>
                            </view>
                            <view></view>
                        </view>
                    </picker>
                </view>
                <view class="a-input-con-line a-mb a-flex-full">
                    <picker class="x-full"
                        mode="multiSelector"
                        @change="classPickerChange"
                        :value="unit.time.index"
                        :range="unit.time.range">
                        <view class="a-flex-space-between y-center">
                            <view>时间:</view>
                            <view class="a-flex">
                                <view>第 {{unit.time.range[0][unit.time.index[0]]}}</view>
                                <view class="a-lml a-lmr">-</view>
                                <view>{{unit.time.range[1][unit.time.index[1]]}} 节</view>
                            </view>
                            <view></view>
                        </view>
                    </picker>
                </view>
            </view>
            <view class="a-flex">
                <view class="a-btn a-btn-blue a-flex-full a-lmt" @click="save(edit)">{{edit === -1 ? "添加" : "保存"}}</view>
                <view class="a-btn a-btn-orange a-flex-full a-lmt a-lml" @click="clear()">重置</view>
            </view>
        </layout>

        <headslot title="课表管理">
            <view class="a-lmr y-full y-center">
                <view class="iconfont icon-jia" @click="edit = -1"></view>
            </view>
        </headslot>

        <view class="a-lmt"></view>

        <view>
            <view v-for="(item, index) in tables" :key="index">
                <layout>
                    <view class="a-flex-space-between classes-con">
                        <view>
                            <view class="classname">{{item.className}}{{edit === index ? "[编辑中]": ""}}</view>
                            <view class="a-lmt">{{item.term}}</view>
                            <view class="a-lmt">第{{item.weekStart}} - {{item.weekEnd}}周</view>
                            <view class="a-flex a-lmt">
                                <view>周{{item.day}}</view>
                                <view class="a-lml">第{{item.timeStart}} - {{item.timeEnd}}节</view>
                            </view>
                        </view>
                        <view class="y-center">
                            <view class="x-center right-con">
                                <view class="classroom">{{item.classroom}}</view>
                                <view class="a-lmt">{{item.teacherName}}</view>
                                <view class="a-flex a-lmt">
                                    <view class="iconfont icon-bianji" @click="editUnit(index)"></view>
                                    <view class="iconfont icon-x a-lml" @click="deleteUnit(index)"></view>
                                </view>
                            </view>
                        </view>
                    </view>
                </layout>
            </view>
        </view>

        <layout v-if="!tables.length">
            <view class="y-center">
                <view class="a-dot" style="background: #eee;"></view>
                <view>暂无课表数据</view>
            </view>
        </layout>

        <layout v-show="operate">
            <view class="a-flex">
                <view class="a-btn a-btn-blue x-full" @click="submit()">保存</view>
                <view class="a-btn a-btn-orange x-full a-lml" @click="clearAll()">清空</view>
            </view>
        </layout>

        <layout>
            <view class="tips-con">
                <view>注意：</view>
                <view>1. 课表目前只能添加数据，不能对原课表进行删除操作。</view>
                <view>2. 进行的操作必须在最后进行保存，否则操作会丢失。</view>
                <view>3. 数据以缓存形式保存在本地，清理缓存会导致数据丢失。</view>
            </view>
        </layout>

    </view>
</template>

<script>
    import headslot from "@/components/headslot/headslot.vue";
    import {propsCopy} from "@/modules/copy.js";
    export default {
        components: { headslot },
        data: () => ({
            edit: -2,
            unit:{
                className: "",
                teacherName: "",
                classroom: "",
                week: {
                    index: [0, 1],
                    range: [[...new Array(20).keys()], [...new Array(20).keys()]]
                },
                day: {
                    index: 0,
                    range: [1, 2, 3, 4, 5, 6, 7]
                },
                time: {
                    index: [0, 0],
                    range: [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]]
                },

            },
            tables: [],
            operate: false
        }),
        beforeCreate: function() {
            this.maper = {
                className: "cn",
                classroom: "cr",
                term: "t",
                day: "d",
                teacherName: "tn",
                weekStart: "ws",
                weekEnd: "we",
                timeStart: "ts",
                timeEnd: "te",
            };
        },
        created: function() {
            uni.$app.onload(async () => {
                var res = await uni.$app.request({
                    load: 2,
                    url: uni.$app.data.url + "/sw/getCustomTable",
                })
                let data = JSON.parse(res.data.info)
                this.tables = data.map(v => {
                    let tmp = {};
                    for(let key in this.maper) tmp[key] = v[this.maper[key]];
                    return tmp;
                })
                if(this.tables.length > 0) this.operate = true;
            })
        },
        filters: {},
        computed: {},
        methods: {
            weekPickerChange: function(e){
                let select = e.detail.value;
                if(select[0] > select[1]) select[1] = select[0];
                this.unit.week.index = select;
            },
            classPickerChange: function(e){
                let select = e.detail.value;
                if(select[0] > select[1]) select[1] = select[0];
                this.unit.time.index = select;
            },
            dayPickerChange: function(e){
                this.unit.day.index = e.detail.value;
            },
            save: function(mode){
                let unit = {};
                let tmp = this.unit;
                propsCopy(unit, tmp, "className", "classroom");
                unit.term = uni.$app.data.curTerm;
                unit.teacherName = tmp.teacherName || "无";
                unit.weekStart = tmp.week.range[0][tmp.week.index[0]];
                unit.weekEnd = tmp.week.range[1][tmp.week.index[1]];
                unit.timeStart = tmp.time.range[0][tmp.time.index[0]];
                unit.timeEnd = tmp.time.range[1][tmp.time.index[1]];
                unit.day = tmp.day.range[tmp.day.index];
                if(!unit.className || !unit.classroom){
                    uni.$app.toast("请将数据填写完整");
                    return void 0;
                }
                if(mode === -1){
                    this.tables.push(unit);
                    this.operate = true;
                }else{
                    this.tables[mode] = unit;
                    this.tables = this.tables.slice();
                    this.edit = -1;
                    this.clear();
                }
            },
            editUnit: function(index){
                this.edit = index;
                let unit = this.tables[index];
                propsCopy(this.unit, unit, "className", "teacherName", "classroom");
                this.unit.week.index = [unit.weekStart, unit.weekEnd];
                this.unit.time.index = [unit.timeStart - 1, unit.timeEnd - 1];
                this.unit.day.index = [unit.day - 1];
                uni.pageScrollTo({ scrollTop: -1 });
            },
            deleteUnit: async function(index){
                var [err,choice] = await uni.showModal({
                    title: "提示",
                    content: "确定要删除吗？",
                })
                if (choice.confirm) {
                    this.tables.splice(index, 1);
                }
            },
            submit: function(){
                let data = this.tables.map(v => {
                    let tmp = {};
                    for(let key in this.maper) tmp[this.maper[key]] = v[key];
                    return tmp;
                })
                uni.$app.throttle(1000, async () => {
                    var res = await uni.$app.request({
                        load: 2,
                        method: "POST",
                        url: uni.$app.data.url + "/sw/setCustomTable",
                        data:{
                            data: JSON.stringify(data)
                        }
                    })
                    uni.$app.toast("保存成功");
                    this.edit = -2;
                    uni.$app.eventBus.commit("RefreshTable", uni.$app.data.curWeek);
                })
            },
            clear: function(){
                this.unit = {
                    className: "",
                    teacherName: "",
                    classroom: "",
                    week: {
                        index: [0, 1],
                        range: [[...new Array(20).keys()], [...new Array(20).keys()]]
                    },
                    day: {
                        index: 0,
                        range: [1, 2, 3, 4, 5, 6, 7]
                    },
                    time: {
                        index: [0, 0],
                        range: [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]]
                    }
                }
            },
            clearAll: function(){
                this.edit = -2;
                this.clear();
                this.tables = [];
            }
        }
    }
</script>

<style lang="scss" scoped>
    .a-btn{
        margin: 0;
    }
    .classes-con{
        color: #aaa;
    }
    .classname{
        color: #333;
        font-size: 15px;
    }
    .classroom{
        color: $a-blue;
        font-size: 18px;
    }
    .right-con{
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .iconfont{
        color: #aaa;
    }
    .icon-jia{
        font-size: 13px;
    }
</style>
