<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};
</script>
<template>
  <nav class="shadow bg-gray-800">
    <div
      class="flex items-center justify-center p-6 mx-autocapitalize text-gray-300"
    >
      <RouterLink
        :to="{ name: 'home' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Home
      </RouterLink>
      <RouterLink
        :to="{ name: 'login' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Login
      </RouterLink>
      <RouterLink
        :to="{ name: 'dashboard' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Dashboard (protected)
      </RouterLink>
      <RouterLink
        to="/not-found-route"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Not found
      </RouterLink>
      <a
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6 cursor-pointer"
        @click="logout"
      >
        Logout
      </a>
    </div>
  </nav>
</template>
