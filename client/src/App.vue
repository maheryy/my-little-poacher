<script setup>
import Nav from "./components/Nav.vue";
</script>

<template>
  <div class="flex">
    <Nav />
    <main class="m-auto ml-20 w-content-view flex-1 px-14 py-10">
      <RouterView />
    </main>
  </div>
</template>
