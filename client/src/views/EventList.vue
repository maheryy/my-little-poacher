<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import EventCard from "../components/cards/EventCard.vue";
import eventList from "../../mock_data/models/events.json";


const events = eventList.slice(0, 10);

// const events = ref([]);
// onMounted(() => {
//   axios
//     .get("events")
//     .then((res) => {
//       events.value = res.data;
//     });
// });

</script>

<template>
  <h1>Prochain évènements</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-wrap gap-8">
        <li v-for="event in events" :key="event.id">
          <EventCard :event="event" />
        </li>
      </ul>
    </div>
  </section>
</template>

<style></style>
