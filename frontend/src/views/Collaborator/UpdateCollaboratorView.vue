<script setup>
import { onMounted,reactive,ref } from "vue";
import { useCollaboratorStore } from "@/stores/collaborator";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";


const { errors } = storeToRefs(useCollaboratorStore());

const route = useRoute();
const { getCollaborator, updateCollaborator, clearErrors } = useCollaboratorStore();
const collaborator = ref(null);

const formData = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});


onMounted(async () => {
    collaborator.value = await getCollaborator(route.params.id);
    formData.name = collaborator.value.name;
    formData.email = collaborator.value.email;
    formData.password = collaborator.value.password;
    formData.password_confirmation = collaborator.value.password;
    console.log(collaborator.value);
})
</script>

<template>
  <div
    class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8"
  >
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img
        class="mx-auto h-24 w-auto"
        src="/assets/LogoSafeRX.svg"
        alt="SafeRX"
      />
      <h1
        class="title mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900"
      >
        Regístrarse
      </h1>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form
        @submit.prevent="updateCollaborator(collaborator, formData)"
        class="space-y-6"
      >
        <div>
          <label for="name" class="block text-sm/6 font-medium text-gray-900"
            >Nombre</label
          >
          <div class="mt-2">
            <input
              id="name"
              v-model="formData.name"
              name="name"
              type="text"
              autocomplete="username"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
            />
            <p v-if="errors.name" class="text-sm text-red-600">
              {{ errors.name[0] }}
            </p>
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900"
            >Dirección de correo electrónico</label
          >
          <div class="mt-2">
            <input
              id="email"
              v-model="formData.email"
              name="email"
              type="email"
              autocomplete="email"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
            />
            <p v-if="errors.email" class="text-sm text-red-600">
              {{ errors.email[0] }}
            </p>
          </div>
        </div>

        <div>
          <label
            for="password"
            class="block text-sm/6 font-medium text-gray-900"
            >Crea una nueva contraseña</label
          >
          <div class="mt-2">
            <input
              id="password"
              v-model="formData.password"
              name="password"
              type="password"
              autocomplete="new-password"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
            />
            <p v-if="errors.password" class="text-sm text-red-600">
              {{ errors.password[0] }}
            </p>
          </div>
        </div>

        <div>
          <label
            for="password_confirmation"
            class="block text-sm/6 font-medium text-gray-900"
            >Confirma la contraseña</label
          >
          <div class="mt-2">
            <input
              id="password_confirmation"
              v-model="formData.password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
            />
          </div>
        </div>

        <div class="flex gap-x-2">
          <RouterLink :to="{ name: 'collaboratorlist' }" class="flex-1">
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
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Puedes agregar estilos adicionales aquí */
</style>
