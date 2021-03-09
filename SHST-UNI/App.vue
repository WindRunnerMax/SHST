<script>
    import dispose from "@/vector/dispose";
    import log from "@/modules/realtime-log";
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
            try{ // 避免无限递归调用
                log.error(err);
                console.log(err);
                dispose.toast("Internal Error");
            }catch(e){
                console.log(e);
            }
        }
    }
</script>

<style lang="scss">
    @import "@/vector/resources/iconfont.wxss";
    @import "@/vector/resources/asse.mini.wxss";
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
    .tips-con{
        line-height: 27px;
    }
</style>
