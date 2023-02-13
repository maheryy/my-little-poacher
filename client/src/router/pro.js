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
  {
    path: "/pro/create-auction",
    name: "create-auction",
    component: () => import("../views/protected/pro/CreateAuction.vue"),
    ...options,
  },
  {
    path: "/pro/create-event",
    name: "create-event",
    component: () => import("../views/protected/pro/CreateEvent.vue"),
    ...options,
  },
];

export default routes;
