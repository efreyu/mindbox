import { InertiaApp } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import { AtomSpinner } from 'epic-spinners'


Vue.use(VueAxios, axios)
Vue.use(AtomSpinner)
Vue.use(InertiaApp)

const app = document.getElementById('app')

new Vue({
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default,
        },
    }),
}).$mount(app)
