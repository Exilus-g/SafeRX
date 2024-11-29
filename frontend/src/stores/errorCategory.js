import { defineStore } from "pinia";

export const useErrorCategoryStore = defineStore("errorCategoryStore", {
  state: () => {
    return {
      errors: {},
    };
  },
  actions: {
    
    // ! ||--------------------------------------------------------------------------------||
    // ! ||                               Get All Categories                               ||
    // ! ||--------------------------------------------------------------------------------||
    async getAllCategories() {
      const res = await fetch("/api/error_categories", {
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },
  },
});
