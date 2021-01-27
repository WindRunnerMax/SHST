<script>
    import dispose from "@/vector/dispose";
    export default {
        globalData: {},
        onPageNotFound: (res) => { //处理404
            uni.reLaunch({
                url: "pages/home/auxiliary/not-found"
            })
        },
        onLaunch: function() {
            console.log("APP INIT");
            dispose.onLaunch.apply(this); //启动加载事件
        },
        onError: (err) => {
            console.log(err);
            dispose.toast("Internal Error");
        }
    }
</script>

<style>
    @import "@/vector/resources/asse.mini.wxss";
    @import "@/vector/resources/iconfont.wxss";
    button:after {
        border: none;
    }
    button {
        background: #fff;
        border: none;
        box-sizing: unset;
        padding: 0;
        margin: 0;
        font-size: inherit;
        color: inherit;
        line-height: unset;
        height: auto;
    }
    .adapt{
        box-sizing: border-box;
    }
    .text-ellipsis{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .tips-con{
        line-height: 27px;
    }
</style>
