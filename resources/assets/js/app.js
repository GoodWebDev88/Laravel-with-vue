import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import VModal from 'vue-js-modal'
// LightBootstrap plugin
import LightBootstrap from './light-bootstrap-main.js'
import Vuelidate from 'vuelidate'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret, faPenAlt, faTrashAlt } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// router setup
import router from './routes/routes.js'
import store from './store'

// plugin setup
Vue.use(VueRouter)
Vue.use(LightBootstrap)
Vue.use(VModal)
Vue.use(Vuelidate)

library.add(faUserSecret, faPenAlt, faTrashAlt)
Vue.component('font-awesome-icon', FontAwesomeIcon)
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
