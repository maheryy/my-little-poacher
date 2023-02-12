import { authProResolver, checkoutResolver } from "./middlewares";

const options = {
  beforeEnter: authProResolver,
};

const routes = [
  {
    path: "/admin",
    name: "admin",
    component: () => import("../views/protected/admin/Admin.vue"),
    ...options,
  },
];

export default routes;
