import Vue from 'vue'
import store from './store'
import router from './router'
import App from './components/App'
import Base from './base'

import './components/forms'

Vue.config.productionTip = false

Vue.mixin(Base);

/* eslint-disable no-new */
new Vue({
  store,
  router,
  ...App
})
