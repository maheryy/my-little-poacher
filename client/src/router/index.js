import { createRouter, createWebHistory } from "vue-router";
import store from "../store";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: () => import("../views/Home.vue"),
    },
    {
      path: "/login",
      name: "login",
      component: () => import("../views/Login.vue"),
      beforeEnter: (_, __, next) => {
        if (store.getters["auth/authenticated"]) {
          return next({ name: "dashboard" });
        }
        next();
      },
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: () => import("../views/protected/Dashboard.vue"),
      meta: { auth: true },
    },
    {
      path: "/:pathMatch(.*)",
      name: "not-found",
      component: () => import("../views/errors/NotFound.vue"),
    },
  ],
});

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }

  next();
});

export default router;
