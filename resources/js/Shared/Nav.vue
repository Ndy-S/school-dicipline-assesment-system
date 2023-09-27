<template>
    <nav class="left-0 top-0 h-screen w-60 bg-neon-custom-color shadow-2xl overflow-hidden">
        <div>
            <div id="header-content" class="pl-4 flex items-center my-10">
                <img
                    src="https://tecdn.b-cdn.net/img/Photos/Avatars/img%20(23).webp"
                    alt="Avatar"
                    class="mr-3 h-auto rounded-full border-2 border-yellow-700 align-middle p-0.5"
                    style="max-width: 50px;"
                />
                <div>
                    <h4 class="font-mono text-2xl text-yellow-400 font-medium leading-[1.2]">Ann Smith</h4>
                    <p class="font-mono text-yellow-500">Teacher</p>
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
                    menuLink="home"
                    menuIcon="chart-line"
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
    <div class="bg-neon-custom-color h-fit p-4 mx-10 rounded-lg">
        <marquee class="text-yellow-500 font-bold uppercase text-lg font-mono" direction="right">
            <span class="flex items-center">
                <span class="mr-4">Sistem Kehadiran dan Kedisiplinan Siswa</span>
                <img src="https://i.pinimg.com/originals/05/1a/b4/051ab4711df54275e98341d84ac68ed6.gif" class="w-8" alt="Animated Image">
            </span>
        </marquee>

    </div>
</template>

<script setup>
    import NavMenu from '../Shared/NavMenu.vue'
</script>