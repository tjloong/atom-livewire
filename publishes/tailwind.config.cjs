/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    mode: 'jit',
    
    content: [
        './resources/**/*.blade.php',
        './resources/js/**/*.js',
        './vendor/jiannius/atom-livewire/resources/js/**/*.js',
        './vendor/jiannius/atom-livewire/resources/**/*.blade.php',
        './vendor/jiannius/atom-livewire/src/Components/**/*.php',
    ],

    safelist: [
        'text-2xs',
        'text-xs',
        'text-sm',
        'text-md',
        'text-base',
        'text-lg',
        'text-xl',
        'text-2xl',
        'text-3xl',
        'text-4xl',
        'text-5xl',
        'text-6xl',
        'text-7xl',
    ],
    
    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
            '3xl': '2000px',
            'print': { 'raw': 'print' },
            'page': { 'raw': 'page' },
        },
        fontSize: {
            '2xs': '.675rem',
            'xs': '.75rem',
            'sm': '.875rem',
            'md': '1rem',
            'base': '1rem',
            'lg': '1.125rem',
            'xl': '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '4rem',
            '7xl': '5rem',
        },
        extend: {
            colors: {
                'primary': {
                    50: '#f4f7fb',
                    100: '#e8eef6',
                    200: '#ccdceb',
                    300: '#9fc0da',
                    400: '#6b9ec5',
                    500: '#558eb9',
                    600: '#366893',
                    700: '#2d5477',
                    800: '#284864',
                    900: '#263e54',
                    950: '#192838',
                    DEFAULT: '#558eb9',
                },
            },
            borderColor: theme => ({
                DEFAULT: theme('colors.gray.200', 'currentColor'),
            }),
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: theme => ({
                outline: '0 0 0 2px ' + theme('colors.blueGray.500'),
            }),
            zIndex: {
                '1': '1',
            },
            fill: theme => theme('colors'),
        },
    },
    
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
