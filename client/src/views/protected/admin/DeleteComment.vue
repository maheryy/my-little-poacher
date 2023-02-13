<script setup>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { onMounted, ref } from "vue";
import axios from "axios";

const store = useStore();

const comments = ref([]);

onMounted(() => {
    axios
        .get("comments", {
            params: { "order[createdAt]": "desc" },
        })
        .then((res) => {
            console.log(res.data);
            comments.value = res.data;
        })
});
</script>

<template>
    <h1 class="mt-10 ml-10">Admin</h1>
    <span class="mt-10 ml-10">Remove problematic comments</span>

    <div class="w-full flex gap-4 flex-wrap justify-center p-10">
        <div v-for="comment in comments" :key="comment.id">
            <div class="flex flex-col items-center gap-4 w-96 border border-gray-500 rounded-lg p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <span class="text-center text-lg font-bold">{{ comment.author.name }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-center">{{ comment.content }}</span>
                    </div>
                </div>
                <div class="flex flex-row gap-4">
                    <button class="bg-red-500 w-28 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>
