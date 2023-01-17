import { authResolver } from "./middlewares";

const options = {
  beforeEnter: authResolver,
};

const routes = [
  {
    path: "/dashboard",
    name: "dashboard",
    component: () => import("../views/protected/Dashboard.vue"),
    ...options,
  },
];

export default routes;
