<template>
	<view>
		<list title="餐厅相关" :info="data" ></list>
	</view>
</template>

<script>

	export default {
		data() {
			return {
				data: [
					"1. 分为ABC三个餐厅，A餐：学苑餐厅，B餐：学海餐厅，C餐：学者餐厅",
					"2. 三个餐厅一楼二楼都是就餐区域，A餐三楼有超市、健身房、台球厅、网吧等；B餐三楼是教职工餐厅，四楼有网吧等；C餐三楼为书香人家",
					"3. 所有餐厅必须使用校园卡支付，不收现金",
					"4. 个人认为A餐的饭菜窗口很贵，但是特色窗口好吃；B餐和C餐一楼的饭菜便宜，但是没有特色菜窗口，而且有时候放很多盐；C餐二楼饭菜偏贵",
					"5. 学校北门以及南门有很多饭店和小吃店"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
