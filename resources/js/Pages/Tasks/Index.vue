<template>
  <app-layout title="Tasks">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Task</h2>
    </template>

    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-1 bg-gray-100 p-2 rounded-md border-0">
              <div class="text-lg text-center" v-if="form.task_id === 0">Create new task</div>
              <div class="text-lg text-center" v-else>Edit task</div>
              <hr class="mt-1 mb-2" />
              <div>
                <input type="hidden" v-model="form.task_id" />
                <div class="mb-2">
                  <label for="name" class="text-xs text-gray-700">
                    Project
                  </label>
                  <select
                    class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    v-model="form.project_id"
                    aria-placeholder="Select project"
                  >
                    <option
                      class="text-xs"
                      v-for="project in projects"
                      :key="project.id"
                      :value="project.id"
                    >
                      {{ project.name }}
                    </option>
                  </select>
                </div>
                <div class="mb-2">
                  <label for="name" class="text-xs text-gray-700">
                    Task Name
                  </label>
                  <input
                    v-model="form.name"
                    id="name"
                    name="name"
                    type="text"
                    class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    placeholder="Type task name..."
                  />
                </div>
                <div class="mb-2">
                  <label for="description" class="text-xs text-gray-700">
                    Task Description
                  </label>
                  <textarea
                    v-model="form.description"
                    id="description"
                    name="description"
                    rows="8"
                    class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    placeholder="Type task description..."
                  ></textarea>
                </div>
                <div class="mb-2">
                  <label for="description" class="text-xs text-gray-700">
                    Task Status
                  </label>
                  <div class="cst-radio text-xs rounded">
                    <label
                      class="radio orange cursor-pointer"
                      v-for="status in task_statuses"
                      :key="status.id"
                    >
                      <input
                        v-model="form.status_id"
                        type="radio"
                        name="group1"
                        :value="status.id"
                        class="hidden"
                      />
                      <span class="mb-1" :style="{ borderColor: status.color }">
                        {{ status.name }}
                      </span>
                    </label>
                  </div>
                </div>
                <div class="mb-2" v-if="form.task_id == 0">
                  <button
                    @click="storeTask()"
                    class="block bg-white rounded shadow-sm p-2 w-full hover:bg-gray-50 active:bg-gray-100"
                  >
                    Submit
                  </button>
                </div>
                <div class="mb-2 flex justify-between" v-else>
                  <div class="">
                    <button
                      @click="cancelEdit()"
                      class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100"
                    >
                      Cancel
                    </button>
                  </div>
                  <div class="flex gap-1">
                    <button
                      @click="deleteTask(form.task_id)"
                      class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100 mr-1"
                    >
                      Delete
                    </button>
                    <button
                      @click="updateTask()"
                      class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100"
                    >
                      Update
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-span-2 bg-gray-100 p-2 rounded-md border-0">
              <div class="flex justify-between mx-2">
                <h5 class="font-semibold text-lg text-center">Task Lists</h5>
                <div class="flex gap-1">
                  <select
                    class="text-xs form-select appearance-none block px-1 py-1 text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none pr-12"
                    v-model="project_id"
                  >
                    <option class="text-xs" value="All" selected>All Project</option>
                    <option
                      class="text-xs"
                      v-for="project in projects"
                      :key="project.id"
                      :value="project.id"
                    >
                      {{ project.name }}
                    </option>
                  </select>
                  <select
                    class="text-xs form-select appearance-none block px-1 py-1 text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none pr-12"
                    v-model="status"
                  >
                    <option class="text-xs" value="All" selected>All Status</option>
                    <option
                      class="text-xs"
                      v-for="status in task_statuses"
                      :key="status.id"
                      :value="status.name"
                    >
                      {{ status.name }}
                    </option>
                  </select>
                </div>
              </div>
              <hr class="mb-2 mt-1" />
              <div class="h-80 overflow-y-auto">
                <div
                  v-if="fetching"
                  class="h-full w-full flex items-center justify-center"
                >
                  <jet-loading-circle-dots class="w-20 h-20" />
                </div>
                <div class="h-full p-2" v-else>
                  <ul class="text-xs" v-if="tasks.length > 0">
                    <li
                      v-for="task in tasks"
                      :key="task.id"
                      class="bg-white rounded-md p-2 mb-2 relative"
                    >
                      <button
                        @click="editTask(task.id)"
                        class="absolute -top-2 -right-1 p-1 rounded-full bg-lime-50 shadow w-6 h-6 flex justify-center items-center"
                      >
                        <i class="fa-solid fa-pencil"></i>
                      </button>
                      <div class="flex gap-1 justify-between">
                        <div class="">
                          {{ task.name }}
                        </div>
                        <div
                          class="p-1 rounded text-xs border text-xs mr-4"
                          :style="{ backgroundColor: task.status.color }"
                        >
                          {{ task.status.name }}
                        </div>
                      </div>
                      <div class="text-gray-500">
                        {{ task.description }}
                      </div>
                    </li>
                  </ul>
                  <div class="h-full flex justify-center items-center" v-else>
                    No Data
                  </div>
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
import AppLayout from "@/Layouts/AppLayout.vue";
import JetLoadingCircleDots from "@/Jetstream/LoadingCircleDots.vue";

