<template>
	<view>
		<list title="医疗相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
					"1. 学校在C区有校医院(口碑一直不好)，小病可以去校医院医治",
					"2. 生病比较严重的，可以于北门乘坐六路车至西海岸医院，也可以于南门乘坐10路车至中医院",
					"3. 学校会发社保卡，激活以后去校医院挂号买药相当便宜，特别是感冒药"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
