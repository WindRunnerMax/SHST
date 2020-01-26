<template name="headslot">
	<view>

		<view class="line">
			<view class="left">
				<view class="row" v-bind:style="{'background-color': color}"></view>
				<view class="title">{{title}}</view>
			</view>
			<view style="margin-top: 3px;">
				<slot></slot>
			</view>
		</view>

	</view>
</template>
<script>
	export default {
		name: "headslot",
		props: {
			title: {
				type: String
			},
			color: {
				type: String,
				default: "#79B2F9"
			},
		},
		methods: {}
	}
</script>
<style>
	.line {
		background-color: #FFFFFF;
		padding: 10px 5px;
		box-sizing: border-box;
		display: flex;
		border-bottom: 1px solid #EEEEEE;
		justify-content: space-between;
	}
	.row {
		width: 2px;
		margin: 2px 5px;
	}
	.left{
		display: flex;
		justify-content: center;
	}
	.title{
		display: flex;
		justify-content: center;
		align-items: center;
		white-space: nowrap;
	}
</style>
