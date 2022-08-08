import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import PrimeVue from 'primevue/config';
import Button from 'primevue/button';
import SplitButton from 'primevue/splitbutton';
import Toolbar from 'primevue/toolbar';
import 'primevue/resources/themes/saga-blue/theme.css'       //theme
 import 'primevue/resources/primevue.min.css'                 //core css
 import 'primeicons/primeicons.css'

 import SelectButton from 'primevue/selectbutton';                        //icons
 import Dropdown from 'primevue/dropdown';
 import Checkbox from 'primevue/checkbox';
 import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';
import MultiSelect from 'primevue/multiselect';
import InputNumber from 'primevue/inputnumber';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(PrimeVue)
            .use(ZiggyVue, Ziggy)
            .component('Button',Button)
            .component('SplitButton',SplitButton)
            .component('Toolbar',Toolbar)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
