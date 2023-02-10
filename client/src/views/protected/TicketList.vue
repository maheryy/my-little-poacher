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
    .get("tickets", { params: { holder: user.id } })
    .then((res) => {
      tickets.value = res.data;
    })
    .catch((error) => {
      console.error(error.message);
    });
});
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
