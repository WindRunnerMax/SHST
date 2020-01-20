<template name="layout">
	<div>

		<div class="line" v-show="title">
			<div class="leftCon">
				<div class="verline" v-bind:style="{'background-color': color}"></div>
				<div>{{title}}</div>
			</div>
			<div >
				<slot name="headslot"></slot>
			</div>
		</div>
		<div class='card' :class="{'minPaddingTop':title}">
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
		justify-content: space-between;
		align-items: center;
	}

	.verline {
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
	.minPaddingTop{
		padding-top: 3px;
	}
	.leftCon{
		display: flex;
	}
</style>
