/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                rose:    '#c8847a',
                'rose-dk': '#a05e56',
                cream:   '#faf7f4',
                sand:    '#f0e8df',
            },
            fontFamily: {
                serif: ['"Cormorant Garamond"', 'serif'],
                sans:  ['"DM Sans"', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
