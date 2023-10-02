<template>
    <li class="relative">
        <Link
            class="group flex h-12 cursor-pointer items-center truncate rounded-[5px] px-6 py-4 text-[0.875rem] text-gray-300 font-mono outline-none transition duration-300 ease-linear hover:bg-gray-100 hover:text-inherit hover:outline-none focus:bg-gray-100 focus:text-inherit focus:outline-none active:bg-gray-100 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none dark:text-gray-300 dark:hover:bg-gray-300/10 dark:focus:bg-gray-300/10 dark:active:bg-gray-300/10"
            :class="{'bg-gray-100 text-black': $page.component.toLowerCase()  === menuLink}"
            :href=menuLink
            data-te-sidenav-link-ref
            :method="method ?? 'GET'"
        >
            <span class="mr-4 [&>svg]:h-4 [&>svg]:w-4">
                <font-awesome-icon :icon="['fas', menuIcon]" />
            </span>
            <span>{{ menuName }}</span>
        </Link>
    </li>
</template>



<script setup>
    let props = defineProps({
        menuName: String,
        menuLink: String,
        menuIcon: String,
        method: String,
    });

</script>