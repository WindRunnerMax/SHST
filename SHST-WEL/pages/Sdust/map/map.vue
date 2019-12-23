<template>
	<view>

		<layout title="嵙地图(点击查看大图)">
			<view style='position: relative;'>
				<image src="http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg" data-viewImgUrl="http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg"
				 @tap='viewImg' class='sdustMap' mode="widthFix"></image>
				<view class='ImgFrom'>山东科技大学新闻媒体部制</view>
			</view>
		</layout>

		<layout title="在线地图">
			<view class='tips'>
				<view v-bind:style='{background:point}' class='point'></view>
				<view class='info'>{{info}}</view>
				<view class='loc'>{{showLongitude}}</view>
				<view class='loc'>{{showLatitude}}</view>
			</view>
			<map class='sdustMap' :longitude="longitude" :latitude="latitude" show-location></map>
		</layout>

	</view>
</template>

<script>
	import layout from "@/components/layout.vue"
	export default {
		components: {layout},
		data() {
			return {
				longitude: 120.12487,
				latitude: 35.99940,
				speed: 0,
				accuracy: 0,
				info: "定位中",
				point: "#FFB800",
				showLongitude: 120.124870,
				showLatitude: 35.999400
			}
		},
		onLoad: function() {
			var that = this
			wx.getLocation({
				type: 'wgs84',
				// altitude: true, //高精度定位
				success: function(res) {
					var latitude = res.latitude
					var longitude = res.longitude
					var speed = res.speed
					var accuracy = res.accuracy
					that.longitude = longitude
					that.latitude = latitude
					that.speed = speed
					that.accuracy = accuracy
					that.info = "定位成功"
					that.point = "#009688"
					that.showLongitude = longitude.toFixed(6)
					that.showLatitude = latitude.toFixed(6)
				},
				fail: function() {
					that.info = "定位失败",
						that.point = "#FF5722"
				}
			})
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
