<template>
        <Layout>
        <div class="mx-10 mt-8 min-h-[85%] p-4 sm:px-6 lg:px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'people-roof']" />
                    <span> Tabel Data User</span>
                </h1>

                <ButtonSearch :userPaginate="userPaginate" :params="params" :openCreateModal="openCreateModal"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="userPaginate"
                :tableFilters="filters"
                :params="params"
                :deleteFunc="deleteFunc"
            />
        </div>

        <!-- Modal Create -->
        <div v-show="createMode" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center font-mono">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-slate-700 w-1/2 md:max-w-screen-lg mx-auto rounded shadow-lg z-50 overflow-y-auto">
                <div class="modal-content py-4 text-left px-6">
                    <div class="flex justify-between items-center pb-3 mb-10">
                        <font-awesome-icon :icon="['fas', 'toolbox']" class="w-6 h-6 text-white mr-4"/>
                        <p class="text-2xl font-bold text-white">Modal Tambah Data</p>
                        <button 
                            type="button" 
                            class="modal-close cursor-pointer z-50 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center" 
                            @click="closeCreateEditModal"
                        >
                            <font-awesome-icon :icon="['fas', 'xmark']" class="w-5 h-5" />
                        </button>
                    </div>

                    <form @submit.prevent="submit" ref="inputForm" enctype="multipart/form-data">
                        <div class="mb-6 flex items-center justify-between">
                            <div 
                                class="h-20 w-20 mx-auto max-w-1/4 rounded-full block bg-center bg-cover bg-neon-custom-color bg-opacity-80 border border-dashed border-gray-300" 
                                :style="{ 'background-image': `url(${previewImage})` }" 
                                @click="fileInput.click()"
                            >
                            </div>
                            <div class="ml-4 w-4/5">
                                <label for="image" class="block mb-2 text-sm font-medium text-gray-200">Foto Profil</label>
                                <input 
                                    ref="fileInput" 
                                    type="file" 
                                    name="image" 
                                    @input="pickFile" 
                                    id="image" 
                                    accept="image/*" 
                                    :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.image_path}" 
                                    class="bg-white border border-gray-300 right-0 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                />
                                <div
                                    v-if="form.errors.image_path"
                                    v-text="form.errors.image_path"
                                    class="text-red-700 text-sm mt-1"
                                >
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-200" :class="{'text-red-500 font-bold': form.errors.nama}">Nama Pengguna</label>
                            <input 
                                type="text" 
                                id="nama" 
                                v-model="form.nama" 
                                name="nama" 
                                :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.nama}" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="Alpha Bobby"
                            />
                            <div
                                v-if="form.errors.nama"
                                v-text="form.errors.nama"
                                class="text-red-700 text-sm mt-1"
                            >
                            </div>
                        </div>

                        <div class="flex items-start mb-6">
                            <div class="flex items-center h-5">
                                <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
                            </div>
                            <label for="remember" class="ml-2 text-sm font-medium text-gray-200">Saya setuju untuk menginput data ini.</label>
                        </div>

                        <button 
                            type="button" 
                            class="text-gray-200 bg-red-700 hover:bg-red-800 focus:ring-2 focus:outline-none focus:ring-red-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            @click="!editMode ? resetForm() : editFunc(tempArtLib)"
                        >
                            Reset
                        </button>

                        <button 
                            type="submit" 
                            class="text-gray-200 bg-green-600 hover:bg-green-700 focus:ring-2 focus:outline-none focus:ring-green-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center ml-2"
                            :disabled="form.processing"
                        >
                            {{ !editMode ? 'Submit' : 'Update' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <ModalDelete :deleteMode="deleteMode" :deleteName="deleteName" :deleteToken="deleteToken" :closeDeleteModal="closeDeleteModal" :submit="submit"/>
        <Notification :successNotification="successNotification" :messageNotification="messageNotification"/>
    </Layout>
</template>

<script setup>
    import { ref, reactive } from 'vue';
    import { useForm } from "@inertiajs/vue3";
    import Layout from '../Shared/Layout.vue';
    import Table from '../Shared/Table.vue';
    import ButtonSearch from '../Shared/ButtonSearch.vue';
    import ModalDelete from '../Shared/ModalDelete.vue';
    import Notification from '../Shared/Notification.vue';


    // Search Input & Sort Filter
    const props = defineProps({
        userQuery: Object,
        userPaginate: Object,
        filters: Object,
    });

    const tableProps = {
        tableHead: ['nama', 'token', 'peran', 'tanggal dibuat', 'aksi'],
    };

    const params = reactive({
        search: props.filters.search,
        field: props.filters.field,
        direction: props.filters.direction,
    });

    // Form Value
    const inputForm = ref(null);
    const form = useForm({
        id: '',
        nama: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            buttonClicked.value = true;

            if (editMode.value === false && deleteMode.value === false) {
                form.post('user-create', {
                    preserveScroll: true,
                    onSuccess: () => {
                        form.reset();
                        inputForm.value.reset();
                        previewImage.value = '';
                        closeCreateEditModal();
                    }
                });
            } else {
                form.delete('user-destroy', {
                    preserveScroll: true,
                    onSuccess: () => {
                        deleteMode.value = false;
                        buttonClicked.value = false;

                        messageNotification.value = 'Data berhasil dihapus'
                        successNotification.value = true;
                        setTimeout(() => {
                            successNotification.value = false;
                        }, 3000);
                    }
                });
            }
        }
    };

    const buttonClicked = ref(false);
    const openCreateModal = () => createMode.value = true;
    const closeCreateEditModal = () => {
        form.reset();
        inputForm.value.reset();
        previewImage.value = '';
        createMode.value = false;
        editMode.value = false;
    }

    // Preview Image
    const previewImage = ref(null);
    const fileInput = ref(null);
    const pickFile = () => {
        const input = fileInput.value;
        const file = input.files;
        if (file && file[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.value = e.target.result;
            };
            reader.readAsDataURL(file[0]);
            form.image_path = file[0];
        }
    };
    
    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);

    // Delete Modal Code
    const deleteMode = ref(false);
    const deleteName = ref(null);
    const deleteToken = ref(null);

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row.nama;
        deleteToken.value = row.token;
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>