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
			header: {'refer': 'https://com.WindrunnerMax.SHST','content-type': 'application/x-www-form-urlencoded','token':''}
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
			  toast: dispose.toast,
			  addIconfont: dispose.addIconfont
			});
		}
	}
</script>


<style>
	.page{
		font-family: Arial, Helvetica, 'STHeiti STXihei', 'Microsoft YaHei', Tohoma, sans-serif;
		font-size: 15px;
		padding: 10px;
		background-color: #F8F8F8;
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

</style>
