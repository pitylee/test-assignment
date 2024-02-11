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

    <!-- Errors -->
    <GlobalErrors :value="store._state.errors" @remove-error="(key) => { store.setErrors({}, key); $forceUpdate(); }"/>

    <div class="pt-10 pl-10">
      <h1 class="text-2xl font-bold">Candidates</h1>
    </div>

    <LoadingWrapper :loading="loading">
      <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
        <div
            v-for="(candidate, key) in candidates"
            :class="[
                candidate?.strengths?.includes('Wordpress') ? 'hidden' : null,
                (candidate?.hired || false) ? 'shadow-sm shadow-teal-100' : (
                    (candidate?.messages.contacted || false) ? 'shadow-sm shadow-blue-300' : null
                )
            ]"
            class="flex flex-col rounded overflow-hidden shadow-lg">
          <img alt="" class="w-full" src="/avatar.png">
          <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ candidate?.name }}</div>
            <p class="text-gray-700 text-base">{{ candidate?.description }}</p>
          </div>
          <div class="px-6 pt-4 pb-2">
            <span v-for="strength in candidate?.strengths"
                  :class="[
                      desiredStrengths.includes(strength) ? 'bg-green-500 text-green-800' : null,
                  ]"
                  class="inline-block bg-gray-200  rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
              {{ strength }}
            </span>
          </div>
          <div class="px-6 pb-2">
            <span v-for="skill in candidate?.soft_skills"
                  :class="[
                      desiredSoftSkills.includes(skill) ? 'bg-blue-500 text-blue-800' : null,
                  ]"
                  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
              {{ skill }}
            </span>
          </div>

          <div class="w-full flex-grow flex items-end p-6">

            <div class="w-1/2 flex items-center opacity-30">
              <!-- Center vertically -->
              <div class="h-full pb-2 flex flex-col justify-center">
                <div class="inline-block">
                  <div v-if="candidate?.messages.contacted || false"
                       class="inline-block bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-blue-700 dark:text-blue-400 border border-blue-500 select-none">
                    <BriefcaseIcon class="w-3 h-3 me-1.5 text-blue-500"/>
                    <span class="pl-1">{{ candidate?.messages.ago || 'Contacted already' }}</span>
                  </div>

                  <div v-if="candidate?.hired || false"
                       class="inline-block bg-teal-100 text-teal-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-teal-700 dark:text-teal-400 border border-teal-500 select-none">
                    <AnnotationIcon class="w-3 h-3 me-1.5 text-teal-500"/>
                    <span class="pl-1">Hired</span>
                  </div>
                </div>
              </div>
              <!-- bottom stick end -->
            </div>
            <!-- left end -->

            <div class="w-1/2 flex justify-center items-center">

              <Modal
                  :id="`contact-${candidate?.id}`"
                  :data="candidates[key]"
                  :loading="modalLoading"
                  :no-interact="candidate?.success || false"
                  :okCallback="() => sendMessage(candidate)"
                  :title="`Contact ${candidate?.name}`"
                  button="Contact"
                  buttonClass="font-semibold py-2 px-4 rounded shadow hover:bg-teal-200 dark:bg-gray-100 dark:text-gray-100 border border-gray-200 bg-gray-100 text-gray-800"
                  cancel="Close"
                  ok="Send"
              >
                <template v-slot:content="slotProps">
                  <ContactForm v-model="slotProps"/>
                </template>
              </Modal>

              <div class="ml-2"/>

              <template name="hire">
                <Modal
                    v-if="candidate?.messages.contacted === true && !candidate?.hired"
                    :id="`hire-${candidate?.id}`"
                    :loading="modalLoading"
                    :okCallback="() => hire(candidate?.id)"
                    :cancel="candidate?.hired === true ? 'Close' : 'No'"
                    :title="`Hire ${candidate?.name}`"
                    button="Hire"
                    buttonClass="font-semibold py-2 px-4 rounded shadow hover:bg-teal-200 dark:bg-gray-100 dark:text-gray-100 border border-gray-200 bg-gray-100 text-gray-800"
                    :cancelCallback="() => { modal.instance(`hire-${candidate?.id}`).hide(); loadCandidate(candidate.id); }"
                    ok="Yes"
                >
                  Are you sure you want to hire them?

                  <template v-if="candidate.success">
                    <p class="mt-2 text-sm text-green-600 dark:text-green-500">
                      Success
                    </p>
                  </template>

                  <template v-if="typeof candidate.validated === 'string'">
                    <p id="contact-error" class="mt-2 text-sm text-red-600 dark:text-red-500">
                      {{ candidate.validated }}
                    </p>
                  </template>

                  <template v-if="typeof candidate.validated === 'string' || candidate?.success === true"
                            v-slot:buttons>
                    <button
                        :data-modal-hide="`hire-${candidate.id}`"
                        class="px-5 py-2.5 font-medium text-sm text-center border rounded-lg focus:z-10 focus:ring-4 focus:outline-none"
                        type="button"
                        v-on:click="() => { modal.instance(`hire-${candidate?.id}`).hide(); }">
                      Close
                    </button>
                  </template>

                </Modal>

                <Modal
                    v-else-if="candidate?.hired || false"
                    :id="`hire-${candidate?.id}`"
                    :cancel="false"
                    :loading="modalLoading"
                    :title="`${candidate?.name} is hired`"
                    button="Hire"
                    buttonClass="font-semibold py-2 px-4 rounded shadow dark:bg-gray-300 dark:text-gray-100 border border-gray-300 bg-gray-300 text-gray-400"
                    ok="I understand"
                >
                  You must contact the candidate first to be able to hire them!
                </Modal>

                <Modal
                    v-else-if="candidate?.messages.contacted !== true"
                    :id="`hire-${candidate?.id}`"
                    :cancel="false"
                    :loading="modalLoading"
                    :title="`Contact ${candidate?.name} first`"
                    button="Hire"
                    buttonClass="font-semibold py-2 px-4 rounded shadow dark:bg-gray-300 dark:text-gray-100 border border-gray-300 bg-gray-300 text-gray-400"
                    ok="I understand"
                >
                  You must contact the candidate first to be able to hire them!
                </Modal>
              </template>

            </div>
          </div>
          <!-- actions end -->
        </div>
        <!-- candidates end -->

      </div>
    </LoadingWrapper>

    <MvpCandidates />
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
import {BriefcaseIcon, AnnotationIcon} from '@vue-hero-icons/solid'
import GlobalErrors from "~common/GlobalErrors.vue";
import MvpCandidates from "./partials/MvpCandidates.vue";

