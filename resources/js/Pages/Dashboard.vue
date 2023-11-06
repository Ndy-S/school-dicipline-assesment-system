<template>
    <Layout :can="can">
        <div class="mx-auto w-[90%] flex h-[45%] justify-between">
            <div class="w-[45%] mt-4 p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono flex flex-col justify-center items-center">
                <div class="text-white">
                    <h5 class="text-2xl font-bold mb-3 text-center">Jadwal Piket: <span class="text-yellow-400 underline">{{ currentDay }}</span></h5>
                    <table class="table-auto m-auto">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Nama</th>
                                <th class="border px-4 py-2">NIP</th>
                                <th class="border px-4 py-2">Token Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="guru in guruPiketQuery">
                                <td class="border px-4 py-2">{{ guru.nama }}</td>
                                <td class="border px-4 py-2">{{ guru.nip }}</td>
                                <td class="border px-4 py-2">{{ guru?.user?.token ?? 'No Account' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-[45%] mt-4 p-4 px-8 gap-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono flex flex-col justify-center items-center" v-if="can.createPelanggaran">
                <div class="text-white items-center mx-auto justify-center flex">
                    <h1 class="text-4xl font-bold text-center">Pelanggaran Siswa</h1>
                </div>
                <button 
                    class="flex items-center justify-center mx-auto h-fit px-4 py-2 rounded-l-md text-gray-100 border border-gray-500 hover:bg-white hover:text-black hover:transition-all"
                    @click="openCreateModal"  
                >
                    <font-awesome-icon :icon="['fas', 'circle-plus']" class="mr-2"/>
                    Tambah Pelanggaran
                </button>
            </div>
            <div class="w-[45%] mt-4 p-4 px-8 gap-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono" v-else-if="$page.props.auth.user.peran == 'Orang Tua'">
                <div class="text-white">
                    <h1 class="text-lg font-bold text-center mb-4">Pelanggaran Siswa {{ pelanggaranSiswaQuery[0]?.siswa?.nama ? ' ('+pelanggaranSiswaQuery[0]?.siswa?.nama+') ' : ''  }}</h1>
                    <table class="table-auto m-auto">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">No</th>
                                <th class="border px-4 py-2">Jenis</th>
                                <th class="border px-4 py-2">Deskripsi</th>
                                <th class="border px-4 py-2">Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pelanggaran, index) in pelanggaranSiswaQuery">
                                <td class="border px-4 py-2">{{ index + 1 }}</td>
                                <td class="border px-4 py-2">{{ pelanggaran?.jenis }}</td>
                                <td class="border px-4 py-2">{{ pelanggaran?.deskripsi }}</td>
                                <td class="border px-4 py-2">{{ pelanggaran.sanksi }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-[45%] mt-4 p-4 px-8 gap-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono" v-else-if="$page.props.auth.user.peran == 'Admin'"> 
                <div class="text-white">
                    <h1 class="text-2xl font-bold text-center mb-4">Total Data</h1>
                    <table class="table-auto m-auto w-full">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Tipe Data</th>
                                <th class="border px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-4 py-2">Siswa</td>
                                <td class="border px-4 py-2 text-center">{{ siswaQuery.length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Guru</td>
                                <td class="border px-4 py-2 text-center">{{ guruQuery.length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Mata Pelajaran</td>
                                <td class="border px-4 py-2 text-center">{{ mataPelajaranQuery.length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">SOP</td>
                                <td class="border px-4 py-2 text-center">{{ SOPQuery.length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Pelanggaran</td>
                                <td class="border px-4 py-2 text-center">{{ pelanggaranQuery.length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">User</td>
                                <td class="border px-4 py-2 text-center">{{ userQuery.length }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mx-auto w-[90%] flex h-[45%] justify-between">
            <div class="w-full mt-4 p-4 px-8 gap-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono" v-if="$page.props.auth.user.peran == 'Admin' || $page.props.auth.user.peran == 'Kepala Sekolah'">
                <div class="text-white ">
                    <h1 class="text-2xl font-bold text-center mb-4">Jumlah Pengguna</h1>
                    <table class="table-auto m-auto w-full">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Peran</th>
                                <th class="border px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-4 py-2">Orang Tua</td>
                                <td class="border px-4 py-2 text-center">{{ userQuery.filter(user => user.peran === 'Orang Tua').length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Guru</td>
                                <td class="border px-4 py-2 text-center">{{ userQuery.filter(user => user.peran === 'Guru').length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Admin</td>
                                <td class="border px-4 py-2 text-center">{{ userQuery.filter(user => user.peran === 'Admin').length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2">Kepala Sekolah</td>
                                <td class="border px-4 py-2 text-center">{{ userQuery.filter(user => user.peran === 'Kepala Sekolah').length }}</td>
                            </tr>
                            <tr>
                                <td class="border px-4 py-2 font-bold">Total Pengguna</td>
                                <td class="border px-4 py-2 text-center font-bold">{{ userQuery.length }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <History :historyQuery="historyQuery" historyTitle="Aktivitas Sistem"/>

        <ModalCreateUpdate 
            :createMode="createMode"
            :closeCreateEditModal="closeCreateEditModal"
            :createEditModalProps="createEditModalProps"
            :resetFunc="resetFunc"
            :form="form"
            :submit="submit"
        />

        <Notification :successNotification="successNotification" :messageNotification="messageNotification"/>
    </Layout>

</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useForm } from "@inertiajs/vue3";
    import Layout from '../Shared/Layout.vue';
    import Notification from '../Shared/Notification.vue';
    import History from '../Shared/History.vue';
    import ModalCreateUpdate from '../Shared/ModalCreateUpdate.vue';

    const props = defineProps({
        historyQuery: Object,
        guruQuery: Object,
        guruPiketQuery: Object,
        siswaQuery: Object,
        mataPelajaranQuery: Object,
        SOPQuery: Object,
        pelanggaranQuery: Object,
        pelanggaranSiswaQuery: Object,
        userQuery: Object,
        can: Object,
    });

    console.log(props.pelanggaranSiswaQuery)
    const form = useForm({
        id: '',
        siswa_id: '',
        s_o_p_id: '',
        deskripsi: '',
        sanksi: '',
        guru_id: '',
        jenis: '',
        mata_pelajaran_id: '',
        bukti_path: '',
    });

    const submit = () => {
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
    };

    const createMode = ref(false);

    const openCreateModal = () => createMode.value = true;
    const closeCreateEditModal = () => {
        form.reset();
        createMode.value = false;
    };

    const resetFunc = (pelanggaran) => {
        form.id = pelanggaran.id;
        form.siswa_id = pelanggaran?.siswa?.nama;
        form.s_o_p_id = pelanggaran?.s_o_p?.kategori;
        form.deskripsi = pelanggaran.deskripsi;
        form.sanksi = pelanggaran.sanksi;
        form.guru_id = pelanggaran?.guru?.nama;
        form.jenis = pelanggaran.jenis;
        form.mata_pelajaran_id = pelanggaran?.mata_pelajaran?.nama ?? '';
        form.bukti_path = pelanggaran.bukti_path;
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

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');

    const currentDay = ref('');
    onMounted(() => {
        const date = new Date();
        const options = { weekday: 'long' };
        currentDay.value = new Intl.DateTimeFormat('id-ID', options).format(date);
    });
</script>