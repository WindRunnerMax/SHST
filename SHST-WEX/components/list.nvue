<template name="list">
	<view>
		
		<view class="line">
			<view class="left" v-bind:style="{'background-color': color}"></view>
			<view class="right">{{title}}</view>
		</view>
		<view v-for="(item,index) in info" :key='index'>
			<view class='card'>
				<text >{{item}}</text>
			</view>
		</view>
		
	</view>
</template>S
<script>
	export default {
		name: "list",
		props: {
			title: {
				type: String, 
				default: "Default"
			},
			color: {
				type: String,
				default: "#79B2F9"
			},
			info: {
				type: Array
			}
		},
		methods: {}
	}
</script>
<style>
	.line{
		background-color: #FFFFFF;
		padding:10px 5px;
		box-sizing: border-box;
		display: flex;
		margin-bottom: 10px;
	}
	.line .left{
		width: 2px;
		margin: 2px 5px;
	}
	.card{
		font-size: 13px;
		background-color: #FFFFFF;
		padding: 15px;
		box-sizing: border-box;
		margin-bottom: 10px;
	}

</style>
