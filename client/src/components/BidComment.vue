<script setup>
defineProps({
  comment: Object,
});
</script>

<template>
  <div class="flex flex-col">
    <span class="underline font-light">{{ comment.author.name }}</span>
    <span>{{ comment.content }}</span>
  </div>
</template>
