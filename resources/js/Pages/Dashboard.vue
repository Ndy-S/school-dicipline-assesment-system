<template>
    <Layout>
        <div class="margin-auto flex flex-col items-center text-center justify-center h-full font-mono">
            <font-awesome-icon :icon="['fas', 'screwdriver-wrench']" class="w-48 h-48 pulse" />
            <p class="mt-4 text-[48px] pulse-text">Under Development</p>
        </div>

        <History :historyQuery="historyQuery" historyTitle="Aktivitas Sistem"/>
    </Layout>

</template>
  

<script setup>
    import Layout from '../Shared/Layout.vue';
    import History from '../Shared/History.vue';

    const props = defineProps({
        historyQuery: Object,
    });
   
</script>

<style scoped>
    .pulse {
    animation: pulse 1.5s infinite;
    }

    .pulse-text {
    animation: pulse-text 1.5s infinite;
    }

    @keyframes pulse {
    0% {
        color: #eee;
    }
    50% {
        color: #888;
    }
    100% {
        color: #eee;
    }
    }

    @keyframes pulse-text {
    0% {
        color: #eee;
    }
    50% {
        color: #888;
    }
    100% {
        color: #eee;
    }
    }
</style>