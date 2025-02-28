import Vue from "vue";
import exampleComponent from "./example.component.vue";

const vueAppInit = (selector: string) => {
    const el = document.querySelector(selector);

    if (!el) return;

    new Vue({
        el: el,
        components: {
            "example-component": exampleComponent,
        },
    });
};

export { vueAppInit };
