<template>
  <app-layout title="Tasks">
    <toast :message="toast.message" :is-shown="toast.show" :type="toast.type" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Task</h2>
    </template>

    <div class="py-12 text-gray-600">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <cust-off-canvas ref="custCanvas" @on-close="resetForm()">
          <template #button>
            <button
              class="bg-white rounded p-2 shadow-sm mb-2"
              ref="openCanvas"
            >
              <div class="font-semibold">
                <i class="fa-solid fa-plus"></i>
              </div>
            </button>
          </template>
          <template #header>
            <div class="" ref="closeCanvas">
              <div class="text-lg font-semibold" v-if="form.task_id === 0">
                Create new task
              </div>
              <div class="text-lg font-semibold" v-else>Edit task</div>
            </div>
          </template>
          <div class="bg-gray-100 p-2 rounded-md border-0 md:block">
            <div>
              <input type="hidden" v-model="form.task_id" />
              <div class="mb-2 tooltip">
                <label for="name" class="text-xs text-gray-700">
                  Project
                </label>
                <select
                  @click="errors.project_id = null"
                  @change="form.parent_id = null"
                  class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0 transition-all ease-in-out duration-300"
                  :class="{
                    'border-1 border-red-400': errors.project_id,
                    'border-0': !errors.project_id,
                  }"
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
                <error-message
                  :errors="errors.project_id"
                  @remove-error="errors.project_id = null"
                />
              </div>
              <div class="mb-2 tooltip">
                <label for="name" class="text-xs text-gray-700">
                  Parent Task
                </label>
                <select
                  @click="errors.parent_id = null"
                  class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0 transition-all ease-in-out duration-300"
                  :class="{
                    'border-1 border-red-400': errors.parent_id,
                    'border-0': !errors.parent_id,
                  }"
                  v-model="form.parent_id"
                  aria-placeholder="Select project"
                >
                  <option class="text-xs" :value="null">None</option>
                  <option
                    class="text-xs"
                    v-for="task in filteredParentTasks"
                    :key="task.id"
                    :value="task.id"
                  >
                    {{ task.name }}
                  </option>
                </select>
                <error-message
                  :errors="errors.parent_id"
                  @remove-error="errors.parent_id = null"
                />
              </div>
              <div class="mb-2 tooltip">
                <label for="name" class="text-xs text-gray-700"> Name </label>
                <input
                  v-model="form.name"
                  ref="name"
                  id="name"
                  name="name"
                  type="text"
                  class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
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
              <div class="mb-2 tooltip">
                <label for="description" class="text-xs text-gray-700">
                  Description
                </label>
                <div
                  class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                >
                  <quill-editor
                    ref="editor"
                    @focus="errors.description = null"
                    v-model:content="form.description"
                    content-type="html"
                    theme="snow"
                    placeholder="Type task description..."
                    rows="8"
                    :toolbar="toolbar"
                  ></quill-editor>
                </div>
                <error-message
                  :errors="errors.description"
                  @remove-error="errors.description = null"
                />
              </div>
              <div class="grid grid-cols-2 mb-2 gap-2">
                <div class="col-span-1 tooltip">
                  <label for="start" class="text-xs text-gray-700">
                    Start
                  </label>
                  <input
                    v-model="form.start_date"
                    @focus="errors.start_date = null"
                    id="start"
                    name="name"
                    type="datetime-local"
                    format
                    class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    placeholder="Type task name..."
                    :class="{
                      'border-1 border-red-400': errors.start_date,
                      'border-0': !errors.start_date,
                    }"
                  />
                  <error-message
                    :errors="errors.start_date"
                    @remove-error="errors.start_date = null"
                  />
                </div>
                <div class="col-span-1 tooltip">
                  <label for="due" class="text-xs text-gray-700"> Due </label>
                  <input
                    v-model="form.due_date"
                    @focus="errors.due_date = null"
                    id="due"
                    name="name"
                    type="datetime-local"
                    class="block w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0"
                    placeholder="Type task name..."
                    :class="{
                      'border-1 border-red-400': errors.due_date,
                      'border-0': !errors.due_date,
                    }"
                  />
                  <error-message
                    :errors="errors.due_date"
                    @remove-error="errors.due_date = null"
                  />
                </div>
              </div>
              <div class="mb-2 tooltip">
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
                      @change="errors.status_id = null"
                      type="radio"
                      :value="status.id"
                      class="hidden"
                    />
                    <span class="mb-1">
                      {{ status.name }}
                    </span>
                  </label>
                </div>
                <error-message
                  :errors="errors.status_id"
                  @remove-error="errors.status_id = null"
                />
              </div>
              <div class="mb-2 text-sm" v-if="form.task_id == 0">
                <button
                  :disabled="form.is_processing"
                  @click="storeTask()"
                  class="block bg-white rounded shadow-sm p-2 w-full hover:bg-gray-50 active:bg-gray-100"
                  :class="{
                    'cursor-wait': form.is_processing,
                  }"
                >
                  {{ form.is_processing ? "Processing" : "Submit" }}
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
                  :disabled="form.is_processing"
                  @click="updateTask()"
                  class="bg-white rounded shadow-sm p-2 hover:bg-gray-50 active:bg-gray-100 block w-40"
                  style="width: 10rem"
                  :class="{
                    'cursor-wait': form.is_processing,
                  }"
                >
                  {{ form.is_processing ? "Processing" : "Save" }}
                </button>
              </div>
            </div>
          </div>
        </cust-off-canvas>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-3 bg-gray-100 p-2 rounded-md border-0">
              <div class="flex justify-between mx-2 gap-2">
                <h5 class="font-semibold text-lg text-center whitespace-nowrap">
                  Task Lists
                </h5>
                <div class="flex gap-1 overflow-x-auto">
                  <select
                    class="text-xs form-select appearance-none block px-1 py-1 text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none pr-12"
                    v-model="sort.type_value"
                  >
                    <option
                      class="text-xs"
                      v-for="(srt, index) in sort.type"
                      :key="index"
                      :value="srt"
                    >
                      {{ srt }}
                    </option>
                  </select>
                  <select
                    class="text-xs form-select appearance-none block px-1 py-1 text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none pr-12"
                    v-model="sort.value"
                  >
                    <option
                      class="text-xs"
                      v-for="(srt, index) in sort.fillable"
                      :key="index"
                      :value="srt"
                    >
                      {{ srt }}
                    </option>
                  </select>
                  <select
                    class="text-xs form-select appearance-none block px-1 py-1 text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none pr-12 block md:hidden"
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
              <div class="flex gap-2 mb-1 md:justify-end px-2 overflow-x-auto">
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
                    class="form-check-label border rounded inline-block text-gray-800 flex justify-between w-full rounded text-gray-600 text-xs focus:border-transparent focus:ring-0 cursor-pointer bg-white whitespace-nowrap"
                    :for="`status-check-${thestatus.id}`"
                  >
                    <div class="p-1">
                      {{ thestatus.name }}
                    </div>
                    <span
                      class="bg-gray-200 p-1 flex items-center justify-center"
                    >
                      {{ totalStatus(thestatus.id) }}
                    </span>
                  </label>
                </div>
              </div>
              <div
                class="overflow-y-auto bg-gray-50 rounded-2xl p-2 grid grid-cols-4"
                style="height: 36rem"
              >
                <div class="col-span-1 hidden md:block">
                  <ul class="bg-gray-100 p-3 rounded-l-2xl h-full">
                    <li
                      class="rounded mb-1 p-1 stroke-white stroke-1 border-l-4 hover:border-l-green-500 shadow ease-linear transition-all duration-150 pl-2 hover:pl-4 hover:shadow-lg bg-white cursor-pointer"
                      @click="project_id = 'All'"
                      :class="{
                        'pl-4 border-l-green-500': project_id == 'All',
                      }"
                    >
                      All
                    </li>
                    <li
                      v-for="project in projects"
                      :key="project.id"
                      :value="project.id"
                      class="rounded mb-1 p-1 stroke-white stroke-1 border-l-4 hover:border-l-green-500 shadow ease-linear transition-all duration-150 pl-2 hover:pl-4 hover:shadow-lg bg-white cursor-pointer"
                      @click="project_id = project.id"
                      :class="{
                        'pl-4 border-l-green-500': project_id == project.id,
                      }"
                    >
                      {{ project.name }}
                      <span v-if="project.tasks.length > 0"
                        >({{ project.tasks.length }})</span
                      >
                    </li>
                  </ul>
                </div>
                <div class="col-span-3 pl-2">
                  <transition name="switch" mode="out-in">
                    <div
                      v-if="fetching"
                      class="h-full w-full flex items-center justify-center"
                    >
                      <jet-loading-circle-dots class="w-20 h-20" />
                    </div>
                    <div class="h-full p-2" v-else>
                      <transition name="switch" mode="out-in">
                        <transition-group
                          tag="ul"
                          name="list"
                          class="text-xs"
                          v-if="filteredTasks.length > 0"
                          appear
                        >
                          <li
                            v-for="(task, index) in filteredTasks"
                            :key="task.id"
                            class="grid grid-cols-4 gap-2 gap-y-5"
                          >
                            <div class="col-span-4 md:col-span-3 group">
                              <div
                                class="relative bg-white rounded-md p-2 px-4 mb-1 transition-all ease-in-out duration-300 task-list group-hover:shadow-md"
                                :class="{
                                  'bg-gray-100': form.task_id === task.id,
                                  'shadow-md -translate-y-1': task.swapping,
                                  'cursor-grab active:cursor-grabbing task-sort':
                                    sortable,
                                }"
                                style="border-right: 0.25rem solid"
                                :style="{
                                  'border-right-color': task.status.color,
                                }"
                                :draggable="sortable"
                                @dragstart="dragStart(task, index, $event)"
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
                                        @input="
                                          liveUpdateDomStatusColor(
                                            `color_${task.status.id}_${index}`
                                          )
                                        "
                                      />
                                      {{ task.status.name }}
                                    </div>
                                  </div>
                                </div>
                                <div
                                  class="bg-gray-50 rounded-lg ql-snow relative group"
                                >
                                  <button
                                    @click="copy(task)"
                                    class="transition-all ease-in-out duration-300 absolute top-2 right-2 bg-white rounded border p-1 opacity-0 group-hover:opacity-100 hover:scale-110 active:scale-100"
                                  >
                                    <i class="fa-solid fa-clone"></i>
                                  </button>
                                  <div
                                    class="ql-editor"
                                    :id="`description-${task.id}`"
                                    v-html="task.description"
                                  ></div>
                                </div>
                              </div>
                              <div
                                class="flex justify-end opacity-0 group-hover:opacity-100 transition-all ease-in-out duration-300 mb-4"
                              >
                                <button
                                  class="transition-all ease-in-out duration-300 bg-white rounded-2xl border-2 border-l-pink-700 p-1 px-3 group-hover:shadow-md hover:scale-110 hover:shadow-lg active:scale-100 mr-2 z-10"
                                >
                                  <i class="fa-solid fa-thumbtack"></i>
                                </button>
                                <button
                                  class="transition-all ease-in-out duration-300 bg-white rounded-2xl border-2 border-l-pink-700 p-1 px-3 group-hover:shadow-md hover:scale-110 hover:shadow-lg active:scale-100 mr-2 z-10"
                                >
                                  <i class="fa-solid fa-face-smile"></i>
                                </button>
                                <button
                                  @click="comment.show = !comment.show"
                                  class="transition-all ease-in-out duration-300 bg-white rounded-2xl border-2 border-l-pink-700 p-1 px-3 group-hover:shadow-md hover:scale-110 hover:shadow-lg active:scale-100 mr-5 z-10"
                                >
                                  <i class="fa-solid fa-reply"></i>
                                </button>
                              </div>
                            </div>
                            <div class="text-xs hidden md:block">
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
                        </transition-group>
                        <div
                          class="h-full flex justify-center items-center"
                          v-else
                        >
                          No Data
                        </div>
                      </transition>
                    </div>
                  </transition>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <reply :show="comment.show" @close-reply="comment.show = false">
      <div class="p-4">testing 123</div>
    </reply>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import Reply from "@/Jetstream/Custom/Reply";

