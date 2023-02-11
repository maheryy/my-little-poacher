<script setup>
import axios from "axios";
import BidCard from "../components/cards/BidCard.vue";
import { onMounted, ref } from "vue";
import { useStore } from "vuex";

const allBids = ref([]);
const trendBids = ref([]);
const store = useStore();

onMounted(() => {
  axios
    .get("bids", {
      // params: {
      //   "page": 1,
      //   "order[id]": "desc",
      // },
    })
    .then((res) => {
      allBids.value = res.data;
      trendBids.value = res.data;
    });
});
</script>

<template>
  <div class="flex items-center justify-between p-14">
    <h1 class="">Enchères en cours !</h1>
    <button class="btn" v-if="!store.getters['auth/isPro']">
      <RouterLink :to="{ name: 'register-pro' }"> Deviens pro ! </RouterLink>
    </button>
  </div>
  <section class="my-12 md:mx-14">
    <h2 class="font-semibold text-2xl mb-10">Enchères du moment</h2>
    <div class="overflow-x-auto">
      <ul class="flex items-center gap-8">
        <li v-for="bid in trendBids" :key="bid.id">
          <BidCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
  <section class="my-12 mx-14 mb-10">
    <h2 class="font-semibold text-2xl mb-10">Plus d'enchères</h2>
    <div class="m-auto w-fit">
      <ul class="flex flex-wrap items-center justify-center gap-8">
        <li v-for="bid in allBids" :key="bid.id">
          <BidCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
