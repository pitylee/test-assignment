<template>
  <div class="inline-block">
    <button v-if="showButton === true"
            :class="buttonClass"
            :data-modal-target="id"
            :data-modal-toggle="id"

            type="button">
      {{ button }}
    </button>

    <!-- Default Modal -->
    <div :id="id" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
         tabindex="-1">
      <div class="relative w-full max-w-lg max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

          <LoadingWrapper :loading="loading">

            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
              <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                {{ title }}
              </h3>
              <button
                  :data-modal-hide="id"
                  class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                  type="button">
                <svg aria-hidden="true" class="w-3 h-3" fill="none" viewBox="0 0 14 14"
                     xmlns="http://www.w3.org/2000/svg">
                  <path d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2"/>
                </svg>
                <span class="sr-only">Close</span>
              </button>
            </div>

            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
              <slot name="content" v-bind:data="data">Modal content</slot>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
              <footer>
                <slot name="buttons">

                  <button
                      v-if="ok"
                      :class="[
                        okClass,
                        noInteract && okCallback !== null ? noInteractClass : null,
                      ]"
                      :data-modal-hide="okCallback !== null ? false : id"
                      :disabled="noInteract"
                      class="px-5 py-2.5 font-medium text-sm text-center border rounded-lg focus:z-10 focus:ring-4 focus:outline-none"
                      type="button"
                      v-on:click="okCallback ? okCallback(data) : () => {}">
                    <LoadingSmall v-if="loading && okCallback !== null"/>
                    {{ ok }}
                  </button>

                  <div class="inline-block ml-2 ms-3"/>

                  <button v-if="cancel"
                          :class="[
                        cancelClass,
                        noInteract && cancelCallback !== null ? noInteractClass : null,
                      ]"
                          :data-modal-hide="cancelCallback !== null ? false : id"
                          :disabled="noInteract"
                          class="px-5 py-2.5 font-medium text-sm text-center border rounded-lg focus:z-10 focus:ring-4 focus:outline-none"
                          type="button"
                          v-on:click="cancelCallback ? cancelCallback(data) : () => {}">
                    <LoadingSmall v-if="loading && cancelCallback !== null"/>
                    {{ cancel }}
                  </button>
                </slot>
              </footer>
            </div>

          </LoadingWrapper>

        </div>
      </div>
    </div>
  </div>
</template>


<script>
import {modal} from '~libraries/Modal';
import LoadingWrapper from "~common/LoadingWrapper.vue";
import LoadingSmall from "~common/LoadingSmall.vue";

export default {
  name: 'Modal',
  components: {LoadingSmall, LoadingWrapper},
  props: {
    id: {
      type: String,
      default: 'modal'
    },
    loading: {
      type: Boolean,
      default: false
    },
    noInteract: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Modal'
    },
    ok: {
      type: String,
      default: 'Ok'
    },
    okClass: {
      type: String,
      default: 'text-white bg-teal-700 hover:bg-teal-800 focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800'
    },
    cancel: {
      type: String,
      default: 'Cancel'
    },
    cancelClass: {
      type: String,
      default: 'text-gray-500 bg-white hover:bg-gray-100 focus:ring-gray-200 border-gray-200 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600'
    },
    button: {
      type: String,
      default: 'Open'
    },
    buttonClass: {
      type: String,
      default: 'block w-full md:w-auto text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800'
    },
    noInteractClass: {
      type: String,
      default: 'bg-white border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700'
    },
    showButton: {
      type: Boolean,
      default: true
    },
    okCallback: {
      type: Function,
      default: () => {
      },
    },
    cancelCallback: {
      type: Function,
      default: null,
    },
    data: {
      type: Object,
      default: () => {
      },
    },
  },
  data() {
    return {
      modal: modal.instance(this.id),
    }
  },
  async mounted() {
    this.$nextTick(function () {
      modal.handle(this.id);
    })
  },
}
</script>
