<template>
        <div class="right-5 my-6 h-[95%] w-[21.5%] fixed font-mono text-white bg-neon-custom-color overflow-hidden hover:overflow-auto ">
            <div class="text-2xl uppercase h-fit w-full p-8 text-center border-b-2 border-b-gray-500 font-bold text-gray-200 mb-2">
                {{ historyTitle }}
                <span>
                    <font-awesome-icon :icon="['fas', 'timeline']" />
                </span>
            </div>

            <div v-if="historyQuery.length" v-for="(oneHistoryData, index) in historyQuery" :key="index">      
                <div v-if="$page.props.auth.user.token == oneHistoryData?.user?.token"  class="p-4 w-full h-auto flex items-end justify-end">
                    <div class="bg-neutral-600 rounded-md w-[65%] p-3">
                        <p class="text-sm text-yellow-400 font-bold">{{ oneHistoryData?.user?.nama ?? 'Deleted Account' }}</p>
                        <p class="text-xs text-yellow-500 font-bold">{{ oneHistoryData?.user?.peran ?? 'Unknown' }}</p>
                        
                        <div class="text-wrap break-words text-gray-200 my-2 text-justify text-sm">
                            <span>Perubahan pada tabel {{ oneHistoryData.nama_tabel }}, data <b class="text-yellow-500">{{ oneHistoryData.nama_data }} ({{ oneHistoryData.token_data }})</b> telah di{{ oneHistoryData.jenis }}.</span>
                        </div>
                        
                        <p class="text-sm text-right text-gray-300">
                            <sub class="bottom-0 right-0">
                                {{ formatDateTime(oneHistoryData.created_at) }}
                            </sub>
                        </p>
                    </div>
                    
                    <img :src="'img/' + (oneHistoryData?.user?.image_path || 'default.png')" class="w-12 h-12 mx-2 my-auto rounded-full">
                </div>
                <div v-else class="p-4 w-full h-auto flex items-start">
                    <img :src="'img/' + (oneHistoryData?.user?.image_path || 'default.png')" class="w-12 h-12 mx-2 my-auto rounded-full">
                    <div class="bg-slate-600 rounded-md w-[65%] p-3">
                        <p class="text-sm text-yellow-400 font-bold">{{ oneHistoryData?.user?.nama ?? 'Akun Tidak Tersedia' }}</p>
                        <p class="text-xs text-yellow-500 font-bold">{{ oneHistoryData?.user?.peran ?? 'Tidak Ada' }}</p>
                        
                        <div class="text-wrap break-words text-gray-200 my-2 text-justify text-sm">
                            <span>Perubahan pada tabel {{ oneHistoryData.nama_tabel }}, data <b class="text-yellow-500">{{ oneHistoryData.nama_data }} ({{ oneHistoryData.token_data }})</b> telah di{{ oneHistoryData.jenis }}.</span>
                        </div>
                        
                        <p class="text-sm text-right text-gray-300">
                            <sub class="bottom-0 right-0">
                                {{ formatDateTime(oneHistoryData.created_at) }}
                            </sub>
                        </p>
                    </div>
                </div>     
            </div>
            <div v-else class="text-gray-300 h-[80%] w-full text-center flex flex-col justify-center items-center">
                <font-awesome-icon :icon="['fas', 'bell']" class="w-16 h-16 mb-8"/>
                <p>Riwayat tidak ditemukan!</p>
            </div>

        </div>
</template>

<script setup>
    const props = defineProps({
        historyQuery: Object,
        historyTitle: String,
    });

    const formatDateTime = (timestamp) => {
        const date = new Date(timestamp);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}-${month}-${year} - ${hours}:${minutes}`;
    }
</script>