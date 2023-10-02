<template>
    <div v-show="createMode" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center font-mono">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-slate-700 w-1/2 md:max-w-screen-lg mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6 shadow">
                <form @submit.prevent="submit()" enctype="multipart/form-data">
                    <div class="flex justify-between items-center pb-3 mb-10">
                        <font-awesome-icon :icon="['fas', 'toolbox']" class="w-6 h-6 text-white mr-4"/>
                        <p class="text-2xl font-bold text-white">{{ !editMode ? 'Modal Tambah Data' : 'Modal Ubah Data'}}</p>
                        <button 
                            type="reset"
                            class="modal-close cursor-pointer z-50 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center" 
                            @click="closeCreateEditModal(); disabledResetPass = false"
                        >
                            <font-awesome-icon :icon="['fas', 'xmark']" class="w-5 h-5" />
                        </button>
                    </div>

                    <div v-if="showProfileInput" class="mb-6 flex items-center justify-between">
                        <div 
                            v-if="editMode" 
                            class="h-20 w-20 mx-auto max-w-1/4 rounded-full block bg-center bg-cover bg-gray-700 border border-dashed border-gray-300" 
                            :style="{ 'background-image': form.previewImage == tempData.image_path ? `url(img/${form.previewImage})` : `url(${form.previewImage})` }"
                            @click="fileInput.click()"
                        >
                        </div>
                        <div 
                            v-else="!editMode"
                            class="h-20 w-20 mx-auto max-w-1/4 rounded-full block bg-center bg-cover bg-gray-700 border border-dashed border-gray-300" 
                            :style="{ 'background-image': `url(${form.previewImage})` }"
                            @click="fileInput.click()"
                        >
                        </div>
                        <div class="ml-4 w-4/5">
                            <label for="image_path" class="block mb-2 text-sm font-medium text-gray-300">Foto Profil</label>
                            <input 
                                type="file"
                                ref="fileInput"
                                name="image_path" 
                                @input="pickFile" 
                                id="image_path" 
                                accept="image/*" 
                                :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.image_path}" 
                                class="bg-gray-700 border border-gray-300 text-gray-50  right-0 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            />
                            <div
                                v-if="form.errors.image_path"
                                v-text="form.errors.image_path"
                                class="text-red-700 text-sm mt-1"
                            >
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" v-for="(createEditModalProp, index) in createEditModalProps" :key="index">
                        <label :for=[createEditModalProp.name] class="block mb-2 text-sm font-medium text-gray-300" :class="{'text-red-500 font-bold': form.errors[createEditModalProp.name]}">{{ createEditModalProp.display }}</label>
                            <div :class="{'flex items-center': createEditModalProp.custom}">
                            <button 
                                v-if="createEditModalProp.custom"
                                type="button" 
                                class="bg-neon-custom-color h-full p-2 rounded-l-lg border border-gray-300 text-gray-50 shadow-lg hover:bg-dark-custom-color hover:transition-all hover:text-gray-200 focus:ring-2 focus:ring-blue-500"
                                @click="generateToken"
                            >
                                Generate
                            </button>

                            <select 
                                v-if="createEditModalProp.selection"
                                :id="createEditModalProp.name" 
                                v-model="form[createEditModalProp.name]"
                                :name="createEditModalProp.name"
                                class="bg-gray-700 border border-gray-300 text-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                :required="createEditModalProp.required"
                            >
                                <option value="" selected>{{ createEditModalProp.placeholder }}</option>
                                <option 
                                    v-for="(option, index) in createEditModalProp.selection" 
                                    :value=option
                                    :key="index"
                                >
                                    {{ option }}
                                </option>
                            </select>

                            <v-select  
                                v-else-if="createEditModalProp.vueselect"
                                :options="createEditModalProp.vueselect"
                                :placeholder="createEditModalProp.placeholder"
                                v-model="form[createEditModalProp.name]"
                                label="nama"
                                track-by="id"
                                class="border border-gray-300 text-gray-600 bg-white text-sm focus:ring-blue-500 focus:border-blue-500 block w-full"
                            />

                            <input 
                                v-else-if="editMode == true && createEditModalProp.name == 'password'"
                                type="button" 
                                :value="disabledResetPass ? 'Password Reseted' : 'Reset Password'"
                                class="bg-neon-custom-color h-full w-full p-2 rounded-lg border border-gray-300 text-gray-50 shadow-lg"
                                :class="{'opacity-50': disabledResetPass, 'hover:bg-dark-custom-color hover:transition-all hover:text-gray-200 focus:ring-2 focus:ring-blue-500': !disabledResetPass}"
                                :disabled="disabledResetPass"
                                @click="resetPass()"
                            >                 
                                    
                            <input 
                                v-else
                                :type="createEditModalProp.type"
                                :id="createEditModalProp.name" 
                                v-model="form[createEditModalProp.name]" 
                                :name="createEditModalProp.name" 
                                :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors[createEditModalProp.name], 'uppercase': createEditModalProp.custom}" 
                                class="bg-gray-700 border border-gray-300 text-gray-50 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                :placeholder="createEditModalProp.placeholder"
                                :minlength="createEditModalProp.minlength"
                                :maxlength="createEditModalProp.maxlength"
                                :readonly="createEditModalProp.readonly"
                                :required="createEditModalProp.required"
                            />
                        </div>
                        <div
                            v-if="form.errors[createEditModalProp.name]"
                            v-text="form.errors[createEditModalProp.name]"
                            class="text-red-700 text-sm mt-1"
                        >
                        </div>
                    </div>

                    <div class="flex items-start mt-4 mb-10">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" v-model="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
                        </div>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-200">Saya setuju untuk menginput data ini</label>
                    </div>

                    <button 
                        v-if="editMode"
                        :type="button"
                        class="text-gray-200 bg-red-700 hover:bg-red-800 focus:ring-2 focus:outline-none focus:ring-red-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                        @click="checkbox = false; resetFunc(tempData); disabledResetPass = false;"
                    >
                        Reset
                    </button>
                    <button
                        v-else
                        type="reset"
                        class="text-gray-200 bg-red-700 hover:bg-red-800 focus:ring-2 focus:outline-none focus:ring-red-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                        @click="form.reset()"
                    >
                        Reset
                    </button>

                    <button 
                        type="submit" 
                        class="text-gray-200 bg-green-600 hover:bg-green-700 focus:ring-2 focus:outline-none focus:ring-green-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center ml-2"
                        :disabled="form.processing"
                    >
                        {{ !editMode ? 'Submit' : 'Update' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import "vue-select/dist/vue-select.css";

    const props = defineProps({
        createMode: Boolean, 
        editMode: Boolean,
        closeCreateEditModal: Function,
        showProfileInput: Boolean,
        createEditModalProps: Array,
        editFunc: Function,
        tempData: Object,
        resetFunc: Object,
        form: Object,
        submit: Function,
    });

    // Preview Image
    const fileInput = ref(null);
    const pickFile = (e) => {
        const input = e.target;
        const file = input.files;
        if (file && file[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                props.form.previewImage = e.target.result;
            };
            reader.readAsDataURL(file[0]);
            props.form.image_path = file[0];
        }
    };

    // Generate Token
    const generateToken = () => {
        let token = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            for (let i = 0; i < 4; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            token += characters.charAt(randomIndex);
        }

        props.form.token = token;
    };

    // Reset Password
    const checkbox = ref(false)
    const disabledResetPass = ref(false);
    const resetPass = () => {
        disabledResetPass.value = true;
        props.form.password = props.form.token;
    };

</script>