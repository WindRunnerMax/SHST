<script>
	const dispose = require('vector/dispose.js');
	export default {
		globalData: {
			account: "",
			curWeek: "1",
			initData: {},
			version: "3.1.0",
			curTerm: "2019-2020-1",
			curTermStart: "2019-08-26",
			colorList: dispose.colorList,
			url: 'http://jwgl.sdust.edu.cn/app.do',
			header: {'refer': 'https://com.WindrunnerMax.SHST','content-type': 'application/x-www-form-urlencoded','token':''}
		},
		onPageNotFound: (res) => { //处理404
			uni.reLaunch({
				url: 'pages/Home/auxiliary/notFound'
			})
		},
		onLaunch: function() {
			console.log("APP INIT");
			dispose.onLunch.apply(this); //启动加载事件
			const util = require('utils/util.js');
			const pubFct = require('vector/pubFct.js');
			util.extDate(); //拓展Date原型
			this.globalData.colorN = this.globalData.colorList.length;
			this.globalData.curWeek = pubFct.getCurWeek(this.globalData.curTermStart); 
			this.$scope.extend = dispose.extend;
			this.$scope.extend({
			  toast: dispose.toast,
			  ajax: dispose.ajax
			});
		}
	}
</script>

<style>
	@import '/vector/asse.wxss';
	@import "/vector/icon/iconfont.wxss";
	page{
		font-family: Arial, Helvetica, 'STHeiti STXihei', 'Microsoft YaHei', Tohoma, sans-serif;
		padding: 10px;
		box-sizing: border-box;
		font-size: 15px;
		background-color: #F8F8F8;
	}
	.link{
	  color: #4C98F7;
	  text-decoration: underline;
	}
	.x-CenterCon{
		display: flex;
		justify-content: center;
	}
	.dot{
		width: 8px;
		height: 8px;
		border-radius: 8px;
	}
	.y-CenterCon{
		display: flex;
		align-items: center;
	}
	button:after {
	  border: none;
	}
	
	button {
	  background: #fff;
	  border: none;
	  box-sizing: unset;
	  padding: 0;
	  margin: 0;
	  font-size: 13px;
	  line-height: unset;
	}
	.adapt{
		box-sizing: border-box;
	}
	.tipsCon view{
		padding: 5px;
	}
</style>
