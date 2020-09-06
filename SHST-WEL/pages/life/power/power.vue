<template>
	<view>
		<list title="用电相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
					"1. 宿舍每学期有150度电，开学一次性充予宿舍",
					"2. 宿舍度数用尽需要充电，一元两度电，充电地点：大活208，时间：16:10-17:00",
					"3. 宿舍用电超功率会跳闸，同学最大使用到220W。注意：似乎学校还会检测波形，吹风机这种转化为内能的用电器特别容易跳闸"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
