<template>
  <app-layout title="Projects">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Projects
      </h2>
    </template>
    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-4 gap-4">
          <div
            class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4"
            v-for="project in projects"
            :key="project.id"
          >
            <div class="text-center text-xl">
              <h5>
                {{ project.name }}
              </h5>
            </div>
            <hr class="mt-2 mb-2">
            <div
              class=""
              v-for="status in project.status"
              :key="status.detail.id"
            >
              <div
                class="grid grid-cols-2 rounded mb-1 p-1 text-white"
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

export default defineComponent({
  components: {
    AppLayout,
  },
  data() {
    return {
      projects: [],
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
  },
  mounted() {
    this.getProjects();
  },
});
</script>

<style></style>
