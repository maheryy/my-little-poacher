<script setup>
import { useRouter } from "vue-router";
import bidList from "../../../mock_data/models/bids.json";
import BidCartCard from "../../components/BidCartCard.vue";
import axios from "axios";

const bids = bidList.slice(0, 2);
const router = useRouter();

const selectedBids = bids.map((bid) => bid.id);

const checkout = async () => {
  try {
    const req = await axios.post("checkout/session", { bids: selectedBids });
    if (!req.data.redirect_url) {
      throw new Error("No redirect url returned from server.");
    }
    window.location.assign(req.data.redirect_url);
  } catch (error) {
    console.error(error.message);
  }
};
</script>

<template>
  <h1>Mes enchères</h1>
  <section class="my-12">
    <button class="btn" @click="checkout">Payer</button>
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <BidCartCard :bid="bid" />
        </li>
      </ul>
    </div>
  </section>
</template>
