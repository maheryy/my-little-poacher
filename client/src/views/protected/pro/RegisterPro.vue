<script setup>
import { useRouter } from "vue-router";
import { onMounted, reactive, ref } from "vue";
import axios from "axios";
import { getErrorMessagesFromResponse } from "../../../utils";
import { useStore } from "vuex";

const router = useRouter();
const store = useStore();
const user = store.getters["auth/user"];
const isPro = store.getters["auth/isPro"];

const form = reactive({
  address: "",
  description: "",
});

const errors = ref({});
const proRequest = ref({});

const onSubmit = async () => {
  try {
    const res = await axios.post("user_sellers", form);
    proRequest.value = {
      ...res.data,
      status: "pending", // TODO REMOVE THIS LINE
    };
  } catch (error) {
    if (error.response.status === 422) {
      errors.value = getErrorMessagesFromResponse(error);
    } else {
      alert("Something went wrong");
    }
  }
};

onMounted(() => {
  if (!isPro) {
    axios
      .get("user_sellers", { params: { user: user.id } })
      .then((response) => {
        proRequest.value = response.data;
      })
      .catch((error) => {
        console.log(error);
      });
  }
});
</script>

<template>
  <div
    v-if="isPro"
    class="w-full h-screen flex flex-col items-center justify-center"
  >
    <h1>Become a seller</h1>
    <p>You are already a seller</p>
  </div>
  <div
    v-else-if="proRequest.status === 'pending'"
    class="w-full h-screen flex flex-col items-center justify-center"
  >
    <h1>Become a seller</h1>
    <p>
      Your request has been sent. You'll receive a confirmation email when it will be processed.
    </p>
  </div>
  <div v-else class="w-full h-screen flex flex-col items-center justify-center">
    <h1>Become a seller!</h1>
    <p>To become a seller, you only need to send a request</p>
    <div class="form-wrapper w-80">
      <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
        <div class="flex flex-col w-full">
          <input
            type="text"
            placeholder="Address"
            name="name"
            class="px-2 py-2 rounded-md text-black"
            v-model="form.address"
            required
          />
          <p v-if="errors.address" class="text-red-500">{{ errors.address }}</p>
        </div>
        <div class="flex flex-col w-full">
          <textarea
            type="text"
            placeholder="Description"
            name="name"
            class="px-2 py-2 rounded-md text-black"
            v-model="form.description"
            required
          />
          <p v-if="errors.description" class="text-red-500">{{ errors.description }}</p>
        </div>
        <button
          type="submit"
          class="px-2 py-2 rounded-md bg-blue-500 text-white"
        >
          Submit
        </button>
      </form>
    </div>
  </div>
</template>

<style></style>
