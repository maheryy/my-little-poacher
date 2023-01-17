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
  <h1>Login page</h1>
  <div class="form-wrapper w-80">
    <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
      <input
        type="text"
        placeholder="email"
        name="email"
        class="px-2 py-2 rounded-md text-black"
        v-model="form.email"
        required
      />
      <input
        type="password"
        placeholder="password"
        name="password"
        class="px-2 py-2 rounded-md text-black"
        v-model="form.password"
        required
      />
      <button type="submit" class="px-2 py-2 rounded-md bg-blue-500 text-white">
        Login
      </button>
    </form>
  </div>
</template>

<style></style>
