<script setup>
import { reactive, onMounted, ref, watch } from "vue";
import { useDiagramStore } from "@/stores/diagram";
import { useProcessStore } from "@/stores/process";

const diagramStore = useDiagramStore();
const processStore = useProcessStore();


// Reactive form data
const formData = reactive({
  json_data: "",
});

const successAlert = reactive({
  show: false,
  message: "¡Diagrama guardado exitosamente!",
});

// References for the diagram and symbol palette
const diagramRef = ref(null);
let diagramInstance = null;

// Diagrams and symbols definitions
let flowShapes = [
  { id: "Fin", shape: { type: "Flow", shape: "Terminator" } },
  { id: "Proceso", shape: { type: "Flow", shape: "Process" } },
  { id: "Datos", shape: { type: "Flow", shape: "Data" } },
  { id: "Documento", shape: { type: "Flow", shape: "Document" } },
];

let basicShapes = [
  { id: "Rectángulo", shape: { type: "Basic", shape: "Rectangle" } },
  { id: "Elipse", shape: { type: "Basic", shape: "Ellipse" } },
  { id: "Hexágono", shape: { type: "Basic", shape: "Hexagon" } },
  { id: "Estrella", shape: { type: "Basic", shape: "Star" } },
];

let connectorSymbols = [
  {
    id: "Flecha",
    type: "Straight",
    sourcePoint: { x: 0, y: 0 },
    targetPoint: { x: 0, y: 60 },
    targetDecorator: { shape: "Arrow" },
  },
  {
    id: "Linea",
    type: "Straight",
    sourcePoint: { x: 0, y: 0 },
    targetPoint: { x: 60, y: 60 },
    targetDecorator: { shape: "None" },
  },
  {
    id: "Codo-Direccional",
    type: "Orthogonal",
    sourcePoint: { x: 0, y: 0 },
    targetPoint: { x: 60, y: 60 },
    targetDecorator: { shape: "Arrow" },
  },
  {
    id: "Codo-Lineal",
    type: "Orthogonal",
    sourcePoint: { x: 0, y: 0 },
    targetPoint: { x: 60, y: 60 },
    targetDecorator: { shape: "None" },
  },
];

// Palette settings for the symbol palette
const paletteSettings = {
  flow: {
    id: "flow",
    title: "Diagrama de Flujo",
    symbols: flowShapes,
  },
  basic: {
    id: "basic",
    title: "Figuras Basicas",
    symbols: basicShapes,
  },
  connectors: {
    id: "connectors",
    title: "Conexiones",
    symbols: connectorSymbols,
  },
};

// Dimensions and other settings
const diagramSettings = {
  width: "1202px",
  height: "702px",
  paletteWidth: "300px",
  paletteHeight: "700px",
  symbolHeight: 60,
  symbolWidth: 60,
  symbolPreview: {
    height: 150,
    width: 150,
    offset: {
      x: 15,
      y: 10,
    },
  },
};

// Save and load diagram methods
const onSave = () => {
  // Asegúrate de que diagramInstance esté inicializado
  if (diagramInstance) {
    const diagramData = diagramInstance.saveDiagram();
    formData.json_data = JSON.stringify(diagramData);
    diagramStore.createDiagram(formData);
    
    const nodes = diagramInstance.nodes;
    const shapesText = nodes.map((node) => {
      return node.annotations && node.annotations.length > 0
        ? node.annotations[0].content
        : "";
    });
    processStore.createProcess(shapesText);
    successAlert.show = true;
    setTimeout(() => {
      successAlert.show = false;
    }, 5000);
  }
};

const onLoad = () => {
  if (diagramInstance && diagramStore.json_data) {
    const diagramData = JSON.parse(diagramStore.json_data);
    diagramInstance.loadDiagram(diagramData);
  }
};

// Load the diagram data when the component mounts
onMounted(async () => {
  diagramInstance = diagramRef.value?.ej2Instances;
  await diagramStore.getDiagram(); // Load the diagram data from the API
  formData.json_data = diagramStore.json_data; // Update formData with the loaded JSON data
  onLoad(); // Call onLoad to display the diagram
});
</script>

<template>
  <div>
  <div
      v-if="successAlert.show"
      class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
      role="alert"
    >
      <span class="font-medium">{{ successAlert.message }}</span>
    </div>
  <div class="container bg-bag">
    <!-- Success alert -->
    
    <div class="palette-container pt-6 pr-2">
      <div class="flex gap-x-2">
        <button
          class="flex w-full justify-center rounded-md bg-buttonTitle px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-mainBlack focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-main"
          @click="onSave"
        >
          Guardar
        </button>
      </div>

      <ejs-symbolpalette
        :width="diagramSettings.paletteWidth"
        :height="diagramSettings.paletteHeight"
        :palettes="[
          paletteSettings.flow,
          paletteSettings.basic,
          paletteSettings.connectors,
        ]"
        :symbolHeight="diagramSettings.symbolHeight"
        :symbolWidth="diagramSettings.symbolWidth"
        :enableSearch="true"
        :symbolPreview="diagramSettings.symbolPreview"
      />
    </div>
    <div>
      <ejs-diagram
        ref="diagramRef"
        :width="diagramSettings.width"
        :height="diagramSettings.height"
      ></ejs-diagram>
    </div>
  </div>
</div>
</template>

<style>
@import "../../../node_modules/@syncfusion/ej2-vue-diagrams/styles/material.css";
@import "../../../node_modules/@syncfusion/ej2-navigations/styles/material.css";
@import "../../../node_modules/@syncfusion/ej2-base/styles/material.css";
@import "../../../node_modules/@syncfusion/ej2-inputs/styles/material.css";
@import "../../../node_modules/@syncfusion/ej2-buttons/styles/material.css";

.container {
  display: flex;
  margin-left: 10px;
}
.palette-container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  margin-bottom: 10px;
}
</style>
