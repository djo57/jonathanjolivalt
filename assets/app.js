import './styles/app.css'
import { createApp } from 'vue'
import App from './App.vue'
import { VueReCaptcha, useReCaptcha } from 'vue-recaptcha-v3'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleXmark } from '@fortawesome/free-solid-svg-icons'

library.add(faCircleXmark)

createApp(App)
.use(VueReCaptcha, {
    siteKey: '6Ld4TxspAAAAAIeFyA2ML9G5Aprsd-LoeoAkxzL_',
    loaderOptions: {
        autoHideBadge: true
    }
})
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app');