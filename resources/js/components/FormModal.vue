<script setup>
import { ref } from "vue";

defineProps({
  title: String,
  show: Boolean,
  onClose: Function,
});

const emit = defineEmits(["submit"]);
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
  >
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl">
      <!-- Header -->
      <div class="flex justify-between items-center p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ title }}</h2>
        <button @click="onClose" class="text-gray-500 hover:text-gray-800">✖</button>
      </div>

      <!-- Slot for content -->
      <div class="p-4">
        <slot />
      </div>
    </div>
  </div>
</template>
