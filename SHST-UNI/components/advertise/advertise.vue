<template>
	<view>
        <!-- #ifdef MP-WEIXIN -->
        <!-- Funct -->
        <ad v-if="computedAdSelect === 0" unit-id="adunit-b82100ae7bddf4ad" @error="adError" class="adapt"></ad>
        <!-- Native-Card-Video -->
        <ad-custom v-if="computedAdSelect === 1" @error="computedAdSelect = 2" class="adapt" unit-id="adunit-b9b2fd0e829c7388"></ad-custom>
        <!-- Native-Banner -->
        <ad-custom v-if="computedAdSelect === 2" @error="computedAdSelect = 0" class="adapt" unit-id="adunit-8fcb99da029141d0" ></ad-custom>
        <!-- Native-Banner-Video -->
        <ad-custom v-if="computedAdSelect === 3" @error="computedAdSelect = 2" class="adapt" unit-id="adunit-281c97c91ba73fd7"></ad-custom>
        <!-- Table -->
        <ad v-if="computedAdSelect === 4" unit-id="adunit-ce81890e6ff0b2a7"  @error="adError" class="adapt"></ad>
        <!-- Grade -->
        <ad v-if="computedAdSelect === 5" unit-id="adunit-31c347091893cf0c"  @error="adError" class="adapt"></ad>
        <!-- Video -->
        <ad v-if="computedAdSelect === 6" unit-id="adunit-d4a5485ea69b2794" ad-type="video" @error="adError" class="adapt"></ad>
        <!-- Games -->
        <ad v-if="computedAdSelect === 7" unit-id="adunit-a0ca2792308b3673" ad-type="grid" grid-count="8" @error="adError" class="adapt"></ad>
        <!-- #endif -->
        <!-- #ifdef MP-QQ -->
        <!-- Funct -->
        <ad v-if="adSelect === 0" unit-id="001b7e7e765436c6351d8a6d693437d2" @error="adError" class="adapt"></ad>
        <!-- Table -->
        <ad v-if="adSelect === 1" unit-id="98766bd6a7f4cc14e978058a3a365551" @error="adError" class="adapt"></ad>
        <!-- Grade -->
        <ad v-if="adSelect === 2" unit-id="e40bef6dbe8ecaf7104fe126bfc34e56" @error="adError" class="adapt"></ad>
        <!-- Card -->
        <ad v-if="adSelect === 3"  unit-id="e72775802b219a19e3709c61ab8ea055" type="card" @error="adError" class="adapt"></ad>
        <!-- #endif -->
	</view>
</template>

<script>
	export default {
        name: "advertise",
        created: function(){

        },
        props: {
            adSelect: { // 选择广告
                type: Number,
                default: 0
            },
            compatible : { // 兼容
                type: Number,
                default: 0
            }
        },
        computed: {
            // #ifdef MP-WEIXIN
            computedAdSelect: function(){
                /* 广告信息 */
                var sdkVersion = ~~(uni.getSystemInfoSync().SDKVersion.replace(/\./g, ""));
                if(sdkVersion < 299 && /[123]/.test(this.adSelect)) return this.compatible;
                else return this.adSelect;
            },
            // #endif
        },
        methods: {
            adError: function(){
                this.$emit("error");
            }
        }
	}
</script>

<style scoped>
    .adapt{
        box-sizing: border-box;
    }
</style>
