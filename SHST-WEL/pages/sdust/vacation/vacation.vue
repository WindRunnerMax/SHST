<template>
	<view>
		
		<list title="节假日安排"></list>
		<view v-for="(item,index) in data" :key="index">
			<layout>
				<view class="fline">
					<view class="dot" v-bind:style="{background: colorList[index]}"></view>
					<view>{{item.name}}</view>
					<view>{{item.v_time}}</view>
				</view>
				<view class="sline">{{item.info}}</view>
			</layout>
		</view>
		
	</view>
</template>

<script>
	import layout from "@/components/layout.vue"
	var colorList = ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC", "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF"]
	export default {
		components: {layout},
		data() {
			return {
				show: 0,
				data: [],
				colorList: colorList
			}
		},
		onLoad() {
			var that = this
			uni.request({
				url:"https://shst.touchczy.top/ext/vacation",
				header: {'content-type': 'application/x-www-form-urlencoded'},
				success: (res) => {
					that.data = res.data.info
					that.show = 1
				}
			})
		},
		methods: {
			
		}
	}
</script>

<style>
	.dot{
		width: 8px;
		height: 8px;
		border-radius: 8px;
	}
	.fline{
		display: flex;
		align-items: center;
	}
	.fline view{
		margin: 5px;
		font-size: 14px;
	}
	.sline{
		margin: 1px 23px;
	}
</style>
