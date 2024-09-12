import './styles/app.scss';
import { createApp, ref  } from 'vue'

window.vue = createApp({
    setup() {
        return {
            count: ref(0)
        }
    }
})
    .mount('#app');
