import "./style.css";
import { createApp, markRaw } from "vue";
import { createPinia } from "pinia";
import {
  DiagramComponent,
  SymbolPaletteComponent,
} from "@syncfusion/ej2-vue-diagrams";
import { ButtonComponent } from "@syncfusion/ej2-vue-buttons";

import App from "./App.vue";
import router from "./router";
import { registerLicense } from "@syncfusion/ej2-base";
registerLicense(
  "Ngo9BigBOggjHTQxAR8/V1NDaF1cX2hIYVdpR2Nbek5zflVGallXVAciSV9jS3pSdEVmWXxfdXBcQmJeWQ=="
);

const app = createApp(App);
const pinia = createPinia();
app.component("ejs-diagram", DiagramComponent);
app.component("ejs-symbolpalette", SymbolPaletteComponent);
app.component("ejs-button", ButtonComponent);

pinia.use(({ store }) => {
  store.router = markRaw(router);
});

app.use(pinia);
app.use(router);

app.mount("#app");
