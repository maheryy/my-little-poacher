<script setup>
import bidList from "../../mock_data/models/bids.json";
import BidListCard from "../components/BidListCard.vue";

const bids = bidList;
</script>

<template>
  <h1>Les enchères actives</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <BidListCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
