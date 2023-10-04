<template>
    <Layout :can="can">

        <Development />
        <History :historyQuery="historyQuery" historyTitle="Aktivitas Sistem"/>
    </Layout>

</template>

<script setup>
    import Layout from '../Shared/Layout.vue';
    import History from '../Shared/History.vue';
    import Development from '../Shared/Development.vue';

    const props = defineProps({
        historyQuery: Object,
        can: Object,
    });   
</script>