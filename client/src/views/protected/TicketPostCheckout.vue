<script setup>
import axios from "axios";
import { onMounted,onUnmounted, ref } from "vue";
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
        router.push({ name: "ticket", params: { id: ticket } });
      }, 2000);
    })
    .catch((err) => {
      error.value = err.response.data.error.message;
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
  <h1>Confirmation de paiement</h1>
  <p v-if="isLoading">Vérification du paiement en cours...</p>
  <p v-else-if="error">
    Une erreur est survenue lors du paiement : {{ error }}
  </p>
  <div v-else>
    <p>Votre paiement a été confirmé</p>
    <p>Vous allez être redirigé vers votre ticket...</p>
  </div>
  <p></p>
</template>
