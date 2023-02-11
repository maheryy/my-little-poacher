<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { toDateDisplayFormat } from "../../utils";
import QrcodeVue from "qrcode.vue";

const { id } = defineProps({
  id: String,
});

const ticket = ref({});
const loading = ref(true);
const error = ref("");

onMounted(() => {
  axios
    .get(`tickets/${id}`)
    .then((res) => {
      ticket.value = res.data;
      ticket.value.qrCodeContent = `www.google.com`;
    })
    .catch((error) => {
      error.value = error.message;
    })
    .finally(() => {
      loading.value = false;
    });
});
</script>

<template>
  <div v-if="loading">Chargement...</div>
  <div v-else-if="error">{{ error }}</div>
  <div v-else>
    <h1 class="text-center">Ticket n°{{ ticket.reference }}</h1>
    <section class="m-auto w-1/2">
      <div class="w-full flex flex-col gap-4 p-6">
        <div class="flex flex-col">
          <p>
            1 place acheté pour l'événement
            <RouterLink
              class="underline"
              :to="{ name: 'event', params: { id: ticket.event.id } }"
            >
              {{ ticket.event.name }}
              </RouterLink
            >
          </p>
        </div>
        <div class="flex flex-col">
          <p>Pour le {{ toDateDisplayFormat(ticket.event.date) }}</p>
          <p>Rendez vous au : {{ ticket.event.address }}</p>
        </div>

        <div class="flex flex-col w-full gap-8">
          <p>Présentez le QR Code ci-dessous à votre arrivé</p>
          <div class="flex items-center justify-center">
            <QrcodeVue :value="ticket.qrCodeContent" size="200" level="H" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
