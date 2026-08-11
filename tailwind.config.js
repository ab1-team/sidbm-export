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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#526D82',
                    50: '#E8EEF1',
                    100: '#D1DDDF',
                    200: '#A3BBC3',
                    300: '#7599A7',
                    400: '#527A8D',
                    500: '#526D82',
                    600: '#3F5568',
                    700: '#324050',
                    800: '#252B39',
                    900: '#181C26',
                },
                secondary: {
                    DEFAULT: '#9DB2BF',
                    50: '#F2F5F7',
                    100: '#E5EBEF',
                    200: '#CBD7DF',
                    300: '#B1C3CF',
                    400: '#9DB2BF',
                    500: '#7A93A3',
                    600: '#5D7A89',
                    700: '#47616E',
                    800: '#374952',
                    900: '#253238',
                },
                surface: '#F8FAFC',
                card: '#FFFFFF',
                text: '#27374D',
                border: '#DDE6ED',
            },
            borderRadius: {
                'card': '18px',
            },
            boxShadow: {
                'card': '0 4px 40px 0 rgba(82, 109, 130, 0.12)',
                'card-hover': '0 8px 50px 0 rgba(82, 109, 130, 0.18)',
                'input': '0 1px 3px 0 rgba(39, 55, 77, 0.06)',
            },
        },
    },

    plugins: [forms],
};
