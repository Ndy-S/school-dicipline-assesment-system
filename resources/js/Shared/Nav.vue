<template>
    <nav class="left-0 top-0 h-screen w-60 bg-neon-custom-color shadow-2xl overflow-hidden">
        <div>
            <div id="header-content" class="pl-4 flex items-center my-10">
                <img
                    :src="'img/' + $page.props.auth.user.image_path"
                    alt="Avatar"
                    class="mr-3 w-14 h-14 rounded-full border-2 border-gray-400 align-middle p-0.5"
                />
                <div>
                    <h4 class="font-mono text-xl text-yellow-400 font-medium leading-[1.2] pr-2">{{ $page.props.auth.user.nama }}</h4>
                    <p class="font-mono text-yellow-500">{{ $page.props.auth.user.peran }}</p>
                </div>
            </div>

            <hr class="border-gray-700"/>
        </div>
        <div class="my-4 px-4">
            <ul
                class="relative m-0 list-none"
                data-te-sidenav-menu-ref
            >
                <NavMenu 
                    menuName="Dashboard"
                    menuLink="dashboard"
                    menuIcon="chart-line"
                />
                <NavMenu 
                    menuName="Siswa"
                    menuLink="siswa"
                    menuIcon="graduation-cap"
                />
                <NavMenu 
                    menuName="Guru"
                    menuLink="guru"
                    menuIcon="chalkboard-user"
                />
                <NavMenu 
                    menuName="Mata Pelajaran"
                    menuLink="matapelajaran"
                    menuIcon="book"
                />
                <NavMenu 
                    menuName="SOP & Peraturan"
                    menuLink="sop"
                    menuIcon="scale-balanced"
                />
                <NavMenu 
                    menuName="Pelanggaran Siswa"
                    menuLink="pelanggaran"
                    menuIcon="person-circle-exclamation"
                />
                <NavMenu
                    menuName="Data User"
                    menuLink="user"
                    menuIcon="id-card-clip"
                />
            </ul>
        </div>
        <hr class="border-gray-700"/>
        <ul class="relative m-0 list-none my-4 px-4">
            <NavMenu
                menuName="Profile"
                menuLink="profile"
                menuIcon="user"
            />
            <NavMenu
                menuName="Log out"
                menuLink="logout"
                method="POST"
                menuIcon="person-walking-arrow-right"
            />
        </ul>
        <div class="absolute bottom-0 left-0 h-32 w-60 text-center">
            <hr class="mb-4 border-gray-700">

            <p class="font-mono text-gray-300 text-lg font-semibold">Kelompok 1</p>

            <span class="flex justify-center">
                <img src="/img/nav_chara.gif" style="max-width: 80px;">
            </span>
        </div>
    </nav>
</template>

<script setup>
    import NavMenu from '../Shared/NavMenu.vue';
</script>