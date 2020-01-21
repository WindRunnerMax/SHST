<template name="layout">
	<view>

		<view class="lay-line" v-show="title">
			<view class="lay-leftCon">
				<view class="lay-verline" v-bind:style="{'background-color': color}"></view>
				<view>{{title}}</view>
			</view>
			<view >
				<slot name="headslot"></slot>
			</view>
		</view>
		<view class='lay-card' :class="{'lay-minPaddingTop':title}">
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
	.lay-line {
		background-color: #FFFFFF;
		padding: 12px 5px 10px 5px;
		box-sizing: border-box;
		display: flex;
		border-bottom: 1px solid #EEEEEE;
		justify-content: space-between;
		align-items: center;
	}

	.lay-verline {
		width: 2px;
		margin: 2px 5px;
	}

	.lay-card {
		font-size: 13px;
		background-color: #FFFFFF;
		padding: 11px;
		box-sizing: border-box;
		margin-bottom: 10px;
	}
	.lay-minPaddingTop{
		padding-top: 3px;
	}
	.lay-leftCon{
		display: flex;
	}
</style>
