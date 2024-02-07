<template>
  <div class="relative z-0 w-full mb-6 group">
    <textarea
        :id="id"
        :class="[
          hasErrors ? labelClasses.error : labelClasses.success
      ]"
        :placeholder="placeholder"
        class="block p-2.5 w-full outline-none text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-teal-500 focus:ring-0"
        rows="5"
        @input="input"
    >{{ data }}</textarea>

    <label
        :class="[
            hasErrors ? labelClasses.error : labelClasses.success
        ]"
        :for="id"
        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-1 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-teal-600 peer-focus:dark:text-teal-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
      {{ label }}
    </label>

    <template v-if="!hasErrors && successMessage">
      <p class="mt-2 text-sm text-green-600 dark:text-green-500">
        {{ successMessage }}
      </p>
    </template>

    <template v-for="(error, key) in validated" v-if="hasErrors">
      <p :id="`${name}-error-${key}`" class="mt-2 text-sm text-red-600 dark:text-red-500">
        {{ error }}
      </p>
    </template>

  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'text',
    },
    name: {
      type: String,
      default: 'text',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    model: {
      type: String,
      default: null,
    },
    label: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      default: null,
    },
    validated: {
      type: [Object, Array],
      default: () => {
      }
    },
    successMessage: {
      type: String,
      default: null,
    },
    inputClasses: {
      type: Object,
      default: () => {
        return {
          error: 'dark:bg-red-100 dark:border-red-400 bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500',
          success: 'dark:bg-green-100 dark:border-green-400 bg-green-50 border-green-500 text-green-900 placeholder-green-700 focus:ring-green-500 focus:border-green-500',
        };
      },
    },
    labelClasses: {
      type: Object,
      default: () => {
        return {
          error: 'text-red-700 dark:text-red-500',
          success: 'text-green-700 dark:text-green-500',
        };
      },
    },
  },
  methods: {
    input($event) {
      this.$emit('input', $event.target.value)
    }
  },
  computed: {
    id: function () {
      return `input-${this.type}-${this.name}`
    },
    data: {
      get() {
        return this.value ?? null;
      },
      set(newValue) {
        this.$emit('input', newValue);
      }
    },
    hasErrors: function () {
      return this.validated.length > 0;
    },
  },
}
</script>