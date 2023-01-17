import axios from "axios";

const state = {
  token: null,
  user: null,
};

const getters = {
  authenticated(state) {
    return state.token && state.user;
    // return true;
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
      // TODO: review login url
      const response = await axios.post("auth/login", credentials);
      return dispatch("attempt", response.data.token);
    } catch (e) {
      throw e;
    }
  },
  async attempt({ commit }, token, user = null) {
    if (!token) return;
    if (user) return commit("setUser", user);
    try {
      commit("setToken", token);
      // TODO: review user url
      const response = await axios.get("auth/me");
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
