import { authProResolver, checkoutResolver } from "./middlewares";

const options = {
  beforeEnter: authProResolver,
};

const routes = [
  {
    path: "/pro/scanner",
    name: "scanner",
    component: () => import("../views/protected/pro/Scanner.vue"),
    ...options,
  },
];

export default routes;
