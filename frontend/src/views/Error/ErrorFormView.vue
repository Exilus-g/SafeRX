<script setup>
import { onMounted, reactive, ref } from "vue";
import { RouterLink, useRoute } from "vue-router";

import { useErrorStore } from "@/stores/error";
import { storeToRefs } from "pinia";
const { errors } = storeToRefs(useErrorStore());
const { createError, clearErrors } = useErrorStore();

import { useProcessStore } from "@/stores/process";
const { getAllProcess } = useProcessStore();
const process = ref([]);

import { useErrorCategoryStore } from "@/stores/errorCategory";
const { getAllCategories } = useErrorCategoryStore();
const category = ref([]);

import { usePlaceStore } from "@/stores/place";
const { getAllPlaces } = usePlaceStore();
const place = ref([]);

import { useStaffInvolvedStore } from "@/stores/staffInvolved";
const { getAllStaff } = useStaffInvolvedStore();
const staff = ref([]);


import { useFactorStore } from "@/stores/factor";
const { getAllFactors } = useFactorStore();
const factor = ref([]);


import { useErrorTypeStore } from "@/stores/errorType";
const { getAllTypes } = useErrorTypeStore();
const type = ref([]);

// Combine all the onMounted calls into one
onMounted(async () => {
  process.value = await getAllProcess();
  category.value = await getAllCategories();
  place.value = await getAllPlaces();
  staff.value = await getAllStaff();
  factor.value = await getAllFactors();
  type.value = await getAllTypes();
});



const formData = reactive({
  error_date: "",
  report_date: "",
  patient_name: "",
  date_of_birth: "",
  area: "",
  folder: "",
  description: "",
  node_id: "",
  place_id: "",
  staff_involved_id: "",
  factor_id: "",
  error_category_id: "",
  error_type_id: [],
});
</script>

