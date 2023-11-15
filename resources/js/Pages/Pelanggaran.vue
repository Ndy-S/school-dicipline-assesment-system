<template>
    <Layout :can="can">
        <div class="mx-auto w-11/12 mt-4 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'table']" />
                    <span> Tabel Data Pelanggaran</span>
                </h1>

                <ButtonSearch :dataPaginate="pelanggaranPaginate" :params="params" :openCreateModal="openCreateModal" :canCreate="can.createPelanggaran"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="pelanggaranPaginate"
                :tableFilters="filters"
                :params="params"
                :editFunc="editFunc"
                :deleteFunc="deleteFunc"
                :canCreate="can.createPelanggaran"
            />
        </div>

        <History :historyQuery="historyQuery" historyTitle="Riwayat Perubahan"/>

        <ModalCreateUpdate 
            :createMode="createMode"
            :editMode="editMode"
            :closeCreateEditModal="closeCreateEditModal"
            :createEditModalProps="createEditModalProps"
            :editFunc="editFunc"
            :tempData="tempPelanggaran"
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
        pelanggaranQuery: Object,
        pelanggaranPaginate: Object,
        filters: Object,
        historyQuery: Object,
        guruQuery: Object,
        siswaQuery: Object,
        mataPelajaranQuery: Object,
        SOPQuery: Object,
        can: Object,
    });

    const commonColumns = [ 'siswa', 'kategori pelanggaran', 'deskripsi', 'sanksi', 'tglPelanggaran', 'guru', 'jenis', 'mata pelajaran', 'bukti dokumentasi' ,'tanggal dibuat'];

    const tableProps = {
        tableHead: props.can.createPelanggaran ? ['aksi', ...commonColumns] : commonColumns,
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
        siswa_id: '',
        s_o_p_id: '',
        deskripsi: '',
        sanksi: '',
        tglPelanggaran: '',
        guru_id: '',
        jenis: '',
        mata_pelajaran_id: '',
        bukti_path: '',
    });

    const submit = () => {
        if (!buttonClicked.value) {
            if (editMode.value === false && deleteMode.value === false) {
                form.post('pelanggaran-create', {
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
                form.post('pelanggaran-update', {
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
                form.delete('pelanggaran-destroy', {
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
            vueselect: props.siswaQuery,
            name: 'siswa_id',
            display: 'Siswa',
            placeholder: 'Pilih siswa',
            required: true,
        },
        {
            vueselect: props.SOPQuery,
            name: 's_o_p_id',
            display: 'Kategori Pelanggaran',
            placeholder: 'Pilih kategori pelanggaran',
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
        {
            name: 'tglPelanggaran',
            display: 'Tanggal Pelanggaran',
            type: 'string',
            placeholder: '20 Januari 2023',
        },
        {
            vueselect: props.guruQuery,
            name: 'guru_id',
            display: 'Guru',
            placeholder: 'Pilih guru',
            required: true,
        },
        {
            radiobutton: {
                display: 'Jenis Pelanggaran',
                radioname: 'jenis',
                radio1id: 'Sekolah',
                radio1display: 'Pelanggaran Sekolah',
                radio2id: 'Kelas',
                radio2display: 'Pelanggaran Kelas',
            },
            name: 'jenis',
            radiovueselect: props.mataPelajaranQuery,
            placeholder: 'Pilih mata pelajaran',
            name: 'mata_pelajaran_id',
        },
        {
            name: 'bukti_path',
            display: 'Bukti Dokumentasi',
        }
    ];

    // Create Modal Code
    const createMode = ref(false);

    // Edit Modal Code
    const editMode = ref(false);
    const tempPelanggaran = ref(null);
    const editFunc = (pelanggaran) => {
        editMode.value = true;
        tempPelanggaran.value = pelanggaran;

        form.id = pelanggaran.id;
        form.siswa_id = pelanggaran?.siswa?.nama;
        form.s_o_p_id = pelanggaran?.s_o_p?.kategori;
        form.deskripsi = pelanggaran.deskripsi;
        form.sanksi = pelanggaran.sanksi;
        form.tglPelanggaran = pelanggaran.tglPelanggaran;
        form.guru_id = pelanggaran?.guru?.nama;
        form.jenis = pelanggaran.jenis;
        form.mata_pelajaran_id = pelanggaran?.mata_pelajaran?.nama ?? '';
        form.bukti_path = pelanggaran.bukti_path;

        createMode.value = true;
    };

    const resetFunc = (pelanggaran) => {
        form.id = pelanggaran.id;
        form.siswa_id = pelanggaran?.siswa?.nama;
        form.s_o_p_id = pelanggaran?.s_o_p?.kategori;
        form.deskripsi = pelanggaran.deskripsi;
        form.sanksi = pelanggaran.sanksi;
        form.tglPelanggaran = pelanggaran.tglPelanggaran;
        form.guru_id = pelanggaran?.guru?.nama;
        form.jenis = pelanggaran.jenis;
        form.mata_pelajaran_id = pelanggaran?.mata_pelajaran?.nama ?? '';
        form.bukti_path = pelanggaran.bukti_path;
    };

    // Delete Modal Code
    const buttonClicked = ref(false);
    const deleteMode = ref(false);
    const deleteName = ref(null);
    const deleteToken = ref(null);

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row?.siswa?.nama;
        deleteToken.value = row?.s_o_p?.kategori;
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>