<template>
    <div id="app" class="x-CenterCon">
        <el-col :xs="24" :sm="17" :md="15" :lg="12" :xl="10">
            <router-view />
        </el-col>
    </div>
</template>

<script>
    console.log(process.env.NODE_ENV);
</script>

<style>
    @import url("vector/asse.min.css");
    .notice{
        line-height: 27px;
        color: #666;
    }
</style>
