<template>
	<view>

		<view style="width: 100%;">
			<scroll-view scroll-x="true">
				<view class="top-swich" style="text-align:center;" v-if="!fullscreen">
					<label v-for="(item,index) in buildlData" :key="index" :id="index" @tap="changePage" class="top-swich-btn widthLim"
					 :class="{'active':isSelectedBuildType == index}">{{item.name}}</label>
				</view>
			</scroll-view>
			<map :longitude="longitude" :latitude="latitude" :scale="buildlData[isSelectedBuildType].scale" :markers="buildlData[isSelectedBuildType].data"
			 @markertap="markertap" @regionchange="regionchange" :include-points="buildlData[isSelectedBuildType].data"
			 :show-location="islocation? 'true': 'false'" enable-overlooking="true" enable-3D="true" :style="{width:'auto',height:fullscreen ? 94+'vh' : 48+'vh'}">
				<cover-view class="controls" :class="{full:fullscreen}">
					<cover-view @tap="navigateSearch">
						<cover-image class="img" src="/static/camptour/search.png" />
					</cover-view>
					<cover-view @tap="location">
						<cover-image class="img" src="/static/camptour/location.png" />
					</cover-view>
				</cover-view>
			</map>
			<button @tap="clickButton">共有{{buildlData[isSelectedBuildType].data.length}}个景观 ◕‿◕</button>
			<scroll-view scroll-y="true" :style="{height:fullscreen ? 0:40+'vh'}" :scroll-top="(isSelectedBuild -1 ) * 70">
				<view v-for="(item,index) in buildlData[isSelectedBuildType].data" :key="index" class="building-item" :style="{'background-color':isSelectedBuild -1 == index ? '#d5d5d5' : ''}">
					<view class="img-view">
						<navigator class="img" :url="'details?tid='+isSelectedBuildType+'&bid='+index">
							<image :src="item.img[0]" mode="aspectFill"> </image>
							<view class="item">
								<view class="itemName">{{item.name}}</view>
								<view class="itemFloor" v-if="item.floor">位置：{{item.floor}} </view>
							</view>
						</navigator>
						<navigator class="text" :url="'polyline?latitude='+item.latitude+'&longitude='+item.longitude">
							<image src="/static/camptour/location.svg"> </image>
						</navigator>
					</view>
				</view>
			</scroll-view>
		</view>

	</view>
</template>

<script>
	var app = getApp();
	let school = require('@/vector/camptour/resources/sdust.js');
	export default {
		data() {
			return {
				fullscreen: false,
				latitude: 35.99940,
				longitude: 120.12487,
				buildlData: {},
				windowHeight: "",
				windowWidth: "",
				isSelectedBuild: 0,
				isSelectedBuildType: 0,
				islocation: true
			}
		},
		onLoad: function() {
			var that = this;
			uni.setNavigationBarColor({
				frontColor: '#ffffff',
				backgroundColor: '#079DF2',
				animation: {
					duration: 200,
					timingFunc: 'easeIn'
				}
			})
			uni.showShareMenu({
				withShareTicket: true
			})
			uni.getSystemInfo({
				success: function(res) {
					//获取当前设备宽度与高度，用于定位控键的位置
					that.windowHeight = res.windowHeight
					that.windowWidth = res.windowWidth
				}
			})
			this.loadSchoolConf();
			that.buildlData = app.globalData.map
			this.location();
		},
		methods: {
			loadSchoolConf: function() {
				app.globalData.map = school.map;
				for (let i = 0; i < app.globalData.map.length; i++) {
					for (let b = 0; b < app.globalData.map[i].data.length; b++) {
						app.globalData.map[i].data[b].id = b + 1;
					}
				}
			},
			regionchange(e) {},
			markertap(e) {
				this.isSelectedBuild= e.markerId
			},
			navigateSearch() {
				uni.navigateTo({
					url: 'search'
				})
			},
			location: function(e) {
				var _this = this
				uni.getLocation({
					type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 uni.openLocation 的坐标  
					success: function(res) {
						app.globalData.latitude = res.latitude;
						app.globalData.longitude = res.longitude;
						app.globalData.islocation = true;
						if (e) {
							_this.longitude = res.longitude
							_this.latitude = res.latitude
						}
					}
				})
			},
			clickButton: function(e) {
				this.fullscreen= !this.fullscreen
			},
			changePage: function(event) {
				this.isSelectedBuildType = event.currentTarget.id
				this.isSelectedBuild = 0
			},
			onShareAppMessage: function() {}
		}
	}
</script>

<style>
	
	page {
		padding: 0;
	}
	
	.building-item {
		height: 50px;
		border-bottom: 1px solid #e0e0e0;
		padding: 10px;
		font-size: 15px;
	}

	.top-swich {
		background-color: #079df2;
		height: 40px;
		padding-top: 10px;
		color: white;
		display: flex;
		justify-content: space-around;
		font-size: 13px;
	}

	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.top-swich-btn {
		background-color: none;
		letter-spacing: 3rpx;
		height: 56rpx;
		color: #fff;
		font-size: 26rpx;
	}

	.active {
		border-bottom: solid white;
		height: 50rpx;
		display: inline-block;
	}


	.img-view {
		height: 100%;
		display: flex;
	}

	.img {
		width: 85%;
		height: 100%;
		display: flex;
	}

	.img image {
		width: 60px;
		height: 90%;
		margin: auto 7rpx;
	}

	.item {
		display: flex;
		flex-direction: column;
		margin: auto 0;
	}

	.itemName {
		margin: 0 20rpx;
		font-size: 32rpx;
	}

	.itemFloor {
		margin: 0 20rpx;
		font-size: 28rpx;
		color: #555;
	}

	.text {
		margin: auto 15px;
		width: 13%;
	}

	.text image {
		width: 70rpx;
		height: 70rpx;
	}

	.controls {
		position: relative;
		top: 65%;
		left: 85%;
		/* display: flex; */
	}

	.controls .img {
		margin-top: 5px;
		width: 80rpx;
		height: 80rpx;
	}

	.full {
		top: 82%;
	}

	.widthLim {
		width: 100%;
	}

	button:after {
		border: none;
	}

	button {
		background: #fff;
		border: none;
		box-sizing: unset;
		margin: 0;
		font-size: 15px;
		background: #F8F8F8;
		line-height: unset;
		padding: 5px 0;
	}
</style>
