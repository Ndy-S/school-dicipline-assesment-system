<template>
    <Layout :can="can">
        <div class="mx-auto w-11/12 mt-4 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'table']" />
                    <span> Tabel Data Mata Pelajaran</span>
                </h1>

                <ButtonSearch :dataPaginate="mataPelajaranPaginate" :params="params" :openCreateModal="openCreateModal" :canCreate="can.createMataPelajaran"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="mataPelajaranPaginate"
                :tableFilters="filters"
                :params="params"
                :editFunc="editFunc"
                :deleteFunc="deleteFunc"
                :canCreate="can.createMataPelajaran"
            />
        </div>

        <History :historyQuery="historyQuery" historyTitle="Riwayat Perubahan"/>

        <ModalCreateUpdate 
            :createMode="createMode"
            :editMode="editMode"
            :closeCreateEditModal="closeCreateEditModal"
            :createEditModalProps="createEditModalProps"
            :editFunc="editFunc"
            :tempData="tempMataPelajaran"
            :resetFunc="resetFunc"
            :form="form"
            :submit="submit"
        />
        <ModalDelete :deleteMode="deleteMode" :deleteName="deleteName" :deleteToken="deleteKelas" :closeDeleteModal="closeDeleteModal" :submit="submit"/>
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
        mataPelajaranQuery: Object,
        mataPelajaranPaginate: Object,
        filters: Object,
        historyQuery: Object,
        guruQuery: Object,
        can: Object,
    });

    const commonColumns = ['nama', 'kelas', 'guru mata pelajaran', 'tanggal dibuat'];

    const tableProps = {
        tableHead: props.can.createMataPelajaran ? ['aksi', ...commonColumns] : commonColumns,
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
        kelas: '',
        guru_id: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            if (editMode.value === false && deleteMode.value === false) {
                form.post('matapelajaran-create', {
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
                form.post('matapelajaran-update', {
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
                form.delete('matapelajaran-destroy', {
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
            -> Textarea: Boolean (Descriptive Text)
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
            display: 'Nama Mata Pelajaran',
            type: 'text',
            placeholder: 'Matematika',
            maxlength: 32,
            required: true,
        },
        {
            name: 'kelas',
            display: 'Kelas',
            type: 'text',
            placeholder: '5',
            maxlength: '2',
            required: true,
        },
        {
            vueselect: props.guruQuery,
            name: 'guru_id',
            display: 'Guru Mata Pelajaran',
            placeholder: 'Pilih guru',
        }
    ];

    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);
    const tempMataPelajaran = ref(null);
    const editFunc = (mataPelajaran) => {
        editMode.value = true;
        tempMataPelajaran.value = mataPelajaran;

        form.id = mataPelajaran.id;
        form.nama = mataPelajaran.nama;
        form.kelas = mataPelajaran.kelas;
        form.guru_id = mataPelajaran?.guru?.nama;
        
        createMode.value = true;
    };

    const resetFunc = (mataPelajaran) => {
        form.id = mataPelajaran.id;
        form.nama = mataPelajaran.nama;
        form.kelas = mataPelajaran.kelas;
        form.guru_id = mataPelajaran?.guru?.nama ?? '';
    };

    // Delete Modal Code
    const buttonClicked = ref(false);
    const deleteMode = ref(false);
    const deleteName = ref(null);
    const deleteKelas = ref(null);

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row.nama;
        deleteKelas.value = 'Kelas ' + row.kelas;
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>