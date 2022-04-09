<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    Link,
  },
  props: ["href", "active"],

  computed: {
    classes() {
      return this.active
        ? "inline-flex items-center px-1 pt-1 border-b-4 border-indigo-400 text-indigo-400 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition w-20 flex justify-center text-lg hover:bg-gray-50 hover:rounded-t"
        : "inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition w-20 flex justify-center text-lg hover:bg-gray-50 hover:rounded-t";
    },
  },
});
</script>
