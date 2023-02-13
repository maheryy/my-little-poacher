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
  {
    path: "/pro/my-events",
    name: "my-events",
    component: () => import("../views/protected/pro/SellerEventList.vue"),
    ...options,
  },
  {
    path: "/update-event/:id",
    name: "update-event",
    component: () => import("../views/protected/pro/UpdateEvent.vue"),
    props: true,
  }
];

export default routes;
