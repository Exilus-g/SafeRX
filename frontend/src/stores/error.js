import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useErrorStore = defineStore("errorStore", {
  state: () => {
    return {
      errors: {},
    };
  },
  actions: {
    clearErrors() {
      this.errors = {};
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Get All Errors                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllErrors() {
      const res = await fetch("/api/errors", {
        headers: {
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

      const res = await fetch(`/api/errors/${error}`, {
        headers: {
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
      const res = await fetch("/api/errors", {
        method: "post",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify(formData),
      });
      const data = await res.json();
      if (data.errors) {
        this.errors = data.errors;
      } else {
        this.router.push({ name: "errors" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Delete a Error                                 ||
    // ! ||--------------------------------------------------------------------------------||
    async deleteError(error) {
      const authStore = useAuthStore();

      if (authStore.user.id === error.user_id) {
        const res = await fetch(`/api/errors/${error.id}`, {
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
    // ! ||                                 Update a Factor                                ||
    // ! ||--------------------------------------------------------------------------------||
    async updateError(error, formData) {
      const authStore = useAuthStore();

      if (
        (authStore.user.id || authStore.user.main_user_id) === error.user_id
      ) {
        const res = await fetch(`/api/errors/${error.id}`, {
          method: "put",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
          body: JSON.stringify(formData),
        });
        const data = await res.json;
        if (data.errors) {
          this.errors = data.errors;
        } else {
          this.router.push({ name: "errors" });
          this.errors = {};
        }
      }
    },
  },
});
