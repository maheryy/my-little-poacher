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
    path: "/checkout/success",
    name: "checkout-success",
    component: () => import("../views/protected/CheckoutSuccess.vue"),
    props: (route) => ({ sessionId: route.query.session_id }),
    beforeEnter: checkoutResolver,
  },
];

export default routes;
