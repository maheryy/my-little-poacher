<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import EventListCard from "../components/cards/EventListCard.vue";

const events = ref([]);
onMounted(() => {
  axios
  .get("events")
  .then((res) => {
      events.value = res.data;
    })
    .catch((error) => {
      console.error(error.message);
    });
});
</script>

<template>
  <h1 class="ml-10 mt-10">Upcoming events</h1>
  <section class="py-12 flex justify-center w-full">
    <div class="my-16">
      <ul class="flex flex-wrap justify-center gap-8">
        <li v-for="event in events" :key="event.id">
          <EventListCard :event="event" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
