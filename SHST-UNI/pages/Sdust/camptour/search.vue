<template>
	<view>

		<view class="search">
			<view class="search-icon">
				<icon type="search" size="16" color="blue" />
			</view>
			<view class="search-form">
				<form>
					<input @input="bindSearchInput" type="text" name="search" placeholder="请输入景点名称关键词" :value="keyword" style="font-size: 30rpx;" />
				</form>
			</view>
			<view class="search-icon" @tap="reset">
				<icon type="cancel" size="16" color="purple" />
			</view>
		</view>

		<view v-for="(item,index) in showData" :key="index" class="building-item">

			<view style="height:100%; display: flex;">
				<navigator class="img" :url="'details?tid='+item.tid+'&bid='+item.bid">
					<image :src="item.img[0]" mode="aspectFill"> </image>
					<view class="item">
						<view class="itemName">
							{{item.name}}
						</view>
						<view class="itemFloor" v-if="item.floor">
							{{item.floor}}
						</view>
					</view>
				</navigator>
				<navigator class="text" :url="'polyline?latitude='+item.latitude+'&longitude='+item.longitude">
					<image src="/static/camptour/location.svg"> </image>
				</navigator>
			</view>
		</view>

	</view>
</template>

<script>
	var app = getApp();
	export default {
		data() {
			return {
				keyword: null,
				buildlData: app.globalData.map,
				showData: null,
				cursor: 0
			}
		},
		onLoad: function(options) {
			wx.setNavigationBarColor({
				frontColor: '#ffffff',
				backgroundColor: '#079DF2',
				animation: {
					duration: 200,
					timingFunc: 'easeIn'
				}
			})

		},
		methods: {
			bindSearchInput: function(e) {
				this.buildlData = app.globalData.map
				let showData = new Array();
				let searchdata = this.buildlData;
				if (e.detail.cursor >= this.cursor) {
					//输入文字
					let inputData = e.detail.value.replace(/(^\s*)|(\s*$)/g, "")

					if (inputData) {
						let z = 0,
							x = 100;
						for (var b in searchdata) {
							for (var i in searchdata[b].data) {
								if (searchdata[b].data[i].name.indexOf(inputData) != -1 || (searchdata[b].data[i].floor && searchdata[b].data[
										i].floor.indexOf(inputData) != -1)) {
									let build = searchdata[b].data[i];
									build.tid = b;
									build.bid = i;
									z = z + 1;
									build.index = z;
									showData.push(build);
								} else if (searchdata[b].data[i].description.indexOf(inputData) != -1) {
									let build = searchdata[b].data[i];
									build.tid = b;
									build.bid = i;
									x = x + 1;
									build.index = x;
									showData.push(build);
								}
							}
						}
						//冒泡排序
						for (var i = 0; i < showData.length; i++) {
							for (var j = 0; j < showData.length - i - 1; j++) {
								if (showData[j].index > showData[j + 1].index) {
									var temp = showData[j];
									showData[j] = showData[j + 1];
									showData[j + 1] = temp;
								}
							}
						}
						if (inputData == 'gxgk') {
							showData.push({
								name: "\u839e\u9999\u5e7f\u79d1"
							})
						}
						this.showData = showData
					}
				} else {
					//删除文字
					this.showData = null
				}
				this.cursor = e.detail.cursor;
			},
			reset: function() {
				this.keyword = null
			}
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

	.search {
		width: 96%;
		height: 80rpx;
		background-color: #f5f5f5;
		border-radius: 15px;
		margin: 20rpx 2%;
		display: flex;
	}

	.search-icon {
		margin: auto 20rpx;
	}

	.search-form {
		margin: auto 15rpx;
		width: 100%;
	}
</style>
