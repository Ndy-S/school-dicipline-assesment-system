<template>
    <div v-show="deleteMode === true" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center font-mono">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container md:max-w-screen-lg mx-auto shadow-lg z-50 overflow-y-auto">
            <div class="relative w-full max-w-md mx-auto">
                <div class="bg-slate-700 rounded-sm shadow">
                    <button
                        type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center"
                        @click="closeDeleteModal"
                    >
                        <font-awesome-icon :icon="['fas', 'xmark']" class="w-5 h-5" />
                        <span class="sr-only">Close modal</span>
                    </button>

                    <form @submit.prevent="submit">
                        <div class="p-6 text-center">
                            <font-awesome-icon :icon="['fas', 'circle-exclamation']" class="mx-auto mb-4 text-red-400 w-14 h-14" />
                            <h3 class="mb-5 text-lg font-mono text-gray-300">Apakah kamu yakin ingin menghapus <b class="text-yellow-500">{{ deleteName }} ({{ deleteToken }})</b>?</h3>

                            <button
                                type="submit"
                                class="text-gray-300 bg-red-700 hover:bg-red-600 focus:bg-red-600 focus:ring-1 focus:ring-white font-semibold rounded-md text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                            >
                                Ya, saya yakin!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        deleteMode: Boolean,
        deleteName: String,
        deleteToken: String,
        closeDeleteModal: Function,
        submit: Function,
    });
</script>