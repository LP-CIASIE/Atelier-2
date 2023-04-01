// Vue
import { createApp } from "vue";
import App from "./App.vue";

// Router and Store
import { createPinia } from "pinia";
import router from "./router";

// PrimeVue
import PrimeVue from "primevue/config";
import "primevue/resources/themes/md-light-indigo/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "primeflex/primeflex.css";

// Create App
const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue);

app.mount("#app");
