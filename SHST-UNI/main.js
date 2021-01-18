import Vue from "vue";
import App from "./App";
// import store from "./store";
import mixin from "./vector/mixins";
import layout from "@/components/layout/layout.vue";

Vue.config.productionTip = false;

App.mpType = "app";

mixin.run(Vue);
Vue.component("layout",layout);

// Vue.prototype.$store = store;

const app = new Vue({
    ...App,
    // store
})
app.$mount();
