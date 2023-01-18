<script setup>
import bidList from "../../../mock_data/models/bids.json";
import BidCartCard from "../../components/BidCartCard.vue";

const bids = bidList.slice(0, 2);
</script>

<template>
  <h1>Mes enchères</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <BidCartCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>
