<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();

const user = computed(() => store.getters["auth/user"]);

console.log(user.value);

const bids = ref([]);
onMounted(() => {
  axios
    .get(`bids?seller=${user.value.id}`)
    .then((res) => {
      bids.value = res.data;
    })
    .catch((error) => {
      console.error(error.message);
    });
});
</script>

<template>
  <h1 class="ml-10 mt-10">My auctions</h1>
  <section class="my-12">
    <div class="m-auto w-fit">
      <ul class="flex flex-col gap-8">
        <li v-for="bid in bids" :key="bid.id">
          <div class="flex rounded-lg gap-2 border-cyan-500 border-2 h-48 w-full">
            <div>
              <img :src="bid.animal.image" alt="Picture of the animal" class="h-full w-80" />
            </div>
            <div class="flex gap-2 basis-full">
              <div class="flex flex-col basis-full p-4 justify-center">
                <span>{{ bid.title }}</span>
                <span>{{ bid.animal.name }}</span>
                <span>Sold by {{ bid.seller.name }}</span>
                <span>{{ bid.description.slice(0, 100) }}</span>
              </div>
              <div class="flex flex-col basis-full p-4 items-center justify-center">
                <span>Starting bid: {{ bid.initialPrice }} €</span>
                <span>Current bid: {{ bid.currentPrice }} €</span>
                <RouterLink class="bg-teal-600 p-2 rounded-md w-fit" :to="{ name: 'update-bid', params: { id: bid.id } }">
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
