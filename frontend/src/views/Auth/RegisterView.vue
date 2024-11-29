<script setup>
import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";
import { onMounted, reactive } from "vue";

// Referencia al store y a los errores almacenados
const { errors } = storeToRefs(useAuthStore());
const { authenticate } = useAuthStore();

const formData = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});
onMounted(() => (errors.value = {}));
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
        @submit.prevent="authenticate('register', formData)"
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
            >Crea una contraseña</label
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

        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-buttonTitle px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Regístrate
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Puedes agregar estilos adicionales aquí */
</style>
