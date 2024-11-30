import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useErrorTypeStore = defineStore("errorTypeStore", {
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
    // ! ||                                  Get All Types                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllTypes() {
      const res = await fetch("/api/error_types", {
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                   Get a Type                                   ||
    // ! ||--------------------------------------------------------------------------------||
    async getType(type) {
      const authStore = useAuthStore();

      const res = await fetch(`/api/error_types/${type}`, {
        headers: {
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
    // ! ||                                  Create a Type                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async createType(formData) {
      const res = await fetch("/api/error_types", {
        method: "post",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (data.errors) {
        this.errors = data.errors;
        this.successMessage=null;
      } else {
        (this.successMessage = "Tipo de error creado exitosamente!"),
          (this.errors = {}),
        this.router.push({ name: "fieldsettings" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  Delete a Type                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async deleteType(type) {
      const authStore = useAuthStore();

      if (authStore.user.id === type.user_id) {
        const res = await fetch(`/api/error_types/${type.id}`, {
          method: "delete",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const data = await res.json;
        window.location.reload();
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                  Update a Type                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async updateType(type, formData) {
      const authStore = useAuthStore();

      if (authStore.user.id === type.user_id) {
        const res = await fetch(`/api/error_types/${type.id}`, {
          method: "put",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(formData),
        });
        const data = await res.json;
        if (data.errors) {
          this.errors = data.errors;
          this.infoMessage=null;
        } else {
          (this.infoMessage = "Tipo de error actualizado exitosamente!"),
          this.router.push({ name: "fieldsettings" });
          this.errors = {};
        }
      }
    },
  },
});
