<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
const store = useStore();
const router = useRouter();
const user = store.state.auth.user;
const isPro = store.getters["auth/isPro"];
const isAdmin = store.getters["auth/isAdmin"];

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};
</script>

<template>
  <h1 class="mt-10 ml-10">Dashboard page</h1>
  <h3 class="mt-10 ml-10">Hello {{ user.name }}</h3>
  <div class="flex flex-col items-center w-full mt-10">
    <button @click="logout" class="px-2 py-2 rounded-md bg-blue-500 text-white">
      Logout
    </button>

    <div v-if="isAdmin" class="flex gap-2 mt-10">
      <RouterLink :to="{ name: 'approve-adm' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
        Approve users
      </RouterLink>
      <RouterLink :to="{ name: 'delete-comments' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
        Delete comments
      </RouterLink>
    </div>
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
      <RouterLink :to="{ name: 'my-auctions' }" class="px-2 py-2 rounded-md bg-blue-500 text-white">
        My auctions
      </RouterLink>
    </div>
  </div>
</template>

<style>

</style>
