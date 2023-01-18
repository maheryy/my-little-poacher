<script setup>
defineProps({
  bid: Object,
});
</script>

<template>
  <div class="flex flex-col rounded-xl border-cyan-500 border-2 w-72 h-80">
    <img :src="bid.animal.image" alt="Photo de l'animal" class="w-full h-1/5"/>
    <div class="flex flex-col p-4">
      <span>{{ bid.title }}</span>
      <span>{{ bid.animal.name }}</span>
      <span>Départ : {{ bid.initialPrice }} €</span>
      <span>Actuel : {{ bid.price }} €</span>
      <span>{{ bid.description.slice(0, 50) }}</span>
      <RouterLink class="bg-teal-600 p-2 rounded-md w-fit" :to="{ name: 'bid', params: { slug: bid.slug } }">
        Consulter
      </RouterLink>
    </div>
  </div>
</template>
