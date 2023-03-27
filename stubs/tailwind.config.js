/** @type {import('tailwindcss').Config} */

const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    mode: 'jit',
    
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/jiannius/atom-livewire/resources/**/*.js',
        './vendor/jiannius/atom-livewire/resources/**/*.blade.php',
        './vendor/jiannius/atom-livewire/src/Components/**/*.php',
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
            'xs': '.75rem',
            'sm': '.875rem',
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
                'theme': {
                    light: '#ede0ff',
                    DEFAULT: '#9f5afd',
                    dark: '#3b028a',
                },
                'theme-inverted': {
                    light: '#ffffff',
                    DEFAULT: '#ffffff',
                    dark: '#ffffff',
                }
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
            fill: theme => theme('colors'),
        },
    },
    
    plugins: [
        require('@tailwindcss/typography'),
    ],
}
