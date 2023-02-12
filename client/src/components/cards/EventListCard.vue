<script setup>
defineProps({
  event: Object,
});
</script>

<template>
  <div class="card">
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
          Learn more
        </RouterLink>
      </div>
    </div>
  </div>
</template>
