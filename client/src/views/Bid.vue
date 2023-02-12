<script setup>
import { ref, onMounted } from "vue";
import bidList from "../../mock_data/models/bids.json";
import commentList from "../../mock_data/models/comments.json";
import BidComment from "../components/BidComment.vue";
import axios from "axios";
import { useStore } from "vuex";

const { id } = defineProps({
  id: String,
});

const store = useStore();

const bid = ref({});
const comment = ref("");
const auction = ref();

onMounted(() => {
  axios.get(`bids/${id}`).then((res) => {
    bid.value = res.data;
    auction.value = bid.value.currentPrice + 1;
  });
});

const submitComment = () => {
  if (!comment.value.trim()) return;
  
  axios
    .post("comments", {
      content: comment.value.trim(),
      bid: `/bids/${id}`,
      author: `/users/${store.state.auth.user.id}`,
    })
    .then((res) => {
      bid.value = {
        ...bid.value,
        comments: [...bid.value.comments, res.data],
      };
      comment.value = "";
    });
};

const outbid = () => {
  axios
    .post(`bids/${id}/outbid`, {
      price: auction.value,
    })
    .then((res) => {
      bid.value = {
        ...bid.value,
        currentPrice: res.data.currentPrice,
      };
    
    });
};
</script>

<template>
  <h1>{{ bid.title }}</h1>
  <section class="my-12 flex">
    <div class="w-1/2">
      <img
        class="w-full h-auto"
        src="http://dummyimage.com/143x100.png/5fa2dd/ffffff"
        alt="Image de l'animal"
      />
    </div>
    <div class="w-1/2 flex flex-col gap-4 p-6">
      <div class="flex flex-col">
        <span>{{ bid.animal?.name }}</span>
        <span>{{ bid.description }}</span>
      </div>
      <div class="flex flex-col">
        <span>Current price: {{ bid.currentPrice }} €</span>
        <form @submit.prevent="outbid" class="flex gap-2">
          <input
            class="border text-sm rounded-lg focus:ring-blue-500 block w-32 p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white darkring-blue-500 focus:border-blue-500"
            type="number"
            v-model="auction"
            :min="bid.currentPrice + 1"
          />
          <button class="bg-teal-600 p-2 rounded-md w-fit">Enchérir</button>
        </form>
      </div>
      <div class="flex flex-col">
        <span>Stating bid: {{ bid.initialPrice }} €</span>
        <span>Auction start: {{ bid.startAt }}</span>
        <span>Auction end: {{ bid.endAt }}</span>
      </div>
      <div class="flex flex-col">
        <span>Sold by {{ bid.seller?.name }}</span>
      </div>
    </div>
  </section>
  <section>
    <h2 class="font-semibold text-2xl">Comments</h2>
    <ul class="flex flex-col gap-3 my-8">
      <li v-for="comment in bid.comments" :key="comment.id">
        <BidComment :comment="comment" />
      </li>
    </ul>
    <div>
      <form @submit.prevent="submitComment" class="flex gap-2">
        <input
          class="border text-sm rounded-lg focus:ring-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white darkring-blue-500 focus:border-blue-500"
          type="text"
          placeholder="Votre commentaire"
          v-model="comment"
        />
        <button class="bg-teal-600 p-2 rounded-md w-fit">Submit</button>
      </form>
    </div>
  </section>
</template>
