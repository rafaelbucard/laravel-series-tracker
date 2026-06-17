import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    red: '#dc2626',
                    blue: '#2563eb',
                    mid: '#a21caf',
                },
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #dc2626 0%, #a21caf 50%, #2563eb 100%)',
                'brand-gradient-soft': 'linear-gradient(135deg, #fee2e2 0%, #ede9fe 50%, #dbeafe 100%)',
                'brand-gradient-dark': 'linear-gradient(135deg, #7f1d1d 0%, #581c87 50%, #1e3a8a 100%)',
            },
            boxShadow: {
                'brand': '0 10px 25px -5px rgba(220, 38, 38, 0.25), 0 10px 25px -5px rgba(37, 99, 235, 0.25)',
            },
        },
    },

    plugins: [forms],
};
