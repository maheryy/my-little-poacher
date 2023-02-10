import { authResolver, checkoutResolver } from "./middlewares";

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
  {
    path: "/cart",
    name: "cart",
    component: () => import("../views/protected/Cart.vue"),
    ...options,
  },
  {
    path: "/checkout/bids",
    name: "checkout-bids",
    component: () => import("../views/protected/CartPostCheckout.vue"),
    props: (route) => ({ sessionId: route.query.session_id }),
    beforeEnter: checkoutResolver,
  },
  {
    path: "/tickets",
    name: "tickets",
    component: () => import("../views/protected/TicketList.vue"),
    props: true,
    ...options,
  },
  {
    path: "/checkout/tickets",
    name: "checkout-tickets",
    component: () => import("../views/protected/TicketPostCheckout.vue"),
    props: (route) => ({
      sessionId: route.query.session_id,
      ticket: route.query.ticket,
    }),
    beforeEnter: checkoutResolver,
  },
];

export default routes;
