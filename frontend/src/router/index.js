import { createRouter, createWebHistory } from "vue-router";

import { useAuthStore } from "@/stores/auth";

import HomeView from "@/views/HomeView.vue";
import RegisterView from "@/views/Auth/RegisterView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import DiagramView from "@/views/Diagram/DiagramView.vue";
import ErrorFormView from "@/views/Error/ErrorFormView.vue";

import FieldSettingsView from "@/views/Field/FieldSettingsView.vue";

import CreatePlaceView from "@/views/Place/CreatePlaceView.vue";
import UpdatePlaceView from "@/views/Place/UpdatePlaceView.vue";

import CreateStaffView from "@/views/Staff/CreateStaffView.vue";
import UpdateStaffView from "@/views/Staff/UpdateStaffView.vue";

import CreateFactorView from "@/views/Factor/CreateFactorView.vue";
import UpdateFactorView from "@/views/Factor/UpdateFactorView.vue";

import CreateTypeView from "@/views/Type/CreateTypeView.vue";
import UpdateTypeView from "@/views/Type/UpdateTypeView.vue";

import CollaboratorsView from "@/views/Collaborator/CollaboratorsView.vue";
import CreateCollaboratorView from "@/views/Collaborator/CreateCollaboratorView.vue";
import UpdateCollaboratorView from "@/views/Collaborator/UpdateCollaboratorView.vue";
import ErrorView from "@/views/Error/ErrorView.vue";
import UpdateErrorView from "@/views/Error/UpdateErrorView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
      meta: { guest: true },
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: { guest: true },
    },
    {
      path: "/diagram",
      name: "diagram",
      component: DiagramView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/errors",
      name: "errors",
      component: ErrorView,
      meta: { auth: true },
    },
    {
      path: "/create/error",
      name: "errorform",
      component: ErrorFormView,
      meta: { auth: true },
    },
    {
      path: "/update/error/:id",
      name: "updateerror",
      component: UpdateErrorView,
      meta: { auth: true },
    },
    {
      path: "/create/place",
      name: "createplace",
      component: CreatePlaceView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/update/place/:id",
      name: "updateplace",
      component: UpdatePlaceView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/create/staff",
      name: "createstaff",
      component: CreateStaffView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/update/staff/:id",
      name: "updatestaff",
      component: UpdateStaffView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/create/factor",
      name: "createfactor",
      component: CreateFactorView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/update/factor/:id",
      name: "updatefactor",
      component: UpdateFactorView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/create/type",
      name: "createtype",
      component: CreateTypeView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/update/type/:id",
      name: "updatetype",
      component: UpdateTypeView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/field/settings",
      name: "fieldsettings",
      component: FieldSettingsView,
      meta: { auth: true },
    },
    {
      path: "/collaborator/list",
      name: "collaboratorlist",
      component: CollaboratorsView,
      meta: { auth: true, role: "admin" },
    },
    {
      path: "/create/collaborator",
      name: "createcollaborator",
      component: CreateCollaboratorView,
      meta: { auth: true , role: "admin"},
    },
    {
      path: "/update/collaborator/:id",
      name: "updatecollaborator",
      component: UpdateCollaboratorView,
      meta: { auth: true,role: "admin" },
    },
  ],
});

router.beforeEach(async (to, from) => {
  const authStore = useAuthStore();

  await authStore.getUser();
  const user = authStore.user;
  const roles = authStore.roles;

  if (user && to.meta.guest) {
    return { name: "home" };
  }

  if (!user && to.meta.auth) {
    return { name: "login" };
  }

  if (to.meta.role && (!roles || !roles.includes(to.meta.role))) {
    return { name: "home" };
  }
});
export default router;
