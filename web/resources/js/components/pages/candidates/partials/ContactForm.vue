<template>
  <form class="w-full mx-auto mt-2">

    <Input v-model="data.email" :disabled="true" :validated="(data.validated || {}).email || {}" label="Email"
           name="email" type="email"/>

    <Input v-model="data.subject" :validated="(data.validated || {}).subject || {}" label="Subject" name="subject"/>

    <Textarea v-model="data.message" :validated="(data.validated || {}).message || {}" label="Message" name="message"/>

    <template v-if="typeof data.validated === 'string'">
      <p id="contact-error" class="mt-2 text-sm text-red-600 dark:text-red-500">
        {{ data.validated }}
      </p>
    </template>

    <template v-if="data.success">
      <p class="mt-2 text-sm text-green-600 dark:text-green-500">
        Sent successfully
      </p>
    </template>
  </form>
</template>

<script>
import {Input, Textarea} from '~common/inputs';

export default {
  components: {Input, Textarea},
  props: {
    value: {
      type: Object,
      default: () => {
      },
    },
  },
  computed: {
    data: {
      get() {
        return this.value?.data ?? {};
      },
      set(newValue) {
        this.$emit('input', {...this.value, data: newValue});
      }
    },
  },
}
</script>
