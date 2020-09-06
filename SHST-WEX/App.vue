<script>
    import dispose from "@/vector/dispose.js";
    export default {
        globalData: {
            url: "http://219.218.128.228/app.do",
        },
        onLaunch: function() {
            console.log("APP INIT");
            dispose.onLaunch.apply(this); //启动加载事件
        }
    }
</script>


<style>
    .page{
    	font-size: 15px;
    	padding: 10px;
    	background-color: #F8F8F8;
    }
    .iconfont{
    	font-family: iconfont;
    }
    .x-center{
    	justify-content: center;
    	flex-direction: row;
    }
    .dot{
    	width: 8px;
    	height: 8px;
    	border-radius: 8px;
    	margin: 0 5px;
    }

    .y-center{
    	align-items: center;
    	flex-direction: row;
    }

    .fill{
    	flex: 1;
    }

    .text{
    	font-size: 13px;
    	color: #111111;
    }

    .link{
      font-size: 13px;
      color: #4C98F7;
      text-decoration: underline;
    }

    .border{
    	border-style: solid;
    	border-color: #EEEEEE;
    }
    
    /* 微距 */
    .a-mt{
        margin-top: 3px !important;
    }
    
    .a-mr{
        margin-right: 3px !important;
    }
    
    .a-mb{
        margin-bottom: 3px !important;
    }
    
    .a-ml{
        margin-left: 3px !important;
    }
    
    .a-pt{
        padding-top: 3px !important;
    }
    
    .a-pr{
        padding-right: 3px !important;
    }
    
    .a-pb{
        padding-bottom: 3px !important;
    }
    
    .a-pl{
        padding-left: 3px !important;
    }
    
    /* 长距 */
    .a-lmt{
        margin-top: 10px !important;
    }
    
    .a-lmr{
        margin-right: 10px !important;
    }
    
    .a-lmb{
        margin-bottom: 10px !important;
    }
    
    .a-lml{
        margin-left: 10px !important;
    }
    
    .a-lpt{
        padding-top: 10px !important;
    }
    
    .a-lpr{
        padding-right: 10px !important;
    }
    
    .a-lpb{
        padding-bottom: 10px !important;
    }
    
    .a-lpl{
        padding-left: 10px !important;
    }
    
</style>
