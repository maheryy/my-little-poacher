<script setup>
defineProps({
  event: Object,
});
</script>

<template>
  <div class="card">
    <img
      src="http://dummyimage.com/143x100.png/5fa2dd/ffffff"
      alt="Picture of the event"
      class="w-full h-44 object-cover card-image"
    />
    <div class="flex flex-col p-4">
      <span class="text-lg font-semibold">{{ event.name }}</span>
      <span class="mt-2 h-20">{{
        event.description.length > 80
          ? event.description.slice(0, 80) + "..."
          : event.description
      }}</span>
      <div class="flex justify-between items-center mt-2">
        <RouterLink
          class="btn"
          :to="{ name: 'event', params: { id: event.id } }"
        >
          View
        </RouterLink>
      </div>
    </div>
  </div>
</template>
