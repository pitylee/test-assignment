<template>
  <div>
    <div class="flex w-full h-16 items-center bg-teal-100">
      <div class="flex flex-col flex-grow w-full h-full justify-center">
        <div class="pl-10">
          <router-link to="/">
            <h1 class="text-2xl font-bold select-none">
              <span class="text-teal-600 ">M</span>
              <span class="text-teal-400 ">Z</span>
              <span class="text-black ">T</span>
            </h1>
          </router-link>
        </div>
      </div>

      <div class="w-60 flex flex-col flex-grow w-full h-full justify-center">
        <div
            :class="loadingCoins ? 'cursor-wait' : null"
            class="h-full flex pr-4 justify-end items-center text-right font-bold cursor-pointer select-none"
            v-on:click="loadCoins">
          <LoadingWrapper :loading="loadingCoins">
            Your wallet has <span>{{ coins }}</span> coins
          </LoadingWrapper>
        </div>
      </div>
    </div>

    <div class="p-10">
      <h1 class="text-4xl font-bold">Candidates</h1>
    </div>
    <LoadingWrapper :loading="loading">
      <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
        <div v-for="(candidate, key) in candidates" class="rounded overflow-hidden shadow-lg flex flex-col">
          <img alt="" class="w-full" src="/avatar.png">
          <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ candidate.name }}</div>
            <p class="text-gray-700 text-base">{{ candidate.description }}</p>
          </div>
          <div class="px-6 pt-4 pb-2">
            <span v-for="strength in candidate.strengths"
                  class="inline-block bg-gray-200  rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
              {{ strength }}
            </span>
          </div>
          <div class="px-6 pb-2">
            <span v-for="skill in candidate.soft_skills"
                  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
              {{ skill }}
            </span>
          </div>

          <div class="w-full flex-grow flex items-end p-6">

            <div class="w-1/2 flex items-center opacity-30">
              <!-- Center vertically -->
              <div class="h-full pb-2 flex flex-col justify-center">
                <div
                    v-if="typeof candidate.messages.contacted || false"
                    class="bg-teal-100 text-teal-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-teal-700 dark:text-teal-400 border border-teal-500 select-none">
                  <svg aria-hidden="true" class="w-2.5 h-2.5 me-1.5 fill-green-500" fill="currentColor"
                       viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586Z"/>
                  </svg>
                  <span class="pl-2">{{ candidate.messages.ago || 'Contacted' }}</span>
                </div>
              </div>
            </div>

            <div class="w-1/2 flex justify-center items-center">

              <Modal
                  :id="`contact-${candidate.id}`"
                  :data="candidates[key]"
                  :loading="modalLoading"
                  :no-interact="candidate.success || false"
                  :okCallback="() => sendMessage(candidate)"
                  :title="`Contact ${candidate.name}`"
                  button="Contact"
                  buttonClass="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
                  cancel="Close"
                  ok="Send"
              >
                <template v-slot:content="slotProps">
                  <ContactForm v-model="slotProps"/>
                </template>
              </Modal>

              <div class="ml-2"/>

              <button
                  class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow">
                Hire
              </button>

            </div>
          </div>
          <!-- actions end -->
        </div>
        <!-- candidates end -->

      </div>
    </LoadingWrapper>
  </div>
</template>

<script>
import CandidateModel from '~models/CandidateModel';
import {login, me} from '~libraries/Auth';
import {store} from '~store';
import LoadingWrapper from '~common/LoadingWrapper.vue';
import {modal} from '~libraries/Modal';
import Modal from "~common/Modal.vue";
import ContactForm from "./partials/ContactForm.vue";
import ContactModel from "../../../models/ContactModel";

export default {
  name: 'Candidates',
  components: {ContactForm, Modal, LoadingWrapper},
  data() {
    return {
      modal,
      store,
      coins: 0,
      loading: false,
      loadingCoins: false,
      modalLoading: false,
      candidates: [],
      desiredStrengths: [
        'Vue.js', 'Laravel', 'PHP', 'TailwindCSS'
      ]
    }
  },
  methods: {
    setValidated: function (candidateKey, validated) {
      this.candidates[candidateKey].validated = validated;
      this.$set(this.candidates, candidateKey, this.candidates[candidateKey])
    },
    sendMessage: async function (candidate) {
      this.modalLoading = true;
      const contactModel = new ContactModel();
      contactModel.form = {
        id: candidate.id,
        ...{
          subject: candidate.subject,
          message: candidate.message,
        },
      };
      const candidateKey = this.candidates.findIndex(entry => entry.id === candidate.id);
      this.setValidated(candidateKey, null);
      this.candidates[candidateKey].success = false;
      this.candidates[candidateKey].loading = this.modalLoading;
      this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);

      await contactModel.sendMessage()
          .then(({data}) => {
            if (data?.success === true) {
              this.candidates[candidateKey].success = true;
              this.candidates[candidateKey].loading = this.modalLoading;
              this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);
              this.loadCoins();

              setTimeout(() => {
                this.modal.instance(`contact-${candidate.id}`).hide()
                this.candidates[candidateKey].success = false;
              }, 5000)
            }
          })
          .catch(({response}) => {
            let errors = response?.data;
            if (typeof errors?.message === 'string') {
              errors = errors?.message;
            }
            this.setValidated(candidateKey, errors);
            this.candidates[candidateKey].success = false;
            this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);
          })
          .finally(() => {
            this.modalLoading = false;
            this.candidates[candidateKey].loading = this.modalLoading;
            this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);
          });
    },
    loadCoins: async function () {
      this.loadingCoins = true;
      await me(true)
          .then(() => {
            this.coins = store.get('me')?.company?.wallet?.coins ?? '?';
            this.loadingCoins = false;
          });
    },
  },
  async mounted() {
    await login();
    this.loadCoins();

    const candidateModel = new CandidateModel();
    candidateModel.all()
        .then((data) => {
          this.candidates = data.data.map((candidate) => {
            return {
              ...candidate,
              strengths: JSON.parse(candidate.strengths),
              soft_skills: JSON.parse(candidate.soft_skills),
            };
          });
          this.loading = false;
        })
        .catch(() => null);

    this.$nextTick(function () {
      // Code that will run only after the
      // entire view has been rendered
    })
  },
}
</script>
