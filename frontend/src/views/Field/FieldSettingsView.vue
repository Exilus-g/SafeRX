<script setup>
import {  onMounted, ref, computed } from "vue";
import { RouterLink, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { usePlaceStore } from "@/stores/place";
import { useStaffInvolvedStore } from "@/stores/staffInvolved";
import { useFactorStore } from "@/stores/factor";
import { useErrorTypeStore } from "@/stores/errorType";

const authStore = useAuthStore();

// Datos y referencias
const { getAllPlaces, deletePlace } = usePlaceStore();
const { getAllStaff, deleteStaff } = useStaffInvolvedStore();
const { getAllFactors, deleteFactor } = useFactorStore();
const { getAllTypes, deleteType } = useErrorTypeStore();

const place = ref([]);
const staff = ref([]);
const factor = ref([]);
const type = ref([]);

onMounted(async () => {
  place.value = await getAllPlaces();
  staff.value = await getAllStaff();
  factor.value = await getAllFactors();
  type.value = await getAllTypes();
});

// Configuración de la paginación
const itemsPerPage = 5;

// Lugares
const currentPagePlaces = ref(1);
const paginatedPlaces = computed(() => {
  const start = (currentPagePlaces.value - 1) * itemsPerPage;
  return place.value.slice(start, start + itemsPerPage);
});
const totalPagesPlaces = computed(() => Math.ceil(place.value.length / itemsPerPage));

// Personal involucrado
const currentPageStaff = ref(1);
const paginatedStaff = computed(() => {
  const start = (currentPageStaff.value - 1) * itemsPerPage;
  return staff.value.slice(start, start + itemsPerPage);
});
const totalPagesStaff = computed(() => Math.ceil(staff.value.length / itemsPerPage));

// Factores
const currentPageFactors = ref(1);
const paginatedFactors = computed(() => {
  const start = (currentPageFactors.value - 1) * itemsPerPage;
  return factor.value.slice(start, start + itemsPerPage);
});
const totalPagesFactors = computed(() => Math.ceil(factor.value.length / itemsPerPage));

// Tipos de errores
const currentPageTypes = ref(1);
const paginatedTypes = computed(() => {
  const start = (currentPageTypes.value - 1) * itemsPerPage;
  return type.value.slice(start, start + itemsPerPage);
});
const totalPagesTypes = computed(() => Math.ceil(type.value.length / itemsPerPage));
</script>

<template>
  <div class="grid grid-cols-2 gap-6 px-40 py-10">
    <!-- Lugares -->
    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-buttonTitle bg-background">
          Lugares
          <RouterLink :to="{ name: 'createplace' }" v-if="authStore.roles[0] === 'admin'">
            <button class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 float-right hover:bg-mainBlack">Agregar</button>
          </RouterLink>
        </caption>
        <thead class="text-xs uppercase border-b text-mainBlack bg-gray-7000">
          <tr>
            <th class="px-6 py-3">Nombre del Lugar</th>
            <th class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody v-if="paginatedPlaces.length > 0">
          <tr v-for="place in paginatedPlaces" :key="place.id" class="bg-background border-b">
            <td class="px-6 py-4 font-medium text-gray-900">{{ place.place_name }}</td>
            <td v-if="authStore.roles[0] === 'admin'" class="flex items-center space-x-2">
              <RouterLink :to="{ name: 'updateplace', params: { id: place.id } }">
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
              <button @click.prevent="deletePlace(place)" class="icon-[tabler--trash-off] w-9 h-9 text-red-700"></button>
            </td>
            <td v-else class="flex items-center space-x-2">
              No tienes permisos para realizar acciones
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="2" class="text-center py-6">No se encontraron datos para mostrar</td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-end p-4">
        <button @click="currentPagePlaces--" :disabled="currentPagePlaces === 1" class="px-4 py-2 mx-1 bg-gray-200 rounded">Anterior</button>
        <button @click="currentPagePlaces++" :disabled="currentPagePlaces === totalPagesPlaces" class="px-4 py-2 mx-1 bg-gray-200 rounded">Siguiente</button>
      </div>
    </div>
    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-buttonTitle bg-background">
          Personal Involucrado
          <RouterLink :to="{ name: 'createstaff' }" v-if="authStore.roles[0] === 'admin'">
            <button class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 float-right hover:bg-mainBlack">Agregar</button>
          </RouterLink>
        </caption>
        <thead class="text-xs uppercase border-b text-mainBlack bg-gray-7000">
          <tr>
            <th class="px-6 py-3">Personal</th>
            <th class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody v-if="paginatedStaff.length > 0">
          <tr v-for="staff in paginatedStaff" :key="staff.id" class="bg-background border-b">
            <td class="px-6 py-4 font-medium text-gray-900">{{ staff.staff_name }}</td>
            <td v-if="authStore.roles[0] === 'admin'" class="flex items-center space-x-2">
              <RouterLink :to="{ name: 'updatestaff', params: { id: staff.id } }">
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
              <button @click.prevent="deletePlace(staff)" class="icon-[tabler--trash-off] w-9 h-9 text-red-700"></button>
            </td>
            <td v-else class="flex items-center space-x-2">
              No tienes permisos para realizar acciones
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="2" class="text-center py-6">No se encontraron datos para mostrar</td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-end p-4">
        <button @click="currentPageStaff--" :disabled="currentPageStaff === 1" class="px-4 py-2 mx-1 bg-gray-200 rounded">Anterior</button>
        <button @click="currentPageStaff++" :disabled="currentPageStaff === totalPagesStaff" class="px-4 py-2 mx-1 bg-gray-200 rounded">Siguiente</button>
      </div>
    </div>
    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-buttonTitle bg-background">
          Factores Involucrados
          <RouterLink :to="{ name: 'createfactor' }" v-if="authStore.roles[0] === 'admin'">
            <button class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 float-right hover:bg-mainBlack">Agregar</button>
          </RouterLink>
        </caption>
        <thead class="text-xs uppercase border-b text-mainBlack bg-gray-7000">
          <tr>
            <th class="px-6 py-3">Nombre del Factor</th>
            <th class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody v-if="paginatedFactors.length > 0">
          <tr v-for="factor in paginatedFactors" :key="factor.id" class="bg-background border-b">
            <td class="px-6 py-4 font-medium text-gray-900">{{ factor.factor_name }}</td>
            <td v-if="authStore.roles[0] === 'admin'" class="flex items-center space-x-2">
              <RouterLink :to="{ name: 'updatefactor', params: { id: factor.id } }">
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
              <button @click.prevent="deletePlace(factor)" class="icon-[tabler--trash-off] w-9 h-9 text-red-700"></button>
            </td>
            <td v-else class="flex items-center space-x-2">
              No tienes permisos para realizar acciones
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="2" class="text-center py-6">No se encontraron datos para mostrar</td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-end p-4">
        <button @click="currentPageFactors--" :disabled="currentPageFactors === 1" class="px-4 py-2 mx-1 bg-gray-200 rounded">Anterior</button>
        <button @click="currentPageFactors++" :disabled="currentPageFactors === totalPagesFactors" class="px-4 py-2 mx-1 bg-gray-200 rounded">Siguiente</button>
      </div>
    </div>
    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-buttonTitle bg-background">
          Tipos de Errores
          <RouterLink :to="{ name: 'createtype' }" v-if="authStore.roles[0] === 'admin'">
            <button class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 float-right hover:bg-mainBlack">Agregar</button>
          </RouterLink>
        </caption>
        <thead class="text-xs uppercase border-b text-mainBlack bg-gray-7000">
          <tr>
            <th class="px-6 py-3">Tipo de Error</th>
            <th class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody v-if="paginatedTypes.length > 0">
          <tr v-for="type in paginatedTypes" :key="type.id" class="bg-background border-b">
            <td class="px-6 py-4 font-medium text-gray-900">{{ type.type_name }}</td>
            <td v-if="authStore.roles[0] === 'admin'" class="flex items-center space-x-2">
              <RouterLink :to="{ name: 'updatetype', params: { id: type.id } }">
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
              <button @click.prevent="deletePlace(type)" class="icon-[tabler--trash-off] w-9 h-9 text-red-700"></button>
            </td>
            <td v-else class="flex items-center space-x-2">
              No tienes permisos para realizar acciones
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="2" class="text-center py-6">No se encontraron datos para mostrar</td>
          </tr>
        </tbody>
      </table>
      <div class="flex justify-end p-4">
        <button @click="currentPageTypes--" :disabled="currentPageTypes === 1" class="px-4 py-2 mx-1 bg-gray-200 rounded">Anterior</button>
        <button @click="currentPageTypes++" :disabled="currentPageTypes === totalPagesTypes" class="px-4 py-2 mx-1 bg-gray-200 rounded">Siguiente</button>
      </div>
    </div>

  </div>
</template>
