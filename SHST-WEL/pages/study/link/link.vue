<template>
	<view>

		<layout title="常用链接">
			<view v-for="(item,index) in data" :key="index">
				<view class="line">
					<view>{{item.name}}：</view>
					<view class="link" @tap="copy" :data-copy="item.url">{{item.url}}</view>
				</view>
			</view>
		</layout>

	</view>
</template>

<script>
	import layout from "@/components/layout.vue"
	export default {
		components: {
			layout
		},
		data() {
			return {
				data: []
			}
		},
		onLoad() {
			var that = this
			uni.request({
				url: "https://shst.touchczy.top/ext/urlshare",
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: (res) => {
					that.data = res.data.url
				}
			})
		},
		methods: {
			copy(e) {
				uni.setClipboardData({
					data: e.currentTarget.dataset.copy
				})
			}
		}
	}
</script>

<style>
	.line {
		display: flex;
		padding: 10px 5px;
		border-bottom: 1px solid #EEEEEE;
		flex-wrap: wrap;
	}
</style>
