<script setup>
import { storeToRefs } from "pinia";

import { RouterLink, useRoute } from "vue-router";
import { onMounted, ref, computed } from "vue";
import { initFlowbite } from "flowbite";
import * as XLSX from "xlsx";

// initialize components based on data attribute selectors

import { useAuthStore } from "@/stores/auth";
const authStore = useAuthStore();

import { useErrorStore } from "@/stores/error";
const { getAllErrors, deleteError, clearMessage } = useErrorStore();
const error = ref([]);
const { successMessage,infoMessage } = storeToRefs(useErrorStore());


import { useProcessStore } from "@/stores/process";
const { getAllProcess } = useProcessStore();
const process = ref([]);


import { useErrorCategoryStore } from "@/stores/errorCategory";
const { getAllCategories } = useErrorCategoryStore();
const category = ref([]);



// Filtros
const filter = ref({
  date_range: { start: null, end: null },
  selected_processes: [],
  selected_categories: [],
});


// Paginación
const currentPage = ref(1);
const itemsPerPage = 20;

const filteredErrors = computed(() => {
  const filtered = error.value.filter((err) => {
    const matchCategoryFilter =
      filter.value.selected_categories.length === 0 ||
      filter.value.selected_categories.includes(
        err.error_category?.category_name
      );

    const matchDateRange =
      (!filter.value.date_range.start ||
        new Date(err.error_date) >= new Date(filter.value.date_range.start)) &&
      (!filter.value.date_range.end ||
        new Date(err.error_date) <= new Date(filter.value.date_range.end));

    const matchProcess =
      filter.value.selected_processes.length === 0 ||
      filter.value.selected_processes.includes(err.node?.process);

    return matchCategoryFilter && matchDateRange && matchProcess;
  });

  // Obtener elementos de la página actual
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(
    error.value.filter((err) => {
      const matchCategoryFilter =
        filter.value.selected_categories.length === 0 ||
        filter.value.selected_categories.includes(
          err.error_category?.category_name
        );

      const matchDateRange =
        (!filter.value.date_range.start ||
          new Date(err.error_date) >=
            new Date(filter.value.date_range.start)) &&
        (!filter.value.date_range.end ||
          new Date(err.error_date) <= new Date(filter.value.date_range.end));

      const matchProcess =
        filter.value.selected_processes.length === 0 ||
        filter.value.selected_processes.includes(err.node?.process);

      return matchCategoryFilter && matchDateRange && matchProcess;
    }).length / itemsPerPage
  );
});

const exportToExcel = () => {
  const dataToExport = filteredErrors.value.map((err) => {
    return {
      Fecha: err.error_date,
      "Fecha de reporte": err.report_date,
      Paciente: err.patient_name,
      "Fecha de nacimiento": err.date_of_birth,
      "Área que detectó el error": err.area,
      Expediente: err.folder,
      Descripción: err.description,
      "Tipo de error": err.error_types.map((type) => type.type_name).join(", "),
      "Etapa de ocurrencia": err.node?.process || "No definido",
      "Lugar del error": err.place?.place_name || "No definido",
      "Personal involucrado": err.staff_involved?.staff_name || "No definido",
      "Factores contribuyentes": err.factor?.factor_name || "No definido",
      Clasificación: err.error_category?.category_name || "No definido",
    };
  });

  const ws = XLSX.utils.json_to_sheet(dataToExport);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Errores");
  XLSX.writeFile(wb, "errores_de_medicion.xlsx");
};
const showModal = ref(false);
const selectedError = ref(null);

import ConfirmationModal from "@/components/ConfirmationModal.vue";
const confirmDelete = async () => {
  if (selectedError.value) {
    await deleteError(selectedError.value); // Llamada al store
    error.value = await getAllErrors(); // Actualizar la lista
    selectedError.value = null;
    showModal.value = false;
  }
};

const openDeleteModal = (error) => {
  selectedError.value = error;
  showModal.value = true;
};
onMounted(async () => {
  initFlowbite();

  // Cargar datos relacionados
  process.value = await getAllProcess();
  category.value = await getAllCategories();
  error.value = await getAllErrors();


  setTimeout(() => {
    clearMessage();
  }, 5000);
});
</script>

