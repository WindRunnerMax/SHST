<template name="layout">
	<view>

		<view class="line" v-show="title">
			<view class="left" v-bind:style="{'background-color': color}"></view>
			<view class="right">{{title}}</view>
		</view>
		<view class='card' :class="{'minPaddingTop':title}">
			<slot></slot>
		</view>

	</view>
</template>
<script>
	export default {
		name: "layout",
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
		padding: 12px 5px 10px 5px;
		box-sizing: border-box;
		display: flex;
		border-bottom: 1px solid #EEEEEE;
	}

	.line .left {
		width: 2px;
		margin: 2px 5px;
	}

	.card {
		font-size: 13px;
		background-color: #FFFFFF;
		padding: 11px;
		box-sizing: border-box;
		margin-bottom: 10px;
	}
	.minPaddingTop{
		padding-top: 3px;
	}
</style>
