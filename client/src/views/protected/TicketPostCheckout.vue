<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const { sessionId, ticket } = defineProps({
  ticket: String,
  sessionId: String,
});

const isLoading = ref(true);

onMounted(() => {
  axios
    .post("checkout/tickets/success", { ticket, session_id: sessionId })
    .then((res) => {
      isLoading.value = false;
    })
    .catch((err) => {
      alert("Une erreur est survenue : " + err.response.data.error.message);
      console.log(err);
    });
});
</script>

<template>
  <h1>Confirmation de paiement</h1>
</template>
