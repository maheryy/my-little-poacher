<script setup>
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { reactive, ref, onMounted } from "vue";
import axios from "axios";

const store = useStore();
const router = useRouter();
const route = useRoute();

const form = reactive({
  email: "",
  password: "",
});

const error = ref("");
const success = ref("");

const onSubmit = async () => {
  try {
    await store.dispatch("auth/login", form);
    router.push({ name: "dashboard" });
  } catch (e) {
    if (e.response?.status === 401) {
      error.value = e.response.data.message;
    } else {
      error.value = "Something went wrong";
    }
  }
};

onMounted(() => {
  if (route.query.token) {
    axios
      .get(`auth/verify-email/${route.query.token}`)
      .then(() => {
        success.value = "Votre email a bien été vérifié";
      })
      .catch((e) => {
        if (e.response.status === 400) {
          error.value = e.response.data.message;
        }
      });
  }
});
</script>

<template>
  <div class="w-full h-screen flex flex-col items-center justify-center">
    <h1>Connexion</h1>
    <div class="form-wrapper w-80">
      <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
        <p v-if="error" class="text-red-500 text-center">{{ error }}</p>
        <p v-else-if="success" class="text-green-500 text-center">{{ success }}</p>
        <input
          type="email"
          placeholder="Email"
          name="email"
          class="px-2 py-2 rounded-md text-black"
          v-model="form.email"
          required
        />
        <input
          type="password"
          placeholder="·············"
          name="password"
          class="px-2 py-2 rounded-md text-black"
          v-model="form.password"
          required
        />
        <button
          type="submit"
          class="px-2 py-2 rounded-md bg-blue-500 text-white"
        >
          Se connecter
        </button>
      </form>
      <p>
        Pas encore de compte ?
        <RouterLink :to="{ name: 'register' }">S'inscrire</RouterLink>
      </p>
    </div>
  </div>
</template>

<style></style>
