<script setup>
import ticketList from "../../../mock_data/models/tickets.json";
import axios from "axios";
import TicketListCard from "../../components/cards/TicketListCard.vue";

const tickets = ticketList.slice(0, 10);
</script>

<template>
  <h1>Mes tickets</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="ticket in tickets" :key="ticket.id">
          <TicketListCard :ticket="ticket" />
        </li>
      </ul>
    </div>
  </section>
</template>
