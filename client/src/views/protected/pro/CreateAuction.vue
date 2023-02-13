<script setup>
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";
import axios from "axios";
import { getErrorMessagesFromResponse } from "../../../utils";

const router = useRouter();

const form = reactive({
    address: "",
    description: "",
});

const animal = reactive({
    name: "",
    scientificName: "",
    captureDate: "",
});

const bid = reactive({
    title: "",
    description: "",
    initialPrice: 0,
    currentPrice: 123,
    animal: "test",
    startAt: "",
    endAt: "",
});

const errors = ref({});

const onSubmit = async () => {
    try {
        await axios.post("animals", animal)
        bid.animal_id = res.data.id;
        bid.currentPrice = bid.initialPrice;
        await axios.post("bids", bid)
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
                <h2 class="text-2xl">Animal</h2>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Animal name</span>
                    <input type="text" placeholder="European robin" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="animal.name" required />
                    <p v-if="errors.name" class="text-red-500">{{ errors.name }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Animal scientific name</span>
                    <input type="text" placeholder="Erithacus rubecula" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="animal.scientificName" required />
                    <p v-if="errors.scientificName" class="text-red-500">{{ errors.scientificName }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Captured on</span>
                    <input type="datetime-local" placeholder="Start at" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="animal.captureDate" required />
                    <p v-if="errors.captureDate" class="text-red-500">{{ errors.captureDate }}</p>
                </div>
                <h2 class="text-2xl mt-10">Auction</h2>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Title</span>
                    <input type="text" placeholder="My amazing auction" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="bid.title" required />
                    <p v-if="errors.title" class="text-red-500">{{ errors.title }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Description</span>
                    <textarea type="text" placeholder="Description" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="bid.description" required />
                    <p v-if="errors.description" class="text-red-500">{{ errors.description }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Initial price (€)</span>
                    <input type="number" placeholder="Initial price" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="bid.initialPrice" required />
                    <p v-if="errors.initialPrice" class="text-red-500">{{ errors.initialPrice }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Starts on</span>
                    <input type="datetime-local" placeholder="Start at" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="bid.startAt" required />
                    <p v-if="errors.startAt" class="text-red-500">{{ errors.startAt }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Ends on</span>
                    <input type="datetime-local" placeholder="End at" name="name"
                        class="px-2 py-2 rounded-md text-black" v-model="bid.endAt" required />
                    <p v-if="errors.endAt" class="text-red-500">{{ errors.endAt }}</p>
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
