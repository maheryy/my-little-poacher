<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";
import EventListCard from "../../../components/cards/EventListCard.vue";

const store = useStore();

const user = computed(() => store.getters["auth/user"]);

console.log(user.value);

const events = ref([]);
onMounted(() => {
  axios
    .get(`events?creator=${user.value.id}`)
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

          <div class="card">
            <div class="flex flex-col p-4">
              <span class="text-lg font-semibold">{{ event.name }}</span>
              <span class="mt-2 h-20">{{
                event.description.length > 80
                  ? event.description.slice(0, 80) + "..."
                  : event.description
              }}</span>
              <div class="flex justify-between items-center mt-2">
                <RouterLink class="btn" :to="{ name: 'update-event', params: { id: event.id } }">
                  Edit
                </RouterLink>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </section>
</template>

<style>

</style>
