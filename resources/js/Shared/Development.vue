<template>
    <div class="margin-auto flex flex-col items-center text-center justify-center h-full font-mono">
        <font-awesome-icon :icon="['fas', 'screwdriver-wrench']" class="w-44 h-44 pulse" />
        <p class="mt-4 text-[40px] pulse-text">Dalam Pengembangan</p>
    </div>
</template>

<style scoped>
    .pulse {
    animation: pulse 1.5s infinite;
    }

    .pulse-text {
    animation: pulse-text 1.5s infinite;
    }

    @keyframes pulse {
    0% {
        color: #fff;
    }
    50% {
        color: #ccc
    }
    100% {
        color: #fff;
    }
    }

    @keyframes pulse-text {
    0% {
        color: #ccc;
    }
    50% {
        color: #fff;
    }
    100% {
        color: #ccc;
    }
    }
</style>