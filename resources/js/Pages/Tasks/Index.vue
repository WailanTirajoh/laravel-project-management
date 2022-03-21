<template>
  <app-layout title="Tasks">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Task</h2>
    </template>

    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-4">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-1 bg-gray-100 p-2 rounded-md border-0">
              <div class="text-lg text-center">Create new task</div>
              <hr class="mt-1 mb-2" />
              <form @submit.prevent="storeTask()" action="">
                <div class="mb-2">
                  <label for="name" class="text-sm text-gray-700">
                    Task Name
                  </label>
                  <input
                    v-model="form.name"
                    id="name"
                    name="name"
                    type="text"
                    :class="{
                      error: form.errors.name,
                    }"
                    class="border-0 block w-full rounded text-gray-600 text-sm focus:border-transparent focus:ring-0"
                    placeholder="Type task name..."
                  />
                  <small class="text-red-500" v-if="form.errors.name">
                    {{ form.errors.name }}
                  </small>
                </div>
                <div class="mb-2">
                  <div
                    class="ex1 text-xs rounded p-2"
                    :class="{
                      error: form.errors.status_id,
                    }"
                  >
                    <label
                      class="radio red"
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
                  <small class="text-red-500" v-if="form.errors.status_id">
                    {{ form.errors.status_id }}
                  </small>
                </div>
                <div class="mb-2">
                  <label for="description" class="text-sm text-gray-700">
                    Task Description
                  </label>
                  <textarea
                    v-model="form.description"
                    id="description"
                    name="description"
                    rows="8"
                    class="border-0 block w-full rounded text-gray-600 text-sm focus:border-transparent focus:ring-0"
                    :class="{
                      error: form.errors.description,
                    }"
                    placeholder="Type task description..."
                  ></textarea>
                  <small class="text-red-500" v-if="form.errors.description">
                    {{ form.errors.description }}
                  </small>
                </div>
                <div class="mb-2">
                  <button
                    class="block bg-white rounded shadow-sm p-2 w-full hover:bg-gray-50 active:bg-gray-100"
                  >
                    Submit
                  </button>
                </div>
              </form>
            </div>
            <div class="col-span-2 bg-gray-100 p-2 rounded-md border-0">
              <div class="flex justify-between mx-2">
                <h5 class="font-semibold text-lg text-center">Task Lists</h5>
                <select
                  class="text-xs form-select appearance-none block w-34 px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                  v-model="status"
                >
                  <option class="text-sm" value="All" selected>All</option>
                  <option
                    class="text-sm"
                    v-for="status in task_statuses"
                    :key="status.id"
                    :value="status.name"
                  >
                    {{ status.name }}
                  </option>
                </select>
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
                  <ul class="text-sm" v-if="tasks.length > 0">
                    <li
                      v-for="task in tasks"
                      :key="task.id"
                      class="bg-white rounded-md p-2 mb-2 relative"
                    >
                      <button
                        @click="deleteTask(task.id)"
                        class="absolute -top-2 -right-1 p-1 rounded-full bg-lime-50 shadow w-6 h-6 flex justify-center items-center"
                      >
                        x
                      </button>
                      <div class="flex gap-1 justify-between">
                        <div class="">
                          {{ task.name }}
                        </div>
                        <div
                          class="p-1 rounded text-sm border text-xs mr-4"
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
  },
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        description: null,
        status_id: null,
      }),
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
  },
  methods: {
    async getTasks() {
      try {
        this.fetching = true;

        const response = await axios.get("/api/tasks", {
          params: {
            status: this.status,
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
          alert(data.message);
          const removeIndex = this.tasks.findIndex(
            (task) => task.id === task_id
          );
          this.tasks.splice(removeIndex, 1);
        }
      } catch (e) {
        alert(e.message);
      } finally {
      }
    },

    async storeTask() {
      try {
        const response = this.form.post(this.route("tasks.store"), {
          preserveScroll: (page) => Object.keys(page.props.errors).length,
        });
        console.log(response);
      } catch (e) {
        console.log(e);
      } finally {
      }
    },
  },
  mounted() {
    this.getTasks();
  },
});
</script>

<style scoped>
.example {
  margin: 20px;
}
.example input {
  display: none;
}
.example label {
  margin-right: 20px;
  display: inline-block;
  cursor: pointer;
}

.ex1 span {
  display: block;
  padding: 5px 10px 5px 25px;
  border: 2px solid #ddd;
  border-radius: 5px;
  position: relative;
  transition: all 0.25s linear;
}
.ex1 span:before {
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
.ex1 input:checked + span {
  background-color: #fff;
  box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
}
.ex1 .red input:checked + span {
  color: red;
  border-color: red;
}
.ex1 .red input:checked + span:before {
  background-color: red;
}
.ex1 .blue input:checked + span {
  color: blue;
  border-color: blue;
}
.ex1 .blue input:checked + span:before {
  background-color: blue;
}
.ex1 .orange input:checked + span {
  color: orange;
  border-color: orange;
}
.ex1 .orange input:checked + span:before {
  background-color: orange;
}

.error {
  border: 1px solid red;
}
</style>
