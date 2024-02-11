<template>
  <div v-if="value">
    <template v-for="(error, key) in value">
      <div v-if="Object.values(error).filter(Boolean).length"
           class="relative p-4 pl-8 text-sm text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <p v-if="isDev && error?.stack" :id="key" class="" v-text="error?.stack"/>

        <p v-if="error?.detail" :id="key" class="" v-text="error.detail"/>
        <p v-else-if="error?.response?.message" :id="key" class="" v-text="error.response.message"/>
        <p v-else-if="error?.response?.data?.detail" :id="key" class="" v-text="error.response.data.detail"/>
        <p v-else-if="error?.response?.data?.message" :id="key" class="" v-text="error.response.data.message"/>
        <p v-else-if="error?.message" :id="key" class="" v-text="error.message"/>
        <p v-else-if="error?.error" :id="key" class="" v-text="error.error"/>

        <template v-for="item in error?.fields" v-if="error?.fields ?? false">
          <p :id="`${key}`" class="">
            <template v-for="err in item" v-if="typeof item === 'object'">
              <span v-text="err"/>
            </template>
            <span v-else v-text="item"/>
          </p>
        </template>

        <button
            class="absolute top-4 left-3 text-gray-400 bg-transparent hover:bg-red-200 hover:text  text-sm w-4 h-4 ms-auto inline-flex justify-center items-center dark:hover:bg-red-200 dark:hover:text-white cursor-pointer"
            type="button"
            v-on:click="() => removeError(key)">
          <svg aria-hidden="true" class="w-2 h-2" fill="none" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
            <path d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" stroke="currentColor" stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2"/>
          </svg>
          <span class="sr-only">Close</span>
        </button>
      </div>
    </template>

  </div>
</template>

<script>
import {store} from '~store'
import Vue from "vue";

export default {
  name: 'GlobalErrors',
  props: {
    value: {
      type: [Object, Array],
      default: () => null,
    },
  },
  computed: {
    errors() {
      return store.errors;
    }
  },
  methods: {
    removeError: function (key) {
      this.$emit('remove-error', key); // Emitting an event to the parent component
    },
  },
  data() {
    return {
      store,
      isDev: process.env.NODE_ENV === 'development'
    }
  },
}
</script>
