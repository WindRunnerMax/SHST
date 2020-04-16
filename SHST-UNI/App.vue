<script>
	const dispose = require('vector/dispose.js');
	export default {
		globalData: {
			tips: "0",
			openid: "",
			userFlag: 0, // 0 未登录 1 已登陆
			initData: {},
			version: "3.3.0",
			curTerm: "2019-2020-1",
			curTermStart: "2019-08-26",
			colorList: dispose.colorList,
			url: 'https://www.touchczy.top/',
			// url: 'http://dev.touchczy.top/',
			header: {'content-type': 'application/x-www-form-urlencoded'}
		},
		onPageNotFound: (res) => { //处理404
			uni.reLaunch({
				url: 'pages/Home/auxiliary/notFound'
			})
		},
		onLaunch: function() {
			console.log("APP INIT");
			dispose.onLaunch.apply(this); //启动加载事件
			this.$scope.extend = dispose.extend;
			this.$scope.extend({
			  ajax: dispose.ajax,
			  toast: dispose.toast,
			  request: dispose.request
			});
		},
		onError: (err) => {
			dispose.toast("Internal Error");
		}
	}
</script>

<style>
	@import '/vector/asse.mini.wxss';
	@import "/vector/icon/iconfont.wxss";
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
	  height: auto;
	}
	.adapt{
		box-sizing: border-box;
	}
	.tipsCon view{
		padding: 5px;
	}
</style>
