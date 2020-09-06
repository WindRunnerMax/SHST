<template>
	<view>
		<list title="洗浴相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
					"1. 中心浴室，没有隔间(俗称大澡堂)，用校园卡计水量收费，需自带十元零钱押金。周二至周天12:00-20:00营业(19:30后只出不进),周一关门。军训期间会延长营业时间。",
					"2. 单间可以去北门或者西村，价格偏贵(尤其是最近很多人在说他们涨价很厉害)",
					"3. A区和C区某些宿舍楼中有浴室(隔间)，B区没有"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
