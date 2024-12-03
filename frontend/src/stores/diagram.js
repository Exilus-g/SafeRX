import { defineStore } from "pinia";

export const useDiagramStore = defineStore("diagramStore", {
  state: () => {
    return {
      errors: {},
      diagramData: null,
      json_data: "",
    };
  },
  actions: {
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  Get a Diagram                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async getDiagram() {
      if (localStorage.getItem("token")) {
        try {
          const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/diagrams", {
            headers: {
              'Content-Type': 'application/json',
              authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });

          if (res.ok) {
            const data = await res.json();

            this.json_data = data.json_data;
          } else {
            console.error("No se pudo obtener el diagrama.");
          }
        } catch (error) {
          console.error("Error al obtener el diagrama:", error);
        }
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                Create a Diagram                                ||
    // ! ||--------------------------------------------------------------------------------||
    async createDiagram(formData) {
      try {
        const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/diagrams", {
          method: "POST",
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem("token")}`,
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),          
        });

        const data = await res.json();
        if (data.errors) {
          this.errors = data.errors;
        }
      } catch (error) {
        console.error("Error al crear el diagrama:", error);
        this.errors = { message: error.message };
      }
    },
  },
});
