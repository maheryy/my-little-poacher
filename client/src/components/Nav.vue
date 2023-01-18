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
  <nav class="shadow bg-slate-900">
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
        :to="{ name: 'bids' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Enchères
      </RouterLink>
      <RouterLink
        :to="{ name: 'cart' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Mes enchères
      </RouterLink>
      <RouterLink
        :to="{ name: 'login' }"
        class="text-gray-200 border-blue-500 mx-1.5 sm:mx-6"
      >
        Login
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
