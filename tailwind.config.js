import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        container: {
            screens: {
                sm: '600px',
                md: '728px',
                lg: '984px',
                xl: '1240px',
                '2xl': '1440px',
            }
        },

        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                rpgSans: ['Inknut Antiqua', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                sand: {
                    'd6': '#D1D0CF',
                    'd8': '#F1D7AF'
                },
                charcoal: {
                    'd8': '#474747',
                    'd10': '#2F2F2F',
                    'd12': '#1B1B1B',
                    'd20': '#101010',
                },
                mage: {
                    'd6': '#AB83E0',
                    'd8': '#4E00B5',
                    'd10': '#5E17BB'
                }
            },
            backgroundImage: {
                'barbarianBG': "url('/images/barbarianbg.jpg')",
                'mageBG': "url('/images/magebg.jpg')",
                'rogueBG': "url('/images/roguebg.jpg')",
                'bardBG': "url('/images/bardbg.jpg')",
                'rogueelfBG': "url('/images/rogueelfBG.jpg')",
                'pirateBG': "url('/images/pirateBG.jpg')",
            },
        },
    },

    plugins: [
        forms,
        require("@tailwindcss/line-clamp"),

    ],

};
