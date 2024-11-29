import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useCollaboratorStore = defineStore("collaboratorStore", {
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
    // ! ||                                  Get All Collaborators                         ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllCollaborators() {
      const res = await fetch("/api/collaborators", {
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                              Get a Collaborator                                ||
    // ! ||--------------------------------------------------------------------------------||
    async getCollaborator(collaborator) {
      const authStore = useAuthStore();

      const res = await fetch(`/api/collaborators/${collaborator}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      if (authStore.user.id === data.main_user_id) {
        return data;
      } else {
        this.router.push({ name: "collaboratorlist" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Create a Collaborator                          ||
    // ! ||--------------------------------------------------------------------------------||
    async createCollaborator(formData) {
      const res = await fetch("/api/collaborators", {
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
        this.router.push({ name: "collaboratorlist" });
      }
    },

    // ! ||--------------------------------------------------------------------------------||
    // ! ||                                 Delete a Collaborator                          ||
    // ! ||--------------------------------------------------------------------------------||
    async deleteCollaborator(collaborator) {
      const authStore = useAuthStore();

      if (authStore.user.id === collaborator.main_user_id) {
        const res = await fetch(`/api/collaborators/${collaborator.id}`, {
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
    // ! ||                                 Update a Collaborator                          ||
    // ! ||--------------------------------------------------------------------------------||
    async updateCollaborator(collaborator, formData) {
      const authStore = useAuthStore();

      if (authStore.user.id === collaborator.main_user_id) {
        const res = await fetch(`/api/collaborators/${collaborator.id}`, {
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
          this.router.push({ name: "collaboratorlist" });
          this.errors = {};
        }
      }
    },
  },
});
