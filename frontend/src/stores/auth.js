import { defineStore } from "pinia";

export const useAuthStore = defineStore("authStore", {
  state: () => {
    return {
      user: null,
      roles: [],
      errors: {},
    };
  },
  actions: {

    /**
     * Asynchronously fetches user data from the server using the token stored in localStorage.
     * If the token is present, it sends a request to the "/api/user" endpoint with the token in the headers.
     * If the response is successful, it updates the user and roles properties of the class.
     * @returns None
     */
    async getUser() {
      const token = localStorage.getItem("token");
      if (token) {
        const res = await fetch("https://estudiante.tecinfouppue.com/backend/public/api/user", {
          headers: {
            'Content-Type': 'application/json',
            authorization: `Bearer ${token}`,
          },
        });
        const data = await res.json();
        if (res.ok) {
          this.user = data.user;
          this.roles = data.roles;
        }
      }
    },

    
    /**
     * Authenticates the user by sending a POST request to the specified API route with the provided form data.
     * If successful, stores the user token in local storage, sets user data and roles, and navigates to the home page.
     * If there are errors in the response data, sets the errors property accordingly.
     * @param {string} apiRoute - The API route to authenticate against.
     * @param {object} formData - The data to be sent in the POST request body.
     * @returns None
     */
    async authenticate(apiRoute, formData) {
      const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/${apiRoute}`, {
        method: "post",
        body: JSON.stringify(formData),
        headers:{
          'Content-Type': 'application/json',
        }
      });

      const data = await res.json();
      if (data.errors) {
        this.errors = data.errors;
      } else {
        this.errors = {};
        localStorage.setItem("token", data.token);
        this.user = data.user;
        console.log(data.rols);
        this.roles = data.rols;
        this.router.push({ name: "errors" });
      }
    },

    
    /**
     * Performs a logout operation by sending a POST request to the '/api/logout' endpoint.
     * Clears user data, errors, and removes the token from local storage upon successful logout.
     * Navigates the user to the 'home' route after logout.
     * @returns None
     */
    async logout() {
      const res = await fetch(`https://estudiante.tecinfouppue.com/backend/public/api/logout`, {
        method: "post",
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });
      const data = await res.json();
      console.log(data);
      if (res.ok) {
        this.user = null;
        this.errors = {};
        localStorage.removeItem("token");
        this.router.push({ name: "home" });
      }
    },
  },
});
