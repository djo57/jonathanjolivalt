import './styles/app.css';
import { createApp } from 'vue';
import App from './App.vue';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleXmark } from '@fortawesome/free-solid-svg-icons'

library.add(faCircleXmark)

createApp(App)
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app');