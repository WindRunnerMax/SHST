<template>
    <view>
        <headslot title-height="45" title="发布新帖"></headslot>
        <form-pack :rules="rules" :form-data="form" ref="form">
            <form-unit rule="content" label="描述" width="65" row>
                <textarea
                    class="a-lmt"
                    placeholder="请输入内容"
                    v-model="form.content"
                    @blur="verifyUnit('content')"
                ></textarea>
            </form-unit>
            <form-unit rule="type" label="类型" width="65" row>
                <radio-group @change="form.type = $event.detail.value" class="x-full" >
                    <view class="a-flex-space-around" v-for="(item, index) in typeGroup" :key="index" >
                        <view class="y-center" v-for="inner in item" :key="inner.index" :class="{'a-lmt': index > 0}">
                            <radio :value="inner.index" />
                            <view>{{inner.name}}</view>
                        </view>
                    </view>
                </radio-group>
            </form-unit>
        </form-pack>

        <view class="form-unit y-center a-flex-space-around">
            <view
                v-for="(item, index) in form.imgs"
                :key="index"
                class="img-unit"
                :style="{width: imgWidth + 'px', height: imgWidth + 'px'}"
            >
                <image
                    class="x-full y-full"
                    :src="item"
                    lazy-load
                    mode="aspectFill"
                    @click="viewImage(item, form.imgs)"
                >
                </image>
                <view class="cancel x-center y-center" @click="deleteImg(index)">
                    <i class="iconfont icon-x" ></i>
                </view>
            </view>
            <view
                class="upload-img x-center y-center"
                v-if="form.imgs.length < 3"
                @click="uploadImg"
                :style="{width: imgWidth + 'px', height: imgWidth + 'px'}"
            >
                <view class="iconfont icon-jia"></view>
            </view>
            <view
                v-if="2 - form.imgs.length > 0"
                v-for="(item, index) in 2 - form.imgs.length"
                :key="index"
                :style="{width: imgWidth + 'px', height: imgWidth + 'px'}"
            >
            </view>
        </view>

        <view class="a-flex a-lmt">
            <button
                class="a-btn a-btn-large a-btn-blue a-flex-full"
                open-type="getUserInfo"
                @getuserinfo="getUserInfo"
            >提交</button>
        </view>


        <canvas canvas-id="canvas" class="canvas" :style="{width: canvas.width + 'px', height: canvas.height + 'px'}"></canvas>
    </view>
</template>

