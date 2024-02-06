<template>
  <div class="w-full h-full items-center justify-center">
    <h1 class="mt-56 p-40 pb-20 text-4xl font-bold text-center">Welcome to the hiring platform</h1>

    <div class="flex items-center justify-center">
      <router-link :to="{ name: 'candidates'}"
                   class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow align-middle">
        Go to candidates list
      </router-link>
    </div>
  </div>
</template>

<script>
import LoginModel from '~models/LoginModel';
import {store} from '~store';
import Modal from "../../common/Modal.vue";

export default {
  components: {Modal},
  created() {
    const loginModel = new LoginModel();
    loginModel.create()
        .then(({data}) => {
          let token = data.token
          store.set('token', token);
        })
        .catch(() => null);

  }
}
</script>

