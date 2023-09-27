<template>
    <div class="flex">
        <Nav/>
        <div class="flex flex-col w-[65%]">
            <Title />
            <div class="mx-10 mt-8 p-4 sm:px-6 lg:px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
                <div class="flex items-center justify-between">
                    
                    <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                        <font-awesome-icon :icon="['fas', 'people-roof']" />
                        <span> Tabel Data User</span>
                    </h1>

                    <div class="w-fit flex items-center">
                        <button class="flex items-center justify-center px-4 py-2 rounded-l-md text-gray-100 border border-gray-500 hover:bg-white hover:text-black hover:transition-all  focus:bg-white focus:text-black">
                            <font-awesome-icon :icon="['fas', 'circle-plus']" class="mr-2"/>
                            Tambah Data
                        </button>
                        <input 
                            type="search" 
                            aria-label="Search" 
                            placeholder="Search..." 
                            class="flex-grow p-2 rounded-r-md border border-gray-500"
                        />
                    </div>
                </div>

                <Table :tableHead="tableProps.tableHead" :tableData="props.userQuery" :tablePaginate="userPaginate"/>
            </div>

        </div>
    </div>
</template>

<script setup>
    import Nav from '../Shared/Nav.vue';
    import Title from '../Shared/Title.vue';
    import Table from '../Shared/Table.vue';

    let props = defineProps({
        userQuery: Object,
        userPaginate: Object,
    });

    const tableProps = {
        tableHead: ['nama', 'token', 'role', 'aksi'],
    };

</script>
