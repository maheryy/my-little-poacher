import { authAdminResolver, checkoutResolver } from "./middlewares";

const options = {
  beforeEnter: authAdminResolver,
};

const routes = [
  {
    path: "/approve-adm",
    name: "approve-adm",
    component: () => import("../views/protected/admin/ApproveUser.vue"),
    ...options,
  },
  {
    path: "/delete-comments",
    name: "delete-comments",
    component: () => import("../views/protected/admin/DeleteComment.vue"),
    ...options,
  },
];

export default routes;
