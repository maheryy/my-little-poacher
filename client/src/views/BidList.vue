<script setup>
import { ref, onMounted } from "vue";
import BidListCard from "../components/cards/BidListCard.vue";
import axios from "axios";

const bids = ref([]);
onMounted(() => {
  axios
    .get("bids")
    .then((res) => {
      bids.value = res.data;
    });
});

</script>

<template>
  <h1>Active auctions</h1>
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
