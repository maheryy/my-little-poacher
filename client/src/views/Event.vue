<script setup>
import { ref, onMounted } from "vue";
import eventList from "../../mock_data/models/events.json";
import axios from "axios";
import { useRouter } from "vue-router";

const { id } = defineProps({
  id: String,
});

const event = eventList[Math.floor(Math.random() * eventList.length)];

const router = useRouter();

const bookTicket = async () => {
  // try {
  //   await axios.post("tickets", { event: `/events/${id}` });
  // } catch (error) {
  //   console.error(error.message);
  // }

  router.push({ name: "tickets" });
};

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
      <button class="btn" @click="bookTicket">Réserver</button>
    </div>
  </section>
</template>
