<template>
    <div class="w-fit flex items-center">
        <button 
            class="flex items-center justify-center px-4 py-2 rounded-l-md text-gray-100 border border-gray-500 hover:bg-white hover:text-black hover:transition-all"
            @click="openCreateModal"    
        >
            <font-awesome-icon :icon="['fas', 'circle-plus']" class="mr-2"/>
            Tambah Data
        </button>
        <input 
            type="search" 
            v-model="params.search"
            aria-label="Search" 
            placeholder="Search..." 
            class="flex-grow p-2 rounded-r-md border border-gray-500"
        />
    </div>
</template>

<script setup>
    import { watch } from 'vue';
    import { debounce } from 'lodash/function';
    import { router } from "@inertiajs/vue3";

    const props = defineProps({
        userPaginate: Object,
        params: Object,
        openCreateModal: Function,
    });

    const params = props.params;

    const handler = debounce(() => {
        const filterObject = (obj) => {
            const filtered = {};
            for (const key in obj) {
                if (obj[key] !== undefined && obj[key] !== null) {
                    filtered[key] = obj[key];
                }
            }
            return filtered;
        };

        const filteredParams = filterObject(params);

        router.get(props.userPaginate.first_page_url, filteredParams, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        }); 
    }, 150);

    watch(() => params, handler, {deep: true});
</script>