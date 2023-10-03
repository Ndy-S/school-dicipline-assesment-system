<template>
    <Layout>
        <div class="mx-auto w-11/12 mt-4 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'people-roof']" />
                    <span> Tabel Data Siswa</span>
                </h1>

                <ButtonSearch :dataPaginate="siswaPaginate" :params="params" :openCreateModal="openCreateModal"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="siswaPaginate"
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
            :createEditModalProps="createEditModalProps"
            :editFunc="editFunc"
            :tempData="tempSiswa"
            :resetFunc="resetFunc"
            :form="form"
            :submit="submit"
        />
        <ModalDelete :deleteMode="deleteMode" :deleteName="deleteName" :deleteToken="deleteToken" :deleteNoInduk="deleteNIS" :closeDeleteModal="closeDeleteModal" :submit="submit"/>
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
        siswaQuery: Object,
        siswaPaginate: Object,
        filters: Object,
        historyQuery: Object,
        userQuery: Object,
    });

    const tableProps = {
        tableHead: ['aksi', 'nama', 'nis', 'kelas', 'gender', 'agama', 'alamat', 'handphone', 'orang tua', 'tanggal dibuat'],
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
        nama: '',
        nis: '',
        kelas: '',
        gender: '',
        agama: '',
        alamat: '',
        handphone: '',
        user_id: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            if (editMode.value === false && deleteMode.value === false) {
                form.post('siswa-create', {
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
                form.post('siswa-update', {
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
                form.delete('siswa-destroy', {
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

    const createEditModalProps = [
        /*
        Object Notes:
            -> Custom: Boolean (Option for Custom Input for Generate Token)
            -> Selection: Array (Option Value Array for Selection)
            -> Vueselect: Array (Option Value Array for Vue-Selection)
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
            display: 'Nama Siswa',
            type: 'text',
            placeholder: 'Alpha Bobby',
            maxlength: 32,
            required: true,
        },
        {
            name: 'nis',
            display: 'Nomor Induk Siswa (NIS)',
            type: 'number',
            placeholder: '213910',
            maxlength: 32,
            required: true
        },
        {
            name: 'kelas',
            display: 'Kelas',
            type: 'string',
            placeholder: '5',
            minlength: 1,
            maxlength: 2,
            required: true,
        },
        {
            selection: [
                'Laki-laki',
                'Perempuan',
            ],
            name: 'gender',
            display: 'Gender/Jenis Kelamin',
            placeholder: 'Pilih jenis kelamin',
            required: true,
        },
        {
            name: 'agama',
            display: 'Agama',
            type: 'string',
            placeholder: 'Hindu',
        },
        {
            vueselect: props.userQuery,
            name: 'user_id',
            display: 'Orang Tua',
            placeholder: 'Pilih orang tua'
        },
        {
            name: 'alamat',
            display: 'Alamat',
            type: 'string',
            placeholder: 'Jalan Pegangsaan Timur No. 56'
        },
        {
            name: 'handphone',
            display: 'No. Handphone',
            type: 'string',
            placeholder: '0821 xxxx xxxx'
        }
    ];

    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);
    const tempSiswa = ref(null);
    const editFunc = (siswa) => {
        editMode.value = true;
        tempSiswa.value = siswa;

        form.id = siswa.id;
        form.nama = siswa.nama;
        form.nis = siswa.nis;
        form.kelas = siswa.kelas;
        form.gender = siswa.gender;
        form.agama = siswa.agama;
        form.alamat = siswa.alamat;
        form.handphone = siswa.handphone;
        form.user_id = siswa?.user?.nama;

        createMode.value = true;
    };

    const resetFunc = (siswa) => {
        form.id = siswa.id;
        form.nama = siswa.nama;
        form.nis = siswa.nis;
        form.kelas = siswa.kelas;
        form.gender = siswa.gender;
        form.agama = siswa.agama ?? '';
        form.alamat = siswa.alamat ?? '';
        form.handphone = siswa.handphone ?? '';
        form.user_id = siswa?.user?.nama ?? '';
    };

    // Delete Modal Code
    const buttonClicked = ref(false);
    const deleteMode = ref(false);
    const deleteName = ref(null);
    const deleteToken = ref(null);
    const deleteNIS = ref(null);

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row.nama;
        deleteToken.value = row?.user?.token;
        deleteNIS.value = row.nis;
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>