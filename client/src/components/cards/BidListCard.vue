<script setup>
defineProps({
  bid: Object,
});
</script>

<template>
  <div class="flex rounded-lg gap-2 border-cyan-500 border-2 h-48 w-full">
    <div>
      <img
        :src="bid.animal.image"
        alt="Picture of the animal"
        class="h-full w-80"
      />
    </div>
    <div class="flex gap-2 basis-full">
      <div class="flex flex-col basis-full p-4 justify-center">
        <span>{{ bid.title }}</span>
        <span>{{ bid.animal.name }}</span>
        <span>Sold by {{ bid.seller.name }}</span>
        <span>{{ bid.description.slice(0, 100) }}</span>
      </div>
      <div class="flex flex-col basis-1/3 p-4 items-center justify-center">
        <span>Starting bid: {{ bid.initialPrice }} €</span>
        <span>Current bid: {{ bid.currentPrice }} €</span>
        <RouterLink
          class="bg-teal-600 p-2 rounded-md w-fit"
          :to="{ name: 'bid', params: { id: bid.id } }"
        >
          View
        </RouterLink>
      </div>
    </div>
  </div>
</template>
