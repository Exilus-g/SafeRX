import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const useStaffInvolvedStore = defineStore("staffInvolvedStore", {
  state: () => {
    return {
      errors: {},
      successMessage: null,
      infoMessage:null,
    };
  },
  actions: {

    
    /**
     * Clear the errors object by setting it to an empty object.
     * @returns None
     */
    clearErrors() {
      this.errors = {};
    },

    clearMessage() {
      this.successMessage = null;
      this.infoMessage = null;
    },
    

    /**
     * Fetches all staff involved from the server using an async function.
     * @returns {Promise} A promise that resolves to the data of all staff involved.
     */
    async getAllStaff() {
      const res = await fetch("/api/staff_involveds", {
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    
    /**
     * Asynchronously fetches staff data from the server and checks if the authenticated user
     * has permission to access the data.
     * @param {string} staff - The ID of the staff member to retrieve data for.
     * @returns {Promise} A promise that resolves to the staff data if the authenticated user has permission,
     * or redirects to the "fieldsettings" route if not.
     */
    async getStaff(staff) {
      const authStore = useAuthStore();

      const res = await fetch(`/api/staff_involveds/${staff}`, {
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

    
    /**
     * Asynchronously creates a new staff member using the provided form data.
     * @param {Object} formData - The data of the staff member to be created.
     * @returns None
     */
    async createStaff(formData) {
      const res = await fetch("/api/staff_involveds", {
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
        (this.successMessage = "Personal creado exitosamente!"),
          (this.errors = {}),
        this.router.push({ name: "fieldsettings" });
      }
    },

    
    /**
     * Asynchronously deletes a staff member from the database if the authenticated user is the same as the staff member being deleted.
     * @param {Object} staff - The staff member object to be deleted.
     * @returns None
     */
    async deleteStaff(staff) {
      const authStore = useAuthStore();

      if (authStore.user.id === staff.user_id) {
        const res = await fetch(`/api/staff_involveds/${staff.id}`, {
          method: "delete",
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const data = await res.json;
        window.location.reload();
      }
    },

    
    /**
     * Asynchronously updates staff information based on the provided form data.
     * @param {Object} staff - The staff object to be updated.
     * @param {Object} formData - The form data containing the updated information.
     * @returns None
     */
    async updateStaff(staff, formData) {
      const authStore = useAuthStore();

      if (authStore.user.id === staff.user_id) {
        const res = await fetch(`/api/staff_involveds/${staff.id}`, {
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
          (this.infoMessage = "Personal actualizado exitosamente!"),
          this.router.push({ name: "fieldsettings" });
          this.errors = {};
        }
      }
    },
  },
});
