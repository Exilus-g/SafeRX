import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useFactorStore = defineStore("factorStore", {
  state: () => {
    return {
      errors: {},
      successMessage: null,
      infoMessage:null,
    };
  },
  actions: {
    /**
     * Clear all errors in the current context by resetting the errors object to an empty object.
     * @returns None
     */
    clearErrors() {
      this.errors = {};
    },
    clearMessage() {
      this.successMessage = null;
      this.infoMessage = null;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Get All Factor                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllFactors() {
      const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/factors", {
        headers: {
          'Content-Type': 'application/json',
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  Get a Factor                                  ||
    // ! ||--------------------------------------------------------------------------------||
    async getFactor(factor) {
      const authStore = useAuthStore();

      const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/factors/${factor}`, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      if (authStore.user.id === data.user_id) {
        return data;
      } else {
        this.router.push({ name: "fieldsettings" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Create a Factor                                ||
    // ! ||--------------------------------------------------------------------------------||
    async createFactor(formData) {
      const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/factors", {
        method: "post",
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (data.errors) {
        this.errors = data.errors;
      } else {
        (this.successMessage = "Factor creado exitosamente!"),
          (this.errors = {}),
        this.router.push({ name: "fieldsettings" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Delete a Factor                                ||
    // ! ||--------------------------------------------------------------------------------||
    async deleteFactor(factor) {
      const authStore = useAuthStore();

      if (authStore.user.id === factor.user_id) {
        const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/factors/${factor.id}`, {
          method: "delete",
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const data = await res.json;
        window.location.reload();
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Update a Factor                                ||
    // ! ||--------------------------------------------------------------------------------||
    async updateFactor(factor, formData) {
      const authStore = useAuthStore();

      if (authStore.user.id === factor.user_id) {
        const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/factors/${factor.id}`, {
          method: "put",
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(formData),
        });
        const data = await res.json;
        if (data.errors) {
          this.errors = data.errors;
        } else {
          (this.infoMessage = "Factor actualizado exitosamente!"),
          this.router.push({ name: "fieldsettings" });
          this.errors = {};
        }
      }
    },
  },
});
