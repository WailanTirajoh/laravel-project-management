<template>
  <app-layout title="Projects">
    <toast :message="toast.message" :is-shown="toast.show" :type="toast.type" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Projects
      </h2>
    </template>
    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <modal class="mb-2" ref="modal">
          <template #button>
            <button
              class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button"
            >
              Create New Project
            </button>
          </template>
          <template #header> Create New Project </template>
          <template #body>
            <div class="">
              <input type="hidden" v-model="form.project_id" />
              <div class="mb-2 tooltip">
                <label for="name" class="text-xs text-gray-700 pl-1">
                  Name
                </label>
                <input
                  v-model="form.name"
                  ref="name"
                  id="name"
                  name="name"
                  type="text"
                  class="block w-full rounded text-gray-600 text-xs focus:border-transparent active:ring-0 focus:ring-0 bg-gray-50 ease-linear transition-all duration-150 hover:shadow-lg focus:shadow-lg hover:pl-4 focus:pl-4"
                  :class="{
                    'border-1 border-red-400': errors.name,
                    'border-0': !errors.name,
                  }"
                  placeholder="Type task name..."
                  @focus="errors.name = null"
                />
                <error-message
                  :errors="errors.name"
                  @remove-error="errors.name = null"
                />
              </div>
            </div>
          </template>
          <template #footer>
            <button
              class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 cursor-pointer"
              type="button"
              @click="storeProject()"
              :disabled="form.is_processing"
            >
              Save
            </button>
          </template>
        </modal>
        <div v-if="projects">
          <transition-group
            tag="ul"
            name="list"
            class="grid grid-cols-4 gap-4"
            appear
            v-if="projects.length > 0"
          >
            <li
              class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4 px-2 col-span-4 sm:col-span-2 lg:col-span-1 ease-linear transition-all duration-150"
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
                  class="grid grid-cols-2 rounded mb-1 p-1 stroke-white stroke-1 border-l-2 shadow ease-linear transition-all duration-150 pl-2 hover:pl-4 hover:shadow-lg"
                  :style="{
                    'border-color': status.detail.color,
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
            </li>
          </transition-group>
          <div v-else>No Project</div>
        </div>
        <div class="flex justify-center items-center h-64" v-else>
          <jet-loading-circle-dots />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  data() {
    return {
      projects: null,
      form: {
        is_processing: false,
        project_id: null,
        name: null,
      },
      errors: {
        name: null,
      },
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
    async storeProject() {
      try {
        this.form.is_processing = false;
        const { status, data } = await axios.post(`/api/projects`, this.form);
        if (status === 200) {
          this.projects.push(data.project);
          this.resetForm();
          this.showToast({ message: data.message, type: "success" });
          this.$refs.modal.toggleModal();
        }
      } catch (e) {
        if (e.response && e.response.data && e.response.data.errors) {
          this.showToast({ message: e.response.data.message, type: "error" });
          return (this.errors = e.response.data.errors);
        }
        errorHandler(e);
      } finally {
        this.form.is_processing = false;
      }
    },
    resetForm() {
      this.form.project_id = null;
      this.form.name = null;
    },
  },
  mounted() {
    this.getProjects();
  },
});
</script>

<style scoped>
.list-enter-from {
  opacity: 0;
  transform: translateY(1rem);
}
.list-enter-to {
  opacity: 1;
  transform: scale(1);
}
.list-enter-active {
  transition: all 0.4s ease;
}
.list-leave-from {
  opacity: 1;
  transform: scale(1);
}
.list-leave-to {
  opacity: 0;
  transform: scale(0.6);
}
.list-leave-active {
  transition: all 0.4s ease;
  position: absolute;
}
.list-move {
  transition: all 0.4s ease;
}
</style>
