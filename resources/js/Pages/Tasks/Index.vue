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
              <div class="text-lg font-semibold" v-if="form.task_id === 0">
                Create new task
              </div>
              <div class="text-lg font-semibold" v-else>Edit task</div>
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
                  <label for="name" class="text-xs text-gray-700"> Name </label>
                  <input
                    v-model="form.name"
                    ref="name"
                    id="name"
                    name="name"
                    type="text"
                    class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    placeholder="Type task name..."
                  />
                </div>
                <div class="mb-2">
                  <label for="description" class="text-xs text-gray-700">
                    Description
                  </label>
                  <div
                    class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                  >
                    <quill-editor
                      ref="editor"
                      v-model:content="form.description"
                      content-type="html"
                      theme="snow"
                      placeholder="Type task description..."
                      rows="8"
                      :toolbar="toolbar"
                    ></quill-editor>
                  </div>
                </div>
                <div class="grid grid-cols-2 mb-2 gap-2">
                  <div class="col-span-1">
                    <label for="start" class="text-xs text-gray-700">
                      Start
                    </label>
                    <input
                      v-model="form.start_date"
                      id="start"
                      name="name"
                      type="datetime-local"
                      format
                      class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                      placeholder="Type task name..."
                    />
                  </div>
                  <div class="col-span-1">
                    <label for="due" class="text-xs text-gray-700"> Due </label>
                    <input
                      v-model="form.due_date"
                      id="due"
                      name="name"
                      type="datetime-local"
                      class="border-0 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                      placeholder="Type task name..."
                    />
                  </div>
                </div>
                <div class="mb-2">
                  <label for="description" class="text-xs text-gray-700">
                    Status
                  </label>
                  <div class="cst-radio text-xs rounded">
                    <label
                      class="radio orange cursor-pointer"
                      v-for="status in statuses"
                      :key="status.id"
                    >
                      <input
                        v-model="form.status_id"
                        type="radio"
                        :value="status.id"
                        class="hidden"
                      />
                      <span class="mb-1">
                        {{ status.name }}
                      </span>
                    </label>
                  </div>
                  <div class="text-red-500" v-if="errors.status_id">
                    {{ errors.status_id }}
                  </div>
                </div>
                <div class="mb-2 text-sm" v-if="form.task_id == 0">
                  <button
                    @click="storeTask()"
                    class="block bg-white rounded shadow-sm p-2 w-full hover:bg-gray-50 active:bg-gray-100"
                  >
                    Submit
                  </button>
                </div>
                <div class="mb-2 flex justify-between text-sm" v-else>
                  <button
                    @click="deleteTask(form.task_id)"
                    class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100 mr-1"
                  >
                    <i class="fa-solid fa-trash"></i>
                  </button>
                  <button
                    @click="updateTask()"
                    class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100 block w-40"
                    style="width: 10rem"
                  >
                    Save
                  </button>
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
                    <option class="text-xs" value="All" selected>
                      All Project
                    </option>
                    <option
                      class="text-xs"
                      v-for="project in projects"
                      :key="project.id"
                      :value="project.id"
                    >
                      {{ project.name }} - {{ project.tasks.length }}
                    </option>
                  </select>
                </div>
              </div>
              <hr class="mb-2 mt-1" />
              <div class="flex gap-2 mb-1 justify-end px-2">
                <div class="form-check">
                  <label
                    class="form-check-label border rounded p-1 inline-block text-gray-800 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0 cursor-pointer bg-white transition-all ease-in-out duration-300"
                    :for="`status-check-0`"
                    @click="resetStatus"
                    :class="{
                      'font-bold':
                        status.length === 0 ||
                        status.length === statuses.length,
                    }"
                  >
                    All
                  </label>
                </div>
                <div
                  class="form-check"
                  v-for="thestatus in statuses"
                  :key="thestatus.id"
                >
                  <input
                    v-model="status"
                    :id="`status-check-${thestatus.id}`"
                    class="hidden status-check"
                    type="checkbox"
                    :value="thestatus.id"
                  />
                  <label
                    class="form-check-label border rounded p-1 inline-block text-gray-800 block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0 cursor-pointer bg-white"
                    :for="`status-check-${thestatus.id}`"
                  >
                    {{ thestatus.name }} {{ totalStatus(thestatus.id) }}
                  </label>
                </div>
              </div>
              <div
                class="overflow-y-auto bg-gray-50 rounded-2xl p-2"
                style="height: 36rem"
              >
                <div
                  v-if="fetching"
                  class="h-full w-full flex items-center justify-center"
                >
                  <jet-loading-circle-dots class="w-20 h-20" />
                </div>
                <div class="h-full p-2" v-else>
                  <ul class="text-xs" v-if="filteredTasks.length > 0">
                    <li
                      v-for="(task, index) in filteredTasks"
                      :key="task.id"
                      class="grid grid-cols-4 gap-4"
                    >
                      <div
                        class="relative bg-white rounded-md p-2 px-4 mb-2 col-span-3 transition-all ease-in-out duration-300 cursor-grab active:cursor-grabbing task-sort"
                        :class="{
                          'bg-gray-100': form.task_id === task.id,
                          'shadow-md -translate-y-1': task.swapping,
                        }"
                        style="border-right: 0.25rem solid"
                        :style="{
                          'border-right-color': task.status.color,
                        }"
                        draggable="true"
                        @dragstart="dragStart(task, index)"
                        @dragover="dragOver(task, index)"
                        @dragenter="dragEnter(task, index)"
                        @dragleave="dragLeave(task, index)"
                        @drop="dragDrop(task, index)"
                        @dragover.prevent
                        @dragenter.prevent
                        :data-index="index"
                        :data-id="task.id"
                      >
                        <button
                          v-if="form.task_id === task.id"
                          @click="cancelEdit(task.id)"
                          class="absolute -top-2 -left-2 p-1 rounded-full bg-gray-100 shadow w-6 h-6 flex justify-center items-center"
                        >
                          <i class="fa-solid fa-xmark"></i>
                        </button>
                        <button
                          v-else
                          @click="editTask(task.id)"
                          class="absolute -top-2 -left-2 p-1 rounded-full bg-gray-100 shadow w-6 h-6 flex justify-center items-center"
                        >
                          <i class="fa-solid fa-pencil"></i>
                        </button>
                        <div class="flex gap-1 justify-between mb-1">
                          <div class="flex items-center">
                            {{ task.name }}
                          </div>
                          <div class="flex gap-1">
                            <div
                              class="p-1 rounded text-xs border text-xs flex items-center gap-2"
                            >
                              <input
                                :ref="`color_${task.status.id}_${index}`"
                                type="color"
                                id="favcolor"
                                class="w-4 h-4 rounded color-input"
                                name="favcolor"
                                :value="task.status.color"
                                @change="
                                  updateStatusColor(
                                    `color_${task.status.id}_${index}`
                                  )
                                "
                              />
                              {{ task.status.name }}
                            </div>
                          </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg ql-snow">
                          <div
                            class="ql-editor"
                            v-html="task.description"
                          ></div>
                        </div>
                      </div>
                      <div class="text-xs">
                        <div>
                          <div class="font-semibold">
                            {{ task.pic.name }}
                          </div>
                          <div class="font-thin">
                            {{ task.project.name }}
                          </div>
                          <!-- <div class="font-thin text-gray-500">
                            {{ task.due_date_date }}
                          </div>
                          <div class="font-thin text-gray-500">
                            {{ task.due_date_hour }}
                          </div> -->
                          <div class="font-thin text-gray-500">
                            {{ task.due_left }}
                          </div>
                        </div>
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
      toolbar: [
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        [{ font: [] }],
        ["bold", "italic", "underline", "strike"], // toggled buttons
        ["blockquote", "code-block"],

        // [{ header: 1 }, { header: 2 }], // custom button values
        [{ list: "ordered" }, { list: "bullet" }],
        [{ script: "sub" }, { script: "super" }], // superscript/subscript
        [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
        [{ direction: "rtl" }], // text direction
        [{ color: [] }, { background: [] }], // dropdown with defaults from theme
        [{ align: [] }],

        ["clean"],
      ],
      form: {
        task_id: 0,
        name: null,
        description: null,
        status_id: null,
        project_id: null,
        start_date: null,
        due_date: null,
      },
      errors: {},
      project_id: "All",
      status: [],
      tasks: [],
      fetching: true,
      drag: {
        start: {
          index: null,
          item: null,
        },
      },
      statuses: this.task_statuses,
    };
  },
  computed: {
    filteredTasks() {
      return this.tasks
        .filter((task) => {
          if (this.status.length === 0) return true;
          return this.status.includes(task.status_id);
        })
        .sort((a, b) => a.sort - b.sort);
    },
  },
  watch: {
    project_id(oldValue, newValue) {
      this.getTasks();
      this.resetForm();
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

    totalStatus(status_id) {
      const filteredTasks = this.tasks.filter((task) => {
        return task.status_id === status_id;
      });

      return filteredTasks.length;
    },

    resetStatus() {
      this.status = [];
    },

    cancelEdit() {
      this.resetForm();
    },

    editTask(task_id) {
      const taskIndex = this.tasks.findIndex((task) => task.id === task_id);
      this.fillForm(this.tasks[taskIndex]);
      this.$refs.name.focus();
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
      this.form.start_date = task.start_date;
      this.form.due_date = task.due_date;

      this.$refs.editor.setHTML(task.description);
    },

    resetForm() {
      this.form.task_id = 0;
      this.form.name = null;
      this.form.description = null;
      this.form.status_id = null;
      this.form.project_id = null;
      this.form.start_date = null;
      this.form.due_date = null;

      this.$refs.editor.setHTML("");
    },

    dragStart(item, index) {
      const taskIndex2 = this.tasks.findIndex((task) => task.id === item.id);
      this.tasks[taskIndex2].swapping = true;

      this.drag.start.index = index;
      this.drag.start.item = item;
    },

    dragEnter(item, index) {
      const taskindex = this.tasks.findIndex((task) => task.id === item.id);

      this.tasks[taskindex].swapping = true;
    },

    dragOver(item, index) {
      const taskindex = this.tasks.findIndex((task) => task.id === item.id);

      this.tasks[taskindex].swapping = true;
    },

    dragLeave(item, index) {
      if (item.id !== this.drag.start.item.id) {
        const taskindex = this.tasks.findIndex((task) => task.id === item.id);

        this.tasks[taskindex].swapping = false;
      }
    },

    async dragDrop(item, index) {
      const taskIndex1 = this.tasks.findIndex(
        (task) => task.id === this.drag.start.item.id
      );
      this.tasks[taskIndex1].sort = index;
      this.tasks[taskIndex1].swapping = false;

      const taskIndex2 = this.tasks.findIndex((task) => task.id === item.id);
      this.tasks[taskIndex2].sort = this.drag.start.index;
      this.tasks[taskIndex2].swapping = false;

      await new Promise((r) => setTimeout(r, 500));
      this.saveSort();
    },

    saveSort() {
      const sorts = document.querySelectorAll(".task-sort");
      const sortable = [];
      sorts.forEach((sort) => {
        sortable.push({
          id: sort.dataset.id,
          index: sort.dataset.index,
        });
      });

      this.updateTaskSort({ sortable });
    },

    async updateTaskSort({ sortable }) {
      try {
        const response = await axios.put(`/api/tasks/sort`, {
          sortable,
        });

        console.log(response);
      } catch (e) {}
    },

    async updateStatusColor(ref) {
      const color = this.$refs[ref][0].value;
      const id = ref.split("_")[1];

      try {
        const { status, data } = await axios.put(`/api/statuses/${id}/color`, {
          color: color,
        });

        if (status === 200) {
          // Update status color
          const statusIndex = this.statuses.findIndex(
            (stat) => stat.id === parseInt(id)
          );
          this.statuses[statusIndex].color = color;

          // Update every task status color
          this.tasks
            .filter((task) => {
              return task.status.id === parseInt(id);
            })
            .map((task) => {
              task.status.color = color;
            });
        }
      } catch (e) {
        console.log(e);
      }
    },
  },
  mounted() {
    this.getTasks();
    this.$refs.name.focus();
  },
});
</script>

<style scoped>
.cst-radio span {
  padding: 5px 10px 5px 25px;
  border: 1px solid #fff;
  border-radius: 5px;
  position: relative;
  transition: all 0.25s linear;
  display: flex;
  align-items: center;
  justify-content: space-between;
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
  color: rgb(148, 148, 148);
  border-color: rgb(148, 148, 148);
}
.cst-radio .black input:checked + span:before {
  background-color: rgb(148, 148, 148);
}

.error {
  border: 1px solid red;
}

.status-check:checked + label {
  font-weight: bold;
}
.color-input::-webkit-color-swatch {
  border: none;
  border-radius: 0.25rem;
  padding: 0;
}
.color-input::-webkit-color-swatch-wrapper {
  border: none;
  border-radius: 0.25rem;
  padding: 0;
}
</style>
