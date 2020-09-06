<script>
	"use strict";
	export default {
		globalData: {
			version: "2.0.0"
		},
		onPageNotFound(res) { 
			wx.reLaunch({url: "pages/home/not-found/not-found"})
		},
		onLaunch: () => {
			Date.prototype.addDate = function(years = 0, months = 0, days = 0) {
			  if (days !== 0) this.setDate(this.getDate() + days);
			  if (months !== 0) this.setMonth(this.getMonth() + months);
			  if (years !== 0) this.setFullYear(this.getFullYear() + years);
			}
		}
	}
</script>

<style>
	@import "/vector/asse.mini.wxss";
	@import "/vector/style/weui.wxss";
	page{
		font-family: Arial, Helvetica, "STHeiti STXihei", "Microsoft YaHei", Tohoma, sans-serif;
		padding: 10px;
		box-sizing: border-box;
		font-size: 15px;
		background-color: #F8F8F8;
	}
	.link{
		color: #4C98F7;
	}
</style>
