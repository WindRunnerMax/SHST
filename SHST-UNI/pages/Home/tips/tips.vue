<template>
	<view>
		
		<layout >
			<weather></weather>
		</layout>
		
		<layout :title="today">
			
		</layout>
		
		<layout title="每日一句">
			<sentence></sentence>
		</layout>
		
	</view>
</template>

<script>
	import weather from "@/components/weather.vue"
	import sentence from "@/components/sentence.vue"
	const app = getApp()
	const util = require("@/utils/util.js")
	export default {
		components: {
			weather,sentence
		},
		data() {
			return {
				today: util.formatDate("yyyy-MM-dd K")
			}
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
