<template>
    <Layout>
        <div class="mx-auto w-11/12 mt-4 min-h-[30%] p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono flex">
            <div class="flex items-center justify-center w-fit h-full">
            <img :src="'img/' + (userProfile.image_path || 'default.png')" class="h-48 rounded-lg drop-shadow-lg">
            </div>
            <div class="ml-6 mt-[5%]">
                <p class="text-gray-400">Ditambahkan oleh: <span class="text-gray-300">{{ createdBy?.user?.nama || 'Sistem' }}</span></p>
                <br>
                <p class="text-white text-[35px]">{{ userProfile.nama }}</p>
                <p class="text-gray-300 text-lg">
                    Peran: {{ userProfile.peran }}
                </p>
            </div>
            <div class="ml-6 text-white"></div>
        </div>
        <div class="mx-auto w-11/12 min-h-[54%] mt-8 p-4 px-8 bg-neon-custom-color rounded-lg shadow-2xl font-mono">
            <h2 class="text-2xl font-semibold text-white mb-4">Profil Anda</h2>
            <p class="text-gray-100 text-md mb-3">Token: <span class="font-bold">{{ userProfile.token }}</span></p>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4 relative">
                    <label for="image_path" class="block text-gray-300 text-sm mb-2">Foto Profil</label>
                    <input
                        type="file"
                        name="image_path" 
                        id="image_path"
                        @input="pickFile"
                        accept="image/*" 
                        class="w-full py-2 px-4 rounded-lg text-gray-100 border border-gray-300 bg-slate-700"
                    >
                </div>
                <div class="mb-4 relative">
                    <label for="nama" class="block text-gray-300 text-sm mb-2">Nama</label>
                    <input
                        type="text"
                        name="nama" 
                        id="nama"
                        v-model="form.nama"
                        class="w-full py-2 px-4 rounded-lg text-gray-100 border border-gray-300 bg-slate-700"
                        required
                    >
                </div>
                <div class="mb-12 relative">
                    <label for="password" class="block text-gray-300 text-sm mb-1">Password</label>
                    <div class="flex items-center text-gray-100 mb-2">
                        <input type="radio" id="old" name="passwordType" value="old" class="mr-1" checked @click="showPassword = false; form.password = '';">
                        <label for="old" class="mr-4">Password Lama</label>
                        <input type="radio" id="new" name="passwordType" value="new" class="mr-1" @click="showPassword = true">
                        <label for="new">Password Baru</label>
                    </div>

                    <input
                        type="password"
                        name="password" 
                        id="password"
                        v-model="form.password"
                        class="w-full py-2 px-4 text-gray-100 border border-gray-300 bg-slate-700 rounded-lg"
                        placeholder="Password telah dienkripsi"
                        :required="showPassword"
                        :disabled="!showPassword"
                    >
                </div>

                <div class="text-center">
                    <button
                        type="submit"
                        class="px-6 py-3 rounded-full text-gray-200 bg-green-600 hover:bg-green-700 focus:ring-2 focus:outline-none focus:ring-green-300 focus:bg-green-700"
                        :disabled="form.processing"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>


        <History :historyQuery="historyQuery" historyTitle="Aktivitas Pengguna"/>
        <Notification :successNotification="successNotification" :messageNotification="messageNotification"/>
    </Layout>
</template>

<script setup>
    import { ref } from "vue";
    import { useForm } from "@inertiajs/vue3";
    import Layout from '../Shared/Layout.vue';
    import Notification from '../Shared/Notification.vue';
    import History from '../Shared/History.vue';

    const props = defineProps({
        userProfile: Object,
        historyQuery: Object,
        createdBy: Object,
    });

    const showPassword = ref(false);

    const form = useForm({
        id: props.userProfile.id,
        image_path: props.userProfile.image_path,
        nama: props.userProfile.nama,
        token: props.userProfile.token,
        password: '',
    });

    const pickFile = (e) => {
        const input = e.target;
        const file = input.files;
        if (file && file[0]) {
            const reader = new FileReader();
            reader.readAsDataURL(file[0]);
            form.image_path = file[0];
        }
    };

    const submit = () => {
        form.post('profile-update', {
            preserveScroll: true,
            onSuccess: () => {
                messageNotification.value = 'Data berhasil diubah'
                successNotification.value = true;
                setTimeout(() => {
                    successNotification.value = false;
                }, 3000);
            }
        });
    };

    // Notification
    const successNotification = ref(false);
    const messageNotification = ref('Tidak ada pesan');
</script>