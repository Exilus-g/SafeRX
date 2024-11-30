import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const usePlaceStore = defineStore("placeStore", {
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
     * Asynchronously fetches all places from the API using a bearer token for authorization.
     * @returns {Promise} A promise that resolves to the data retrieved from the API.
     */
    async getAllPlaces() {
      const res = await fetch("/api/places", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      return data;
    },

    
    /**
     * Asynchronously fetches information about a specific place from the server.
     * @param {string} place - The identifier of the place to retrieve information for.
     * @returns {Promise<Object>} A promise that resolves to the data of the specified place.
     * @throws {Error} If the user ID in the retrieved data does not match the authenticated user's ID.
     */
    async getPlace(place) {
      const authStore = useAuthStore();

      const res = await fetch(`/api/places/${place}`, {
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
     * Asynchronously creates a new place by sending a POST request to the "/api/places" endpoint
     * with the provided form data.
     * @param {Object} formData - The data to be sent in the request body.
     * @returns None
     */
    async createPlace(formData) {
      const res = await fetch("/api/places", {
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
        (this.successMessage = "Lugar creado exitosamente!"),
          (this.errors = {}),
        this.router.push({ name: "fieldsettings" });
      }
    },

    
    /**
     * Deletes a place if the authenticated user is the owner of the place.
     * @param {Object} place - The place object to be deleted.
     * @returns None
     * @throws Error if the authenticated user is not the owner of the place.
     */
    async deletePlace(place) {
      const authStore = useAuthStore();

      if (authStore.user.id === place.user_id) {
        const res = await fetch(`/api/places/${place.id}`, {
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
     * Updates a place with the provided form data if the authenticated user is the owner of the place.
     * @param {Object} place - The place object to be updated.
     * @param {Object} formData - The form data to update the place with.
     * @returns None
     */
    async updatePlace(place, formData) {
      const authStore = useAuthStore();

      if (authStore.user.id === place.user_id) {
        const res = await fetch(`/api/places/${place.id}`, {
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
          (this.infoMessage = "Lugar actualizado exitosamente!"),
          this.router.push({ name: "fieldsettings" });
          this.errors = {};
        }
      }
    },
  },
});
