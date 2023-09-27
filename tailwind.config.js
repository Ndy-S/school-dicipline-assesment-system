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
                'dark-custom-color': '#181f2e',
                'neon-custom-color': '#2c334e',
            },
        },
    },
    plugins: [],
}

