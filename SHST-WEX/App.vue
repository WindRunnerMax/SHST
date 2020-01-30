<script>
	const dispose = require('@/vector/dispose.js');
	export default {
		globalData: {
			tips: 10,
			account: "",
			curWeek: "1",
			initData: {},
			version: "3.1.0",
			curTerm: "2019-2020-1",
			curTermStart: "2019-08-26",
			colorList: dispose.colorList,
			url: 'http://jwgl.sdust.edu.cn/app.do',
			fontUrl: "https://at.alicdn.com/t/font_1582902_a1btjrevzq.ttf",
			header: {'refer': 'https://com.WindrunnerMax.SHST','content-type': 'application/x-www-form-urlencoded','token':''}
		},
		onPageNotFound: (res) => { //处理404
			uni.reLaunch({
				url: 'pages/Home/auxiliary/notFound'
			})
		},
		onLaunch: function() {
			console.log("APP INIT");
			const util = require('@/utils/util.js');
			const pubFct = require('@/vector/pubFct.js');
			util.extDate(); //拓展Date原型
			this.globalData.colorN = this.globalData.colorList.length;
			this.globalData.curWeek = pubFct.getCurWeek(this.globalData.curTermStart); 
			this.extend = dispose.extend;
			this.extend({
			  ajax: dispose.ajax,
			  delay: dispose.delay,
			  toast: dispose.toast,
			  resize: dispose.resize,
			  nextTick: dispose.nextTick
			});
		}
	}
</script>


<style>
	.page{
		font-family: Arial, Helvetica, 'STHeiti STXihei', 'Microsoft YaHei', Tohoma, sans-serif;
		padding: 10px;
		font-size: 15px;
		background-color: #F8F8F8;
	}
	
	.signalPage{
		flex: 1;
	}
	
	.iconfont{
		font-family: iconfont;
	}
	.x-CenterCon{
		justify-content: center;
		flex-direction: row;
	}
	.dot{
		width: 8px;
		height: 8px;
		border-radius: 8px;
		margin: 0 5px;
	}
	.y-CenterCon{
		align-items: center;
		flex-direction: row;
	}
	
	.fill{
		flex: 1;
	}
	
	.text{
		font-size: 13px;
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

</style>
