<template>

	<web-view :src="url" :webview-styles="webviewStyles"></web-view>

</template>

<script>
	export default {
		data() {
			return {
				url: "",
				webviewStyles: {
					progress: {
						color: '#4C98F7'
					}
				}
			}
		},
		onLoad: function(options) {
			this.url = decodeURIComponent(options.url);
		},
		methods: {

		}
	}
</script>

<style>

</style>
