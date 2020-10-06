<template>
    <view>
        <layout>
            <view class="y-center a-flex-space-around">
                <view class="select-con x-center">
                    <picker @change="floorSelectChange" :value="floorIndex" :range="floorGroup">
                        <view>{{floorGroup[floorIndex]}}</view>
                    </picker>
                </view>
                <view class="select-con x-center">
                    <picker @change="classroomSelectChange" :value="classroomIndex" :range="classroomGroup" >
                        <view>{{classroomGroup[classroomIndex]}}</view>
                    </picker>
                </view>
                <view class="a-btn a-btn-blue" @click="loadClassroom">确定</view>
            </view>
        </layout>

        <layout v-if="show" :title="classroomGroup[classroomIndex]" :topSpace="true">
            <view class="a-flex-space-between y-center a-lpl a-lpr">
                <view>周次：{{week}}</view>
                <view>
                    <view class="a-btn a-btn-blue a-btn-small a-lmr" @click="pre(week)">
                        <view class="iconfont icon-arrow-lift"></view>
                    </view>
                    <view class="a-btn a-btn-blue a-btn-small" @click="next(week)">
                        <view class="iconfont icon-arrow-right"></view>
                    </view>
                </view>
            </view>
            <view class="a-hr"></view>
            <view v-for="(item, itemIndex) in 5" :key="item">
                <view class="a-flex">
                    <view v-for="(inner, innerIndex) in 7" :key="inner" class="a-full">
                        <view v-if="table[innerIndex] && table[innerIndex][itemIndex]" class="timetable-hide"
                            :style="{'background':table[innerIndex][itemIndex].background}">
                            <view>{{table[innerIndex][itemIndex].class_name}}</view>
                            <view>{{table[innerIndex][itemIndex].teacher}}</view>
                            <view>{{table[innerIndex][itemIndex].date_start.replace(/\d{4}-/, "")}}</view>
                        </view>
                        <view v-else class="timetable-hide"></view>
                    </view>
                </view>
                <view class="a-hr"></view>
            </view>
        </layout>

        <layout>
            <view class="tips-con">
                <view>提示：</view>
                <view>1. 该数据为2020-2021-1学期数据，数据仅供参考。</view>
                <view>2. 数据为根据课程信息整理，某些教室信息收录不全。</view>
                <view>3. J5数据收录严重不全，请酌情对待。</view>
            </view>
        </layout>

        <layout v-show="show && adShow">
            <!-- #ifdef MP-WEIXIN -->
            <advertise :ad-select="3" :compatible="0" @error="adShow = false"></advertise>
            <!-- #endif -->
            <!-- #ifdef MP-QQ -->
            <advertise :ad-select="3" @error="adShow = false"></advertise>
            <!-- #endif -->
        </layout>

    </view>
</template>

