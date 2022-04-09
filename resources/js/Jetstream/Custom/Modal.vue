<template>
  <div>
    <span @click="toggleModal()">
      <slot name="button"></slot>
    </span>
    <transition-group name="slide-fade">
      <div
        v-if="showModal"
        class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center pt-40 flex w-100"
      >
        <div
          class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
          style="width: 100%"
        >
          <div
            class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none"
          >
            <div
              class="flex items-start justify-between p-5 border-b border-solid border-slate-200 rounded-t"
            >
              <h3 class="text-3xl font-semibold">
                <slot name="header"></slot>
              </h3>
              <button
                class="p-1 ml-auto bg-transparent border-0 text-black opacity-30 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                @click="toggleModal()"
              >
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
            <!--body-->
            <div class="relative p-6 flex-auto">
              <slot name="body"></slot>
            </div>
            <!--footer-->
            <div
              class="flex items-center justify-end p-6 border-t border-solid border-slate-200 rounded-b"
            >
              <slot name="footer"></slot>
            </div>
          </div>
        </div>
      </div>
    </transition-group>
    <transition
      enter-active-class="ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="ease-in duration-200"
      leave-from-class="opacity-25"
      leave-to-class="opacity-0"
      appear
    >
      <div v-if="showModal" class="fixed inset-0 z-40 bg-black opacity-25 transition-all"></div>
    </transition>
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
    toggleModal() {
      this.showModal = !this.showModal;
    },
  },
};
</script>

<style scoped>
.slide-fade-enter-from {
  opacity: 0;
}
.slide-fade-enter-to {
  opacity: 1;
}
.slide-fade-enter-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
