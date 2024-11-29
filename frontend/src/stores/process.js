import { defineStore } from "pinia";

export const useProcessStore = defineStore("processStore", {
  state: () => {
    return {
      errors: {},
    };
  },
  actions: {

    
    /**
     * Asynchronously fetches all processes from the server.
     * @returns {Promise} A promise that resolves to the data fetched from the server.
     */
    async getAllProcess() {
      const res = await fetch("/api/nodes", {
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    
    /**
     * Asynchronously sends a POST request to the "/api/nodes" endpoint with the provided shapesText.
     * @param {string} shapesText - The text to be sent in the request body.
     * @returns None
     */
    async createProcess(shapesText) {
      const res = await fetch("/api/nodes", {
        method: "post",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ texts: shapesText }), // Enviar el arreglo dentro de la clave "texts"
      });
      const data = await res.json();
      if (data.errors) {
        this.errors = data.errors;
      }
    },
  },
});
