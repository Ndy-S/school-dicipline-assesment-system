<template>
    <div class="overflow-x-scroll border border-gray-500 sm:rounded-lg my-2">
        <div class="shadow-2xl sm:rounded-lg text-gray-300 p-2">
            <table class="min-w-full divide-y divide-gray-500 table-fixed font-mono">
                <thead class="border-b border-gray-700 rounded-lg">
                    <tr>
                        <th 
                            scope="col" 
                            class="py-3 px-6 w-3/12 text-xs font-semibold tracking-wider text-left text-gray-400 uppercase"
                            v-for="(oneTableHead, index) in tableHead"
                            :key="index"
                        >
                            {{ oneTableHead }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        class="border-b border-gray-700 rounded-lg"
                        v-for="(oneTableData, index) in tablePaginate.data"
                    >
                        <td 
                            class="py-4 px-6 text-m text-gray-300 whitespace-nowrap"
                            v-for="(oneTableHead, index) in tableHead"
                            :key="index"
                        >
                            <div class="flex items-center">
                                <img 
                                    v-if="oneTableHead === 'nama'"
                                    src="https://tecdn.b-cdn.net/img/Photos/Avatars/img%20(23).webp" 
                                    class="w-6 flex mr-4 rounded-full"
                                >
                                <!-- :src="oneTableData['image_path']"  -->
                                <span v-if="oneTableHead === 'aksi'">
                                    <button title="Edit">
                                        <font-awesome-icon :icon="['fas', 'pen-clip']" style="color: paleturquoise;"/>
                                    </button>
                                    &nbsp;
                                    <button title="Delete">
                                        <font-awesome-icon :icon="['fas', 'trash-can']" style="color: darkorange;"/>
                                    </button>
                                </span>
                                <span v-else-if="oneTableHead === 'role'">
                                    {{ 
                                        oneTableData[oneTableHead] == 0 ? 'Admin' :
                                        oneTableData[oneTableHead] == 1 ? 'Siswa/Ortu' :
                                        oneTableData[oneTableHead] == 2 ? 'Guru' :
                                        oneTableData[oneTableHead] == 3 ? 'Kepala Sekolah' :
                                        'Unknown Role'
                                    }}
                                </span>
                                <span v-else class="flex">{{ oneTableData[oneTableHead] }}</span>
                            </div>
                        </td>
                    </tr>
                    
                </tbody>
            </table>

            <div class="flex flex-wrap justify-center mt-8" v-show="tablePaginate.links.length > 3">
                <template v-for="(link, key) in tablePaginate.links" :key="key">
                    <div 
                        v-if="link.url === null" 
                        :key="`${key}-disabled`" 
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-white border rounded"
                        v-html="link.label"
                    />
                    <Link 
                        v-else 
                        :key="key" 
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white hover:text-gray-900 text-gray-200" 
                        :class="{'bg-white text-gray-900': link.active }" 
                        :href="link.url"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        tableHead: Array,
        tableData: Object,
        tablePaginate: Object
    });
</script>