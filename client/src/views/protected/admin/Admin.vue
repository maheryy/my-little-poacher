<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { onMounted } from "vue";
import axios from "axios";

const store = useStore();
const router = useRouter();
const user = store.state.auth.user;

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};

onMounted(() => {
  axios
    .get("user_sellers?status=pending")
    .then((res) => {
      console.log(res.data);
    })
});
</script>

<template>
  <h1>Admin</h1>
  <h3>Hello {{ user.name }}</h3>
  <button @click="logout" class="px-2 py-2 rounded-md bg-blue-500 text-white">
    Logout
  </button>
</template>

<style>

</style>
