import store from "../../store";

export const authResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }

  next();
};

export const authProResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }
  if (!store.getters["auth/isPro"]) {
    return next({ name: "not-found", params: { pathMatch: to.path.slice(1) } });
  }
  
  next();
};

export const authAdminResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }
  if (!store.getters["auth/isAdmin"]) {
    return next({ name: "not-found", params: { pathMatch: to.path.slice(1) } });
  }
  
  next();
};

export const loginResolver = (to, from, next) => {
  if (store.getters["auth/authenticated"]) {
    return next({ name: "dashboard" });
  }

  next();
};

export const checkoutResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }
  if (!to.query.session_id) {
    return next({
      name: "not-found",
      params: { pathMatch: to.path.slice(1) }, // TODO: fix urls with '/'
    });
  }
  next();
};

/*
export const adminResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }
  if (!store.getters["auth/admin"]) {
    return next({ name: "not-found", params: { pathMatch: to.path.slice(1) } });
  }

  next();
};
*/
