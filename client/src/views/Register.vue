<script setup>
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";
import axios from "axios";
import { getErrorMessagesFromResponse } from "../../utils";
const router = useRouter();

const form = reactive({
  name: "",
  email: "",
  plainPassword: "",
});

const confirmPassword = ref("");
const errors = ref({});

const onSubmit = async () => {
  if (form.plainPassword !== confirmPassword.value) {
    errors.value = { confirmPassword: "Passwords do not match" };
    return;
  }

  try {
    await axios.post("users", form);
    router.push({ name: "login" });
  } catch (error) {
    if (error.response.status === 422) {
      errors.value = getErrorMessagesFromResponse(error);
    } else {
      alert("Something went wrong");
    }
  }
};
</script>

<template>
  <div class="w-full h-screen flex flex-col items-center justify-center">
    <h1>Inscription</h1>
    <div class="form-wrapper w-80">
      <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
        <div class="flex flex-col w-full">
          <input
            type="text"
            placeholder="Nom d'utilisateur"
            name="name"
            class="px-2 py-2 rounded-md text-black"
            v-model="form.name"
            required
          />
          <p v-if="errors.name" class="text-red-500">{{ errors.name }}</p>
        </div>
        <div class="flex flex-col w-full">
          <input
            type="email"
            placeholder="Email"
            name="email"
            class="px-2 py-2 rounded-md text-black"
            v-model="form.email"
            required
          />
          <p v-if="errors.email" class="text-red-500">{{ errors.email }}</p>
        </div>
        <div class="flex flex-col w-full">
          <input
            type="password"
            placeholder="Mot de passse"
            name="password"
            class="px-2 py-2 rounded-md text-black"
            v-model="form.plainPassword"
            required
          />
          <p v-if="errors.plainPassword" class="text-red-500">
            {{ errors.plainPassword }}
          </p>
        </div>
        <div class="flex flex-col w-full">
          <input
            type="password"
            placeholder="Confirmation du mot de passe"
            name="password-confirm"
            class="px-2 py-2 rounded-md text-black"
            v-model="confirmPassword"
            required
          />
          <p v-if="errors.confirmPassword" class="text-red-500">
            {{ errors.confirmPassword }}
          </p>
        </div>
        <button
          type="submit"
          class="px-2 py-2 rounded-md bg-blue-500 text-white"
        >
          S'inscrire
        </button>
      </form>
      <p>
        Déjà un compte ?
        <RouterLink :to="{name: 'login'}">Se connecter</RouterLink>
      </p>
    </div>
  </div>
</template>

<style></style>