export default defineComponent({
  components: {
    Reply,
  },
  props: {
    task_statuses: Array,
    projects: Array,
  },
  data() {
    return {
      form: {
        is_processing: false,
        task_id: 0,
        name: null,
        description: null,
        status_id: null,
        project_id: null,
        start_date: null,
        due_date: null,
        parent_id: null,
      },
      errors: {
        name: null,
        description: null,
        status_id: null,
        project_id: null,
        start_date: null,
        due_date: null,
        parent_id: null,
      },
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
      sort: {
        value: "Date",
        type_value: "Desc",
        fillable: ["Date", "Priority"],
        type: ["Asc", "Desc"],
      },
      statuses: this.task_statuses,
      comment: {
        show: false,
      },
    };
  },
  computed: {
    filteredTasks() {
      let vm = this;
      return this.tasks
        .filter((task) => {
          if (this.status.length === 0) return true;
          return this.status.includes(task.status_id);
        })
        .sort(function (a, b) {
          if (vm.sort.value === "Priority") {
            return vm.sort.type_value === "Desc"
              ? a.sort - b.sort
              : b.sort - a.sort;
          }
          return vm.sort.type_value === "Desc"
            ? new Date(b.due_date_default) - new Date(a.due_date_default)
            : new Date(a.due_date_default) - new Date(b.due_date_default);
        });
    },
    filteredParentTasks() {
      let vm = this;
      return this.tasks.filter((task) => {
        if (this.form.project_id == null) return true;

        return task.project_id === this.form.project_id;
      });
    },
    sortable() {
      return this.sort.value === "Priority";
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
        this.showToast({ message: data.message, type: "error" });
        errorHandler(e);
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
          this.showToast({ message: data.message, type: "success" });
        }
      } catch (e) {
        this.showToast({ message: data.message, type: "error" });
        errorHandler(e);
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

    async editTask(task_id) {
      this.$refs.openCanvas.click();
      await new Promise((r) => setTimeout(r, 300));
      const taskIndex = this.tasks.findIndex((task) => task.id === task_id);
      this.fillForm(this.tasks[taskIndex]);
      this.$refs.name.focus();
    },

    async storeTask() {
      try {
        this.form.is_processing = true;
        const { status, data } = await axios.post(`/api/tasks`, this.form);
        if (status == 200) {
          const { task } = data;

          this.tasks.push(task);

          this.resetForm();
          this.showToast({ message: data.message, type: "success" });
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

    async updateTask() {
      try {
        this.form.is_processing = true;
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
          this.showToast({ message: data.message, type: "success" });
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

    fillForm(task) {
      this.resetErrors();
      this.form.task_id = task.id;
      this.form.parent_id = task.parent_id;
      this.form.name = task.name;
      this.form.description = task.description;
      this.form.status_id = task.status.id;
      this.form.project_id = task.project_id;
      this.form.start_date = task.start_date;
      this.form.due_date = task.due_date;
      this.$refs.editor.setHTML(task.description);
    },

    resetForm() {
      this.resetErrors();
      if (this.form.task_id != 0) {
        this.$refs.editor.setHTML("");
        this.$refs.closeCanvas.click();
      }
      this.form.task_id = 0;
      this.form.name = null;
      this.form.description = null;
      this.form.status_id = null;
      this.form.project_id = null;
      this.form.start_date = null;
      this.form.due_date = null;
    },

    dragStart(item, index, e) {
      const taskIndex2 = this.tasks.findIndex((task) => task.id === item.id);
      this.tasks[taskIndex2].swapping = true;

      this.drag.start.index = index;
      this.drag.start.item = item;

      e.dataTransfer.effectAllowed = "move";
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
          this.showToast({ message: data.message, type: "success" });
        }
      } catch (e) {
        this.showToast({ message: data.message, type: "error" });
        errorHandler(e);
      }
    },

    liveUpdateDomStatusColor(ref) {
      const color = this.$refs[ref][0].value;
      const id = ref.split("_")[1];
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
    },

    resetErrors() {
      let vm = this;
      Object.keys(this.errors).forEach(function (index) {
        vm.errors[index] = null;
      });
    },

    copy(task) {
      const e = document.querySelector(`#description-${task.id}`);
      const r = document.createRange();
      const startIndex = 0;
      r.setStart(e, startIndex);
      r.setEnd(e, e.children.length);
      const s = window.getSelection();
      s.removeAllRanges();
      s.addRange(r);
      document.execCommand("copy");
      s.removeAllRanges();

      this.showToast({ message: "Text copied", type: "success" });
    },
  },
  mounted() {
    this.getTasks();
    // this.$refs.name.focus();
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

.group .task-list::after {
  content: "";
  opacity: 0;
  position: absolute;
  right: 0.15rem;
  bottom: -1.15rem;
  border-right: 0.175rem solid pink;
  border-bottom: 0.175rem solid pink;
  transition: all 300ms ease;
  height: 1.15rem;
  width: 7.5rem;
  border-bottom-right-radius: 0.5rem;
}

.group:hover .task-list::after {
  opacity: 1;
}

/* switch transition */

.switch-enter-from,
.switch-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

.switch-enter-to,
.switch-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.switch-enter-active {
  transition: all 0.5s ease;
}

.switch-leave-active {
  transition: all 0.5s ease;
}

/* end switch */
</style>
