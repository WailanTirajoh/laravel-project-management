<template>
  <div class="">
    <span @click="openOffCanvas()">
      <slot name="button"></slot>
    </span>
    <transition-group name="slide-fade">
      <div
        v-if="showModal"
        class="fixed bottom-0 left-0 z-50 flex flex-col max-w-full bg-white bg-clip-padding transition-all duration-300 ease-in-out h-full shadow-2xl"
        style="width: 400px"
      >
        <div
          class="flex justify-between p-4 border-b-2 border-b-gray-200 items-center"
        >
          <h3 class="font-medium text-2xl" @click="closeOffCanvas()">
            <slot name="header"></slot>
          </h3>
          <button
            class="p-1 ml-auto bg-transparent border-0 text-black opacity-30 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
            @click="closeOffCanvas()"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div class="p-4 overflow-y-auto" style="max-height: 90vh">
          <slot />
        </div>
      </div>
    </transition-group>
    <div
      v-if="showModal"
      class="opacity-50 fixed inset-0 z-40 bg-black backdrop-blur"
      @click="closeOffCanvas()"
    ></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
    };
  },
  methods: {
    openOffCanvas() {
      this.showModal = true;
    },
    closeOffCanvas() {
      this.showModal = false;
      this.$emit("on-close");
    },
  },
};
</script>

<style scoped>
.slide-fade-enter-from {
  transform: translateX(-400px);
}
.slide-fade-enter-active {
  transition: all 0.5s ease-in-out;
}
.slide-fade-leave-active {
  transition: all 0.5s ease-in-out;
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(-400px);
}
</style>
