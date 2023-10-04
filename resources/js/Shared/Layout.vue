<template>
    <div class="flex">
        <Nav :can="can" />

        <div class="flex flex-col w-[65%]" v-if="!showLoader">
            <Title />
            <slot />
        </div>
    </div>
</template>

<script setup>
    import Nav from './Nav.vue';
    import Title from './Title.vue';

    const props = defineProps({
        can: Object,
    });
</script>