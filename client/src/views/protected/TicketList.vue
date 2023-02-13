<script setup>
import axios from "axios";
import TicketListCard from "../../components/cards/TicketListCard.vue";
import { onMounted, ref } from "vue";
import { useStore } from "vuex";

const tickets = ref([]);
const store = useStore();
const user = store.getters["auth/user"];

onMounted(() => {
  axios
    .get("tickets", {
      params: { "order[createdAt]": "desc", "order[status]": "asc" },
    })
    .then((res) => {
      tickets.value = res.data;
    })
    .catch((error) => {
      console.error(error.message);
    });
});
</script>

<template>
  <h1 class="ml-10 mt-10">My tickets</h1>
  <section class="my-12">
    <div class="m-auto w-2/5">
      <ul class="flex flex-col gap-8">
        <li v-for="ticket in tickets" :key="ticket.reference">
          <TicketListCard :ticket="ticket" />
        </li>
      </ul>
    </div>
  </section>
</template>
