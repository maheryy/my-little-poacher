<script setup>
import axios from "axios";
import { TICKET_STATUS } from "../../config/constants";

defineProps({
  ticket: Object,
});

const confirmTicket = async (id) => {
  try {
    const req = await axios.post("checkout/tickets/session", { ticket: id });
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
  <div class="flex rounded-lg gap-2 border-cyan-500 border-2 h-36 w-full">
    <div class="flex gap-2 basis-full">
      <div class="flex flex-col basis-full p-4 justify-center gap-3">
        <span>{{ ticket.event.name }}</span>
        <span>Limité à {{ ticket.event.capacity }} personnes</span>
      </div>
      <div class="flex flex-col basis-1/3 p-4 justify-center gap-2">
        <span class="font-semibold">{{ ticket.reference }}</span>
        <span>{{ ticket.status }}</span>
        <button
          v-if="ticket.status === TICKET_STATUS.PENDING"
          class="btn"
          @click="confirmTicket(ticket.id)"
        >
          Payer {{ ticket.event.price }} €
        </button>
        <RouterLink
          v-else-if="ticket.status === TICKET_STATUS.CONFIRMED"
          class="bg-cyan-500 text-white rounded-md p-2"
          :to="{ name: 'ticket', params: { reference: ticket.reference } }"
          >Voir mon ticket</RouterLink
        >
      </div>
    </div>
  </div>
</template>
