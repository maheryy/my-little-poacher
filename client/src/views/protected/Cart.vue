<script setup>
import bidList from "../../../mock_data/models/bids.json";
import BidCartCard from "../../components/BidCartCard.vue";
import { loadStripe } from "@stripe/stripe-js/pure";
import axios from "axios";

const bids = bidList.slice(0, 2);

const selectedBids = bids.map((bid) => bid.id);

const checkout = async () => {
  try {
    const req = await axios.post("checkout/session", { bids: selectedBids });
    const sessionId = req.data.session_id;

    if (!sessionId) throw new Error("No session id returned from server.");

    const stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
    const { error } = await stripe.redirectToCheckout({ sessionId });

    if (error) throw new Error(error);
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
