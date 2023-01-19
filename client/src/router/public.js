import { loginResolver } from "./middlewares";

const routes = [
  {
    path: "/",
    name: "home",
    component: () => import("../views/Home.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("../views/Login.vue"),
    beforeEnter: loginResolver,
  },
  {
    path: "/bids",
    name: "bids",
    component: () => import("../views/BidList.vue"),
  },
  {
    path: "/bid/:id",
    name: "bid",
    component: () => import("../views/Bid.vue"),
    props: true,
  },
  {
    path: "/map",
    name: "map",
    component: () => import("../views/Map.vue")
  },
];

export default routes;
