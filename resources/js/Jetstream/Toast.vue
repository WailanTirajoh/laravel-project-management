<template>
  <div class="fixed top-10 left-1/2" v-if="isShown">
    <div
      class="p-3 text-white rounded-3xl px-5 shadow bg-opacity-95"
      :class="classType"
    >
      {{ message }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: {
      type: String,
      required: true,
    },
    isShown: {
      type: Boolean,
      required: false,
      default: () => {
        return false;
      },
    },
    type: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      templateType: this.type,
    };
  },
  computed: {
    classType() {
      switch (this.templateType) {
        case "success":
          return { "bg-green-400": true };
        case "error":
          return { "bg-red-400": true };
        default:
          return { "bg-green-400": true };
      }
    },
  },
};
</script>

<style></style>
