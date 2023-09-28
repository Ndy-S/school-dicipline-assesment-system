<template>
    <div v-show="createMode" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center font-mono">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-slate-700 w-1/2 md:max-w-screen-lg mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6 shadow">
                <div class="flex justify-between items-center pb-3 mb-10">
                    <font-awesome-icon :icon="['fas', 'toolbox']" class="w-6 h-6 text-white mr-4"/>
                    <p class="text-2xl font-bold text-white">Modal Tambah Data</p>
                    <button 
                        type="button" 
                        class="modal-close cursor-pointer z-50 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center" 
                        @click="closeCreateEditModal"
                    >
                        <font-awesome-icon :icon="['fas', 'xmark']" class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submit" ref="inputForm" enctype="multipart/form-data">
                    <div class="mb-6 flex items-center justify-between">
                        <div 
                            class="h-20 w-20 mx-auto max-w-1/4 rounded-full block bg-center bg-cover bg-gray-700 border border-dashed border-gray-300" 
                            :style="{ 'background-image': `url(${previewImage})` }" 
                            @click="fileInput.click()"
                        >
                        </div>
                        <div class="ml-4 w-4/5">
                            <label for="image_path" class="block mb-2 text-sm font-medium text-gray-300">Foto Profil</label>
                            <input 
                                ref="fileInput" 
                                type="file" 
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

                    <div class="mb-3">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-300" :class="{'text-red-500 font-bold': form.errors.nama}">Nama Pengguna</label>
                        <input 
                            type="text" 
                            id="nama" 
                            v-model="form.nama" 
                            name="nama" 
                            :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.nama}" 
                            class="bg-gray-700 border border-gray-300 text-gray-50 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                            placeholder="Alpha Bobby"
                            maxlength="32"
                            required
                        />
                        <div
                            v-if="form.errors.nama"
                            v-text="form.errors.nama"
                            class="text-red-700 text-sm mt-1"
                        >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="token" class="block mb-2 text-sm font-medium text-gray-300" :class="{'text-red-500 font-bold': form.errors.token}">Token</label>
                        <div class="flex items-center">
                            <button 
                                type="button" 
                                class="bg-neon-custom-color h-full p-2 rounded-l-lg border border-gray-300 text-gray-50 shadow-lg hover:bg-dark-custom-color hover:transition-all hover:text-gray-200 focus:ring-2 focus:ring-blue-500"
                                @click="generateToken"
                            >
                                Generate
                            </button>
                            <input 
                                type="text" 
                                id="token" 
                                v-model="form.token" 
                                name="token" 
                                :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.token}" 
                                class="bg-gray-700 border border-gray-300 text-gray-50 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="4DIG"
                                minlength="4"
                                maxlength="4"
                                readonly
                                required
                            />
                        </div>
                        <div
                            v-if="form.errors.token"
                            v-text="form.errors.token"
                            class="text-red-700 text-sm mt-1"
                        >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-300" :class="{'text-red-500 font-bold': form.errors.password}">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            v-model="form.password" 
                            name="password" 
                            :class="{'text-red-900 placeholder-red-700 border border-red-500': form.errors.password}" 
                            class="bg-gray-700 border border-gray-300 text-gray-50 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                            placeholder="**********"
                            required
                        />
                        <div
                            v-if="form.errors.password"
                            v-text="form.errors.password"
                            class="text-red-700 text-sm mt-1"
                        >
                        </div>
                    </div>

                    <div>
                        <label for="peran" class="block mb-2 text-sm font-medium text-gray-300">Peran</label>
                        <select 
                            id="peran" 
                            v-model="form.peran" 
                            name="peran" 
                            class="bg-gray-700 border border-gray-300 text-gray-50 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                            required
                        >
                            <option value="" selected>Pilih peran</option>
                            <option value="Admin">Admin</option>
                            <option value="Guru">Guru</option>
                            <option value="Orang Tua">Orang Tua</option>
                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                        </select>
                    </div>

                    <div class="flex items-start mt-4 mb-10">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" required>
                        </div>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-200">Saya setuju untuk menginput data ini</label>
                    </div>

                    <button 
                        type="button" 
                        class="text-gray-200 bg-red-700 hover:bg-red-800 focus:ring-2 focus:outline-none focus:ring-red-300 font-semibold rounded-md text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                        @click="!editMode ? resetForm() : editFunc(tempArtLib)"
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
</script>