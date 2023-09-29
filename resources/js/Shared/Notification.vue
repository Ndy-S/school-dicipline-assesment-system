<template>
    <div
        class="bg-green-500 fixed bottom-4 right-4 w-56 p-3 rounded-lg text-white flex items-center justify-center space-x-2 shadow-md success-notification"
        :class="{ 'visible': successNotification === true}"
    >
        <font-awesome-icon :icon="['fas', 'circle-check']" class="w-5 h-5" />
        <span>{{ messageNotification }}</span>
    </div>
</template>

<script setup>
    const props= defineProps({
        successNotification: Boolean,
        messageNotification: String,
    });
</script>

<style>
    .success-notification {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
    }

    .success-notification.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>