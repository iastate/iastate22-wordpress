# Modal

**This is a custom component (the class has been prefaced with `.iastate22-[classname]`).**

- This uses a 3rd-party library (which is lightweight and very accessible) called [Micromodal.js](https://micromodal.vercel.app/)
- There are two variants, the default `modal` contains an optional image, with headline, raw html and a raw html call to action section, and `--with-form` with also contains an optional image, with headline, raw html and a small form with an input and submit button. The type of input field and button is configurable.
- The `image` field is optional in both variants
- This uses a `data-attribute` of `[data-micromodal-trigger]` on the modal trigger and requires a corresponding `id` on the modal
- The examples here use the `.iastate22-button` but any element can be used to trigger the modal
- The custom styles are imported into the `index.scss` file as `@import "../components/modal/modal";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import Modal from "./components/modal";` and called as `Modal.init();`
