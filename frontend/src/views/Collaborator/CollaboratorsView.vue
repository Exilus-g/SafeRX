<script setup>
import { storeToRefs } from "pinia";
import { RouterLink, useRoute } from "vue-router";
import { onMounted, ref } from "vue";

import { useCollaboratorStore } from "@/stores/collaborator";
import ConfirmationModal from "@/components/ConfirmationModal.vue";
const { successMessage,infoMessage } = storeToRefs(useCollaboratorStore());
const { getAllCollaborators, deleteCollaborator, clearMessage } =
  useCollaboratorStore();
const collaborator = ref([]);
const showModal = ref(false);
const selectedCollaborator = ref(null);

onMounted(async () => {
  collaborator.value = await getAllCollaborators();
  setTimeout(() => {
    clearMessage();
  }, 5000);
});
const confirmDelete = async () => {
  if (selectedCollaborator.value) {
    await deleteCollaborator(selectedCollaborator.value); // Llamada al store
    collaborator.value = await getAllCollaborators(); // Actualizar la lista
    selectedCollaborator.value = null;
    showModal.value = false;
  }
};

const openDeleteModal = (collaborator) => {
  selectedCollaborator.value = collaborator;
  showModal.value = true;
};
</script>

<template>
  <div class="grid grid-cols-1 gap-6 px-40 py-10">
    <div v-if="successMessage" class="text-green-600 p-4 bg-green-100 border-l-4 border-green-600">
      {{ successMessage }}
    </div>
    <div v-if="infoMessage" class="text-blue-600 p-4 bg-blue-100 border-l-4 border-blue-600">
      {{ infoMessage }}
    </div>
    <div class="relative overflow-x-auto border rounded-lg">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption
          class="p-5 text-lg font-semibold text-left rtl:text-right text-buttonTitle bg-background"
        >
          Colaboradores
          <RouterLink :to="{ name: 'createcollaborator' }">
            <button
              class="bg-buttonTitle text-lg text-white rounded-lg w-20 h-10 justify-center float-right hover:bg-mainBlack"
            >
              Agregar
            </button>
          </RouterLink>
        </caption>
        <thead
          class="text-xs uppercase border-b border text-mainBlack bg-gray-7000"
        >
          <tr>
            <th scope="col" class="px-6 py-3">Nombre del Colaborador</th>
            <th scope="col" class="px-6 py-3">Correo Electrónico</th>
            <th scope="col" class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody v-if="collaborator.length > 0">
          <tr
            class="bg-background border-b"
            v-for="collaborator in collaborator"
            :key="collaborator.id"
          >
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
            >
              {{ collaborator.name }}
            </th>
            <th
              scope="row"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
            >
              {{ collaborator.email }}
            </th>
            <th class="flex items-center space-x-2">
              <RouterLink
                :to="{
                  name: 'updatecollaborator',
                  params: { id: collaborator.id },
                }"
              >
                <span class="icon-[tabler--edit] w-9 h-9 text-mainBlack"></span>
              </RouterLink>
                <button
                @click.prevent="openDeleteModal(collaborator)"
                class="icon-[tabler--trash-off] w-9 h-9 text-red-700"
              ></button>
            </th>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="3" class="text-center py-6">
              <p>No se encontraron errores para mostrar</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <ConfirmationModal
      :visible="showModal"
      message="¿Estás seguro de que deseas eliminar este colaborador?"
      confirmButtonText="Sí, eliminar"
      cancelButtonText="Cancelar"
      @confirm="confirmDelete"
      @close="showModal = false"
    />
  </div>
</template>