<script>
    import {startLoading, endLoading} from "@/modules/loading.js";
    import headslot from "@/components/headslot/headslot.vue";
    import formPack from "@/components/form/form-pack.vue";
    import formUnit from "@/components/form/form-unit.vue";
    import {verifyUnit} from "@/components/form/form-utils.js";
    import {propsCopy} from "@/modules/copy.js";
    export default {
        components: {headslot, formPack, formUnit},
        mixins: [verifyUnit],
        data: () => ({
            form: {
                id: 0,
                content: "",
                imgs: [],
                type: ""
            },
            rules: {
                content: [
                    { min: 1, msg: "请输入详细信息"},
                    { max: 150, msg: "内容不应大于150个字"},
                ],
                type: [
                    { min: 1, msg: "请选择类型" },
                ],
            },
            typeGroup: [
                  [{name: "失物", index: 1},
                   {name: "招领", index: 2},
                   {name: "表白", index: 3}],
                   [{name: "二手", index: 4},
                   {name: "拼车", "index": 5},
                   {name: "其他", index: 6}],
            ],
            canvas: {
                width: 0,
                height: 0
            },
            screen: {
                width: 0,
                height: 0
            }
        }),
        beforeCreate: function() {
            this.paths = [];
        },
        created: function() {
            uni.getSystemInfo({
               success: res => {
                 //获取屏幕窗口高度和宽度
                 this.screen.height = res.windowHeight;
                 this.screen.width = res.windowWidth;
               },
            })
            if(uni.$app.data.tmp.post){
                this.form = propsCopy(this.form, uni.$app.data.tmp.post, "id", "content", "imgs", "type");
                this.paths = uni.$app.data.tmp.post.paths;
                uni.$app.data.tmp.post = null;
            }
        },
        filters: {},
        computed: {
            imgWidth: ($vm) => ($vm.screen.width < 100) ?  90 : ($vm.screen.width - 40) / 3,
        },
        methods: {
            deleteImg: async function(index){
                let [err,choice] = await uni.showModal({
                    title: "提示",
                    content: "确定要删除吗？",
                })
                if(choice.confirm) {
                    this.form.imgs.splice(index, 1);
                    this.paths.splice(index, 1);
                }
            },
            uploadImg: async function(){
                let [err, res] = await uni.chooseImage({
                    count: 1,
                    sizeType: ["original", "compressed"], //原图或压缩后的图片
                    sourceType: ["album", "camera"], // 相册、相机
                })
                if(err) return void 0; // 不再处理
                new Promise((resolve, reject) => {
                    startLoading({load: 3, title: "图片处理中"});
                    const file = res.tempFilePaths[0];
                    uni.getImageInfo({
                        src: file, // 图片的路径
                    }).then(([err, res]) => {
                        if(err) reject(err);
                        // console.log(res);
                        // console.log(res, this.screen)
                        if(res.width < this.screen.width && res.height < this.screen.height) resolve(file);
                        this.canvas.width = this.screen.width;
                        this.canvas.height = this.screen.width / res.width * res.height;
                        const ctx = uni.createCanvasContext("canvas");
                        ctx.drawImage(file, 0, 0, this.canvas.width, this.canvas.height);
                        ctx.draw(false, setTimeout(() => {
                            uni.canvasToTempFilePath({
                                canvasId: "canvas",
                                fileType: "jpg",
                                destWidth: this.canvas.width,
                                destHeight: this.canvas.height
                            }).then(([err, res]) => {
                                if(err) reject(err);
                                resolve(res.tempFilePath);
                            });
                        }, 500));
                    })
                }).then((url) => {
                    // uni.getImageInfo({
                    //     src: url,
                    //     success: (res) => console.log(res)
                    // })
                    // console.log(url);
                    return uni.uploadFile({
                        url: `${uni.$app.data.url}/news/uploadImg`,
                        filePath: url,
                        name: "image",
                        header: {cookie: uni.$app.request.headers.cookie}
                    })
                }).then(([err, res]) => {
                    if(err) return Promise.reject(err);
                    endLoading({load: 3});
                    console.log(res.data);
                    res.data = JSON.parse(res.data);
                    if(res.data.status === 1){
                        this.form.imgs.push(res.data.url);
                        this.paths.push(res.data.path);
                    }else if(res.data.status === -1){
                        uni.$app.toast(res.data.msg);
                    }else{
                        uni.$app.toast("发生错误");
                    }
                }).catch((err) => {
                    console.log(err);
                    endLoading({load: 3});
                    uni.$app.toast("发生内部错误");
                })
            },
            getUserInfo: function(event){
                if(!event.detail.errMsg.toLowerCase().includes("ok")) return void 0;
                uni.$app.throttle(1000, async () => {
                    if(this.$refs.form.verifyRules()) {
                        console.log("submit");
                        const data = {...this.form};
                        delete data.imgs;
                        data.img_url = this.paths.join(",");
                        data.nick_name = event.detail.userInfo.nickName;
                        data.avatar_url = event.detail.userInfo.avatarUrl;
                        let res = await uni.$app.request({
                            url: `${uni.$app.data.url}/news/publish`,
                            load: 3,
                            method: "POST",
                            data: data
                        })
                        uni.$app.toast("发布成功").then(() => {
                            uni.$app.eventBus.commit("RefreshMyList");
                            this.nav("/pages/sdust/news/mine/my-detail?id="+res.data.id, "redirect");
                        })
                    }
                })
            }
        }
    }
</script>

<style lang="scss">
    page{
        color: $a-font-grey;
        padding: 0;
        overflow: hidden;
        background-color: $a-white;
    }
    .form-unit{
        padding: 10px;
        border-bottom: 1px solid #EEEEEE;
    }
    .label{
        margin-left: 5px;
        width: 60px;
    }
    .input{
        border: none;
        padding: 0 5px;
    }
    textarea{
        border: none;
        height: 70px;
    }
    radio{
        zoom: 0.8;
    }
    .img-unit{
        position: relative;
    }
    .img-unit > .cancel{
        width: 15px;
        height: 15px;
        position: absolute;
        top: -6px;
        right: -6px;
        z-index: 100;
        border-radius: 50%;
        background-color: $a-font-grey;
    }
    .img-unit > .cancel > view{
        color: #fff;
        font-size: 10px;
    }
    .img-unit, .upload-img{
        border: 1px solid #eee;
    }
    .upload-img > view{
        color: #ccc;
        font-size: 30px;
    }
    .canvas{
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
</style>
