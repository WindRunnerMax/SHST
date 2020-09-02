<template>
	<view>
		<list title="网络相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
					"1. 校内的无线有CMCC与Giwifi，有线有电信宽带(原快通)与Giwifi有线业务",
					"2. 办理移动卡会赠送CMCC，具体时长需要参见每年的优惠政策，CMCC限速500k/s",
					"3. 往年办联通卡会赠送Giwifi一学期，Giwifi不同的价格有不同的速度，换算：(m/s) = (Mbps)/8",
					"4. 校内网可以免费下载百度文库文档以及中国知网论文",
					"5. 移动教室信号不太好，联通在部分宿舍信号不太好",
					"6. 无论入网哪个运营商，流量都是相当多的，基本50G起步，但是用超过一定量会限速，具体优惠量还得参见每年的政策"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
