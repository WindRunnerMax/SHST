<template>
	<view>

		<layout title="嵙地图">
			<view style='position: relative;'>
				<image src="http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg" data-viewImgUrl="http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg"
				 @tap='viewImg' class='sdustMap' mode="widthFix"></image>
				<view class='ImgFrom'>山东科技大学新闻媒体部制</view>
			</view>
		</layout>

	</view>
</template>

<script>
	export default {
		data() {
			return {

			}
		},
		onLoad: function() {
			
		},
		methods: {
			bindViewTap: function() {},
			viewImg(e) {
				var current = e.currentTarget.dataset.viewimgurl;
				wx.previewImage({
					current: current,
					urls: [current]
				})
			}
		}
	}
</script>

<style>
	.ImgFrom {
		text-align: right;
		font-size: 12px;
		color: rgb(122, 122, 122);
		position: absolute;
		bottom: 7px;
		right: 5px;
	}

	.sdustMap {
		width: 100%;
		border-radius: 3px;
	}
	
	.tips{
	  border:1px solid #eee;
	  display: flex;
	  flex-wrap: wrap;
	  margin-bottom: 5px;
	  background: #eee;
	  color: #666;
	  font-size: 15px;
	}
	
	.tips view{
	  margin-left: 5px;
	  align-self: center;
	}
	
	.point{
	  width: 8px;
	  height: 8px;
	  border-radius: 8px;
	}
	
	map{
		height: 230px;
	}
</style>
