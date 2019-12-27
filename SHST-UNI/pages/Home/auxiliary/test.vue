<template>
	<view>
		
		<rich-text :nodes="html"></rich-text>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				html: ""
			}
		},
		onLoad:function(){
			var that = this;
			uni.request({
				url:"https://mp.weixin.qq.com/s/ggaC8YK7UgZ4FARCyfOu2A",
				success:function(res){
					
					that.html = res.data
					console.log(that.html)
				}
			})
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
