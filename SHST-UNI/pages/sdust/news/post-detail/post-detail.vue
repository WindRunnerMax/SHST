<template>
    <view>

        <detail
            :post="post"
            :review="review"
            @save-post="savePost"
            @save-review="saveReview"
        ></detail>

    </view>
</template>

<script>
    import detail from "../components/detail.vue";
    import {share} from "@/vector/pub-fct.js";
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
                praised: null
            },
            review: []
        }),
        beforeCreate: function() {},
        onShareAppMessage: function(){
            if(this.post.host){
                return {
                    title: this.post.content,
                    imageUrl: this.post.host+'public/upload/'+this.post.imgs[0],
                    path: "/pages/sdust/news/post-detail/post-detail?id=" + this.post.id
                }
            }else{
                return share("/pages/sdust/news/post-detail/post-detail?id=" + this.post.id);
            }
        },
        onLoad: function(option) {
            if(option.id) {
                uni.$app.onload(async () => {
                    let res = await uni.$app.request({
                        load: 3,
                        url: `${uni.$app.data.url}/news/getDetail/${option.id}`,
                    })
                    this.post = res.data.detail;
                    this.post.imgs = res.data.detail.img_url.split(",");
                    this.post.id = Number(option.id);
                    this.post.praised = res.data.praised;
                    this.review = res.data.review;
                })
            }
        },
        filters: {},
        computed: {},
        methods: {
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
</style>
