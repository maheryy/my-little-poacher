<script setup>
defineProps({
  bid: Object,
});
</script>

<template>
  <div class="card">
    <!-- <img :src="bid.animal.image" alt="Photo de l'animal" class="w-full h-44 object-cover card-image" /> -->
    <img src="http://dummyimage.com/143x100.png/5fa2dd/ffffff" alt="Photo de l'animal" class="w-full h-44 object-cover card-image" />
    <div class="flex flex-col p-4">
      <span class="text-lg font-semibold">{{ bid.title }}</span>
      <span class="text-sm">{{ bid.animal.name }}</span>
      <span class="mt-2 h-20">{{ bid.description.length > 80 ? bid.description.slice(0, 80) + '...' : bid.description }}</span>
      <div class="flex justify-between items-center mt-2">
        <div class="flex flex-col items-center text-sm">
          <span>Starting bid: <span class="font-bold">{{ bid.initialPrice }} €</span></span>
          <span>Current bid: <span class="font-bold">{{ bid.currentPrice }} €</span></span>
        </div>
        <RouterLink class="btn" :to="{ name: 'bid', params: { id: bid.id } }">
          View
        </RouterLink>
      </div>
    </div>
  </div>
</template>
