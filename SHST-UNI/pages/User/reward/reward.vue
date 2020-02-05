<template>
	<view>

		<view class="x-CenterCon">
			<image class='rewardImg' src='/static/img/Reward.jpg' data-viewImgUrl="http://cdn.liyanzuishuai.top/Reward.jpg-original"
			 @tap='viewImg'></image>
		</view>

		<view class='tipsCon'>
			
			<!-- #ifdef MP-WEIXIN -->
			<view>点击二维码后长按识别赞赏码</view>
			<!-- #endif -->
			<!-- #ifdef MP-QQ -->
			<view>点击保存赞赏码微信扫一扫</view>
			<!-- #endif -->
			<view>记得写上您的名字哦</view>
			<view>感谢您的支持!</view>

			<navigator url="rewardList" open-type="navigate" class='rewardList a-link' hover-class="none">赞赏记录</navigator>
		</view>

	</view>
</template>

<script>
	export default {
		data() {
			return {

			}
		},
		methods: {
			viewImg(e) {
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
	page {
		padding: 0;
		background: #FFFFFF;
	}

	.rewardImg {
		width: 497px;
		height: 563px;
		zoom: 0.6;
	}

	.tipsCon {
		margin-top: 20px;
		font-size: 20px;
		text-align: center;
		color: #8f8f8f;
	}

	.tipsCon view {
		margin: 10px 0;
	}

	.rewardList {
		margin-top: 30px;
		font-size: 17px;
	}
</style>
