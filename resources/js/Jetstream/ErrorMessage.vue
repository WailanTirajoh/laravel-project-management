<template>
  <span
    class="opacity-0 tooltiptext text-xs transition-opacity ease-in-out duration-300"
    :class="{
      'opacity-80': errors,
    }"
  >
    <button
      @click="$emit('removeError')"
      class="absolute -top-1 -right-1 shadow rounded-full w-4 h-4 bg-red-700 font-xs flex justify-center items-center"
    >
      <i class="fa-solid fa-xmark" style="font-size: 0.5rem"></i>
    </button>
    <ul>
      <li v-for="(error, index) in errors" :key="index">
        {{ error }}
      </li>
    </ul>
  </span>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    errors: {
      type: Array,
      required: false,
      default: () => {
        return null;
      },
    },
  },
});
</script>

<style></style>
