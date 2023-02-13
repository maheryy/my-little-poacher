<script setup>
import { onMounted, ref } from "vue";
import BidCartCard from "../../components/cards/BidCartCard.vue";
import axios from "axios";

const bids = ref([]);

const checkout = async () => {
  try {
    const req = await axios.post("checkout/bids/session", {
      bids: selectedBids,
    });
    if (!req.data.redirect_url) {
      throw new Error("No redirect url returned from server.");
    }
    window.location.assign(req.data.redirect_url);
  } catch (error) {
    console.error(error.message);
  }
};

onMounted(() => {
  axios.get("user_bids").then((res) => {
    const data = Array.isArray(res.data) ? res.data : [];
    bids.value = data.map((datum) => datum.bid);
  });
});
</script>

<template>
  <h1 class="ml-10 mt-10">My bids</h1>
  <section class="my-12 flex flex-col items-center">
    <button class="btn w-min mb-10" @click="checkout">Pay</button>
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <BidCartCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>
