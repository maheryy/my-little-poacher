<script setup>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { reactive } from "vue";

const store = useStore();
const router = useRouter();

const form = reactive({
  email: "",
  password: "",
});

const onSubmit = async () => {
  try {
    await store.dispatch("auth/login", form);
    router.push({ name: "dashboard" });
  } catch (error) {
    if (error.response?.status === 401) {
      alert("Invalid credentials");
    } else {
      alert("Something went wrong");
    }
  }
};
</script>

<template>
  <div class="w-full h-screen flex flex-col items-center justify-center">
    <h1>Connexion</h1>
    <div class="form-wrapper w-80">
      <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
        <input
          type="text"
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
        <RouterLink :to="{name: 'register'}">S'inscrire</RouterLink>
      </p>
    </div>
  </div>
</template>

<style></style>
