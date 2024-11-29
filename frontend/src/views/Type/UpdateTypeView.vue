<script setup>
import { onMounted, reactive, ref } from "vue";
import { useErrorTypeStore } from "@/stores/errorType";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";

const { errors } = storeToRefs(useErrorTypeStore());

const route = useRoute();
const { getType, updateType, clearErrors } = useErrorTypeStore();
const type = ref(null);

const formData = reactive({
  type_name: "",
});

onMounted(async () => {
  type.value = await getType(route.params.id);

  formData.type_name = type.value.type_name;
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
        Editar Registro
      </h1>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="updateType(type, formData)" class="space-y-6">
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
              class="block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
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
            Editar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
