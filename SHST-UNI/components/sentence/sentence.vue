<template>
    <view>

        <view class="tips">{{sentence}}</view>
        <view class="tips">{{content}}</view>
        <image class="sent-image" :src="url" mode="aspectFill"></image>

    </view>
</template>
<script>
    import storage from "@/modules/storage.js";
    import {formatDate, safeDate} from "@/modules/datetime.js";
    export default {
        name: "sentence",
        props: {},
        methods: {},
        data:() => ({
            url: "",
            sentence: "",
            content: ""
        }),
        created: async function() {
            let data = {};
            if(storage.get("sentence")){
                data = storage.get("sentence");
            }else{
                let [err, res] = await uni.request({
                    // url: "https://open.iciba.com/dsapi/",
                    url: "https://sentence.iciba.com/api/sentence/list?limit=1"
                })
                if(!err){
                    data = res.data.data.sentenceViewList[0].dailysentence;
                    storage.setPromise("sentence-longtime", data);
                    storage.setPromise("sentence", data, safeDate(formatDate() + " 23:59:59"));
                }else{
                    data = storage.get("sentence-longtime");
                }
            }
            try{
                this.url = data.picture2;
                this.sentence = data.note;
                this.content = data.content;
            }catch(e){
                console.warn(e);
            }

        }
    }
</script>
<style>
    .tips{
        margin: 6px 0 8px 3px;
    }
    .sent-image {
        width: 100%;
    }
</style>
