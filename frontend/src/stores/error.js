import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useErrorStore = defineStore("errorStore", {
  state: () => {
    return {
      errors: {},
      successMessage: null,
      infoMessage:null,
    };
  },
  actions: {
    clearErrors() {
      this.errors = {};
    },
    clearMessage() {
      this.successMessage = null;
      this.infoMessage = null;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Get All Errors                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllErrors() {
      const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/errors", {
        headers: {
          'Content-Type': 'application/json',
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                   Get a Error                                  ||
    // ! ||--------------------------------------------------------------------------------||
    async getError(error) {
      const authStore = useAuthStore();

      const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/errors/${error}`, {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      if ((authStore.user.id || authStore.user.main_user_id) === data.user_id) {
        console.log(data);
        return data;
      } else {
        this.router.push({ name: "errors" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Create a Error                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async createError(formData) {
      const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/errors", {
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
        (this.successMessage = "Error registrado exitosamente!"),
          (this.errors = {}),
        this.router.push({ name: "errors" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Delete a Error                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async deleteError(error) {
      const authStore = useAuthStore();

      if (authStore.user.id === error.user_id) {
        const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/errors/${error.id}`, {
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
    async updateError(error, formData) {
      const authStore = useAuthStore();

      if (
        (authStore.user.id || authStore.user.main_user_id) === error.user_id
      ) {
        const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/errors/${error.id}`, {
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
          (this.infoMessage = "Error actualizado exitosamente!"),
          this.router.push({ name: "errors" });
          this.errors = {};
        }
      }
    },
  },
});
