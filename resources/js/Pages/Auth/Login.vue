<template>
    <div class="min-h-screen flex items-center justify-center font-mono">
        <Loader v-if="showLoader" class="absolute my-auto mx-auto" />

        <div class="login-box p-8 bg-opacity-70 rounded-lg bg-neon-custom-color text-white shadow-slate-600 shadow-md" v-if="!showLoader">
            <img src="/img/default.png" class="w-[25%] h-[25%] mx-auto mb-4 rounded-full ">
            <h2 class="text-2xl mb-8 text-center">Login Sistem</h2>
            <form @submit.prevent="login">
                <div class="mb-6 relative">
                    <input v-model="form.token" type="text" name="token" minlength="4" maxlength="4" required class="input">
                    <label class="label">Token</label>
                </div>
                <div class="mb-6 relative">
                    <input v-model="form.password" type="password" name="password" required class="input">
                    <label class="label">Password</label>
                </div>
                <div v-if="form.errors.error" class="text-red-500 text-sm mb-4">
                    {{ form.errors.error }}
                </div>
                <button class="w-full py-3 px-4 mt-8 rounded-full border hover:bg-dark-custom-color hover:opacity-90 hover:transition-all focus:outline-none focus:bg-dark-custom-color text-white">
                    Log In
                </button>
            </form>
        </div>
    </div>
</template>
  
<script setup>
    import { ref } from "vue";
    import { useForm } from "@inertiajs/vue3";
    import Loader from '../../Shared/Loader.vue';

    const form = useForm({
        token: '',
        password: '',
    });

    const showLoader = ref(false);
    
    const login = () => {
        showLoader.value = true;

        form.post('login', {
            onSuccess: () => {
            },
            onError: () => {
                showLoader.value = false;
            },
        });
    };
</script>
  
<style scoped>
    .login-box {
        width: 400px;
    }
    
    .input {
        width: 100%;
        padding: 10px 0;
        font-size: 16px;
        color: #fff;
        border: none;
        border-bottom: 1px solid #fff;
        outline: none;
        background: transparent;
    }
    
    .label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 10px 0;
        font-size: 16px;
        color: #fff;
        pointer-events: none;
        transition: .5s;
    }
    
    .input:focus ~ .label,
    .input:valid ~ .label {
        top: -25px;
        left: 0;
        color: #03e9f4;
        font-size: 14px;
    }
</style>
  