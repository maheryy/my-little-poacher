<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const { id } = defineProps({
    id: String,
});

const router = useRouter();

let bid = ref({})

const errors = ref({});

onMounted(() => {
    axios
        .get(`bids/${id}`)
        .then((res) => {
            const { title, description, initialPrice, endAt } = res.data;
            bid.value = {
                title,
                description,
                initialPrice: initialPrice,
                endAt: endAt.slice(0, 16)
            }
            console.log(bid)
        })
        .catch((error) => {
            console.error(error.message);
        });
});

const onSubmit = async () => {
    try {
        console.log(bid.value)
        await axios.put(`bids/${id}`, bid.value)
        router.push({ name: "dashboard" });
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = getErrorMessagesFromResponse(error);
        } else {
            alert("Something went wrong");
        }
    }
};

const deleteAuction = async () => {
    try {
        await axios.delete(`bids/${id}`)
        router.push({ name: "dashboard" });
    } catch (error) {
        if (error.response.status === 422) {
            errors.value = getErrorMessagesFromResponse(error);
        } else {
            alert("Something went wrong");
        }
    }
};

const finishAuction = async () => {
    try {
        await axios.post(`bids/${id}/end`)
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
        <h1>Update an auction</h1>
        <p>Update an auction you created</p>
        <div class="form-wrapper w-80">
            <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-8">
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
                    <span class="text-gray-300">Price (€)</span>
                    <input type="number" placeholder="Price" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="bid.initialPrice" required />
                    <p v-if="errors.initialPrice" class="text-red-500">{{ errors.initialPrice }}</p>
                </div>
                <div class="flex flex-col w-full">
                    <span class="text-gray-300">Ends on:</span>
                    <input type="datetime-local" name="name" class="px-2 py-2 rounded-md text-black"
                        v-model="bid.endAt" required />
                    <p v-if="errors.endAt" class="text-red-500">{{ errors.endAt }}</p>
                </div>
                <button type="submit" class="px-2 py-2 rounded-md bg-blue-500 text-white">
                    Submit
                </button>
            </form>
            <button @click="deleteAuction" class="px-2 py-2 rounded-md bg-red-500 w-80 text-white">
                Delete
            </button>
            <button @click="finishAuction" class="px-2 mt-8 py-2 rounded-md bg-emerald-500 w-80 text-white">
                End the auction
            </button>
        </div>
    </div>
</template>
