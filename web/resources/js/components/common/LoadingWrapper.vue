<template>
  <div
    :class="{
      'pointer-events-none relative': loading,
      [height]: loading
    }"
  >
    <div
        v-if="loading"
        class="left-0 top-0 absolute w-full h-full flex  items-top justify-center"
    >
      <div class="flex flex-col items-center">
        <Loading
            v-if="showLoader"
            size="w-12 h-12"
        />
        <slot name="loading-content"/>
      </div>
    </div>
    <div
        :class="{ 'opacity-25': loading }"
    >
      <slot/>
    </div>
  </div>
</template>

<script>
import Loading from "./Loading.vue";

export default {
  components: {Loading},
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
    height: {
      type: String,
      default: '',
    },
    showLoader: {
      type: Boolean,
      default: true,
    },
  },
};
</script>
