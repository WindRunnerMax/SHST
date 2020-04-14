<template>
	<view>

		<layout title="图书信息">
			<view class="lineH">
				<view class="strong">{{data.name}}</view>
				<view>{{data.bookInfoArray[0]}}</view>
				<view>{{data.bookInfoArray[1]}}</view>
				<view>{{data.bookInfoArray[2]}}</view>
			</view>

		</layout>

		<layout title="馆藏信息">
			<view class="lineH">
				<view v-for="(item,index) in data.bookStroageArray" :key="index">
					<view v-if="index % 4 === 0 && index !== 0" style='width:100%;height:20px;'></view>
					<view>{{item}}</view>
				</view>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp()
	import {regMatch} from "@/utils/util.js";
	export default {
		data() {
			return {
				data: {
					bookInfoArray: []
				}
			}
		},
		onLoad: async function(e) {
			var res = await app.request({
				load: 2,
				url: app.globalData.url + "lib/detail",
				data: {
					id: e.id
				},
			})
			try{
				if (res.data.Message === "Yes") {
					var bookInfo = {};
					bookInfo.name = regMatch(/<h2>(.*?)<\/h2>/g,res.data.info)[0];
					var bookInfoArray = [];
					regMatch(/<tr><td>([\S]*?)<\/?td><\/tr>/g,res.data.info).forEach(value => bookInfoArray.push(value));
					var liBookInfo = regMatch(/<ul data-role="listview">[\s\S]*?<\/ul>/g,res.data.info)[0];
					var bookStroageArray = regMatch(/<p.*?>(.*?)<\/p>/g,liBookInfo);
					bookInfo.bookInfoArray = bookInfoArray;
					bookInfo.bookStroageArray = bookStroageArray;
					this.data = bookInfo
				} else {
					app.toast("External Error");
				}
			}catch(e){
				console.log(e);
				app.toast("Internal Error");
			}
		},
		methods: {

		}
	}
</script>

<style>
	.strong {
		font-size: 23px;
		line-height: 30px;
		margin-top: 10px;
	}

	.lineH {
		line-height: 27px;
	}
</style>
