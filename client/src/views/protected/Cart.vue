<script setup>
import { onMounted, ref } from "vue";
import BidCartCard from "../../components/cards/BidCartCard.vue";
import axios from "axios";
import { BID_STATUS, USER_BID_STATUS } from "../../config/constants";

const bids = ref([]);
const wonBids = ref([]);

const checkout = async () => {
  if (!wonBids.value.length) return;
  try {
    const req = await axios.post("checkout/bids/session", {
      bids: wonBids.value,
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
    wonBids.value = data
      .filter(
        (userBid) =>
          userBid.status === USER_BID_STATUS.WINNING &&
          userBid.bid.status === BID_STATUS.DONE
      )
      .map((userBid) => userBid.bid.id);
  });
});
</script>

<template>
  <h1 class="ml-10 mt-10">My bids</h1>
  <section class="my-12 flex flex-col items-center">
    <span v-if="!bids.length">No active bids</span>
    <button
      v-if="wonBids.length"
      class="btn w-min mb-10"
      @click="checkout"
    >
      Pay all finished auctions
    </button>
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <BidCartCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>
