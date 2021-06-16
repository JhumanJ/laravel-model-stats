import Vue from 'vue'
import store from './store'
import router from './router'
import App from './components/App'

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  store,
  router,
  ...App
})
