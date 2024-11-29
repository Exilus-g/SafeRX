<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";
import { useAuthStore } from "./stores/auth";

const authStore = useAuthStore();

const dropdownOpen = ref(false);
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};
</script>

<template>
  <div>
    <header>
      <nav class="bg-gcolor text-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
          <div class="relative flex h-16 items-center justify-between">
            <div class="flex items-center">
              <img
                class="h-14 w-auto"
                src="/assets/LogoSafeRX.svg"
                alt="SafeRx"
              />
            </div>
            <div class="hidden sm:block">
              <div class="flex space-x-4">
                <RouterLink :to="{ name: 'home' }" class="nav-link">
                  Inicio
                </RouterLink>

                <div
                  v-if="authStore.user"
                  class="flex space-x-4 items-center relative"
                >
                  <!-- Links adicionales -->
                  <RouterLink :to="{ name: 'diagram' }" class="nav-link" v-if="authStore.roles[0] ==='admin'">
                    Diagrama
                  </RouterLink>
                  <RouterLink :to="{ name: 'errors' }" class="nav-link">
                    Registro de Errores
                  </RouterLink>
                  <RouterLink :to="{ name: 'fieldsettings' }" class="nav-link">
                    Ajustes de Campos
                  </RouterLink>

                  <!-- Dropdown menú -->
                  <div
                    @click="toggleDropdown"
                    class="rounded-lg bg-buttonTitle cursor-pointer relative"
                  >
                    <p
                      class="rounded-md px-3 py-2 text-base font-medium flex items-center"
                    >
                      Bienvenid@ {{ authStore.user.name }}
                      <span class="ml-2">&#9662;</span>
                    </p>

                    <div
                      v-show="dropdownOpen"
                      class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-10"
                    >
                      <ul class="py-1">
                        <li>
                          <RouterLink v-if="authStore.roles[0] ==='admin'"
                            :to="{ name: 'collaboratorlist' }"
                          >
                            <button
                              class="w-full text-left px-4 py-2 text-sm hover:bg-background"
                            >
                              Colaboradores
                            </button>
                          </RouterLink>
                        </li>
                        <li>
                          <button
                            @click="authStore.logout()"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-background"
                          >
                            Cerrar sesión
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div v-else class="flex space-x-4">
                  <RouterLink :to="{ name: 'register' }" class="nav-link">
                    Registrarse
                  </RouterLink>
                  <RouterLink :to="{ name: 'login' }" class="nav-link">
                    Iniciar sesión
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <RouterView />
  </div>
</template>

<style>
.nav-link {
  @apply hover:bg-mainBlack hover:text-white rounded-md px-3 py-2 text-base font-medium;
}
</style>