export default {
  name: 'Candidates',
  components: {MvpCandidates, GlobalErrors, ContactForm, Modal, LoadingWrapper, BriefcaseIcon, AnnotationIcon},
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
      ],
      desiredSoftSkills: [
        'Team player', 'PHP'
      ],
      candidateModel: null,
      isDev: process.env.NODE_ENV === 'development'
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
        id: candidate?.id,
        ...{
          subject: candidate?.subject,
          message: candidate?.message,
        },
      };

      // Id may differ from the array key, so we work with candidate key
      // Then we reset the validated, success and loading fields, so that it can be dynamically rerun
      const candidateKey = Object.keys(this.candidates).find(key => this.candidates[key].id === candidate.id);
      this.setValidated(candidateKey, null);
      this.candidates[candidateKey].success = false;
      this.candidates[candidateKey].loading = this.modalLoading;
      this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);

      // Attempt to send message, if success, will reload coins and the candidate worked will be reloaded
      await contactModel.sendMessage()
          .then(({data}) => {
            if (data?.success === true) {
              this.loadCoins();
              this.candidates[candidateKey].success = true;
              this.candidates[candidateKey].loading = this.modalLoading;
              this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);

              setTimeout(() => {
                this.modal.instance(`contact-${candidate?.id}`).hide()
                this.loadCandidate(candidate.id);
              }, 2000)
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

            // show global errors
            store.setErrors({fields: errors});
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
            this.coins = store.get('me')?.company_with_wallet?.wallet?.coins ?? '?';
            this.loadingCoins = false;
          })
          .catch((errors) => store.setErrors(errors));
    },
    loadCandidates: async function () {
      this.loading = true;

      this.candidateModel.all()
          .then((response) => {
            this.candidates = response.data;
            this.loading = false;
          })
          .catch((errors) => store.setErrors(errors));
    },
    loadCandidate: async function (id) {
      this.loading = true;

      await this.candidateModel.find(id)
          .then((data) => {
            this.candidates[id] = data;
            this.candidates[id].loading = this.loading;
            this.$set(this.candidates, id, this.candidates[id]);
          })
          .catch((errors) => store.setErrors(errors))
          .finally(() => {
            this.loading = false;
            this.candidates[id].loading = this.loading;
            this.$set(this.candidates, id, this.candidates[id]);
          });
    },
    hire: async function (id) {
      this.loading = true;

      // Id may differ from the array key, so we work with candidate key
      // Then we reset the validated, success and loading fields, so that it can be dynamically rerun
      const candidateKey = Object.keys(this.candidates).find(key => this.candidates[key].id === id);

      this.setValidated(candidateKey, null);
      this.candidates[candidateKey].success = false;
      this.candidates[candidateKey].loading = this.modalLoading;
      this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);

      // Attempt to hire by id, if success reload coins and show success message in modal
      // After some time the modal gets closed and the candidate reloaded
      await this.candidateModel.hire(id)
          .then((data) => {
            if (data?.success === true) {
              this.loadCoins();
              this.candidates[candidateKey].success = true;
              this.candidates[candidateKey].loading = this.modalLoading;
              this.$set(this.candidates, candidateKey, this.candidates[candidateKey]);

              setTimeout(() => {
                this.modal.instance(`hire-${id}`).hide()
                this.loadCandidate(id);
              }, 2000)
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

            // show global errors
            store.setErrors({message: errors});
          })
          .finally(() => {
            this.loading = false;
            this.candidates[id].loading = this.loading;
            this.$set(this.candidates, id, this.candidates[id]);
          });
    },
  },
  async mounted() {
    await login();
    this.loadCoins();

    this.candidateModel = new CandidateModel();
    this.loadCandidates();
  },
  beforeUpdate() {
    this.$nextTick(() => {
      store._state.errors = {}
    })
  }
}
</script>
