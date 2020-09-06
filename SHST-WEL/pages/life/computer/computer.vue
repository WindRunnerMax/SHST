<template>
	<view>
		<list title="机房相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
					"1. 上课安排的上机(J1)是免费的",
					"2. 每人每学期在J1有20小时免费上机时间,超时后直接扣校园卡收费(具体价格不清楚，可能是3元/h)",
					"3. 非课程安排去J1机房是不允许携带电脑的",
					"4. J13(计算机学院楼)上机是免费的，1楼与3楼机房只要开门并且近期无考试是允许所有同学入内的，可以携带自己的笔记本电脑"
				]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
