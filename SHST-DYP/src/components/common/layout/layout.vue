<template>
	<div>

		<div class="line" v-show="title">
			<div class="left" v-bind:style="{'background-color': color}"></div>
			<div class="right">{{title}}</div>
		</div>
		<div class='card'>
			<slot></slot>
		</div>

	</div>
</template>
<script>
	export default {
		name: "layout",
		props: {
			title: {
				type: String
			},
			color: {
				type: String,
				default: "#79B2F9"
			},
		},
		methods: {}
	}
</script>
<style>
	.line {
		background-color: #FFFFFF;
		padding: 12px 5px 10px 5px;
		box-sizing: border-box;
		display: flex;
		border-bottom: 1px solid #EEEEEE;
	}

	.line .left {
		width: 2px;
		margin: 2px 5px;
	}

	.card {
		font-size: 13px;
		background-color: #FFFFFF;
		padding: 11px;
		box-sizing: border-box;
		margin-bottom: 10px;
	}

</style>