export default defineComponent({
  components: {
    AppLayout,
    JetLoadingCircleDots,
  },
  props: {
    task_statuses: Array,
    projects: Array,
  },
  data() {
    return {
      form: {
        task_id: 0,
        name: null,
        description: null,
        status_id: null,
        project_id: null,
      },
      project_id: "All",
      status: "All",
      tasks: [],
      fetching: true,
    };
  },
  watch: {
    status(oldValue, newValue) {
      this.tasks = [];
      this.getTasks();
    },
    project_id(oldValue, newValue) {
      this.tasks = [];
      this.getTasks();
    },
  },
  methods: {
    async getTasks() {
      try {
        this.fetching = true;

        const response = await axios.get("/api/tasks", {
          params: {
            status: this.status,
            project_id: this.project_id,
          },
        });

        this.tasks = response.data.data;
      } catch (e) {
      } finally {
        this.fetching = false;
      }
    },

    async deleteTask(task_id) {
      try {
        const { status, data } = await axios.delete(`/api/tasks/${task_id}`);

        if (status == 200) {
          const removeIndex = this.tasks.findIndex(
            (task) => task.id === task_id
          );
          this.resetForm();
          this.tasks.splice(removeIndex, 1);
        }
      } catch (e) {
        alert(e.message);
      } finally {
      }
    },

    cancelEdit() {
      this.resetForm();
    },

    editTask(task_id) {
      const taskIndex = this.tasks.findIndex((task) => task.id === task_id);
      this.fillForm(this.tasks[taskIndex]);
    },

    async storeTask() {
      try {
        const { status, data } = await axios.post(`/api/tasks`, this.form);
        if (status == 200) {
          const { task } = data;

          this.tasks.push(task);

          this.resetForm();
        }
      } catch (e) {
        console.log(e);
      }
    },

    async updateTask() {
      try {
        const { status, data } = await axios.put(
          `/api/tasks/${this.form.task_id}`,
          this.form
        );

        if (status == 200) {
          const { task } = data;

          const taskIndex = this.tasks.findIndex(
            (task) => task.id === this.form.task_id
          );
          this.tasks[taskIndex] = task;

          this.resetForm();
        }
      } catch (e) {
        console.log(e);
      }
    },

    fillForm(task) {
      this.form.task_id = task.id;
      this.form.name = task.name;
      this.form.description = task.description;
      this.form.status_id = task.status.id;
      this.form.project_id = task.project_id;
    },

    resetForm() {
      this.form.task_id = 0;
      this.form.name = null;
      this.form.description = null;
      this.form.status_id = null;
      this.form.project_id = null;
    },
  },
  mounted() {
    this.getTasks();
  },
});
</script>

<style scoped>
.cst-radio span {
  display: block;
  padding: 5px 10px 5px 25px;
  border: 2px solid #ddd;
  border-radius: 5px;
  position: relative;
  transition: all 0.25s linear;
}
.cst-radio span:before {
  content: "";
  position: absolute;
  left: 5px;
  top: 50%;
  -webkit-transform: translatey(-50%);
  transform: translatey(-50%);
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background-color: #ddd;
  transition: all 0.25s linear;
}
.cst-radio input:checked + span {
  background-color: #fff;
  box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
}
.cst-radio .red input:checked + span {
  color: red;
  border-color: red;
}
.cst-radio .red input:checked + span:before {
  background-color: red;
}
.cst-radio .blue input:checked + span {
  color: blue;
  border-color: blue;
}
.cst-radio .blue input:checked + span:before {
  background-color: blue;
}
.cst-radio .orange input:checked + span {
  color: orange;
  border-color: orange;
}
.cst-radio .orange input:checked + span:before {
  background-color: orange;
}

.error {
  border: 1px solid red;
}
</style>
