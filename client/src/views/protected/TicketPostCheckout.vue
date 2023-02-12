<script setup>
import axios from "axios";
import { onMounted, onUnmounted, ref } from "vue";
import { useRouter } from "vue-router";

const { sessionId, ticket } = defineProps({
  ticket: String,
  sessionId: String,
});

const router = useRouter();
const isLoading = ref(true);
const error = ref("");

let timeout = null;

onMounted(() => {
  axios
    .post("checkout/tickets/success", { ticket, session_id: sessionId })
    .then((res) => {
      timeout = setTimeout(() => {
        router.push({ name: "tickets" });
      }, 2000);
    })
    .catch((err) => {
      error.value = err.response.data.message;
    })
    .finally(() => {
      isLoading.value = false;
    });
});

onUnmounted(() => {
  clearTimeout(timeout);
});
</script>

<template>
  <h1>Payment confirmation</h1>
  <p v-if="isLoading">Payment verification in progress...</p>
  <p v-else-if="error">An error occured during payment: {{ error }}</p>
  <div v-else>
    <p>Your payment has been confirmed</p>
    <p>You will be redirected to your tickets...</p>
  </div>
  <p></p>
</template>
