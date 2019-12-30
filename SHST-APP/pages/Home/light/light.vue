<template>
	<view>
		
		<layout title="最近使用" v-if="recentUse.length">
			<view class="y-CenterCon" style="margin: 6px 0;">
				<view v-for="(innerItem) in recentUse" :key="innerItem.title" :data-obj="innerItem" class='icon'  @tap='jump'>
					<imagecache mode="aspectFill" :src="'https://windrunner_max.gitee.io/imgpath/LightApp/'+innerItem.img"></imagecache>
					<view style="margin-top: 6px;">{{innerItem.title}}</view>
				</view>
			</view>
		</layout>

		<layout title="应用列表">
			<view v-for="(item,index) in lightList" :key="index">
				<view class="y-CenterCon" style="margin-top: 20px;">
					<view v-for="(innerItem,innderIndex) in item" :key="innderIndex" :data-obj="innerItem" class='icon'  @tap='jump'>
						<imagecache mode="aspectFill" :src="'https://windrunner_max.gitee.io/imgpath/LightApp/'+innerItem.img"></imagecache>
						<view class="name">{{innerItem.title}}</view>
					</view>
				</view>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp();
	export default {
		data() {
			return {
				lightList: [],
				recentUse: []
			}
		},
		onLoad: function() {
			var that = this;
			app.ajax({
				load: 1,
				url: "https://windrunner_max.gitee.io/imgpath/LightApp/config.json",
				success: function(res) {
					var extList = [];
					var innerList = [];
					res.data.map(function(value, index) {
						if (index && index % 4 === 0) {
							extList.push(innerList);
							innerList = [];
						}
						innerList.push(value);
					});
					extList.push(innerList);
					that.lightList = extList;
				}
			})
			uni.getStorage({
				key: "recent",
				success: function(res) {
					that.recentUse = res.data
				} 
			})
		},
		methods: {
			jump: function(e) {
				var obj = e.currentTarget.dataset.obj;
				uni.navigateTo({
					url: '/pages/Home/auxiliary/webview?url=' + encodeURIComponent(obj.url)
				})
				var curUse = this.recentUse;
				if(JSON.stringify(curUse).indexOf(JSON.stringify(obj))==-1){
					curUse.unshift(obj);
					this.recentUse = curUse.splice(0,4);
					uni.setStorage({
						key:"recent",
						data: this.recentUse
					})
				}	
			}
		}
	}
</script>

<style>
	.icon {
		width: 25%;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		padding: 5px 0;
	}

	image {
		width: 35px;
		height: 35px;
		border-radius: 30px;
		overflow: hidden;
	}
	
	.name{
		margin-top: 6px;
		overflow: hidden;
		text-overflow:ellipsis;
		white-space: nowrap;
	}
</style>
