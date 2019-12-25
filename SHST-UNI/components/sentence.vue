<template name="sentence">
	<view>
		
		<view style="margin: 6px 0 8px 3px;">{{sentence}}</view>
		<view style="margin: 3px 0 8px 3px;">{{content}}</view>
		<image :src="url" mode="aspectFill"></image>

	</view>
</template>
<script>
	export default {
		name: "sentence",
		props: {},
		methods: {},
		data() {
			return {
				url: "",
				sentence: "",
				content: ""
			}
		},
		created: function() {
			var that = this;
			uni.request({
				url: "https://open.iciba.com/dsapi/",
				success: function(res) {
					that.url = res.data.picture2;
					that.sentence = res.data.note;
					that.content = res.data.content;
				}
			})
		}
	}
</script>
<style>
	image {
		width: 100%;
	}
</style>
