import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
  faUser, faLock, faSignOutAlt, faCog, faTimes, fas, faSearch
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub, faGooglePay
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog, faGithub, faTimes, fas, faSearch, faGooglePay
)

Vue.component('Fa', FontAwesomeIcon)
