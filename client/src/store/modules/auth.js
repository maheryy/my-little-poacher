import axios from "axios";

const state = {
  token: null,
  user: null,
};

const getters = {
  authenticated(state) {
    return state.token && state.user;
  },
};

const mutations = {
  setToken(state, token) {
    state.token = token;
  },
  setUser(state, user) {
    state.user = user;
  },
};

const actions = {
  async login({ dispatch }, credentials) {
    try {
      const response = await axios.post("auth", credentials);
      return dispatch("attempt", response.data.token);
    } catch (e) {
      throw e;
    }
  },
  async attempt({ commit }, { token, user }) {
    if (!token) return;
    if (user) {
      commit("setToken", token);
      commit("setUser", user);
      return;
    }
    try {
      commit("setToken", token);
      const response = await axios.get("users/me");
      commit("setUser", response.data.user);
    } catch (e) {
      commit("setToken", null);
      commit("setUser", null);
    }
  },
  logout({ commit }) {
    commit("setToken", null);
    commit("setUser", null);
  },
};

export default { namespaced: true, state, getters, mutations, actions };
