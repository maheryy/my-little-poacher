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
    path: "/register",
    name: "register",
    component: () => import("../views/Register.vue"),
  },
  {
    path: "/bids",
    name: "bids",
    component: () => import("../views/BidList.vue"),
  },
  {
    path: "/bids/:id",
    name: "bid",
    component: () => import("../views/Bid.vue"),
    props: true,
  },
  {
    path: "/events",
    name: "events",
    component: () => import("../views/EventList.vue"),
  },
  {
    path: "/events/:id",
    name: "event",
    component: () => import("../views/Event.vue"),
    props: true,
  },
  {
    path: "/map",
    name: "map",
    component: () => import("../views/Map.vue")
  },
];

export default routes;
