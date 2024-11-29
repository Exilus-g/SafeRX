<script setup>
import BenefitsComponent from "../components/BenefitsComponent.vue";
</script>

<template>
  <div>
    <main
      class="flex min-h-screen items-center justify-center px-8 py-16 bg-gray-100"
    >
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl">
        <!-- Columna izquierda -->
        <div class="flex flex-col justify-center space-y-6">
          <h1 class="text-4xl font-bold text-mainBlack leading-tight">
            Asegura la Seguridad en <br />
            la Administración de <br />
            Medicamentos
          </h1>
          <p class="text-text">
            Gestiona, monitorea y previene errores de medicación en tiempo real
            con nuestra plataforma diseñada para mejorar la seguridad del
            paciente. Optimiza los procesos clínicos, reduce los riesgos, y
            contribuye a un entorno hospitalario más seguro y eficiente.
          </p>
          <RouterLink :to="{ name: 'register' }" >
            <button
              class="mt-4 px-6 py-3 bg-buttonTitle text-white font-semibold rounded-md hover:bg-mainBlack transition duration-300"
            >
              Empieza ahora
            </button>
          </RouterLink>
        </div>

        <!-- Columna derecha -->
        <div class="flex items-center justify-center">
          <img
            src="/image/doctor.svg"
            alt="Doctor"
            class="w-full h-auto object-cover max-w-md rounded-lg"
          />
        </div>
      </div>
    </main>
    <BenefitsComponent />
  </div>
</template>

<style scoped>
/* Puedes agregar estilos personalizados aquí si es necesario */
</style>
