<template>
	<view>
		<list title="早起相关" :info="data"></list>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data: [
				      "1. 所有学院大一都需要早起，采用 打早卡/跑操/打指纹 的方式强制早起",
				      "2. 某些学院甚至大二仍需要早起打卡",
				      "3. 另外说一下，几乎所有学院都需要上晚自习，晚上各班班长安排教室上晚自习"
				    ]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
