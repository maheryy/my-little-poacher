<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const { sessionId } = defineProps({
  sessionId: String,
});

const router = useRouter();
const error = ref("");
const isLoading = ref(true);

onMounted(() => {
  axios
    .post("checkout/bids/success", { session_id: sessionId })
    .then((res) => {
      timeout = setTimeout(() => {
        router.push({ name: "home" });
      }, 2000);
    })
    .catch((err) => {
      error.value = err.response.data.message;
    })
    .finally(() => {
      isLoading.value = false;
    });
});
</script>

<template>
  <h1>Payment confirmation</h1>
  <p v-if="isLoading">Payment verification in progress...</p>
  <p v-else-if="error">An error occured during payment: {{ error }}</p>
  <div v-else>
    <p>Your payment has been confirmed</p>
    <p>You will be redirected to home page...</p>
  </div>
</template>
