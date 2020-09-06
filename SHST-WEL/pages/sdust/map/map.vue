<template>
	<view>

		<layout title="嵙地图(点击查看大图)">
			<view style="position: relative;">
				<image src="https://windrunner_max.gitee.io/imgpath/SHST/Static/map.jpg" data-viewimgurl="https://windrunner_max.gitee.io/imgpath/SHST/Static/map.jpg"
				 @tap="viewImg" class="sdust-map" mode="widthFix"></image>
				<view class="img-from">山东科技大学新闻媒体部制</view>
			</view>
		</layout>

		<layout title="在线地图">
			<view class="tips">
				<view v-bind:style="{background:point}" class="point"></view>
				<view class="info">{{info}}</view>
				<view class="loc">{{showLongitude}}</view>
				<view class="loc">{{showLatitude}}</view>
			</view>
			<map class="sdust-map" :longitude="longitude" :latitude="latitude" show-location></map>
		</layout>

	</view>
</template>

<script>
	import layout from "@/components/layout.vue";
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
			wx.getLocation({
				type: "wgs84",
				// altitude: true, //高精度定位
				success: (res) => {
					var latitude = res.latitude;
					var longitude = res.longitude;
					var speed = res.speed;
					var accuracy = res.accuracy;
					this.longitude = longitude;
					this.latitude = latitude;
					this.speed = speed;
					this.accuracy = accuracy;
					this.info = "定位成功";
					this.point = "#009688";
					this.showLongitude = longitude.toFixed(6);
					this.showLatitude = latitude.toFixed(6);
				},
				fail: () => {
					this.info = "定位失败";
					this.point = "#FF5722";
				}
			})
		},
		methods: {
			bindViewTap: function() {},
			viewImg(e) {
				console.log(e.currentTarget.dataset.viewimgurl);
				var current = e.currentTarget.dataset.viewimgurl;
				uni.previewImage({
					current: current,
					urls: [current]
				})
			}
		}
	}
</script>

<style>
	.img-from {
		text-align: right;
		font-size: 12px;
		color: rgb(122, 122, 122);
		position: absolute;
		bottom: 7px;
		right: 5px;
	}

	.sdust-map {
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
		height: 230px !important;
		width: 100% !important;
	}
</style>
