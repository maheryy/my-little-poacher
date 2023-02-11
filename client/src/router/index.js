import { createRouter, createWebHistory } from "vue-router";
import publicRoutes from "./public";
import protectedRoutes from "./protected";
import protectedProRoutes from "./pro";

const routes = [
  ...publicRoutes,
  ...protectedRoutes,
  ...protectedProRoutes,
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