<template>
  <main>
    <div class="px-28">
      <form class="space-y-8 pt-8" @submit.prevent="createError(formData)">
        <h1
          class="text-3xl flex justify-center font-bold text-buttonTitle mb-4"
        >
          Registro de Error de Medicación
        </h1>

        <!-- Section 1: Basic Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label
              for="error_date"
              class="block text-sm font-medium text-gray-700"
              >Fecha del error</label
            >
            <input
              id="error_date"
              v-model="formData.error_date"
              type="date"
              name="error_date"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.error_date" class="text-sm text-red-600">
              {{ errors.error_date[0] }}
            </p>
          </div>
          <div>
            <label
              for="report_date"
              class="block text-sm font-medium text-gray-700"
              >Fecha del reporte</label
            >
            <input
              type="date"
              id="report_date"
              v-model="formData.report_date"
              name="report_date"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.report_date" class="text-sm text-red-600">
              {{ errors.report_date[0] }}
            </p>
          </div>
          <div>
            <label
              for="patient_name"
              class="block text-sm font-medium text-gray-700"
              >Nombre del paciente</label
            >
            <input
              type="text"
              id="patient_name"
              v-model="formData.patient_name"
              name="patient_name"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.patient_name" class="text-sm text-red-600">
              {{ errors.patient_name[0] }}
            </p>
          </div>
          <div>
            <label
              for="date_of_birth"
              class="block text-sm font-medium text-gray-700"
              >Fecha de nacimiento</label
            >
            <input
              type="date"
              id="date_of_birth"
              v-model="formData.date_of_birth"
              name="date_of_birth"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.date_of_birth" class="text-sm text-red-600">
              {{ errors.date_of_birth[0] }}
            </p>
          </div>
        </div>

        <!-- Section 2: Error Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="area" class="block text-sm font-medium text-gray-700"
              >Área que detectó el error</label
            >
            <input
              type="text"
              id="area"
              v-model="formData.area"
              name="area"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.area" class="text-sm text-red-600">
              {{ errors.area[0] }}
            </p>
          </div>
          <div>
            <label for="folder" class="block text-sm font-medium text-gray-700"
              >Expediente</label
            >
            <input
              type="text"
              id="folder"
              v-model="formData.folder"
              name="folder"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            />
            <p v-if="errors.folder" class="text-sm text-red-600">
              {{ errors.folder[0] }}
            </p>
          </div>
        </div>
        <div>
          <label
            for="description"
            class="block text-sm font-medium text-gray-700"
            >Descripción del error</label
          >
          <textarea
            id="description"
            v-model="formData.description"
            name="description"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          ></textarea>
          <p v-if="errors.description" class="text-sm text-red-600">
            {{ errors.description[0] }}
          </p>
        </div>

        <!-- Section 3: Error Classification (Radio Buttons) -->
        <fieldset>
          <div>
            <label
              for="clasificacion-error"
              class="block text-sm font-medium text-gray-700"
              >Tipo de error</label
            >
            <div class="grid grid-cols-3 gap-4 mt-2">
              <div
                v-for="type in type"
                :key="type.id"
                class="flex items-center"
              >
                <input
                  type="checkbox"
                  :id="type.id"
                  :value="type.id"
                  v-model="formData.error_type_id"
                  class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                />
                <label
                  :for="type.id"
                  class="ml-2 text-sm font-medium text-gray-700"
                >
                  {{ type.type_name }}
                </label>
                
              </div>
              <p v-if="errors.error_type_id" class="text-sm text-red-600">
                  {{ errors.error_type_id[0] }}
                </p>
            </div>
          </div>
        </fieldset>

        <!-- Section 4: Select Fields -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label
              for="etapa-sistema"
              class="block text-sm font-medium text-gray-700"
              >Etapa del sistema en la que ocurrió el error</label
            >
            <select
              id="etapa-sistema"
              v-model="formData.node_id"
              name="etapa_sistema"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
              <option value="">Selecciona una opción</option>
              <option
                v-for="process in process"
                :key="process.id"
                :value="process.id"
              >
                {{ process.process }}
              </option>
            </select>
            <p v-if="errors.node_id" class="text-sm text-red-600">
                  {{ errors.node_id[0] }}
                </p>
          </div>
          <div>
            <label
              for="lugar-error"
              class="block text-sm font-medium text-gray-700"
              >Lugar donde ocurrió el error</label
            >
            <select
              id="place_id"
              v-model="formData.place_id"
              name="place_id"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
              <option value="">Selecciona una opción</option>
              <option v-for="place in place" :key="place.id" :value="place.id">
                {{ place.place_name }}
              </option>
            </select>
            <p v-if="errors.place_id" class="text-sm text-red-600">
                  {{ errors.place_id[0] }}
                </p>
          </div>
          <div>
            <label
              for="personal-involucrado"
              class="block text-sm font-medium text-gray-700"
              >Personal involucrado</label
            >
            <select
              id="personal-involucrado"
              v-model="formData.staff_involved_id"
              name="personal_involucrado"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
              <option value="">Selecciona una opción</option>
              <option v-for="staff in staff" :key="staff.id" :value="staff.id">
                {{ staff.staff_name }}
              </option>
            </select>
            <p v-if="errors.staff_involved_id" class="text-sm text-red-600">
                  {{ errors.staff_involved_id[0] }}
                </p>
          </div>
          <div>
            <label
              for="factores"
              class="block text-sm font-medium text-gray-700"
              >Factores contribuyentes</label
            >
            <select
              id="factores"
              v-model="formData.factor_id"
              name="factores"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
              <option value="">Selecciona una opción</option>
              <option
                v-for="factor in factor"
                :key="factor.id"
                :value="factor.id"
              >
                {{ factor.factor_name }}
              </option>
            </select>
            <p v-if="errors.factor_id" class="text-sm text-red-600">
                  {{ errors.factor_id[0] }}
                </p>
          </div>
        </div>
        <div>
          <div class="mx-auto">
            <label
              for="clasificacion-error"
              class="block text-sm font-medium text-gray-700"
              >Clasificación del error</label
            >
            <select
              id="clasificacion-error"
              v-model="formData.error_category_id"
              name="clasificacion_error"
              class="mt-1 w-full rounded-md border-gray-300 shadow-sm block"
            >
              <option value="">Selecciona una opción</option>
              <option
                v-for="category in category"
                :key="category.id"
                :value="category.id"
              >
                {{ category.category_name + " - " + category.description }}
              </option>
            </select>
            <p v-if="errors.error_category_id" class="text-sm text-red-600">
                  {{ errors.error_category_id[0] }}
                </p>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-x-2 py-5">
          <RouterLink :to="{ name: 'errors' }" class="flex-1">
            <button
              @click.native="clearErrors"
              class="w-full justify-center rounded-md bg-red-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-mainBlack focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              Cancelar
            </button>
          </RouterLink>
          <button
            type="submit"
            class="w-full flex-1 justify-center rounded-md bg-buttonTitle px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-mainBlack focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Registrar
          </button>
        </div>
      </form>
    </div>
  </main>
</template>
