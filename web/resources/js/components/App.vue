<template>
  <router-view/>
</template>

<script>
import {initFlowbite} from 'flowbite';

export default {
  mounted() {
    initFlowbite();
  }
}
</script>
