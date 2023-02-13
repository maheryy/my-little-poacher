<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { computed } from "@vue/reactivity";
import Burger from '../assets/icons/burger.svg'
import Home from '../assets/icons/home.svg'
import Auctions from '../assets/icons/auctions.svg'
import Scanner from '../assets/icons/scanner.svg'
import MyBids from '../assets/icons/my-bids.svg'
import Account from '../assets/icons/account.svg'
import Ticket from '../assets/icons/ticket.svg'
import Event from '../assets/icons/event.svg'
import Checklist from '../assets/icons/checklist.svg'

const store = useStore();
const router = useRouter();
const isLogged = computed(() => store.getters["auth/authenticated"]);
const isPro = computed(() => store.getters["auth/isPro"]);

const currentRoute = computed(() => router?.currentRoute.value.name)

const logout = () => {
  store.dispatch("auth/logout");
  router.push({ name: "home" });
};
</script>
<template>
  <nav class="shadow bg-slate-900 w-20 hidden md:block h-screen fixed">
    <div class="flex flex-col h-screen items-center justify-start mx-autocapitalize text-gray-300">
      <div class="h-20 w-20 cursor-pointer flex items-center justify-center">
        <img :src="Burger" class="hover:scale-105 transition-all duration-200" />
      </div>
      <div class="px-2 w-full">
        <div class="h-[1px] w-full bg-white"></div>
      </div>
      <div class="flex-1">
        <div class="h-20 w-20 flex justify-center items-center">
          <RouterLink :to="{ name: 'home' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'home' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="Home" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
        <div class="h-20 w-20 flex justify-center items-center">
          <RouterLink :to="{ name: 'bids' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'bids' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="Auctions" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
        <div class="h-20 w-20 flex justify-center items-center" v-if="isLogged">
          <RouterLink :to="{ name: 'cart' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'cart' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="MyBids" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
        <div class="h-20 w-20 flex justify-center items-center">
          <RouterLink :to="{ name: 'events' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'events' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="Event" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
        <div class="h-20 w-20 flex justify-center items-center" v-if="isLogged">
          <RouterLink :to="{ name: 'tickets' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'tickets' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="Ticket" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
        <div class="h-20 w-20 flex justify-center items-center" v-if="isPro">
          <RouterLink :to="{ name: 'scanner' }" :class="`h-16 w-16 transition-all duration-200 rounded-lg cursor-pointer flex items-center justify-center ${currentRoute === 'scanner' ? 'bg-slate-700' : 'hover:bg-slate-800'}`">
            <img :src="Scanner" class="hover:scale-105 transition-all duration-200" />
          </RouterLink>
        </div>
      </div>
      <div class="h-20 w-20 flex justify-center items-center bg-slate-700">
        <RouterLink :to="{ name: 'login' }" class="h-16 w-16 cursor-pointer flex items-center justify-center">
          <img :src="Account" class="hover:scale-105 transition-all duration-200" />
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
