<template>
  <transition-group name="slide-up">
    <div class="fixed bottom-0 w-full z-40" v-if="show">
      <div
        class="mx-auto flex-1 rounded-none bg-white md:rounded-t-2xl max-h-screen mt-auto overflow-auto flex flex-col w-full md:w-3/4 md:max-h-80 transition-transform"
        style="
          box-shadow: rgba(27, 33, 58, 0.4) 0px 20px 60px -2px;
          max-width: 800px;
        "
      >
        <slot />
      </div>
    </div>
  </transition-group>
  <transition
    enter-active-class="ease-in-out duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="ease-in-out duration-300"
    leave-from-class="opacity-25"
    leave-to-class="opacity-0"
    appear=""
  >
    <div
      v-if="show"
      @click="$emit('close-reply')"
      class="fixed inset-0 z-30 bg-black transition-all ease-in-out opacity-25"
    ></div>
  </transition>
</template>

<script>
export default {
  props: {
    show: Boolean,
  },
};
</script>

<style scoped>
.slide-up-enter-from {
  transform: translateY(100%);
}
.slide-up-enter-active {
  transition: all 0.5s ease-in-out;
}
.slide-up-leave-active {
  transition: all 0.5s ease-in-out;
}
.slide-up-enter,
.slide-up-leave-to {
  transform: translateY(100%);
}
</style>
