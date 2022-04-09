require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { QuillEditor } from '@vueup/vue-quill'
import { errorHandler } from "@/Utils/error.js";
import Toast from "@/Jetstream/Toast.vue";
import CustOffCanvas from "@/Jetstream/Custom/OffCanvas";
import Modal from "@/Jetstream/Custom/Modal";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetLoadingCircleDots from "@/Jetstream/LoadingCircleDots.vue";
import ErrorMessage from "@/Jetstream/ErrorMessage.vue";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => require(`./Pages/${name.replace(".", "/")}.vue`),
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .mixin({
        components: {
          QuillEditor,
          Toast,
          CustOffCanvas,
          ErrorMessage,
          Modal,
          AppLayout,
          JetLoadingCircleDots
        },
        data() {
          return {
            toast: {
              show: false,
              message: "",
              type: "error",
            },
            toolbar: [
              [{ header: [1, 2, 3, 4, 5, 6, false] }],
              [{ font: [] }],
              ["bold", "italic", "underline", "strike"], // toggled buttons
              ["blockquote", "code-block"],
              [{ list: "ordered" }, { list: "bullet" }],
              [{ script: "sub" }, { script: "super" }], // superscript/subscript
              [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
              [{ direction: "rtl" }], // text direction
              [{ color: [] }, { background: [] }], // dropdown with defaults from theme
              [{ align: [] }],
              ["clean"],
            ],
          }
        },
        methods: {
          route,

          showToast({ message, type }) {
            this.toast.show = true;
            this.toast.message = message;
            this.toast.type = type;
            this.hideToast();
          },

          async hideToast() {
            await this.wait(2000);
            this.toast.show = false;
            this.toast.message = "";
            this.toast.type = "success";
          },

          wait(timeout) {
            return new Promise((resolve) => {
              setTimeout(resolve, timeout);
            });
          },
          errorHandler
        },
      })
      .mount(el)
  },
});

InertiaProgress.init({ color: '#4B5563' });
