<script setup>
import bidList from "../../mock_data/models/bids.json";
import BidCard from "../components/BidCard.vue";

const trendBids = bidList.slice(0, 10);
const otherBids = bidList.slice(10, 20);

</script>

<template>
  <h1>Enchères en cours !</h1>
  <section class="my-12">
    <h2 class="font-semibold text-2xl">Enchères du moment</h2>
    <div class="overflow-x-scroll">
      <ul class="flex items-center gap-8">
        <li v-for="bid in trendBids" :key="bid.id">
          <BidCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
  <section class="my-12">
    <h2 class="font-semibold text-2xl">Plus d'enchères</h2>
    <div class="m-auto w-fit">
      <ul class="flex flex-wrap items-center gap-8">
        <li v-for="bid in otherBids" :key="bid.id">
          <BidCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
