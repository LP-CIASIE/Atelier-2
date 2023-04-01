// Vue
import { createApp } from "vue";
import App from "./App.vue";

// Router and Store
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import router from "./router";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

// PrimeVue
import PrimeVue from "primevue/config";
import "primevue/resources/themes/md-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "primeflex/primeflex.css";
import ToastService from "primevue/toastservice";

// Create App
const app = createApp(App);

// Router and Store
app.use(pinia);
app.use(router);

// PrimeVue
app.use(PrimeVue);
app.use(ToastService);

// Mount App
app.mount("#app");
