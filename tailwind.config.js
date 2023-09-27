/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            backgroundColor: {
                'light-custom-color': '#F3F4F6',
                'dark-custom-color': '#0E0E0E',
                'neon-custom-color': '#1B1730',
            },
        },
    },
    plugins: [],
}

