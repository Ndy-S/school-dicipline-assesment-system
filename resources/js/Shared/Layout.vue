<template>
    <div class="flex">
        <Nav/>

        <Loader v-if="showLoader" class="my-auto mx-auto" />

        <div class="flex flex-col w-[65%]" v-if="!showLoader">
            <Title />
            <slot />
        </div>

    </div>

</template>

<script setup>
    import { ref } from 'vue';
    import Nav from './Nav.vue';
    import Title from './Title.vue';
    import Loader from './Loader.vue';

    const showLoader = ref(true);

    setTimeout(() => {
        showLoader.value = false;
    }, Math.floor(Math.random() * (800 - 50 + 1)) + 50);

</script>