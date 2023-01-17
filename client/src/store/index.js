import { createStore } from "vuex";
import auth from "./modules/auth";
import subscriber from "./subscribers/mutation";

const store = createStore({
  modules: { auth },
});

store.subscribe(subscriber);

export default store;
