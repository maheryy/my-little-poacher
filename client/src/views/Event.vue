<script setup>
import { ref, onMounted } from "vue";
import eventList from "../../mock_data/models/events.json";
import axios from "axios";

const { id } = defineProps({
  id: String,
});

const event = eventList[Math.floor(Math.random() * eventList.length)];

// const event = ref({});
// onMounted(() => {
//   axios.get(`events/${id}`).then((res) => {
//     event.value = res.data;
//   });
// });
</script>

<template>
  <h1>{{ event.name }}</h1>
  <section class="my-12 flex">
    <div class="w-1/2">
      <img
        class="w-full h-auto"
        src="http://dummyimage.com/143x100.png/5fa2dd/ffffff"
        alt="Image de l'event"
      />
    </div>
    <div class="w-1/2 flex flex-col gap-4 p-6">
      <div class="flex flex-col">
        <span>{{ event.description }}</span>
        <span>Prix unitaire : {{ event.price }} €</span>
      </div>
      <div class="flex flex-col">
        <span>L'événement se déroule le {{ event.date }}</span>
        <span>Organisé par {{ event.creator?.name }}</span>
      </div>
      <RouterLink class="btn" :to="{ name: 'book-event', params: { id: event.id } }">
        Réserver
      </RouterLink>
    </div>
  </section>
</template>
