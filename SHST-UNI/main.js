import Vue from "vue"
import App from "./App"

Vue.config.productionTip = false

App.mpType = "app"

const app = new Vue({
    ...App
})
app.$mount()

import layout from "@/components/layout/layout.vue";
Vue.component("layout",layout);
