import store from "../../store";

export const authResolver = (to, from, next) => {
  if (!store.getters["auth/authenticated"]) {
    return next({ name: "login" });
  }

  next();
};

export const loginResolver = (to, from, next) => {
  if (store.getters["auth/authenticated"]) {
    return next({ name: "dashboard" });
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