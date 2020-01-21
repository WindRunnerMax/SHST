<template>
	<view>

		<layout title="共享课表">
			<view class='top' v-if="data.status === 1">
				<view class="x-CenterCon" style="flex-direction: column;">
					<input class='a-input' @input="accountInput" placeholder='对方学号' :value="account"></input>
					<input class='a-input' @input="nameInput" style='margin-top:5px;' placeholder='对方姓名' :value="name"></input>
					<view class='a-btn a-btn-blue' @tap='req'>发起请求</view>
				</view>
			</view>

			<view class='top' v-if="data.status === 2">
				<view style='display:flex;align-items: center;'>
					<view>{{data.pair_user[0]}}</view>
					<view style='margin-left:5px;'>{{data.pair_user[1]}}</view>
					<view class='a-btn a-btn-blue a-btn-mini' @tap='cancelreq'>撤销请求</view>
				</view>
			</view>

			<view class='a-hr' v-if="data.status !== 0"></view>

			<view class='top' v-if="data.status !== 0">
				<view v-for="(item,index) in data.data" style='display:flex;align-items: center;' :key="index">
					<view>{{item.account}}</view>
					<view style='margin-left:5px;'>{{item.name}}</view>
					<view class='a-btn a-btn-blue a-btn-mini' :data-id="item.id" @tap='agree'>同意</view>
					<view class='a-btn a-btn-blue a-btn-mini' :data-id="item.id" @tap='refuse'>拒绝</view>
				</view>
			</view>

			<view v-if="data.status === 0">
				<view v-for="(item,index) in [0,1,2,3,4]" :key="index">
					<view class='line'>
						<view v-for="(inner,innerIndex) in [0,1,2,3,4,5,6]" :key="innerIndex" style='width:calc(100% / 7);margin-left:3px;background:#eee;'>
							<view class='tableUnitCon' v-if="(data.succ.timetable1[inner] && data.succ.timetable1[inner][item]) || (data.succ.timetable2[inner] && data.succ.timetable2[inner][item])">

								<view v-if="(data.succ.timetable2[inner] && data.succ.timetable2[inner][item])" class='timetableHideBot' style='background:rgb(100, 149, 237);'>
									<view>{{data.succ.timetable2[inner][item][2]}}</view>
									<view>{{data.succ.timetable2[inner][item][4]}}</view>
								</view>
								<view v-else>
									<view class='timetableHideBot'></view>
								</view>


								<view v-if="(data.succ.timetable1[inner] && data.succ.timetable1[inner][item])" class='timetableHideTop' style='background:rgb(234, 167, 140);}}'>
									<view>{{data.succ.timetable1[inner][item][2]}}</view>
									<view>{{data.succ.timetable1[inner][item][4]}}</view>
								</view>
								<view v-else>
									<view class='timetableHideTop'></view>
								</view>

							</view>
							<view v-else>
								<view class='timetableHide'></view>
							</view>
						</view>
					</view>
					<view class="a-hr timetablehr"></view>
				</view>
				<view class='a-hr'></view>
				<view style='display:flex;align-items: center;'>
					<view>{{data.user}} -</view>
					<view> {{data.succ.pair}}</view>
					<view class='a-btn a-btn-blue a-btn-mini' :data-id="data.succ.id" @tap='lifting'>解除关系</view>
				</view>
			</view>
		</layout>

		<layout title="Tips" v-if="data.status === 1">
			<view>1.向对方发起请求(对方必须是正常登陆过软件或者小程序才可以)，对方通过后，你们将能够在此看到自己与对方的课表</view>
		</layout>
	</view>
</template>

<script>
	const app = getApp();
	const pubFct = require('@/vector/pubFct.js');
	export default {
		data() {
			return {
				data: [],
				account: "",
				name: ""
			}
		},
		onLoad: function(options) {
			this.onloadData();
		},
		methods: {
			onloadData: function(){
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + "share/tableshare",
					data: {
						week: app.globalData.curWeek,
						term: app.globalData.curTerm
					},
					fun: res => {
						if (res.data.info.succ) {
							res.data.info.succ.timetable1 = pubFct.tableDispose(res.data.info.succ.timetable1);
							res.data.info.succ.timetable2 = pubFct.tableDispose(res.data.info.succ.timetable2);
						}
						console.log(res.data.info);
						that.data = res.data.info
					}
				})
			},
			accountInput(e) {
				this.account = e.detail.value
			},
			nameInput(e) {
				this.name = e.detail.value
			},
			req() {
				var that = this;
				console.log(this)
				if (this.account === "" | this.name === "") {
					app.toast("请输入完整信息");
				}
				app.ajax({
					url: app.globalData.url + "share/startReq",
					method: 'POST',
					data: {
						account: this.account,
						user: this.name
					},
					fun: res => {
						app.toast(res.data.message);
						that.onloadData();
					}
				})
			},
			cancelreq() {
				var that = this;
				app.ajax({
					url: app.globalData.url + "share/cancelReq",
					fun: res => {
						app.toast(res.data.message);
						that.onloadData();
					}
				})
			},
			agree(e) {
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + "share/agreereq",
					data: {
						id: e.currentTarget.dataset.id
					},
					fun: res => {
						app.toast(res.data.message);
						that.onloadData();
					}
				})
			},
			lifting(e) {
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + "share/lifting",
					data: {
						id: e.currentTarget.dataset.id
					},
					fun: res => {
						app.toast("成功");
						that.onloadData();
					}
				})
			},
			refuse(e) {
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + "share/refusereq",
					data: {
						id: e.currentTarget.dataset.id
					},
					fun: res => {
						app.toast(res.data.message);
						that.onloadData();
					}
				})
			}
		}
	}
</script>

<style>

	.top {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
	}

	.a-input {
		align-self: center;
		border: 1px solid #eee;
		width: 200px;
		border-radius: 3px;
	}

	.a-hr {
		margin-bottom: 3px;
	}


	.line {
		display: flex;
	}

	.info {
		margin-left: 7px;
	}
	.a-hr {
		margin-bottom: 3px;
	}


	.timetableHide {
		min-height: 230px;
		border-radius: 3px;
		margin-left: 3px;
		text-align: center;
		word-break: break-all;
		color: #fff;
		padding: 1px;
		background: #eee;
		font-size: 13px;
	}

	.timetablehr {
		margin-top: 3px;
	}

	.a-hr {
		background-color: #eee !important;
		height: 1px;
		border: none;
	}

	.tableUnitCon {
		display: flex;
		flex-wrap: wrap;
		height: 100%;
	}

	.timetableHideTop {
		min-height: 115px;
		text-align: center;
		word-break: break-all;
		color: #fff;
		padding: 1px;
		background: #eee;
		font-size: 13px;
		border-bottom: 1px solid #eee;
	}

	.timetableHideBot {
		min-height: 115px;
		text-align: center;
		word-break: break-all;
		color: #fff;
		padding: 1px;
		background: #eee;
		font-size: 13px;
	}

	.card,
	.cardTips {
		padding: 10px;
		line-height: 23px;
	}
	.info1 {
		margin-top: 3px;
	}
</style>
