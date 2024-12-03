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
      const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/error_categories", {
        headers: {
          'Content-Type': 'application/json',
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },
  },
});
