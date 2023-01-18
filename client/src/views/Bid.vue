<script setup>
import { ref } from "vue";
import bidList from "../../mock_data/models/bids.json";
import commentList from "../../mock_data/models/comments.json";
import BidComment from "../components/BidComment.vue";

const { slug } = defineProps({
  slug: String,
});

const bid = bidList[Math.floor(Math.random() * bidList.length)];
const comments = commentList.slice(0, Math.floor(Math.random() * 10) + 1);

const comment = ref("");
const auction = ref(bid.price + 1);

const submitComment = () => {
  console.log(comment);
};

const submitAuction = () => {
  console.log(auction);
};
</script>

<template>
  <h1>{{ bid.title }}</h1>
  <section class="my-12 flex">
    <div class="w-1/2">
      <img
        class="w-full h-auto"
        :src="bid.animal.image"
        alt="Image de l'animal"
      />
    </div>
    <div class="w-1/2 flex flex-col gap-4 p-6">
      <div class="flex flex-col">
        <span>{{ bid.animal.name }}</span>
        <span>{{ bid.description }}</span>
      </div>
      <div class="flex flex-col">
        <span>Actuel : {{ bid.price }} €</span>
        <form @submit.prevent="submitAuction" class="flex gap-2">
          <input
            class="border text-sm rounded-lg focus:ring-blue-500 block w-32 p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white darkring-blue-500 focus:border-blue-500"
            type="number"
            v-model="auction"
            :min="bid.price + 1"
          />
          <button class="bg-teal-600 p-2 rounded-md w-fit">Enchérir</button>
        </form>
      </div>
      <div class="flex flex-col">
        <span>Départ : {{ bid.initialPrice }} €</span>
        <span>Début de l'enchère : {{ bid.startAt }}</span>
        <span>Fin de l'enchère : {{ bid.endAt }}</span>
      </div>
      <div class="flex flex-col">
        <span>Vendu par {{ bid.seller.username }}</span>
      </div>
    </div>
  </section>
  <section>
    <h2 class="font-semibold text-2xl">Commentaires</h2>
    <ul class="flex flex-col gap-3 my-8">
      <li v-for="comment in comments" :key="comment.id">
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
        <button class="bg-teal-600 p-2 rounded-md w-fit">Envoyer</button>
      </form>
    </div>
  </section>
</template>
