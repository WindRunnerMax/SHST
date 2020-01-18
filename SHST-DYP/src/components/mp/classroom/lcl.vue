<template>
  <div>

  <meta content="white=1.0" />
	<iframe style='width: 1px; min-width: 100%; *width: 100%;position: fixed;height:100%;border:none;top:0;left: 0;' src='http://xxfw.sdust.edu.cn/skdDoor/KCB/BuildingCalendar.aspx'></iframe>

  </div>
</template>
<script>
  export default {
    data() {
      return {

      }
    },
    beforeCreate:function(){
      document.getElementsByTagName("meta").viewport.content = "";
    },
    beforeDestroy:function(){
      document.getElementsByTagName("meta").viewport.content = "width=device-width,initial-scale=1.0";
    },
    methods: {

    }

  }
</script>

<style>

</style>
