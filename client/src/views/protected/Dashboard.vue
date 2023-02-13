<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
const store = useStore();
const router = useRouter();
const user = store.state.auth.user;
const isPro = store.getters["auth/isPro"];

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};
</script>

<template>
  <h1>[PROTECTED] Dashboard page</h1>
  <h3>Hello {{ user.name }}</h3>
  <button @click="logout" class="px-2 py-2 rounded-md bg-blue-500 text-white">
    Logout
  </button>
  <div v-if="isPro" class="flex gap-2 mt-10">
    <RouterLink :to="{ name: 'create-event' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
      Create an event
    </RouterLink>
    <RouterLink :to="{ name: 'create-auction' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
      Create an auction
    </RouterLink>
    <RouterLink :to="{ name: 'my-events' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
      My events
    </RouterLink>
  </div>
</template>

<style>

</style>
