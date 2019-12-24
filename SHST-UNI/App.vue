<script>
	const dispose = require('vector/dispose.js');
	export default {
		globalData: {
			tips: "1",
			openid: "",
			userFlag: 2, // 0 未登录 1 已登陆 2 加载中
			initData: {},
			version: "3.0.2",
			curTerm: "2019-2020-1",
			curTermStart: "2019-08-26",
			colorList: dispose.colorList,
			host: 'https://www.liyanzuishuai.top',
			url: 'https://www.liyanzuishuai.top/sdust/index.php/',
			// url: 'http://127.0.0.1/Swisdom/Web/index.php/',
			header: {'Cookie': '','content-type': 'application/x-www-form-urlencoded'}
		},
		extend: dispose.extend,
		onPageNotFound: (res) => { //处理404
			uni.reLaunch({
				url: 'pages/Home/notFound/notfound'
			})
		},
		onLaunch: function() {
			console.log("APP INIT");
			// dispose.onLunch.apply(this); //启动加载事件
			const util = require('utils/util.js');
			const pubFct = require('vector/pubFct.js');
			util.extDate(); //拓展Date原型
			dispose.checkUpdate(); //小程序更新
			this.globalData.colorN = this.globalData.colorList.length;
			this.globalData.curWeek = pubFct.getCurWeek(this.globalData.curTermStart);
		}
	}
</script>

<style>
	@import '/vector/asse.wxss';
	page{
		font-family: Arial, Helvetica, 'STHeiti STXihei', 'Microsoft YaHei', Tohoma, sans-serif;
		padding: 10px;
		box-sizing: border-box;
		font-size: 15px;
		background-color: #F8F8F8;
	}
	.link{
	  color: #4C98F7;
	}
</style>