<template>
  <div class="space-y-4">
    <div v-if="successMessage" class="text-green-600 p-4 bg-green-100 border-l-4 border-green-600">
      {{ successMessage }}
    </div>
    <div v-if="infoMessage" class="text-blue-600 p-4 bg-blue-100 border-l-4 border-blue-600">
      {{ infoMessage }}
    </div>
    <div class="pt-10 px-10">
      <h1 class="text-buttonTitle text-xl font-semibold">Filtros</h1>
      <!-- Grid de 4 columnas -->
      <div class="grid grid-cols-4 gap-4">
        <!-- Filtro por Categoría -->
        <div class="flex flex-col py-6">
          <button
            id="dropdownCheckboxButton1"
            data-dropdown-toggle="dropdownDefaultCheckbox1"
            class="text-white bg-buttonTitle hover:bg-mainBlack focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center w-auto"
            type="button"
          >
            Clasificación del error
            <svg
              class="w-2.5 h-2.5 ms-3"
              aria-hidden="true"
              fill="none"
              viewBox="0 0 10 6"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 4 4 4-4"
              />
            </svg>
          </button>

          <div
            id="dropdownDefaultCheckbox1"
            class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
          >
            <ul
              class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200"
              aria-labelledby="dropdownCheckboxButton1"
            >
              <li
                v-for="category in category"
                :key="category.id"
                class="flex items-center"
              >
                <input
                  type="checkbox"
                  :value="category.category_name"
                  v-model="filter.selected_categories"
                  class="mr-2"
                />
                <span>{{ category.category_name }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Filtro por Proceso -->
        <div class="flex flex-col py-6">
          <button
            id="dropdownCheckboxButton3"
            data-dropdown-toggle="dropdownDefaultCheckbox3"
            class="text-white bg-buttonTitle hover:bg-mainBlack focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center w-auto"
            type="button"
          >
            Proceso
            <svg
              class="w-2.5 h-2.5 ms-3"
              aria-hidden="true"
              fill="none"
              viewBox="0 0 10 6"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m1 1 4 4 4-4"
              />
            </svg>
          </button>

          <div
            id="dropdownDefaultCheckbox3"
            class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
          >
            <ul
              class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200"
              aria-labelledby="dropdownCheckboxButton3"
            >
              <li
                v-for="process in process"
                :key="process.id"
                class="flex items-center"
              >
                <input
                  type="checkbox"
                  :value="process.process"
                  v-model="filter.selected_processes"
                  class="mr-2"
                />
                <span>{{ process.process }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Filtro por Fecha de Inicio -->
        <div class="flex flex-col px-5">
          <label
            for="start_date"
            class="block text-sm font-medium text-gray-700"
            >Fecha de inicio</label
          >
          <input
            id="start_date"
            v-model="filter.date_range.start"
            type="date"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <!-- Filtro por Fecha de Fin -->
        <div class="flex flex-col px-5">
          <label for="end_date" class="block text-sm font-medium text-gray-700"
            >Fecha de fin</label
          >
          <input
            id="end_date"
            v-model="filter.date_range.end"
            type="date"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>
      </div>
    </div>

    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption
          class="p-5 text-lg font-semibold text-left rtl:text-right text-buttonTitle bg-background"
        >
          Errores de Medicación
          <div class="float-right flex gap-2">
            <RouterLink
              :to="{ name: 'errorform' }"
            >
              <button
                class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 hover:bg-mainBlack"
              >
                Registrar
              </button>
            </RouterLink>
            <button
              @click="exportToExcel"
              class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 hover:bg-mainBlack"
            >
              Exportar
            </button>
          </div>
        </caption>

        <thead
          class="text-[10px] uppercase border-b border text-mainBlack bg-gray-7000"
        >
          <tr>
            <th scope="col" class="px-6 py-3">Fecha</th>
            <th scope="col" class="px-6 py-3">Fecha de reporte</th>
            <th scope="col" class="px-6 py-3">Paciente</th>
            <th scope="col" class="px-6 py-3">Fecha de nacimiento</th>
            <th scope="col" class="px-6 py-3">Área que detectó el error</th>
            <th scope="col" class="px-6 py-3">Expediente</th>
            <th scope="col" class="px-6 py-3">Descripción</th>
            <th scope="col" class="px-6 py-3">Tipo de error</th>
            <th scope="col" class="px-6 py-3">Etapa de ocurrencia</th>
            <th scope="col" class="px-6 py-3">Lugar del error</th>
            <th scope="col" class="px-6 py-3">Personal involucrado</th>
            <th scope="col" class="px-6 py-3">Factores contribuyentes</th>
            <th scope="col" class="px-6 py-3">Clasificación</th>
            <th scope="col" class="px-6 py-3">Acciones</th>
          </tr>
        </thead>

        <tbody class="text-[13px]" v-if="filteredErrors.length > 0">
          <tr
            class="bg-background border-b"
            v-for="error in filteredErrors"
            :key="error.id"
          >
            <!-- Celdas de la tabla -->
            <th>{{ error.error_date }}</th>
            <th>{{ error.report_date }}</th>
            <th>{{ error.patient_name }}</th>
            <th>{{ error.date_of_birth }}</th>
            <th>{{ error.area }}</th>
            <th>{{ error.folder }}</th>
            <th>{{ error.description }}</th>
            <th>
              <ul>
                <li v-for="type in error.error_types" :key="type.id">
                  {{ type.type_name }}
                </li>
              </ul>
            </th>
            <th>{{ error.node?.process || "No definido" }}</th>
            <th>{{ error.place?.place_name || "No definido" }}</th>
            <th>{{ error.staff_involved?.staff_name || "No definido" }}</th>
            <th>{{ error.factor?.factor_name || "No definido" }}</th>
            <th>{{ error.error_category?.category_name || "No definido" }}</th>
            <th class="flex items-center space-x-2">
              <RouterLink
                :to="{ name: 'updateerror', params: { id: error.id } }"
              >
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
              <button v-if="authStore.roles[0] === 'admin'"
                @click.prevent="openDeleteModal(error)"
                class="icon-[tabler--trash-off] w-9 h-9 text-red-700"
              ></button>
            </th>
          </tr>
        </tbody>
        <tbody v-if="filteredErrors.length === 0">
          <tr>
            <td colspan="14" class="text-center py-6">
              <p>No se encontraron errores para mostrar</p>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginación -->
      <div class="flex justify-center mt-4">
        <button
          @click="currentPage > 1 && currentPage--"
          :disabled="currentPage === 1"
          class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400"
        >
          Anterior
        </button>
        <button
          v-for="page in totalPages"
          :key="page"
          @click="currentPage = page"
          :class="{
            'bg-buttonTitle text-white': currentPage === page,
            'bg-gray-300': currentPage !== page,
          }"
          class="px-4 py-2 mx-1 rounded hover:bg-gray-400"
        >
          {{ page }}
        </button>
        <button
          @click="currentPage < totalPages && currentPage++"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400"
        >
          Siguiente
        </button>
      </div>
      <ConfirmationModal
      :visible="showModal"
      message="¿Estás seguro de que deseas eliminar este error?"
      confirmButtonText="Sí, eliminar"
      cancelButtonText="Cancelar"
      @confirm="confirmDelete"
      @close="showModal = false"
    />
    </div>
  </div>
</template>
