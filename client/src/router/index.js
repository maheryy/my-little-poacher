import { createRouter, createWebHistory } from "vue-router";
import publicRoutes from "./public";
import protectedRoutes from "./protected";

const routes = [
  ...publicRoutes,
  ...protectedRoutes,
  {
    path: "/:pathMatch(.*)",
    name: "not-found",
    component: () => import("../views/errors/NotFound.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

export default router;
