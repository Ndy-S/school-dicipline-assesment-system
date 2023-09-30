import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faUserSecret, faChartLine, faIdCardClip, faUser, faPersonWalkingArrowRight, faCirclePlus, faPenClip, faTrashCan, faPeopleRoof, faArrowUpAZ, faArrowDownZA, faXmark, faCircleExclamation, faCircleCheck, faToolbox, faTimeline } from '@fortawesome/free-solid-svg-icons';


library.add(faUserSecret, faChartLine, faIdCardClip, faUser, faPersonWalkingArrowRight, faCirclePlus, faPenClip, faTrashCan, faPeopleRoof, faArrowUpAZ, faArrowDownZA, faXmark, faCircleExclamation, faCircleCheck, faToolbox, faTimeline);

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el)
    },

    progress: {
        delay: 250,
        color: 'red',
        includeCSS: true,
        showSpinner: true,
    }
});


