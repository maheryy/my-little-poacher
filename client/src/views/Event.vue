<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { toDateDisplayFormat } from "../utils";
import { useStore } from "vuex";

const { id } = defineProps({
  id: String,
});

const router = useRouter();
const event = ref({});
const store = useStore();

const bookTicket = async () => {
  if (!store.getters["auth/authenticated"]) {
    return router.push({ name: "login" });
  }
  const hasConfirmed = confirm(
    "Do you really want to book a ticket for this event ?"
  );
  if (!hasConfirmed) {
    return;
  }

  try {
    await axios.post("tickets", { event: `/events/${id}` });
    router.push({ name: "tickets" });
  } catch (error) {
    alert(error.response.data.detail);
    console.error(error.response.data.detail);
  }
};

onMounted(() => {
  axios
    .get(`events/${id}`)
    .then((res) => {
      event.value = res.data;
    })
    .catch((error) => {
      console.error(error.message);
    });
});
</script>

<template>
  <h1>{{ event.name }}</h1>
  <section class="my-12 flex">
    <div class="w-1/2 flex flex-col gap-4 p-6">
      <div class="flex flex-col">
        <span>{{ event.description }}</span>
        <span>Price : {{ event.price }} €</span>
      </div>
      <div class="flex flex-col">
        <span>{{ toDateDisplayFormat(event.date) }}</span>
        <span>Organized by {{ event.creator?.name }}</span>
      </div>
      <button class="btn" @click="bookTicket">Book this event</button>
    </div>
  </section>
</template>
