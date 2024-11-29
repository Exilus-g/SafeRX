<script setup>
import { reactive } from "vue";
import { RouterLink, useRoute } from "vue-router";

import { useErrorTypeStore } from "@/stores/errorType";
import { storeToRefs } from "pinia";

const { errors } = storeToRefs(useErrorTypeStore());
const { createType, clearErrors } = useErrorTypeStore();

const formData = reactive({
  type_name: "",
});
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
        Crea Un Nuevo Registro
      </h1>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="createType(formData)" class="space-y-6">
        <div>
          <label
            for="type_name"
            class="block text-sm/6 font-medium text-gray-900"
            >Nombre del Tipo</label
          >
          <div class="mt-2">
            <input
              id="type_name"
              v-model="formData.type_name"
              name="type_name"
              type="type_name"
              autocomplete="type_name"
              class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
            />
            <p v-if="errors.type_name" class="text-sm text-red-600">
              {{ errors.type_name[0] }}
            </p>
          </div>
        </div>

        <div class="flex gap-x-2">
          <RouterLink :to="{ name: 'fieldsettings' }" class="flex-1">
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
  </div>
</template>
