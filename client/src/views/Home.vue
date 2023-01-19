<script setup>
import bidList from "../../mock_data/models/bids.json";
import BidCard from "../components/BidCard.vue";

const trendBids = bidList.slice(0, 10);
const otherBids = bidList.slice(10, 20);

</script>

<template>
  <h1 class="md:mx-14 mt-14">Enchères en cours !</h1>
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
        <li v-for="bid in otherBids" :key="bid.id">
          <BidCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
