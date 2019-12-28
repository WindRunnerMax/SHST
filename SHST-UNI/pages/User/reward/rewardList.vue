<template>
	<view>

		<headslot title="赞赏列表"></headslot>
		<view style="margin-top: 10px;"></view>

		<layout v-for="(item,index) in data" :key="index">
			<view class='infoCon'>
				<view class='left'>
					<view class='name'>{{item.name}}</view>
					<view class='time'>{{item.reward_time}}</view>
				</view>
				<view class='amount' style="color: #4C98F7;">{{item.amount}}</view>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp()
	import headslot from "@/components/headslot.vue"
	export default {
		components: {
			headslot
		},
		data() {
			return {
				data: []
			}
		},
		onLoad: function(options) {
			var that = this;
			app.ajax({
				load: 2,
				url: app.globalData.url + 'ext/rewardlist',
				fun: res => {
					if (res.data.info) {
						res.data.info.reverse();
						that.data = res.data.info
					}
				}
			})
		},
		methods: {

		}
	}
</script>

<style>
	.infoCon {
		font-size: 13px;
		line-height: 23px;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.name {
		font-size: 16px;
	}

	.time {
		font-size: 12px;
		color: #aaa;
		margin-top: 5px;
	}

	.amount {
		font-size: 17px;
		margin-right: 5px;
	}
</style>
