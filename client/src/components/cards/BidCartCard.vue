<script setup>
defineProps({
  bid: Object,
});
</script>

<template>
  <div class="flex rounded-lg gap-2 border-cyan-500 border-2 h-36 w-full">
    <div>
      <img
        :src="bid.animal.image"
        alt="Photo de l'animal"
        class="h-full w-80"
      />
    </div>
    <div class="flex gap-2 basis-full">
      <div class="flex flex-col basis-full p-4 justify-center">
        <RouterLink
          :to="{ name: 'bid', params: { id: bid.id } }"
          class="text-2xl"
        >
          {{ bid.title }}
        </RouterLink>
        <span>{{ bid.animal.name }}</span>
        <span>Sold by {{ bid.seller.name }}</span>
      </div>
      <div class="flex flex-col basis-1/3 p-4 items-center justify-center">
        <span>{{ bid.currentPrice }} €</span>
      </div>
    </div>
  </div>
</template>
