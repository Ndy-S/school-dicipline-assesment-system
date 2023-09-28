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
        <ModalCreateUpdate />
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
    import ModalCreateUpdate from '../Shared/ModalCreateUpdate.vue';
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
        token: '',
        password: '',
        peran: '',
        nama: '',
        image_path: '',
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

    const openCreateModal = () => createMode.value = true;
    const closeCreateEditModal = () => {
        form.reset();
        inputForm.value.reset();
        previewImage.value = '';
        createMode.value = false;
        editMode.value = false;
    };

    const resetForm = () => {
        form.reset(); 
        inputForm.value.reset(); 
        previewImage = '';
    };

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

    const generateToken = () => {
        let token = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            for (let i = 0; i < 4; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            token += characters.charAt(randomIndex);
        }

        form.token = token;
    };

    // Edit Modal Code
    const editMode = ref(false);

    // Delete Modal Code
    const buttonClicked = ref(false);
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