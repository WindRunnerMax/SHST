import Vue from 'vue'
import App from './App.vue'
import router from './router'
import dispose from '@/vector/dispose'


Vue.config.productionTip = false

import layout from "@/components/layout"
Vue.component('layout', layout);


var $app = {};
dispose.initApp();
dispose.extend($app,dispose);
if(!window.$app) window.$app = $app;

import { Form,FormItem,Input,Button,Col } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(Form)
Vue.use(FormItem)
Vue.use(Input)
Vue.use(Button)
Vue.use(Col)


new Vue({
  router,
  render: h => h(App)
}).$mount('#app')