<script>
    import advertise from "@/components/advertise/advertise.vue";
    export default {
        components:{
            advertise
        },
        data: function() {
            return {
                classroom_all: {},
                week: null,
                table: [],
                show: false,
                floorIndex: 0,
                classroomIndex: 0,
                adShow: true
            }
        },
        beforeCreate: function() {},
        created: function() {
            this.classroom_all = {
                "J1": ["J1-107", "J1-108", "J1-110", "J1-111", "J1-112", "J1-113", "J1-114", "J1-115", "J1-116",
                    "J1-117", "J1-118", "J1-119", "J1-120", "J1-121", "J1-122", "J1-123", "J1-124",
                    "J1-126", "J1-207", "J1-208", "J1-209", "J1-210", "J1-211", "J1-212", "J1-213",
                    "J1-214", "J1-216", "J1-217", "J1-218", "J1-219", "J1-220", "J1-221", "J1-222",
                    "J1-225", "J1-226", "J1-227", "J1-228", "J1-229", "J1-230", "J1-307", "J1-308",
                    "J1-309", "J1-310", "J1-311", "J1-312", "J1-313", "J1-314", "J1-317", "J1-318",
                    "J1-319", "J1-320", "J1-323", "J1-324", "J1-325", "J1-326", "J1-407", "J1-408",
                    "J1-409", "J1-410", "J1-411", "J1-412", "J1-413", "J1-414", "J1-417", "J1-419",
                    "J1-420", "J1-423", "J1-424", "J1-425", "J1-426", "J1-602"
                ],
                "J3": ["J3-103", "J3-104", "J3-106", "J3-107", "J3-115", "J3-116", "J3-118", "J3-202", "J3-203",
                    "J3-204", "J3-206", "J3-207", "J3-208", "J3-209", "J3-211", "J3-214", "J3-215",
                    "J3-216", "J3-218", "J3-219", "J3-220", "J3-222", "J3-303", "J3-304", "J3-306",
                    "J3-307", "J3-309", "J3-315", "J3-316", "J3-319", "J3-320", "J3-322", "J3-402",
                    "J3-404", "J3-406", "J3-407", "J3-415", "J3-416", "J3-422", "J3-504", "J3-506",
                    "J3-507", "J3-515", "J3-516", "J3-518"
                ],
                "J5": ["J5-208", "J5-302", "J5-308", "J5-402", "J5-408"],
                "J7": ["J7-103", "J7-104", "J7-105", "J7-106", "J7-107", "J7-108", "J7-111", "J7-112", "J7-113",
                    "J7-114", "J7-115", "J7-203", "J7-204", "J7-205", "J7-206", "J7-207", "J7-208",
                    "J7-209", "J7-210", "J7-211", "J7-212", "J7-213", "J7-214", "J7-215", "J7-217",
                    "J7-218", "J7-303", "J7-304", "J7-305", "J7-306", "J7-307", "J7-308", "J7-309",
                    "J7-310", "J7-311", "J7-312", "J7-313", "J7-314", "J7-315", "J7-317", "J7-318",
                    "J7-403", "J7-404", "J7-405", "J7-406", "J7-407", "J7-408", "J7-409", "J7-410",
                    "J7-411", "J7-412", "J7-413", "J7-414", "J7-415", "J7-417", "J7-418", "J7-504",
                    "J7-505", "J7-506", "J7-507", "J7-511", "J7-512", "J7-513", "J7-514"
                ],
                "J14": ["J14-103", "J14-105", "J14-107", "J14-109", "J14-111", "J14-113", "J14-119", "J14-121",
                    "J14-123", "J14-203", "J14-205", "J14-207", "J14-209", "J14-211", "J14-213", "J14-219",
                    "J14-221", "J14-223", "J14-302", "J14-303", "J14-304", "J14-305", "J14-306", "J14-307",
                    "J14-308", "J14-309", "J14-310", "J14-311", "J14-312", "J14-313", "J14-314", "J14-316",
                    "J14-318", "J14-319", "J14-320", "J14-321", "J14-323", "J14-324", "J14-326", "J14-328",
                    "J14-330", "J14-332", "J14-334", "J14-336", "J14-338", "J14-340", "J14-342", "J14-402",
                    "J14-403", "J14-404", "J14-405", "J14-406", "J14-407", "J14-408", "J14-409", "J14-410",
                    "J14-411", "J14-412", "J14-413", "J14-414", "J14-416", "J14-418", "J14-419", "J14-420",
                    "J14-421", "J14-423", "J14-424", "J14-426", "J14-428", "J14-430", "J14-432", "J14-434",
                    "J14-436", "J14-438", "J14-440", "J14-442", "J14-448", "J14-502", "J14-503", "J14-504",
                    "J14-505", "J14-506", "J14-507", "J14-508", "J14-509", "J14-510", "J14-511", "J14-512",
                    "J14-513", "J14-514", "J14-516", "J14-518", "J14-519", "J14-520", "J14-521", "J14-522",
                    "J14-523", "J14-524", "J14-526", "J14-528", "J14-530", "J14-532", "J14-536", "J14-538",
                    "J14-540", "J14-542"
                ],
                "JS1": ["JS1-103", "JS1-105", "JS1-113", "JS1-115", "JS1-117", "JS1-119", "JS1-203", "JS1-205",
                    "JS1-209", "JS1-211", "JS1-213", "JS1-215", "JS1-219", "JS1-303", "JS1-305", "JS1-309",
                    "JS1-311", "JS1-313", "JS1-315", "JS1-319", "JS1-403", "JS1-405", "JS1-409", "JS1-411",
                    "JS1-413", "JS1-415", "JS1-419", "JS1-503", "JS1-505", "JS1-509", "JS1-511", "JS1-513",
                    "JS1-515", "JS1-519"
                ]
            };
        },
        filters: {},
       computed: {
           floorGroup: function() {
               return Object.keys(this.classroom_all);
           },
           classroomGroup: function(){
               return this.classroom_all[this.floorGroup[this.floorIndex]];
           }
       },
        methods: {
            floorSelectChange: function(e){
                this.floorIndex = e.target.value;
                this.show = false;
                this.week = null;
            },
            classroomSelectChange: function(e){
                this.classroomIndex = e.target.value;
                this.show = false;
                this.week = null;
            },
            loadClassroom: function(){
                uni.$app.throttle(500, async () => {
                    var data = {classroom: this.classroomGroup[this.classroomIndex]};
                    if(this.week) data["term_week"] = this.week;
                    var res = await uni.$app.request({
                        load: 2,
                        throttle: true,
                        url: uni.$app.data.url + "/sw/loadclassromm",
                        data: data
                    })
                    this.week = res.data.term_week;
                    var table = [];
                    res.data.data.forEach(v => {
                        if(!table[v.day_of_week]) table[v.day_of_week] = [];
                        var uniqueNum = Array.prototype.reduce.call(v.class_name, (pre, cur) => pre+cur.charCodeAt(), 0);
                        v.background = uni.$app.data.colorList[uniqueNum % uni.$app.data.colorList.length];
                        table[v.day_of_week][v.turn_index] = v;
                    })
                    this.table = table;
                    this.$nextTick(() => this.show = true);
                })
            },
            pre: function(week){
                uni.$app.throttle(500, () => {
                    if(week <= 1) return void 0;
                    --week;
                    this.week = week;
                    this.loadClassroom();
                })
            },
            next: function(week){
                uni.$app.throttle(500, () => {
                    ++week;
                    this.week = week;
                    this.loadClassroom();
                })
            }
        }
    }
</script>

<style scoped>
    .select-con{
        width: 70px;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 3px;
    }
    .timetable-hide {
        min-height: 130px;
        margin: 0 1.5px;
        /* text-align: center; */
        word-break: break-all;
        color: #fff;
        padding: 3px;
        background: #eee;
        font-size: 12px;
        border-radius: 2px;
    }

    .timetable-hide > view{
        margin-bottom: 3px;
    }

</style>
