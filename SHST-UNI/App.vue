<script>
	const dispose = require('vector/dispose.js');
	export default {
		globalData: {
			tips: "1",
			openid: "oswg346SdNbI76qbUAzXyfkvlymY",
			userFlag: 2, // 0 未登录 1 已登陆 2 加载中
			initData: {},
			version: "3.0.2",
			curTerm: "2019-2020-1",
			curTermStart: "2019-08-26",
			colorList: dispose.colorList,
			host: 'https://www.liyanzuishuai.top',
			url: 'https://www.liyanzuishuai.top/sdust/index.php/',
			// url: 'http://127.0.0.1/Swisdom/Web/index.php/',
			header: {'Cookie': 'PHPSESSID=sa6qjdg9nsp4aki52jqld3hfm5;','content-type': 'application/x-www-form-urlencoded'}
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
			dispose.checkUpdate(); //小程序更新
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
	  color: #888;
	  line-height: unset;
	}
	.adapt{
		box-sizing: border-box;
	}
	.tipsCon view{
		padding: 5px;
	}
</style>
