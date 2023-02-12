<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { toDateDisplayFormat } from "../../utils";
import QrcodeVue from "qrcode.vue";

const { reference } = defineProps({
  reference: String,
});

const ticket = ref({});
const loading = ref(true);
const error = ref("");

onMounted(() => {
  axios
    .get(`tickets/${reference}`)
    .then((res) => {
      ticket.value = res.data;
    })
    .catch((e) => {
      error.value = e.response.data.message || "Une erreur est survenue";
    })
    .finally(() => {
      loading.value = false;
    });
});
</script>

<template>
  <div v-if="loading">Loading...</div>
  <div v-else-if="error">{{ error }}</div>
  <div v-else>
    <h1 class="text-center">Ticket n°{{ ticket.reference }}</h1>
    <section class="m-auto w-1/2">
      <div class="w-full flex flex-col gap-4 p-6">
        <div class="flex flex-col">
          <p>
            1 ticket bought for the event:
            <RouterLink
              class="underline"
              :to="{ name: 'event', params: { id: ticket.event.id } }"
            >
              {{ ticket.event.name }}
            </RouterLink>
          </p>
        </div>
        <div class="flex flex-col">
          <p>On {{ toDateDisplayFormat(ticket.event.date) }}</p>
          <p>At: {{ ticket.event.address }}</p>
        </div>

        <div class="flex flex-col w-full gap-8">
          <p>Show the following QR Code upon arrival</p>
          <div class="flex items-center justify-center">
            <QrcodeVue :value="ticket.reference" :size="200" level="H" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
