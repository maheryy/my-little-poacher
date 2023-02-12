<script setup>
import { onMounted, ref, onUnmounted, watch } from "vue";
import axios from "axios";
import { QrcodeStream } from "vue3-qrcode-reader";

const loading = ref(false);
const success = ref("");
const error = ref("");
const inputText = ref("");
const scannerOpen = ref(false);

let messageTimeout = null;

const verify = async (reference) => {
  if (loading.value) return;

  if (!reference) {
    return (error.value = "No reference provided");
  }
  if (reference.length !== 10) {
    return (error.value = "The reference must be 10 characters long");
  }

  loading.value = true;
  try {
    const res = await axios.get(`tickets/verify/${reference.toUpperCase()}`);
    success.value = res.data.message;
    error.value = "";
    inputText.value = "";
  } catch (e) {
    success.value = "";
    error.value = e.response.data.message;
  }

  loading.value = false;
};

const onDetect = async (promise) => {
  try {
    const { content } = await promise;
    verify(content);
  } catch (error) {
    console.error("error");
  }
};

const onInit = async (promise) => {
  try {
    await promise;
  } catch (error) {
    console.error(error);
  }
};

const switchScanner = () => {
  scannerOpen.value = !scannerOpen.value;
};

// Clear flash messages after 5 seconds
watch([success, error], () => {
  clearTimeout(messageTimeout);
  messageTimeout = setTimeout(() => {
    success.value = "";
    error.value = "";
  }, 5000);
});

onUnmounted(() => {
  clearTimeout(messageTimeout);
});
</script>

<template>
  <h1 class="text-center">Ticket verification</h1>
  <section class="w-1/2 m-auto">
    <article class="w-full border border-slate-300 rounded-md my-8 p-8">
      <h2 class="text-center text-xl">Manually confirm a ticket</h2>
      <p class="italic text-center">
        Type the reference of the ticket you want to confirm
      </p>
      <div class="m-auto flex gap-4 items-center w-fit py-4">
        <input
          class="p-2 rounded-md text-gray-900"
          placeholder="ex: ADF8D5A6E2"
          v-model="inputText"
          type="text"
        />
        <button class="btn" @click="verify(inputText)">Confirm</button>
      </div>
    </article>
    <p
      v-if="success || error"
      :class="`rounded-lg p-4 m-auto text-center ${
        success ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500'
      }`"
    >
      {{ success || error }}
    </p>
    <article class="w-full border border-slate-300 rounded-md my-8">
      <div class="m-auto flex gap-4 items-center w-fit py-4">
        <button class="btn" @click="switchScanner">
          {{ scannerOpen ? "Fermer le scanner" : "Utiliser le scanner" }}
        </button>
      </div>
      <div id="reader" v-if="scannerOpen">
        <QrcodeStream @detect="onDetect" @init="onInit" />
      </div>
    </article>
  </section>
</template>

<style></style>
