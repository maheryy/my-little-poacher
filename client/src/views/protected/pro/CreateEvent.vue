<script setup>
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";
import axios from "axios";
import { getErrorMessagesFromResponse } from "../../../utils";

const router = useRouter();

const event = reactive({
    name: "",
    description: "",
    price: "",
    address: "",
    capacity: 10,
    date: "",
});

const errors = ref({});

const onSubmit = async () => {
    try {
        await axios.post("events", event)
        router.push({ name: "dashboard" });
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
    <div class="w-full h-screen flex flex-col items-center mt-10">
        <h1>Create an auction</h1>
        <p>Create an auction for an animal you own</p>
        <div class="form-wrapper w-80">
            <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
                <h2 class="text-2xl mt-10">Event</h2>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Name</span>
                    <input type="text" placeholder="My amazing event" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="event.name" required />
                    <p v-if="errors.name" class="text-red-500">{{ errors.name }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Description</span>
                    <textarea type="text" placeholder="Description" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="event.description" required />
                    <p v-if="errors.description" class="text-red-500">{{ errors.description }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Price (€)</span>
                    <input type="text" placeholder="Price" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="event.price" required />
                    <p v-if="errors.price" class="text-red-500">{{ errors.price }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Address</span>
                    <input type="text" placeholder="Address" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="event.address" required />
                    <p v-if="errors.address" class="text-red-500">{{ errors.address }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Ticket price</span>
                    <input type="number" placeholder="Ticket price" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="event.capacity" required />
                    <p v-if="errors.capacity" class="text-red-500">{{ errors.capacity }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Date</span>
                    <input type="datetime-local" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="event.date" required />
                    <p v-if="errors.date" class="text-red-500">{{ errors.date }}</p>
                </div>
                <button type="submit" class="px-2 py-2 rounded-md bg-blue-500 text-white">
                    Submit
                </button>
            </form>
        </div>
    </div>
</template>

<style>

</style>
