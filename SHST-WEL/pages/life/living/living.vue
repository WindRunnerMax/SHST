<template>
	<view>
		<list title="宿舍相关" :info="data"></list>
		<list title="上床下桌" ></list>
		<image mode="widthFix" style='width:100%;' src='https://windrunner_max.gitee.io/imgpath/SHST/Static/MMRoom.png' ></image>
		<list title="普通上下铺"></list>
		<image mode="widthFix" style='width:100%;' src='https://windrunner_max.gitee.io/imgpath/SHST/Static/GGRoom.png' ></image>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data:[
				      "1. 宿舍分为ABC三区，A区有本科女生宿舍，研究生宿舍，留学生宿舍和外教宿舍；B区有B6电子学院女生宿舍，其他都是男生宿舍；C区大部分都是男生宿舍，少部分女生宿舍和研究生宿舍",
				      "2. 大部分宿舍是6人间，少部分4人间，4人间每人每年1000，六人间每人每年800",
				      "3. 男生宿舍大部分是上下铺，少部分是上床下桌，宿舍是按照姓名分配，住哪种宿舍是随机的",
				      "4. 女生宿舍绝大部分都是上床下桌",
				      "5. 每年的6-7月份，各个学院会组织搬宿舍(有些学院年级不会搬动)",
				      "6. 理论上大学四年是两年阳面两年阴面(当然也有像我一样大学四年阴面)",
				      "7. 基本所有本科宿舍都没有独立卫浴，都没有空调，除7-8月(很热的时候)之外每晚十点半断电，周六12电熄灯，宿舍内有阳台",
				      "8. 学校规定 吹风机、电热毯、卷发棒、锅以及充电台灯等都属于违禁电器"
				    ]
			}
		},
		methods: {

		}
	}
</script>

<style>

</style>
