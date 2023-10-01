<template>
    <Layout>
        <div class="mx-auto w-11/12 mt-8 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'people-roof']" />
                    <span> Tabel Data User</span>
                </h1>

                <ButtonSearch :dataPaginate="userPaginate" :params="params" :openCreateModal="openCreateModal"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="userPaginate"
                :tableFilters="filters"
                :params="params"
                :editFunc="editFunc"
                :deleteFunc="deleteFunc"
            />
        </div>

        <History :historyQuery="historyQuery" historyTitle="Riwayat Perubahan"/>

        <ModalCreateUpdate 
            :createMode="createMode"
            :editMode="editMode"
            :closeCreateEditModal="closeCreateEditModal"
            :showProfileInput="showProfileInput"
            :createEditModalProps="createEditModalProps"
            :editFunc="editFunc"
            :tempUser="tempUser"
            :resetFunc="resetFunc"
            :form="form"
            :submit="submit"
        />
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
    import History from '../Shared/History.vue';


    const props = defineProps({
        userQuery: Object,
        userPaginate: Object,
        filters: Object,
        historyQuery: Object,
    });

    const tableProps = {
        tableHead: ['nama', 'token', 'peran', 'tanggal dibuat', 'aksi'],
    };

    // Search Input & Sort Filter
    const params = reactive({
        search: props.filters.search,
        field: props.filters.field,
        direction: props.filters.direction,
    });

    // Form Value
    const form = useForm({
        id: '',
        token: '',
        password: '',
        peran: '',
        nama: '',
        image_path: '',
        previewImage: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            if (editMode.value === false && deleteMode.value === false) {
                form.post('user-create', {
                    preserveScroll: true,
                    onSuccess: () => {
                        form.reset();
                        closeCreateEditModal();

                        messageNotification.value = 'Data berhasil ditambah'
                        successNotification.value = true;
                        setTimeout(() => {
                            successNotification.value = false;
                        }, 3000);
                    }
                });
            } else if (editMode.value) {
                form.post('user-update', {
                    preserveScroll: true,
                    onSuccess: () => {
                        form.reset();
                        closeCreateEditModal();

                        messageNotification.value = 'Data berhasil diubah'
                        successNotification.value = true;
                        setTimeout(() => {
                            successNotification.value = false;
                        }, 3000);
                    }
                });
            } else {
                buttonClicked.value = true;
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
        createMode.value = false;
        editMode.value = false;
    };
    
    const showProfileInput = ref(true);
    const createEditModalProps = [
        /*
        Object Notes:
            -> Custom: Boolean (Option for Custom Input for Generate Token)
            -> Selection: Array (Option Value Array for Selection)
            -> Name: String (Input Name for Submit)
            -> Display: String (Text to Display)
            -> Type: String (Type of the Input)
            -> Placeholder: String (Input Placeholder)
            -> MinLength: Number (Min Input Length)
            -> MaxLength: Number (Max Input Length)
            -> ReadOnly: Boolean (Readonly Input)
            -> Required: Boolean (Input Required)
        */
        {
            name: 'nama',
            display: 'Nama Pengguna',
            type: 'text',
            placeholder: 'Alpha Bobby',
            maxlength: 32,
            required: true,
        },
        {
            custom: true,
            name: 'token',
            display: 'Token',
            type: 'text',
            placeholder: '4DIG',
            minlength: 4,
            maxlength: 4,
            required: true,
        },
        {
            name: 'password',
            display: 'Password',
            type: 'password',
            placeholder: 'Optional',
            maxlength: 32,
            required: false,
        },
        {
            selection: [
                'Admin',
                'Guru',
                'Orang Tua',
                'Kepala Sekolah',
            ],
            name: 'peran',
            display: 'Peran',
            placeholder: 'Pilih Peran',
            required: true,
        }
    ];

    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);
    const tempUser = ref(null);
    const editFunc = (user) => {
        editMode.value = true;
        tempUser.value = user;

        form.id = user.id;
        form.token = user.token;
        form.password = user.password;
        form.peran = user.peran;
        form.nama = user.nama;
        form.image_path = user.image_path;
        form.previewImage = user.image_path;

        createMode.value = true;
    };

    const resetFunc = (user) => {
        form.id = user.id;
        form.token = user.token;
        form.password = user.password;
        form.peran = user.peran;
        form.nama = user.nama;
        form.previewImage = user.image_path;
    };

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