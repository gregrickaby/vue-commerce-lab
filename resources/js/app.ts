import '../css/app.css'
import './bootstrap'

import { library } from '@fortawesome/fontawesome-svg-core'
import {
    faAngleLeft,
    faAngleRight,
    faEye,
    faPenToSquare,
    faTrashCan
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { DefineComponent, createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

library.add(faPenToSquare, faEye, faAngleRight, faAngleLeft, faTrashCan)

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
        app.component('FontAwesomeIcon', FontAwesomeIcon)
        app.mount(el)
    },
    progress: {
        delay: 0,
        color: '#2563eb',
        includeCSS: true
    }
})
