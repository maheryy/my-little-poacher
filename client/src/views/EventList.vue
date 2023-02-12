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
  <h1>Upcoming events</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-wrap gap-8">
        <li v-for="event in events" :key="event.id">
          <EventListCard :event="event" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
