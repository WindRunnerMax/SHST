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
	export default {
		data() {
			return {
				data: []
			}
		},
		onLoad: function(e) {
			var that = this;
			if(!e.id) {
				app.toast("ERROR");
				return ;
			}
			app.ajax({
				load: 2,
				url: "http://interlib.sdust.edu.cn/opac/m/book/"+e.id,
				fun: function(res) {
						var bookInfo = {};
						var repx = /<table.*?>[\s\S]*?<\/table>/;
						var bookInfoString = res.data.match(repx);
						repx = /<h2>.*?<\/h2>/;
						bookInfo.name = bookInfoString[0].match(repx)[0].replace("<h2>", "").replace("</h2>", "");
						repx = /<tr><td>.*<\/td><\/tr>/g;
						var bookInfoArray = [];
						bookInfoString[0].match(repx).map((value, index) => {
							bookInfoArray.push(value.replace("<tr><td>", "").replace("</td></tr>", ""));
							return value;
						})
						var bookStroageArray = [];
						repx = /<li>[\s\S]*?<\/li>/g;
						bookInfoString = res.data.match(repx);
						repx = /<p.*>.*<\/p>/g;
						bookInfoString.forEach(value => {
							var stroageMatch = value.match(repx);
							if (stroageMatch) {
								stroageMatch.map((value, index) => {
									bookStroageArray.push(value.replace(/<p.*?>/, "").replace("</p>", ""));
									return value;
								})
							}
						})
						bookInfo.bookInfoArray = bookInfoArray;
						bookInfo.bookStroageArray = bookStroageArray;
						that.data = bookInfo
				}
			})
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
