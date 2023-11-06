<template>
    <Layout :can="can">
        <div class="mx-auto w-11/12 mt-4 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'table']" />
                    <span> Tabel Data SOP & Peraturan</span>
                </h1>

                <ButtonSearch :dataPaginate="SOPPaginate" :params="params" :openCreateModal="openCreateModal" :canCreate="can.createSOP"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="SOPPaginate"
                :tableFilters="filters"
                :params="params"
                :editFunc="editFunc"
                :deleteFunc="deleteFunc"
                :canCreate="can.createSOP"
            />
        </div>

        <History :historyQuery="historyQuery" historyTitle="Riwayat Perubahan"/>

        <ModalCreateUpdate 
            :createMode="createMode"
            :editMode="editMode"
            :closeCreateEditModal="closeCreateEditModal"
            :createEditModalProps="createEditModalProps"
            :editFunc="editFunc"
            :tempData="tempSOP"
            :resetFunc="resetFunc"
            :form="form"
            :submit="submit"
        />
        <ModalDelete :deleteMode="deleteMode" :deleteName="deleteName" 
        :deleteToken="deleteToken"
        :closeDeleteModal="closeDeleteModal" :submit="submit"/>
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
        SOPQuery: Object,
        SOPPaginate: Object,
        filters: Object,
        historyQuery: Object,
        can: Object,
    });
    
    const commonColumns = ['kategori', 'deskripsi', 'sanksi', 'tanggal dibuat'];

    const tableProps = {
        tableHead: props.can.createSOP ? ['aksi', ...commonColumns] : commonColumns,
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
        kategori: '',
        deskripsi: '',
        sanksi: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            if (editMode.value === false && deleteMode.value === false) {
                form.post('sop-create', {
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
                form.post('sop-update', {
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
                form.delete('sop-destroy', {
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
            name: 'kategori',
            display: 'Kategori Pelanggaran',
            type: 'text',
            placeholder: 'Pelanggaran Disiplin',
            maxlength: 32,
            required: true,
        },
        {
            textarea: true,
            name: 'deskripsi',
            display: 'Deskripsi Pelanggaran',
            placeholder: 'Pelanggaran berupa...',
        },
        {
            textarea: true,
            name: 'sanksi',
            display: 'Sanksi Pelanggaran',
            placeholder: 'Peringatan terhadap siswa',
        },
    ];

    
    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);
    const tempSOP = ref(null);
    const editFunc = (SOP) => {
        editMode.value = true;
        tempSOP.value = SOP;

        form.id = SOP.id;
        form.kategori = SOP.kategori;
        form.deskripsi = SOP.deskripsi;
        form.sanksi = SOP.sanksi;
        
        createMode.value = true;
    };

    const resetFunc = (SOP) => {
        form.id = SOP.id;
        form.kategori = SOP.kategori;
        form.deskripsi = SOP.deskripsi;
        form.sanksi = SOP.sanksi;
    };


    // Delete Modal Code
    const buttonClicked = ref(false);
    const deleteMode = ref(false);
    const deleteName = ref(null);
    const deleteToken = ref(null);

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row.kategori;
        deleteToken.value = '-';
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>