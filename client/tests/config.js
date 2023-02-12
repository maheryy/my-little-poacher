import router from "../src/router";
import store from "../src/store";

store.commit("auth/setToken", "test-token");
store.commit("auth/setUser", {
  id: 1,
  name: "Test user",
  email: "test.user@example.com",
  roles: ["ROLE_USER"],
  status: 1,
});

export const defaultRenderOptions = {
  global: {
    plugins: [router, store],
  },
};
