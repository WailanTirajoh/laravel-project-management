<template>
  <app-layout title="Projects">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Projects
      </h2>
    </template>
    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <modal class="mb-2">
          <template #button>
            <button
              class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button"
            >
              Create New Project
            </button>
          </template>
          <template #header> Add Project </template>
          <template #body>
            Ini body
          </template>
        </modal>
        <div class="grid grid-cols-4 gap-4">
          <div
            class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 col-span-4 sm:col-span-2 lg:col-span-1 ease-linear transition-all duration-150"
            v-for="project in projects"
            :key="project.id"
          >
            <div class="text-center text-xl">
              <h5>
                {{ project.name }}
              </h5>
            </div>
            <hr class="mt-2 mb-2" />
            <div
              class=""
              v-for="status in project.status"
              :key="status.detail.id"
            >
              <div
                class="grid grid-cols-2 rounded mb-1 p-1 stroke-white stroke-1"
                :style="{
                  backgroundColor: status.detail.color,
                }"
              >
                <div>
                  {{ status.detail.name }}
                </div>
                <div class="text-right pr-4">
                  {{ status.val }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import { errorHandler } from "@/Utils/error.js";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Jetstream/CustModal";

export default defineComponent({
  components: {
    AppLayout,
    Modal,
  },
  data() {
    return {
      projects: [],
      showModal: false,
    };
  },
  methods: {
    async getProjects() {
      try {
        const { status, data } = await axios.get(`/api/projects`);

        if (status === 200) {
          this.projects = data.projects;
        }
      } catch (e) {
        errorHandler(e);
      }
    },
    toggleModal() {
      this.showModal = !this.showModal;
    },
  },
  mounted() {
    this.getProjects();
  },
});
</script>

<style></style>
