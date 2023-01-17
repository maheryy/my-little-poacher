<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import router from "../../router";
const store = useStore();
const user = store.state.auth.user;

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};
</script>

<template>
  <h1>[PROTECTED] Dashboard page</h1>
  <h3>Hello {{ user.username }}</h3>
  <button @click="logout" class="px-2 py-2 rounded-md bg-blue-500 text-white">
    Logout
  </button>
</template>

<style></style>
