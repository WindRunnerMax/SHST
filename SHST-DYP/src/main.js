// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import dispose from '@/vector/dispose'
import util from '@/utils/util.js'

Vue.config.productionTip = false

import layout from '@/components/common/layout';
Vue.use(layout)

import { Button } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(Button)


util.extDate();
Vue.prototype.$custApp = {};
Vue.prototype.$custApp.ajax = dispose.ajax;
Vue.prototype.$custApp.toast = dispose.toast;
Vue.prototype.$custApp.globalData = dispose.globalData;
const getApp = () => {return Vue.prototype.$custApp;}
if(!window.custApp) window.custApp = getApp();

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: {
    App
  },
  template: '<App/>'
})

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !getApp().globalData.user) {
    next({path: "/"})
  } else {
    next();
  }
})
