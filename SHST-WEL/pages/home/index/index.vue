<template>
	<view>
		<view class="page">
			<view class="page__hd">
				<view class="page__title">
					<view class="welcome">
						<view class="welcome-left">山科小站</view>
						<view class="welcome-right"> -- 迎新专版</view>
					</view>
				</view>
				<view class="page__desc"></view>
			</view>
			<view class="page__bd page__bd_spacing">
				<view class="kind-list">
					<block v-for="(item,index) in list" :key="index">
						<view class="kind-list__item">
							<view @click="kindToggle(item.id)">
								<view hidden="true" :value="item.id" name="id"></view>
								<view class="weui-flex kind-list__item-hd list-btn" v-bind:class="{'kind-list__item-hd_show': item.open}">
									<view class="weui-flex__item">{{item.name}}</view>
									<image class="kind-list__img" :src="'/static/img/icon_nav_'+item.id+'.png'"></image>
								</view>
							</view>
							<view class="kind-list__item-bd" v-bind:class="{'kind-list__item-bd_show': item.open}">
								<view class="weui-cells" :class="{'weui-cells_show': item.open}">
									<block v-for="(page,pageindex) in item.pages" :key="pageindex">
										<navigator :url="item.url[pageindex]" open-type="navigate" class="navto">
											<view class="weui-cell__bd font-set">{{page}}</view>
											<view class="weui-cell__ft weui-cell__ft_in-access"></view>
										</navigator>
									</block>
								</view>
							</view>
						</view>
					</block>
				</view>
			</view>
		</view>
		<view class="weui-footer" style="margin: 30px 0;">
			<view class="weui-footer__text flex-footer">
				<button open-type="share">分享</button>
				<view style="margin:0 5px;">|</view>
				<button @tap="toAbout">关于</button>
			</view>
			<view class="weui-footer__text">Copyright © 2019 WindrunnerMax</view>
		</view>
	</view>
</template>

<script>
	export default {
		// components:{info},
		data() {
			return {
				list: [{
						id: "feedback",
						name: "新生指南",
						open: true,
						pages: ["来校路线", "防偷防骗", "常用缴费", "新生军训"],
						url: ["/pages/life/traffic/traffic", "/pages/new-stu/antifraud/antifraud", "/pages/new-stu/payment/payment",
							"/pages/new-stu/miltrain/miltrain"
						]
					},
					{
						id: "form",
						name: "科大生活",
						open: false,
						pages: ["嵙地图", "校历", "放假安排"],
						url: ["/pages/sdust/map/map", "/pages/sdust/calendar/calendar", "/pages/sdust/vacation/vacation"]
					},
					{
						id: "widget",
						name: "学习相关",
						open: false,
						pages: ["时间", "常用链接", "转专业相关", "社团一览表"],
						url: ["/pages/study/time/time", "/pages/study/link/link", "/pages/study/major/major",
							"/pages/study/league/league"
						]
					},
					{
						id: "nav",
						name: "生活指南",
						open: false,
						pages: ["用电相关", "机房相关", "宿舍相关", "网络相关", "餐厅相关", "洗浴相关", "医疗相关", "早起相关", "快递相关"],
						url: ["/pages/life/power/power", "/pages/life/computer/computer", "/pages/life/living/living",
							"/pages/life/network/network", "/pages/life/canteen/canteen", "/pages/life/shower/shower",
							"/pages/life/medical/medical", "/pages/life/getup/getup", "/pages/life/express/express"
						]
					}
				],
				version: getApp().globalData.version
			}
		},
		methods: {
			onShareAppMessage: function() {
				return {
				      title: "山科小站",
				      desc: "山科小站迎新专版",
				      path: "pages/home/index/index",
				    };
			},
			toAbout() {
				wx.navigateTo({
					url: "/pages/user/about/about"
				})
			},
			kindToggle: function(id) {
				var list = this.list;
				for (var i = 0, len = list.length; i < len; ++i) {
					if (list[i].id == id) list[i].open = !list[i].open;
                    else list[i].open = false;
				}
				this.data = list;
			}
		}
	}
</script>

<style>
	/**index.wxss**/
	page {
		background-color: #F8F8F8;
	}

	.welcome {
		display: flex;
	}

	.font-set {
		font-size: 15px;
		padding: 5px;
	}

	.welcome-left {
		font-size: 20px;
		align-self: flex-end;
	}

	.welcome-right {
		font-size: 13px;
		align-self: flex-end;
		margin-left: 5px;
	}

	.weui-flex {
		-webkit-box-align: center;
		-webkit-align-items: center;
		align-items: center
	}

	.weui-cells {
		margin-top: 0;
		opacity: 0;
		-webkit-transform: translateY(-50%);
		transform: translateY(-50%);
		-webkit-transition: .3s;
		transition: .3s
	}

	.weui-cells:after,
	.weui-cells:before {
		display: none
	}

	.weui-cells_show {
		opacity: 1;
		-webkit-transform: translateY(0);
		transform: translateY(0)
	}

	.weui-cell:before {
		right: 15px
	}

	.kind-list__item {
		margin: 10px 0;
		background-color: #fff;
		border-radius: 2px;
		overflow: hidden
	}

	.kind-list__item:first-child {
		margin-top: 0
	}

	.kind-list__img {
		width: 30px;
		height: 30px
	}

	.kind-list__item-hd {
		padding: 20px;
		-webkit-transition: opacity .3s;
		transition: opacity .3s
	}

	.kind-list__item-hd_show {
		opacity: .4
	}

	.kind-list__item-bd {
		height: 0;
		overflow: hidden
	}

	.kind-list__item-bd_show {
		height: auto
	}

	.navto {
		display: flex;
		padding: 10px;
		box-sizing: border-box;
	}

	.page__hd {
		padding: 30px;
	}

	.flex-footer {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	button:after {
		border: none;
	}

	button {
		background: #fff;
		border: none;
		box-sizing: unset;
		padding: 0;
		margin: 0;
		font-size: 13px;
		background: #F8F8F8;
		color: #888888;
		line-height: unset;
		height: auto;
	}

	.about {
		font-size: 23rpx;
		color: #888888;
	}

	.list-btn {
		text-align: left;
		background: #fff;
		color: #000;
		font-size: unset;
	}
</style>
