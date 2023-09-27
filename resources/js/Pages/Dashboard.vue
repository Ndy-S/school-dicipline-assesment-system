<template>
    <div class="flex">
        <Nav/>

        <Loader v-if="showLoader" class="my-auto mx-auto" />

        <div class="flex flex-col" v-if="!showLoader">
        <Title />
        </div>

    </div>

</template>

<script setup>
    import { ref } from 'vue';
    import Nav from '../Shared/Nav.vue';
    import Title from '../Shared/Title.vue';
    import Loader from '../Shared/Loader.vue';

    const showLoader = ref(true);

    setTimeout(() => {
        showLoader.value = false;
    }, 1500);

</script>

