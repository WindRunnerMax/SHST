<template>
	<view>

		<layout title="图书检索">
			<view style='display:flex;justify-content: center;margin: 10px 0 6px 0;'>
				<input class='asse-input' @input="bookInput" :value="book"></input>
				<view class='asse-btn asse-btn-blue' @tap='query'>检索</view>
			</view>
		</layout>

		<view v-for="(item,index) in info" :key="index">
			<layout>
				<view style='display:flex;justify-content: space-between;' @tap='jump' :data-id="item.id">
					<view class='leftInfo'>
						<view>{{item.infoList[0]}}</view>
						<view>{{item.infoList[1]}}</view>
						<view>{{item.infoList[2]}}</view>
						<view>{{item.infoList[3]}}</view>
					</view>
					<view class='rightJump'>
						<view> > </view>
					</view>
				</view>
			</layout>
		</view>

		<layout v-if="show">
			<view class='operat'>
				<view style='display:flex;'>
					<view @tap='pre' class='asse-btn asse-btn-blue'>上一页</view>
					<view @tap='next' class='asse-btn asse-btn-blue'>下一页</view>
				</view>
				<view>{{pageInfo}}</view>
			</view>
		</layout>

		<layout title="Tips:" v-if="!show">
			<view class="card">
				<view>1.图书馆的服务器挺容易崩溃的，如果出现PARSE ERROR，那一般是学校图书馆崩溃了</view>
				<view>2.学校图书馆外网访问好像会定时关闭，正常使用时间大约是在 7:00-22:00</view>
			</view>

		</layout>


	</view>
</template>

<script>
	const app = getApp();
	const util = require("@/utils/util.js")
	export default {
		data() {
			return {
				book: "",
				page: 1,
				show: 0,
				pageInfo: "",
				info: []
			}
		},
		onLoad: function(options) {
			var curData = new Date();
			var startTime = "7:00";
			var endTime = "22:30";
			var curTime = curData.getHours() + ":" + curData.getMinutes();
			if (util.compareTimeInSameDay(startTime, curTime) || util.compareTimeInSameDay(curTime, endTime)) {
				app.toast("当前时间图书馆服务已关闭");
				return;
			}
		},
		methods: { 
			bookInput(e) {
				this.book = e.detail.value
			},
			query(e) {
				var param = "?q=" + this.book;
				if (typeof(e) === "number") {
					param += "&page=" + e;
				}
				var that = this;
				app.ajax({
					load: 2,
					url: "http://interlib.sdust.edu.cn/opac/m/search" + param,
					data: {
						"searchType": "standard",
						"isFacet": "true",
						"view": "standard",
						"rows": "10",
						"displayCoverImg": ""
					},
					fun: function(res) {
						var bookList = [];
						var repx = /<li onclick.*?>[\s\S]*?<\/li>/g;
						var pageInfo = res.data.match(/第[\S]*页/);
						res.data.match(repx).forEach(function(value, index, array) {
							var listObject = {};
							var infoBookFour = [];
							repx = /<em>.*<\/em>/g;
							value.match(repx).map(function(valueBook, indexBook, arrayBook) {
								valueBook = valueBook.replace("<em>", "");
								valueBook = valueBook.replace("</em>", "");
								infoBookFour.push(valueBook);
								return value;
							})
							listObject.infoList = infoBookFour;
							repx = /javascript:bookDetail(.)*/g;
							listObject.id = value.match(repx)[0].match(/[0-9]+/)[0];
							bookList.push(listObject);
						})
						that.info = bookList
						that.page = typeof(e) === "number" ? e : 1
						that.pageInfo = pageInfo[0]
						that.show = 1

					}
				})
			},
			pre() {
				var curPage = parseInt(this.page);
				if (curPage <= 0) return;
				this.query(curPage - 1);
			},
			next() {
				var curPage = parseInt(this.page);
				this.query(curPage + 1);
			},
			jump(e) {
				if (!e.currentTarget.dataset.id) {
					app.toast("出错了");
					return;
				}
				uni.navigateTo({
					url: "libDetail?id=" + e.currentTarget.dataset.id
				})
			}
		}
	}
</script>

<style>
	.asse-input {
		align-self: center;
		border: 1px solid #eee;
		margin: 0;
		display: block;
		height: 26px;
		width: 150px;
		margin-right: 10px;
	}

	.leftInfo:first-line {
		font-size: 18px;
	}

	.info {
		margin-top: 3px;
	}

	.rightJump {
		width: 50px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		border-left: 1px solid #eee;
	}

	.operat {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.card {
		line-height: 27px;
	}
</style>
