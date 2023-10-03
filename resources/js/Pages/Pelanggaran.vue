<template>
    <Layout>
        <div class="mx-auto w-11/12 mt-4 min-h-[87.5%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <div class="flex items-center justify-between mb-5">
                
                <h1 class="font-mono text-gray-100 text-3xl font-bold my-2">
                    <font-awesome-icon :icon="['fas', 'table']" />
                    <span> Tabel Data Pelanggaran</span>
                </h1>

                <ButtonSearch :dataPaginate="pelanggaranPaginate" :params="params" :openCreateModal="openCreateModal"/>
            </div>

            <Table 
                :tableHead="tableProps.tableHead" 
                :tablePaginate="pelanggaranPaginate"
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
    });

    const tableProps = {
        tableHead: ['aksi', 'tanggal dibuat'],
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
        sop_id: '',
        deskripsi: '',
        sanksi: '',
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
            placeholder: 'Pilih siswa'
        },
        {
            vueselect: props.SOPQuery,
            name: 'sop_id',
            display: 'Kategori Pelanggaran',
            placeholder: 'Pilih kategori pelanggaran'
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
            vueselect: props.guruQuery,
            name: 'guru_id',
            display: 'Guru',
            placeholder: 'Pilih guru'
        },
        {
            radiobutton: {
                display: 'Jenis Pelanggaran',
                radioname: 'jenis',
                radio1id: 'sekolah',
                radio1display: 'Pelanggaran Sekolah',
                radio2id: 'kelas',
                radio2display: 'Pelanggaran Kelas',
            },
            name: 'jenis',
            radiovueselect: props.mataPelajaranQuery,
            placeholder: 'Pilih mata pelajaran',
            name: 'mata_pelajaran_id',
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

    const deleteFunc = (row) => {
        deleteMode.value = true;
        deleteName.value = row?.siswa?.nama;
        deleteToken.value = row?.sop?.kategori;
        form.id = row.id;
    };

    const closeDeleteModal = () => deleteMode.value = false;

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>