<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { onMounted, ref } from "vue";
import { USER_SELLER_STATUS } from "../../../config/constants";
import axios from "axios";

const store = useStore();
const router = useRouter();
const user = store.state.auth.user;

const userReqs = ref([]);

const logout = () => {
    store.dispatch("auth/logout");
    router.push({ name: "home" });
};

onMounted(() => {
    axios
        .get("user_sellers?status=pending")
        .then((res) => {
            userReqs.value = res.data;
        })
});

const approveUser = (id) => {
    axios
        .patch(`user_sellers/${id}`, { status: USER_SELLER_STATUS.APPROVED })
        .then((res) => {
            userReqs.value = userReqs.value.filter((userReq) => userReq.id !== id);
        })
        .catch((error) => {
            console.error(error.message);
        });
};

const rejectUser = (id) => {
    axios
        .patch(`user_sellers/${id}`, { status: USER_SELLER_STATUS.REJECTED })
        .then((res) => {
            userReqs.value = userReqs.value.filter((userReq) => userReq.id !== id);
        })
        .catch((error) => {
            console.error(error.message);
        });
};
</script>

<template>
    <h1 class="mt-10 ml-10">Admin</h1>
    <span class="mt-10 ml-10">Approve users to become sellers</span>

    <div class="w-full flex flex-wrap justify-center p-10">
        <div v-for="userReq in userReqs" :key="userReq.id">
            <div class="flex flex-col items-center gap-4 w-96 border border-gray-500 rounded-lg p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <span class="text-center text-lg font-bold">{{ userReq.seller.name }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-center">{{ userReq.address }}</span>
                        <span class="text-center">{{ userReq.description }}</span>
                    </div>
                </div>
                <div class="flex flex-row gap-4">
                    <button class="bg-green-500 w-28 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    @click="() => { approveUser(userReq.id) }">
                        Approve
                    </button>
                    <button class="bg-red-500 w-28 flex-1 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    @click="() => { rejectUser(userReq.id) }"
                    >
                        Deny
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>
