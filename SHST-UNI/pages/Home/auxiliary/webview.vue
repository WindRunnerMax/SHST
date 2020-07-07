<template>

        <web-view :src="url"></web-view>

</template>

<script>
    export default {
        data() {
            return {
                url: ""
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
