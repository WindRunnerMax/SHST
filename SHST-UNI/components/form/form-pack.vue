<template>
    <view>

        <slot></slot>

    </view>
</template>

<script>
    import {defineInfo, verifyRules, verifyRulesUnit} from "./form-utils.js";
    export default {
        components: {},
        data: function(){
            return {
                info: defineInfo(this.rules)
            }
        },
        provide: function(){
            return {
                info: this.info
            }
        },
        props: {
            formData: {
                type: Object,
                default: () => ({})
            },
            rules: {
                type: Object,
                default: () => ({})
            },
        },
        beforeCreate: function() {},
        created: function() {},
        filters: {},
        computed: {},
        created: function() {
            uni.$app.eventBus.on("VerifyUnitAction", this._verifyUnit);
        },
        beforeDestroy:function(){
            uni.$app.eventBus.off("VerifyUnitAction", this._verifyUnit);
        },
        methods: {
            verifyRules: function(){
                const result = verifyRules(this.formData, this.rules, this.info);
                console.log("verify", result);
                return result;
            },
            _verifyUnit: function(rule){
                const rules = this.rules[rule];
                const value = this.formData[rule];
                verifyRulesUnit(value, rules, this.info[rule]);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
